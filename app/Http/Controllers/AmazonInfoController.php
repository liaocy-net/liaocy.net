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
            ->select('*', 'product_batches.id AS product_batch_id')
            ->where([
                ['user_id', auth()->id()],
                ['action', 'extract_amazon_info'],
            ])
            ->leftJoin('job_batches', 'product_batches.job_batch_id', '=', 'job_batches.id')
            ->orderBy("product_batches.created_at", "desc")
            ->paginate(env("PAGE_MAX_LIMIT", 50));
        foreach ($batches as $batch) {
            $batch->status = UtilityService::getPatchStatus($batch);
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
                    $productBatch->action = "extract_amazon_info";
                    $productBatch->filename = UtilityService::genRandomFileName() . ".csv";
                    $productBatch->save();

                    $extractAmazonInfos = array();
                    foreach ($asins as $asin) {
                        $product = new Product();
                        $product->user_id = auth()->id();
                        $product->product_batch_id = $productBatch->id;
                        $product->asin = $asin;
                        $product->save();

                        array_push($extractAmazonInfos, new ExtractAmazonInfo($product));
                    }

                    $batch = Bus::batch($extractAmazonInfos)->name("extract_amazon_info")->allowFailures()->dispatch();

                    $productBatch->job_batch_id = $batch->id;
                    $productBatch->save();

                    return redirect()->route('amazon_info.index')->with('success', 'Amazon情報取得ジョブを登録しました。');
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
        try {
            $productBatch = ProductBatch::find($id);
            if (empty($productBatch) || $productBatch->user_id !== auth()->id()) {
                throw new \Exception("指定されたバッチが見つかりません。");
            }

            $products = Product::where('product_batch_id', $id)->get();

            $headers = [
                "asin",
                "ap_jp",
                "title_jp",
                "title_us",
                "brand_jp",
                "brand_us",
                "cate_us",
                "color_us",
                "cp_jp",
                "cp_point",
                "cp_us",
                "imgurl01",
                "imgurl02",
                "imgurl03",
                "imgurl04",
                "imgurl05",
                "imgurl06",
                "imgurl07",
                "imgurl08",
                "imgurl09",
                "imgurl10",
                "isAmazon_jp",
                "isAmazon_us",
                "materialtype_us",
                "maximumHours_jp",
                "maximumHours_us",
                "minimumHours_jp",
                "minimumHours_us",
                "model_us",
                "nc_jp",
                "nc_us",
                "np_jp",
                "np_us",
                "pp_jp",
                "pp_us",
                "rankid_jp",
                "rank_jp",
                "rank_us",
                "sellerFeedbackCount",
                "sellerFeedbackRating",
                "sellerId",
                "shippingcost",
                "size_h_us",
                "size_l_us",
                "size_w_us",
                "size_us",
                "weight_us",
            ];
            $csv = join(',',$headers) . "\n";
            foreach($products as $product){
                $csv .= $product->asin . ",";
                $csv .= $product->ap_jp . ",";
                $csv .= $product->title_jp . ",";
                $csv .= $product->title_us . ",";
                $csv .= $product->brand_jp . ",";
                $csv .= $product->brand_us . ",";
                $csv .= $product->cate_us . ",";
                $csv .= $product->color_us . ",";
                $csv .= $product->cp_jp . ",";
                $csv .= $product->cp_point . ",";
                $csv .= $product->cp_us . ",";
                $csv .= $product->img_url_01 . ",";
                $csv .= $product->img_url_02 . ",";
                $csv .= $product->img_url_03 . ",";
                $csv .= $product->img_url_04 . ",";
                $csv .= $product->img_url_05 . ",";
                $csv .= $product->img_url_06 . ",";
                $csv .= $product->img_url_07 . ",";
                $csv .= $product->img_url_08 . ",";
                $csv .= $product->img_url_09 . ",";
                $csv .= $product->img_url_10 . ",";
                $csv .= $product->is_amazon_jp . ",";
                $csv .= $product->is_amazon_us . ",";
                $csv .= $product->material_type_us . ",";
                $csv .= $product->maximum_hours_jp . ",";
                $csv .= $product->maximum_hours_us . ",";
                $csv .= $product->minimum_hours_jp . ",";
                $csv .= $product->minimum_hours_us . ",";
                $csv .= $product->model_us . ",";
                $csv .= $product->nc_jp . ",";
                $csv .= $product->nc_us . ",";
                $csv .= $product->np_jp . ",";
                $csv .= $product->np_us . ",";
                $csv .= $product->pp_jp . ",";
                $csv .= $product->pp_us . ",";
                $csv .= $product->rank_id_jp . ",";
                $csv .= $product->rank_jp . ",";
                $csv .= $product->rank_us . ",";
                $csv .= $product->seller_feedback_count . ",";
                $csv .= $product->seller_feedback_rating . ",";
                $csv .= $product->seller_id . ",";
                $csv .= $product->shipping_cost . ",";
                $csv .= $product->size_h_us . ",";
                $csv .= $product->size_l_us . ",";
                $csv .= $product->size_w_us . ",";
                $csv .= $product->size_us . ",";
                $csv .= $product->weight_us . ",";
                $csv .= "\n";
            }
            # convert to SHIFT-JIS
            $csv = mb_convert_encoding($csv, 'SJIS', 'UTF-8');

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $productBatch->filename . '"',
            ];
            return response($csv, 200, $headers);
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
