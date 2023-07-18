<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProductBatch;
use App\Models\Product;
use App\Services\UtilityService;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;
use Throwable;
use App\Jobs\ProcessAsinFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use SplFileObject;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AmazonInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $my = User::find(auth()->id());
        $batches = DB::table('product_batches')
            ->select('*', 'product_batches.id AS product_batch_id', 'product_batches.finished_at AS product_batch_finished_at', 'product_batches.created_at AS product_batch_created_at')
            ->where([
                ['user_id', auth()->id()],
                ['action', 'extract_amazon_info'],
            ])
            ->leftJoin('job_batches', 'product_batches.job_batch_id', '=', 'job_batches.id')
            ->orderBy("product_batches.created_at", "desc")
            ->paginate(env("PAGE_MAX_LIMIT", 50));
        foreach ($batches as $batch) {
            $batch->status = UtilityService::getExtractAmazonInfoPatchStatus($batch);
        }
        return view('amazon_info.index', [
            'my' => $my,
            'batches' => $batches,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if ($request->hasFile('asin_file')) {
                $my = User::find(auth()->id());

                $fileExtension = $request->asin_file->getClientOriginalExtension();

                if ($fileExtension !== "csv") {
                    throw new \Exception("ファイルの拡張子がcsvではありません。");
                }

                $fileRelativePath = $request->file('asin_file')->store('asin_files');

                if (!Storage::exists($fileRelativePath)) {
                    throw new \Exception("ファイルのアップロードに失敗しました。" . $fileRelativePath);
                }

                $asinFileOriginalName = $request->asin_file->getClientOriginalName();
                $asinFileAbsolutePath = storage_path() . '/app/' . $fileRelativePath;

                $productBatch = new ProductBatch();
                $productBatch->user_id = auth()->id();
                $productBatch->action = "extract_amazon_info";
                $filename = pathinfo($asinFileOriginalName, PATHINFO_FILENAME);
                $ext = pathinfo($asinFileOriginalName, PATHINFO_EXTENSION);
                $existing_file_count = ProductBatch::where('user_id', auth()->id())->where('filename', 'like', $filename . '%')->count();
                if ($existing_file_count > 0) {
                    $filename = $filename . "_" . ($existing_file_count + 1);
                }
                $productBatch->filename = $filename. "." . $ext;
                $productBatch->save();

                # ファイルの処理Queue
                ProcessAsinFile::dispatch($asinFileAbsolutePath, $productBatch, $my, "extract_amazon_info", null, false)->onQueue("process_asin_file_" . $my->getJobSuffix());

                return redirect()->route('amazon_info.index')->with('success', 'Amazon情報取得ジョブが登録されました。');
            } else {
                throw new \Exception("ASINファイルが選択されていません。");
            }
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function downloadASINTemplateXLSX(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('ASIN');

        // ヘッダー
        $sheet->setCellValue('A1', 'ASIN');

        // asinサンプル
        $sheet->setCellValue('A2', 'B08GM14SQQ');
        $sheet->setCellValue('A3', 'B09QSCYRYH');
        $sheet->setCellValue('A4', 'B08W2C5W59');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="asin.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $productBatch = ProductBatch::find($id);
            if (empty($productBatch) || $productBatch->user_id !== auth()->id()) {
                throw new \Exception("指定されたバッチが見つかりません。");
            }

            $products = $productBatch->products()->get();

            $spreadsheet = UtilityService::getProductsExcel($products);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="products.xlsx"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function cancelAmazonInfoBatch(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_batch_id' => ['required', 'integer', 'min:1'],
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            $productBatchId = $request->product_batch_id;
            $productBatch = ProductBatch::find($productBatchId);
            if (empty($productBatch) || $productBatch->user_id !== auth()->id()) {
                throw new \Exception("指定されたバッチが見つかりません。");
            }

            $batch = Bus::findBatch($productBatch->job_batch_id);
            if ($batch === null) {
                throw new \Exception("バッチジョブが見つかりません。");
            }

            $batch->cancel();

            return redirect()->route('amazon_info.index')->with('success', 'Amazon情報取得ジョブが停止されました。');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
