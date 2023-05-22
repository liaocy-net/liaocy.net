<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Services\UtilityService;
use App\Jobs\ExtractAmazonInfo;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Bus\Batch;
use Throwable;

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
            'my' => $my
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
                'exhibit_yahoo_category' => ['required', 'string', 'max:255']
            ]);
    
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            if ($request->hasFile('asin_file')) {
                //拡張子がCSVであるかの確認
                if ($request->asin_file->getClientOriginalExtension() !== "csv") {
                    throw new \Exception("不適切な拡張子です。CSVファイルを選択してください。");
                }
                if (($handle = fopen($request->asin_file, "r")) !== FALSE) {
                    $asins = array();
                    
                    $row = 0;
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $row++;
                        if ($row === 1) {
                            if (count($data) != 1 || strcmp($data[0], "ASIN") !== 0) { //(?:[/dp/]|$)([A-Z0-9]{10})
                                throw new \Exception("CSVファイルのフォーマットが不適切です。もう一度ダウンロードしてください。");
                            }
                        } else {
                            if (count($data) != 1) {
                                throw new \Exception("CSVファイルのフォーマットが不適切です。もう一度ダウンロードしてください。");
                            }
                            if (empty($data[0])) {
                                continue;
                            }

                            $matches = array();
                            preg_match('/^(B[\dA-Z]{9}|\d{9}(X|\d))$/', $data[0], $matches);
                            if (count($matches) != 2) {
                                throw new \Exception("ASINのフォーマットが不適切です。" . $row . " 行目にある " . $data[0] . " を確認してください。");
                            }

                            if (!in_array($data[0], $asins)) {
                                array_push($asins, $data[0]);
                            }
                        }
                        
                        if ($row > 10000) {
                            throw new \Exception("CSVファイルの行数が10000行を超えています。もう一度ダウンロードしてください。");
                        }
                    }
                    fclose($handle);

                    if (count($asins) === 0) {
                        throw new \Exception("CSVファイルにASINが含まれていません。");
                    }

                    $productBatch = new ProductBatch();
                    $productBatch->user_id = auth()->id();
                    $productBatch->action = "extract_amazon_info_for_exhibit";

                    $filename = pathinfo($request->asin_file->getClientOriginalName(), PATHINFO_FILENAME);
                    $existing_file_count = ProductBatch::where('user_id', auth()->id())->where('filename', 'like', $filename . '%')->count();
                    if ($existing_file_count > 0) {
                        $filename = $filename . "_" . ($existing_file_count + 1);
                    }

                    $productBatch->filename = $filename . ".csv";
                    if (in_array("amazon", $request->exhibit_to)) {
                        $productBatch->is_exhibit_to_amazon = true;
                    } else {
                        $productBatch->is_exhibit_to_amazon = false;
                    }
                    if (in_array("yahoo", $request->exhibit_to)) {
                        $productBatch->is_exhibit_to_yahoo = true;
                        $productBatch->exhibit_yahoo_category = $request->exhibit_yahoo_category;
                    } else {
                        $productBatch->is_exhibit_to_yahoo = false;
                    }
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
                }
            } else {
                throw new \Exception("CSVファイルが選択されていません。");
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
