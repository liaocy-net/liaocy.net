<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProductBatch;
use App\Models\Product;
use App\Services\UtilityService;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;
use App\Jobs\ExtractAmazonInfo;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            ->select('*', 'product_batches.id AS product_batch_id', 'product_batches.finished_at AS product_batch_finished_at')
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
                //拡張子がxlsxであるかの確認
                if ($request->asin_file->getClientOriginalExtension() !== "xlsx") {
                    throw new \Exception("不適切な拡張子です。EXCEL(xlsx)ファイルを選択してください。");
                }
                //Excelファイルを読み込み
                $reader = IOFactory::createReader("Xlsx");
                $spreadsheet = $reader->load($request->asin_file);

                //シートの読み込み
                $sheet = $spreadsheet->getSheet(0);
                $headers = $sheet->rangeToArray('A1:A1', null, true, false);
                if (strcmp($headers[0][0], "ASIN") !== 0) {
                    throw new \Exception("EXCELファイルのフォーマットが不適切です。もう一度ダウンロードしてください。");
                }

                $rows = $sheet->rangeToArray('A2:A' . $sheet->getHighestRow(), null, true, false);
                $asins = array();
                foreach ($rows as $index => $row) {
                    $matches = array();
                    preg_match('/^(B[\dA-Z]{9}|\d{9}(X|\d))$/', $row[0], $matches);
                    if (count($matches) != 2) {
                        throw new \Exception("ASINのフォーマットが不適切です。" . ($index + 2) . " 行目にある " . $row[0] . " を確認してください。");
                    }

                    if (!in_array($row[0], $asins)) {
                        array_push($asins, $row[0]);
                    }
                }

                if (count($asins) === 0) {
                    throw new \Exception("EXCELファイルにASINが含まれていません。");
                }

                $productBatch = new ProductBatch();
                $productBatch->user_id = auth()->id();
                $productBatch->action = "extract_amazon_info";

                $filename = pathinfo($request->asin_file->getClientOriginalName(), PATHINFO_FILENAME);
                $existing_file_count = ProductBatch::where('user_id', auth()->id())->where('filename', 'like', $filename . '%')->count();
                if ($existing_file_count > 0) {
                    $filename = $filename . "_" . ($existing_file_count + 1);
                }

                $productBatch->filename = $filename . ".xlsx";
                $productBatch->save();

                $extractAmazonInfos = array();
                foreach ($asins as $asin) {
                    $product = new Product();
                    $product->user_id = auth()->id();
                    $product->asin = $asin;
                    $product->sku = UtilityService::genSKU($product);
                    $product->save();

                    $product->productBatches()->attach($productBatch);

                    array_push($extractAmazonInfos, new ExtractAmazonInfo($product));
                }

                $batch = Bus::batch($extractAmazonInfos)->name("extract_amazon_info")->then(function (Batch $batch) {
                    // すべてのジョブが正常に完了
                })->catch(function (Batch $batch, Throwable $e) {
                    // バッチジョブの失敗をはじめて検出
                })->finally(function (Batch $batch) {
                    // バッチジョブの完了
                    $productBatch = ProductBatch::where('job_batch_id', $batch->id)->first();
                    $productBatch->finished_at = now();
                    $productBatch->save();
                })->allowFailures()->dispatch();

                $productBatch->job_batch_id = $batch->id;
                $productBatch->save();

                return redirect()->route('amazon_info.index')->with('success', 'Amazon情報取得ジョブを登録しました。');
                
            } else {
                throw new \Exception("Excel(.xlsx)ファイルが選択されていません。");
            }
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }   
    }

    public function downloadASINTemplateXLSX(Request $request){
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
