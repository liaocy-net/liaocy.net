<?php

namespace App\Http\Controllers;

use App\Jobs\ExhibitToAmazonJP;
use App\Jobs\ExhibitToYahooJP;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\User;
use App\Services\UtilityService;
use App\Services\YahooService;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExhibitHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $my = User::find(auth()->id());
        return view('exhibit_history.index', [
            'my' => $my
        ]);
    }

    public function getExhibitHistories(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'platform' => ['required', 'string', 'max:255', 'regex:/^[amazon|yahoo]+$/u'],
                'page' => ['required', 'integer', 'min:1'],
                'filename' => ['nullable', 'string', 'max:255'],
                'period_from' => ['nullable', 'date', 'max:255'],
                'period_to' => ['nullable', 'date', 'max:255'],
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            $platform = $request->input('platform');
            $page = $request->input('page');
            $my = User::find(auth()->id());

            $where = [];
            if ($request->input('filename')) {
                array_push($where, ['filename', 'like', '%' . $request->input('filename') . '%']);
            }
            if ($request->input('period_from') && $request->input('period_to')) {
                array_push($where, ['product_batches.created_at', '>=', $request->input('period_from')]);
                array_push($where, ['product_batches.created_at', '<=', $request->input('period_to')]);
            }

            if ($platform == "amazon") {
                array_push($where, ['is_exhibit_to_amazon', true]);
                $action = ['extract_amazon_info_for_exhibit', 'exhibit_to_amazon_jp'];
            } elseif ($platform == "yahoo") {
                array_push($where, ['is_exhibit_to_yahoo', true]);
                $action = ['extract_amazon_info_for_exhibit', 'exhibit_to_yahoo_jp'];
            } else {
                throw new \Exception("platform is invalid", 442);
            }

            $productBatches = $my->productBatches()
                ->select('*', 
                    'product_batches.id AS id', 
                    'product_batches.id AS product_batch_id', 
                    'product_batches.finished_at AS product_batch_finished_at',
                    'product_batches.message AS product_batch_message')
                ->where($where)
                ->whereIn('action', $action)
                ->leftJoin('job_batches', 'product_batches.job_batch_id', '=', 'job_batches.id')
                ->orderBy('product_batches.created_at', 'desc')
                ->paginate(
                    $perPage = 5, 
                    $columns = ['*'], 
                    $pageName = 'page',
                    $page = $page
                );

            foreach ($productBatches as $productBatch) {
                if ($productBatch->action == 'extract_amazon_info_for_exhibit') {
                    $productBatch->patch_status = UtilityService::getExtractAmazonInfoPatchStatus($productBatch);
                } else if ($productBatch->action == 'exhibit_to_amazon_jp') {
                    $productBatch->patch_status = UtilityService::getExhibitPatchStatus($productBatch);
                } else if ($productBatch->action == 'exhibit_to_yahoo_jp') {
                    $productBatch->patch_status = UtilityService::getExhibitPatchStatus($productBatch);
                }
                
                $productBatch->start_at = $productBatch->created_at ? date('Y-m-d H:i:s', strtotime($productBatch->created_at)) : false;
                $productBatch->end_at = $productBatch->product_batch_finished_at ? date('Y-m-d H:i:s', strtotime($productBatch->product_batch_finished_at)) : false;
                $productBatch->cancelled_at = $productBatch->cancelled_at ? date('Y-m-d H:i:s', $productBatch->cancelled_at) : false;

                $productBatch->products_count = $productBatch->products()->count();

                $productBatch->has_message = !empty($productBatch->message);
                unset($productBatch->message);
                unset($productBatch->options);
                $productBatch->has_feed_document = !empty($productBatch->feed_document);
                unset($productBatch->feed_document);
            }

            return response()->json($productBatches);

        } catch (\Exception $e) {
            return response($e->getMessage(), 442);
        }
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
        //
    }

    public function hasExhibited($productBatch, $action) {

        $productBatchExhibitToAmazonJP = ProductBatch::where('filename', $productBatch->filename)
            ->where('action', $action)
            ->first();
        
        return empty($productBatchExhibitToAmazonJP) ? false : true;
    }

    public function detailAmazonJP(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'product_batch_id' => ['required', 'integer', 'min:1'],
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            $my = User::find(auth()->id());

            $productBatch = ProductBatch::find($params['product_batch_id']);
            if (!$productBatch || $productBatch->user_id != $my->id) {
                throw new \Exception('Product Batch not found', 442);
            }
            
            return view('exhibit_history.detail_amazon_jp', [
                'my' => $my,
                'has_exhibited_to_amazon_jp' => $this->hasExhibited($productBatch, "exhibit_to_amazon_jp"),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function detailYahooJP(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'product_batch_id' => ['required', 'integer', 'min:1'],
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            $my = User::find(auth()->id());

            $productBatch = ProductBatch::find($params['product_batch_id']);
            if (!$productBatch || $productBatch->user_id != $my->id) {
                throw new \Exception('Product Batch not found', 442);
            }
            
            return view('exhibit_history.detail_yahoo_jp', [
                'my' => $my,
                'has_exhibited_to_yahoo_jp' => $this->hasExhibited($productBatch, "exhibit_to_yahoo_jp"),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function getProducts(Request $request) {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'product_batch_id' => ['required', 'integer', 'min:1'],
                'page' => ['required', 'integer', 'min:1'],
                'exhibit_to' => ['required', 'string', 'max:255', 'regex:/^[amazon_jp|yahoo_jp]+$/u'],
                'asin' => ['nullable', 'string', 'max:255'],
                'brand' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:255'],
                'search_sort_column' => ['required', 'string', 'max:255', 'regex:/^[created|asin|title|price|weight]+$/u'],
                'search_sort_order' => ['required', 'string', 'max:255', 'regex:/^[asc|desc]+$/u'],
                'donot_show_product_cannot_exhibit' => ['required', 'boolean'],
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            $productBatchId = $request->input('product_batch_id');
            $page = $request->input('page');
            $my = User::find(auth()->id());

            $where = [
                ["is_amazon_us", "=", true]
            ];

            if ($request->input('exhibit_to') == 'amazon_jp') {
                array_push($where, ["is_amazon_jp", "=", true]);
            }

            if ($request->input('asin')) {
                array_push($where, ['asin', 'like', '%' . $request->input('asin') . '%']);
            }
            if ($request->input('brand')) {
                array_push($where, ['brand_jp', 'like', '%' . $request->input('brand') . '%']);
            }
            if ($request->input('title')) {
                array_push($where, ['title_jp', 'like', '%' . $request->input('title') . '%']);
            }

            $productBatch = ProductBatch::find($productBatchId);
            if (!$productBatch || $productBatch->user_id != $my->id) {
                throw new \Exception('product batch not found', 442);
            }

            $searchSortColumn = $request->input('search_sort_column');
            $searchSortOrder = $request->input('search_sort_order');
            $orderByRaw = "";
            if ($searchSortColumn == "created") {
                $orderByRaw = "products.created_at " . $searchSortOrder;
            } elseif ($searchSortColumn == "asin") {
                $orderByRaw = "asin " . $searchSortOrder;
            } elseif ($searchSortColumn == "title") {
                $orderByRaw = "ifnull(title_jp, title_en) " . $searchSortOrder;
            } elseif ($searchSortColumn == "price") {
                $orderByRaw = "ifnull(cp_us, np_us) " . $searchSortOrder;
            } elseif ($searchSortColumn == "weight") {
                $orderByRaw = "ifnull(weight_us, 0) " . $searchSortOrder;
            } else {
                throw new \Exception("search_sort_column is invalid", 442);
            }

            $products = $productBatch->products()
                ->select('*')
                ->where($where)
                ->orderByRaw($orderByRaw)
                ->limit(10000)
                ->get();

            $data = [];

            foreach ($products as $product) {
                $product->purchase_price_us = UtilityService::getPurchasePriceUS($product);
                $product->hope_price_jpy = UtilityService::calAmazonJPHopePrice($my, $product);
                $product->rate_price_jpy = UtilityService::calAmazonJPRatePrice($my, $product);
                $product->min_hope_price_jpy = UtilityService::calAmazonJPMinHopePrice($my, $product);
                $product->min_rate_price_jpy = UtilityService::calAmazonJPMinRatePrice($my, $product);
                $product->exhibit_price = 0;

                $product->yahoo_jp_min_hope_price_jpy = UtilityService::calYahooJPMinHopePrice($my, $product);
                $product->yahoo_jp_min_rate_price_jpy = UtilityService::calYahooJPMinRatePrice($my, $product);

                if ($request->input('exhibit_to') == 'amazon_jp') {
                    $canBeExhibitToAmazonJP = UtilityService::canBeExhibitToAmazonJP($my, $product);
                    $product->can_be_exhibit_to_amazon_jp = $canBeExhibitToAmazonJP["canBeExhibit"];
                    $product->can_be_exhibit_to_amazon_jp_message = $canBeExhibitToAmazonJP["message"];
                    $product->can_be_exhibit_to_amazon_jp_price = $canBeExhibitToAmazonJP["exhibitPrice"];
                    if ($request->input("donot_show_product_cannot_exhibit") && !$product->can_be_exhibit_to_amazon_jp) {
                        continue;
                    }
                }
                if ($request->input('exhibit_to') == 'yahoo_jp') {
                    $canBeExhibitToYahooJP = UtilityService::canBeExhibitToYahooJP($my, $product);
                    $product->can_be_exhibit_to_yahoo_jp = $canBeExhibitToYahooJP["canBeExhibit"];
                    $product->can_be_exhibit_to_yahoo_jp_message = $canBeExhibitToYahooJP["message"];
                    $product->can_be_exhibit_to_yahoo_jp_price = $canBeExhibitToYahooJP["exhibitPrice"];
                    if ($request->input("donot_show_product_cannot_exhibit") && !$product->can_be_exhibit_to_yahoo_jp) {
                        continue;
                    }
                }
                array_push($data, $product);
            }
            $data = $this->paginate($data, env("PAGE_MAX_LIMIT", 50), $page);
            return response()->json($data);
        } catch (\Exception $e) {
            return response($e->getMessage(), 442);
        }
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }

    public function processProducts(Request $request) {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'act' => ['required', 'string', 'max:255', 'regex:/^[cancel_exhibit_to_amazon_jp|cancel_exhibit_to_yahoo_jp|exhibit_to_amazon_jp|exhibit_to_yahoo_jp]+$/u'],
                'product_batch_id' => ['required', 'integer', 'min:1'],
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            $my = User::find(auth()->id());

            
            if ($params["act"] == "cancel_exhibit_to_amazon_jp") { //AmazonJPチェックした商品を削除
                $validator = Validator::make($params, [
                    'product_ids' => ['required', 'array', 'max:255'],
                ]);
                if ($validator->fails()) {
                    throw new \Exception($validator->errors()->first(), 442);
                }

                foreach ($params["product_ids"] as $productId) {
                    $product = Product::find($productId);
                    if (!$product) {
                        throw new \Exception("product not found", 442);
                    }
                    $product->cancel_exhibit_to_amazon_jp = true;
                    $product->save();
                }
            } else if($params["act"] == "cancel_exhibit_to_yahoo_jp") {
                $validator = Validator::make($params, [
                    'product_ids' => ['required', 'array', 'max:255'],
                ]);
                if ($validator->fails()) {
                    throw new \Exception($validator->errors()->first(), 442);
                }

                foreach ($params["product_ids"] as $productId) {
                    $product = Product::find($productId);
                    if (!$product) {
                        throw new \Exception("product not found", 442);
                    }
                    $product->cancel_exhibit_to_yahoo_jp = true;
                    $product->save();
                }
            } else if($params["act"] == "exhibit_to_amazon_jp"){ //Amazon JPに出品
                $productBatch = ProductBatch::find($params['product_batch_id']);
                if (!$productBatch || $productBatch->user_id != $my->id) {
                    throw new \Exception('product batch not found', 442);
                }

                $hasExhibited = $this->hasExhibited($productBatch, "exhibit_to_amazon_jp");
                if ($hasExhibited) {
                    throw new \Exception('Already exhibited', 442);
                }

                $exhibitToJPProductBatch = new ProductBatch();
                $exhibitToJPProductBatch->user_id = $my->id;
                $exhibitToJPProductBatch->filename = $productBatch->filename;
                $exhibitToJPProductBatch->action = "exhibit_to_amazon_jp";
                $exhibitToJPProductBatch->is_exhibit_to_amazon = true;
                $exhibitToJPProductBatch->save();

                foreach ($productBatch->products as $product) {
                    $canBeExhibitToAmazonJP = UtilityService::canBeExhibitToAmazonJP($my, $product);

                    if ($canBeExhibitToAmazonJP["canBeExhibit"]) {
                        $exhibitToJPProductBatch->products()->attach($product);

                        // 同じユーザ/SKUの商品のAmazonJP出品済みフラグをFalseにする
                        DB::table('products')
                            ->where([
                                ['user_id', $my->id],
                                ['sku', $product->sku]
                            ])
                            ->update([
                                'amazon_jp_has_exhibited' => false,
                            ]);

                        // 出品済みフラグ/価格を保存
                        $product->amazon_jp_latest_exhibit_price = $canBeExhibitToAmazonJP["exhibitPrice"]; //最新出品価格
                        $product->amazon_jp_latest_exhibit_quantity = $my->amazon_stock; //最新出品数量
                        $product->amazon_jp_has_exhibited = true; //AmazonJP出品済みフラグ
                        $product->amazon_is_in_checklist = false; //Amazon CheckList に入っているかどうか
                        $product->amazon_latest_check_at = Carbon::now(); //最新チェック日時

                        $product->amazon_jp_leadtime_to_ship = $my->amazon_lead_time_prime;
                        if ($product->maximum_hours_us && $product->maximum_hours_us > $my->amazon_lead_time_more) {
                            $product->amazon_jp_leadtime_to_ship = $my->amazon_lead_time_more;
                        }
                        if ($product->maximum_hours_us && $product->maximum_hours_us < $product->amazon_lead_time_less) {
                            $product->amazon_jp_leadtime_to_ship = $my->amazon_lead_time_less;
                        }

                        $product->save();
                    }
                }
                

                $exhibitToAmazonJPJobs = array();
                array_push($exhibitToAmazonJPJobs, new ExhibitToAmazonJP($exhibitToJPProductBatch));

                $batch = Bus::batch($exhibitToAmazonJPJobs)->name("exhibit_to_amazon_jp_" . $my->getJobSuffix())->then(function (Batch $batch) {
                    // すべてのジョブが正常に完了
                })->catch(function (Batch $batch, Throwable $e) {
                    // バッチジョブの失敗をはじめて検出
                })->finally(function (Batch $batch) {
                    // バッチジョブの完了
                    $productBatch = ProductBatch::where('job_batch_id', $batch->id)->first();
                    $productBatch->finished_at = now();
                    $productBatch->save();
                })->onQueue('exhibit_to_amazon_jp_' . $my->getJobSuffix())->allowFailures()->dispatch();

                
                $exhibitToJPProductBatch->job_batch_id = $batch->id;
                $exhibitToJPProductBatch->save();

            } else if($params["act"] == "exhibit_to_yahoo_jp"){ //Yahoo JPに出品
                $productBatch = ProductBatch::find($params['product_batch_id']);
                if (!$productBatch || $productBatch->user_id != $my->id) {
                    throw new \Exception('product batch not found', 442);
                }

                $hasExhibited = $this->hasExhibited($productBatch, "exhibit_to_yahoo_jp");
                if ($hasExhibited) {
                    throw new \Exception('Already exhibited', 442);
                }

                $exhibitToYahooJPProductBatch = new ProductBatch();
                $exhibitToYahooJPProductBatch->user_id = $my->id;
                $exhibitToYahooJPProductBatch->filename = $productBatch->filename;
                $exhibitToYahooJPProductBatch->action = "exhibit_to_yahoo_jp";
                $exhibitToYahooJPProductBatch->is_exhibit_to_yahoo = true;
                $exhibitToYahooJPProductBatch->save();

                $exhibitToYahooJPJobs = array();
                foreach ($productBatch->products as $product) {
                    $canBeExhibitToYahooJP = UtilityService::canBeExhibitToYahooJP($my, $product);

                    if ($canBeExhibitToYahooJP["canBeExhibit"]) {
                        $exhibitToYahooJPProductBatch->products()->attach($product);

                        // 同じユーザ/SKUの商品のYahooJP出品済みフラグをFalseにする
                        DB::table('products')
                            ->where([
                                ['user_id', $my->id],
                                ['sku', $product->sku]
                            ])
                            ->update([
                                'yahoo_jp_has_exhibited' => false,
                            ]);

                        // 出品済みフラグ/価格を保存
                        $product->yahoo_jp_latest_exhibit_price = $canBeExhibitToYahooJP["exhibitPrice"]; //最新出品価格
                        $product->yahoo_jp_latest_exhibit_quantity = $my->yahoo_stock; //最新出品数量
                        $product->yahoo_jp_has_exhibited = true; //YahooJP出品済みフラグ
                        $product->yahoo_is_in_checklist = false; //Yahoo CheckList に入っているかどうか
                        $product->yahoo_latest_check_at = Carbon::now(); //最新チェック日時

                        $product->save();

                        array_push($exhibitToYahooJPJobs, new ExhibitToYahooJP($product, $exhibitToYahooJPProductBatch->id));
                    }

                    $batch = Bus::batch($exhibitToYahooJPJobs)->name("exhibit_to_yahoo_jp")->then(function (Batch $batch) {
                        // すべてのジョブが正常に完了
                    })->catch(function (Batch $batch, Throwable $e) {
                        // バッチジョブの失敗をはじめて検出
                    })->finally(function (Batch $batch) {
                        // バッチジョブの完了
                        $productBatch = ProductBatch::where('job_batch_id', $batch->id)->first();

                        // debug: Attempt to read property "user_id" on null
                        if (is_null($productBatch)) {
                            return;
                        }

                        $user = User::find($productBatch->user_id);
                        $yahooService = new YahooService($user);
                        
                        // setStock
                        sleep(10);
                        $products = $productBatch->products->all();
                        $yahooService->setStock($products);

                        // reservePublish
                        sleep(10);
                        $yahooService->reservePublish();

                        // save product batch
                        $productBatch->finished_at = now();
                        $productBatch->save();

                    })->onQueue('exhibit_to_yahoo_jp_' . $my->getJobSuffix())->allowFailures()->dispatch();

                    $exhibitToYahooJPProductBatch->job_batch_id = $batch->id;
                    $exhibitToYahooJPProductBatch->save();
                }

            } else {
                throw new \Exception("act is invalid", 442);
            }

            return response()->json(array("status" => "success", "act" => $params["act"]));

        } catch (\Exception $e) {
            return response($e->getMessage(), 442);
        }

        return response()->json($request);
    }

    public function getProductBatchMessage(Request $request) {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'product_batch_id' => ['required', 'integer', 'min:1'],
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            $productBatch = ProductBatch::find($params['product_batch_id']);
            if (!$productBatch || $productBatch->user_id != auth()->id()) {
                throw new \Exception('product batch not found', 442);
            }

            return response($productBatch->message, 200)->header('Content-Type', 'text/plain');

        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function downloadProductBatchFeedDocumentTSV(Request $request) {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'product_batch_id' => ['required', 'integer', 'min:1'],
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            $productBatch = ProductBatch::find($params['product_batch_id']);
            if (!$productBatch || $productBatch->user_id != auth()->id()) {
                throw new \Exception('product batch not found', 442);
            }

            $headers = array(                     //ヘッダー情報を指定する
                'Content-Type' => 'text/tsv',
                'Content-Disposition' => 'attachment; filename=feed_doc_' . $productBatch->id . '.tsv'
            );

            return response($productBatch->feed_document, 200, $headers);

        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
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
