<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Services\UtilityService;

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
            } elseif ($platform == "yahoo") {
                array_push($where, ['action', 'update_yahoo_jp_exhibit']);
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
