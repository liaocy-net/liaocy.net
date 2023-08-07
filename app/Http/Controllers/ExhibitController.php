<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAsinFile;
use App\Models\ProductBatch;
use App\Models\User;
use App\Models\YahooJpCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function searchYahooJpCategories(Request $request)
    {
        $query = YahooJpCategory::query();
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where('path', 'LIKE', "%$search%")->orWhere('product_category', '=', $search);
        }
        return $query->paginate(20);
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
            $my = User::find(auth()->id());

            $validator = Validator::make($request->all(), [
                'exhibit_to' => ['required'],
                'exhibit_to.*' => ['in:amazon,yahoo']
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 442);
            }

            if (!$request->hasFile('asin_file')) {
                throw new \Exception("出品用ファイルが選択されていません。");
            }

            # ファイルの拡張子がcsvであるかの確認
            $fileExtension = $request->asin_file->getClientOriginalExtension();
            if ($fileExtension !== "csv") {
                throw new \Exception("出品用ファイルの拡張子がcsvではありません。");
            }

            $fileRelativePath = $request->file('asin_file')->store('asin_files');
            if (!Storage::exists($fileRelativePath)) {
                throw new \Exception("出品用ファイルのアップロードに失敗しました。" . $fileRelativePath);
            }

            $asinFileOriginalName = $request->asin_file->getClientOriginalName();
            $asinFileAbsolutePath = storage_path() . '/app/' . $fileRelativePath;

            $yahooJpCategory = null;
            if (in_array("yahoo", $request->exhibit_to)) {
                # Yahoo! JAPANカテゴリーの確認
                $yahooJpCategory = YahooJpCategory::find($request->yahoo_jp_category_id);
                if ($yahooJpCategory === null) {
                    throw new \Exception("Yahoo! JAPANカテゴリーを選択してください。");
                }
            }

            # ファイルの処理Queue
            ProcessAsinFile::dispatch(
                $asinFileAbsolutePath,
                $my, 
                "extract_amazon_info_for_exhibit", 
                $yahooJpCategory, 
                true, 
                $asinFileOriginalName,
                $request->exhibit_to
            )->onQueue("process_asin_file_" . $my->getJobSuffix());

            return redirect()->route('exhibit.index')->with('success', 'Amazon情報取得ジョブを登録しました。');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function cancelExhibitBatch(Request $request)
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

            if ($productBatch === null) {
                throw new \Exception("不正なリクエストです。");
            }

            if ($productBatch->user_id !== auth()->id()) {
                throw new \Exception("不正なリクエストです。");
            }

            $batch = Bus::findBatch($productBatch->job_batch_id);
            if ($batch === null) {
                throw new \Exception("バッチジョブが見つかりません。");
            }

            $batch->cancel();
        } catch (\Exception $e) {
            return response($e->getMessage(), 442);
        }

        return response("OK", 200);
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
