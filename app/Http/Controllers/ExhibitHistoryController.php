<?php

namespace App\Http\Controllers;

use App\Jobs\ExhibitToAmazonJP;
use App\Models\JobBatch;
use App\Models\ProductBatch;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\Array_;
use App\Services\UtilityService;
use App\Models\Product;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Batch;
use Throwable;

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
            } elseif ($platform == "yahoo") {
                array_push($where, ['is_exhibit_to_yahoo', true]);
            } else {
                throw new \Exception("platform is invalid", 442);
            }

            $productBatches = $my->productBatches()
                ->select('*', 
                    'product_batches.id AS id', 
                    'product_batches.id AS product_batch_id', 
                    'product_batches.finished_at AS product_batch_finished_at')
                ->where($where)
                ->whereIn('action', ['extract_amazon_info_for_exhibit', 'exhibit_to_amazon_jp'])
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
                }
                
                $productBatch->start_at = $productBatch->created_at ? date('Y-m-d H:i:s', strtotime($productBatch->created_at)) : false;
                $productBatch->end_at = $productBatch->product_batch_finished_at ? date('Y-m-d H:i:s', strtotime($productBatch->product_batch_finished_at)) : false;
            
                $productBatch->products_count = $productBatch->products()->count();

                $productBatch->has_message = !empty($productBatch->message);
                unset($productBatch->message);
                unset($productBatch->options);
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

    public function detail(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'product_batch_id' => ['required', 'integer', 'min:1'],
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            $productBatch = ProductBatch::find($params['product_batch_id']);
            $my = User::find(auth()->id());

            if (!$productBatch || $productBatch->user_id != $my->id) {
                throw new \Exception('product batch not found', 442);
            }

            $productBatchExhibitToAmazonJP = ProductBatch::where('filename', $productBatch->filename)
                ->where('action', 'exhibit_to_amazon_jp')
                ->first();
            
            $has_exhibited = empty($productBatchExhibitToAmazonJP) ? false : true;
            
            return view('exhibit_history.detail', [
                'my' => $my,
                'has_exhibited' => $has_exhibited,
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
                'asin' => ['nullable', 'string', 'max:255'],
                'brand' => ['nullable', 'string', 'max:255'],
                'title' => ['nullable', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            $productBatchId = $request->input('product_batch_id');
            $page = $request->input('page');
            $my = User::find(auth()->id());

            $where = [
                
            ];

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

            $products = $productBatch->products()
                ->select('*')
                ->where($where)
                ->orderBy('products.created_at', 'desc')
                ->paginate(
                    $perPage = env("PAGE_MAX_LIMIT", 50), 
                    $columns = ['*'], 
                    $pageName = 'page',
                    $page = $page
                );

            foreach ($products as $product) {
                $product->hope_price_jpy = UtilityService::calAmazonJPHopePrice($my, $product);
                $product->rate_price_jpy = UtilityService::calAmazonJPRatePrice($my, $product);
                $product->min_hope_price_jpy = UtilityService::calAmazonJPMinHopePrice($my, $product);
                $product->min_rate_price_jpy = UtilityService::calAmazonJPMinRatePrice($my, $product);
                $product->exhibit_price = 0;
                $canBeExhibitToAmazonJP = UtilityService::canBeExhibitToAmazonJP($my, $product);
                $product->can_be_exhibit_to_amazon_jp = $canBeExhibitToAmazonJP["canBeExhibit"];
                $product->can_be_exhibit_to_amazon_jp_message = $canBeExhibitToAmazonJP["message"];
                $product->can_be_exhibit_to_amazon_jp_price = $canBeExhibitToAmazonJP["exhibitPrice"];
            }

            return response()->json($products);

        } catch (\Exception $e) {
            return response($e->getMessage(), 442);
        }
    }

    public function processProducts(Request $request) {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'act' => ['required', 'string', 'max:255', 'regex:/^[cancel_exhibit_to_amazon_jp|exhibit]+$/u'],
                'product_batch_id' => ['required', 'integer', 'min:1'],
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            $my = User::find(auth()->id());

            //チェックした商品を削除
            if ($params["act"] == "cancel_exhibit_to_amazon_jp") {
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
                    $product->is_deleted = true;
                    $product->save();
                }

            } else if($params["act"] == "exhibit_to_amazon_jp"){
                $productBatch = ProductBatch::find($params['product_batch_id']);
                if (!$productBatch || $productBatch->user_id != $my->id) {
                    throw new \Exception('product batch not found', 442);
                }

                $exhibitToJPProductBatch = new ProductBatch();
                $exhibitToJPProductBatch->user_id = $my->id;
                $exhibitToJPProductBatch->filename = $productBatch->filename;
                $exhibitToJPProductBatch->action = "exhibit_to_amazon_jp";
                $exhibitToJPProductBatch->is_exhibit_to_amazon = true;
                $exhibitToJPProductBatch->save();
                foreach ($productBatch->products as $product) {
                    if ($product->is_exhibit_to_amazon_jp) {
                        $exhibitToJPProductBatch->products()->attach($product);
                    }
                }
                

                $exhibitToAmazonJPJobs = array();
                array_push($exhibitToAmazonJPJobs, new ExhibitToAmazonJP($exhibitToJPProductBatch));

                $batch = Bus::batch($exhibitToAmazonJPJobs)->name("exhibit_to_amazon_jp")->then(function (Batch $batch) {
                    // すべてのジョブが正常に完了
                })->catch(function (Batch $batch, Throwable $e) {
                    // バッチジョブの失敗をはじめて検出
                })->finally(function (Batch $batch) {
                    // バッチジョブの完了
                    $productBatch = ProductBatch::where('job_batch_id', $batch->id)->first();
                    $productBatch->finished_at = now();
                    $productBatch->save();
                })->allowFailures()->dispatch();

                
                $exhibitToJPProductBatch->job_batch_id = $batch->id;
                $exhibitToJPProductBatch->save();

            } else {
                throw new \Exception("act is invalid", 442);
            }

            return response()->json(array("status" => "success"));

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
