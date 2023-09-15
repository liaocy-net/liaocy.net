<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UtilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SplFileObject;

class UpdateHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $my = User::find(auth()->id());
        return view('update_history.index', [
            'my' => $my
        ]);
    }

    public function getUpdateHistories(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'platform' => ['required', 'string', 'max:255', 'regex:/^[amazon|yahoo]+$/u'],
                'page' => ['required', 'integer', 'min:1'],
                'period_from' => ['nullable', 'date', 'max:255'],
                'period_to' => ['nullable', 'date', 'max:255'],
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            $action = $request->input('action');
            $platform = $request->input('platform');
            $page = $request->input('page');
            $my = User::find(auth()->id());

            $where = [];
            if ($request->input('search_period_from') && $request->input('search_period_to')) {
                array_push($where, ['product_batches.created_at', '>=', $request->input('search_period_from')]);
                array_push($where, ['product_batches.created_at', '<=', $request->input('search_period_to')]);
            }

            if ($platform == "amazon") {
                array_push($where, ['action', 'update_amazon_jp_exhibit']);
                $exhibitingProductCount = $my->products()->where([
                    ["amazon_jp_has_exhibited", true], //AmazonJPへ出品済み
                    ["cancel_exhibit_to_amazon_jp", "=", false], //削除されていない
                ])->count();
            } elseif ($platform == "yahoo") {
                array_push($where, ['action', 'update_yahoo_jp_exhibit']);
                $exhibitingProductCount = $my->products()->where([
                    ["yahoo_jp_has_exhibited", true], //AmazonJPへ出品済み
                    ["cancel_exhibit_to_yahoo_jp", "=", false], //削除されていない
                ])->count();
            } else {
                throw new \Exception("platform is invalid", 442);
            }

            $productBatches = $my->productBatches()
                ->select('*', 
                    'product_batches.id AS id', 
                    'product_batches.id AS product_batch_id', 
                    'product_batches.finished_at AS product_batch_finished_at')
                ->where($where)
                ->leftJoin('job_batches', 'product_batches.job_batch_id', '=', 'job_batches.id')
                ->orderBy('product_batches.created_at', 'desc')
                ->paginate(
                    $perPage = 5, 
                    $columns = ['*'], 
                    $pageName = 'page',
                    $page = $page
                );

            foreach ($productBatches as $productBatch) {
                $productBatch->patch_status = UtilityService::getUpdatePatchStatus($productBatch);
                
                $productBatch->start_at = $productBatch->created_at ? date('Y-m-d H:i:s', strtotime($productBatch->created_at)) : false;
                $productBatch->end_at = $productBatch->product_batch_finished_at ? date('Y-m-d H:i:s', strtotime($productBatch->product_batch_finished_at)) : false;
            
                $productBatch->products_count = $productBatch->products()->count();

                $productBatch->has_message = !empty($productBatch->message);
                unset($productBatch->message);
                unset($productBatch->options);
                $productBatch->has_feed_document = !empty($productBatch->feed_document);
                unset($productBatch->feed_document);
            }

            return response()->json(array(
                'status' => 'success',
                'product_batches' => $productBatches,
                'exhibiting_product_count' => $exhibitingProductCount,
            ));

        } catch (\Exception $e) {
            return response($e->getMessage(), 442);
        }
    }


    public function deleteExhibitingProducts(Request $request) {
        try {
            $my = User::find(auth()->id());

            $validator = Validator::make($request->all(), [
                'platform' => ['required', 'string', 'max:255', 'regex:/^[amazon|yahoo]+$/u'],
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            if (!$request->hasFile('asin_file')) {
                throw new \Exception("ASINファイルが選択されていません。");
            }

            # ファイルの拡張子がcsvであるかの確認
            $fileExtension = $request->asin_file->getClientOriginalExtension();
            if ($fileExtension !== "csv") {
                throw new \Exception("ASINファイルの拡張子がcsvではありません。");
            }

            $file = new SplFileObject($request->asin_file);
            $file->setFlags(SplFileObject::READ_CSV);
            $codes = [];
            foreach ($file as $rowIndex => $row) {
                if ($rowIndex === 0) {
                } else {
                    if (empty($row[0])) {
                        continue;
                    }
                    if (!in_array($row[0], $codes)) {
                        array_push($codes, $row[0]);
                    }
                }
            }

            $platform = $request->input('platform');
            if ($platform == "amazon") {
                $my->products()->where([
                    ["amazon_jp_has_exhibited", true], //AmazonJPへ出品済み
                    ["cancel_exhibit_to_amazon_jp", "=", false], //削除されていない
                ])->whereIn('sku', $codes)->update([
                    "cancel_exhibit_to_amazon_jp" => true,
                ]);
                return redirect()->route('update_history.index')->with('success', 'Amazon JP 出品中から削除しました。');
            } elseif ($platform == "yahoo") {
                $my->products()->where([
                    ["yahoo_jp_has_exhibited", true], //YahooJPへ出品済み
                    ["cancel_exhibit_to_yahoo_jp", "=", false], //削除されていない
                ])->whereIn('item_code', $codes)->update([
                    "cancel_exhibit_to_yahoo_jp" => true,
                ]);
                return redirect()->route('update_history.index')->with('success', 'Yahoo JP 出品中から削除しました。');
            } else {
                throw new \Exception("platform is invalid: " . $platform, 442);
            }
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
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
