<?php

namespace App\Jobs;

use App\Models\ProductBatch;
use App\Models\User;
use App\Services\UtilityService;
use App\Services\YahooService;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Throwable;

class PrepareExhibitToYahooJP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $exhibitToYahooJPProductBatch;
    protected $productBatch;
    protected $my;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($exhibitToYahooJPProductBatch, $productBatch, $my)
    {
        $this->exhibitToYahooJPProductBatch = $exhibitToYahooJPProductBatch;
        $this->productBatch = $productBatch;
        $this->my = $my;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $exhibitToYahooJPJobs = array();
        foreach ($this->productBatch->products as $product) {
            if ($product->can_be_exhibit_to_yahoo_jp) {
                $this->exhibitToYahooJPProductBatch->products()->attach($product);

                // 同じユーザ/ItemCodeの商品のYahooJP出品済みフラグをFalseにする
                DB::table('products')
                    ->where([
                        ['user_id', $this->my->id],
                        ['item_code', $product->item_code]
                    ])
                    ->update([
                        'yahoo_jp_has_exhibited' => false,
                    ]);

                // 出品済みフラグ/価格を保存
                $product->yahoo_jp_latest_exhibit_price = $product->can_be_exhibit_to_yahoo_jp_price; //最新出品価格
                $product->yahoo_jp_latest_exhibit_quantity = $this->my->yahoo_stock; //最新出品数量
                $product->yahoo_jp_has_exhibited = true; //YahooJP出品済みフラグ
                $product->yahoo_is_in_checklist = false; //Yahoo CheckList に入っているかどうか
                $product->yahoo_latest_check_at = Carbon::now(); //最新チェック日時
                $product->yahoo_jp_has_edit_item_done = false; //YahooJP商品編集済みフラグ

                $product->save();

                array_push($exhibitToYahooJPJobs, new ExhibitToYahooJP($product, $this->exhibitToYahooJPProductBatch->id));
            }
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

        })->onQueue('exhibit_to_yahoo_jp_' . $this->my->getJobSuffix())->allowFailures()->dispatch();

        $this->exhibitToYahooJPProductBatch->job_batch_id = $batch->id;
        $this->exhibitToYahooJPProductBatch->save();
    }

    /**
     * 失敗したジョブの処理
     * @param  \Exception  $exception
     * @return void
     */
    public function failed($exception)
    {
        if (env('APP_DEBUG', 'false') == 'true') {
            var_dump($exception->getMessage());
        }
    }
}
