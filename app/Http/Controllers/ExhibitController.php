<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\YahooJpCategory;
use App\Services\UtilityService;
use App\Jobs\ExtractAmazonInfo;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Bus\Batch;
use Throwable;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExhibitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $my = User::find(auth()->id());
        return view('exhibit.index', [
            'my' => $my,
            'yahooJpCategories' => YahooJpCategory::all(),
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
            $validator = Validator::make($request->all(), [
                'exhibit_to' => ['required'],
                'exhibit_to.*' => ['in:amazon,yahoo'],
                'yahoo_jp_category_id' => ['required', 'integer']
            ]);
    
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

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
                $productBatch->action = "extract_amazon_info_for_exhibit";

                $filename = pathinfo($request->asin_file->getClientOriginalName(), PATHINFO_FILENAME);
                $existing_file_count = ProductBatch::where('user_id', auth()->id())->where('filename', 'like', $filename . '%')->count();
                if ($existing_file_count > 0) {
                    $filename = $filename . "_" . ($existing_file_count + 1);
                }

                $productBatch->filename = $filename . ".xlsx";
                if (in_array("amazon", $request->exhibit_to)) {
                    $productBatch->is_exhibit_to_amazon = true;
                } else {
                    $productBatch->is_exhibit_to_amazon = false;
                }
                if (in_array("yahoo", $request->exhibit_to)) {
                    $productBatch->is_exhibit_to_yahoo = true;
                } else {
                    $productBatch->is_exhibit_to_yahoo = false;
                }
                $productBatch->save();

                $yahooJpCategory = YahooJpCategory::find($request->yahoo_jp_category_id);
                if ($yahooJpCategory === null) {
                    throw new \Exception("Yahoo! JAPANカテゴリーが不適切です。");
                }

                $extractAmazonInfos = array();
                foreach ($asins as $asin) {
                    $product = new Product();
                    $product->user_id = auth()->id();
                    $product->asin = $asin;
                    $product->sku = UtilityService::genSKU($product);
                    $product->yahoo_jp_product_category = $yahooJpCategory->product_category;
                    $product->yahoo_jp_path = $yahooJpCategory->path;
                    $product->save();

                    $product->productBatches()->attach($productBatch);

                    array_push($extractAmazonInfos, new ExtractAmazonInfo($product));
                }

                $batch = Bus::batch($extractAmazonInfos)->name("extract_amazon_info_for_exhibit")->then(function (Batch $batch) {
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

                return redirect()->route('exhibit.index')->with('success', 'Amazon情報取得ジョブを登録しました。');
                
            } else {
                throw new \Exception("Excel(.xlsx)ファイルが選択されていません。");
            }
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
