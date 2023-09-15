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

class PrepareExhibitToAmazonJP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $exhibitToAmazonJPProductBatch;
    protected $productBatch;
    protected $my;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($exhibitToAmazonJPProductBatch, $productBatch, $my)
    {
        $this->exhibitToAmazonJPProductBatch = $exhibitToAmazonJPProductBatch;
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
        foreach ($this->productBatch->products as $product) {
            if ($product->can_be_exhibit_to_amazon_jp) {
                $this->exhibitToAmazonJPProductBatch->products()->attach($product);

                // 同じユーザ/SKUの商品のAmazonJP出品済みフラグをFalseにする
                DB::table('products')
                    ->where([
                        ['user_id', $this->my->id],
                        ['sku', $product->sku]
                    ])
                    ->update([
                        'amazon_jp_has_exhibited' => false,
                    ]);

                // 出品済みフラグ/価格を保存
                $product->amazon_jp_latest_exhibit_price = $product->can_be_exhibit_to_amazon_jp_price; //最新出品価格
                $product->amazon_jp_latest_exhibit_quantity = $this->my->amazon_stock; //最新出品数量
                $product->amazon_jp_has_exhibited = true; //AmazonJP出品済みフラグ
                $product->amazon_is_in_checklist = false; //Amazon CheckList に入っているかどうか
                $product->amazon_latest_check_at = Carbon::now(); //最新チェック日時

                // プライムリードタイムを設定
                $product->amazon_jp_leadtime_to_ship = $this->my->amazon_lead_time_prime;
                if ($product->maximum_hours_us && $product->maximum_hours_us > $this->my->amazon_lead_time_more) {
                    $product->amazon_jp_leadtime_to_ship = $this->my->amazon_lead_time_more;
                }
                if ($product->maximum_hours_us && $product->maximum_hours_us < $product->amazon_lead_time_less) {
                    $product->amazon_jp_leadtime_to_ship = $this->my->amazon_lead_time_less;
                }
                // maximumHours_usが24の倍数以外のものが表示されている場合で、pp_usで数字が入っているものに関してはプライムリードタイムを採用
                // 24の倍数以外のものが表示されている場合で、PP_USに数字が入っていないものに関しては、リードタイム(○日未満の場合)（短い方）を採用
                if ($product->maximum_hours_us % 24 != 0) {
                    if ($product->pp_us) {
                        $product->amazon_jp_leadtime_to_ship = $this->my->amazon_lead_time_prime;
                    } else {
                        $product->amazon_jp_leadtime_to_ship = $this->my->amazon_lead_time_less;
                    }
                }
                

                $product->save();
            }
        }
        

        $exhibitToAmazonJPJobs = array();
        array_push($exhibitToAmazonJPJobs, new ExhibitToAmazonJP($this->exhibitToAmazonJPProductBatch));

        $batch = Bus::batch($exhibitToAmazonJPJobs)->name("exhibit_to_amazon_jp_" . $this->my->getJobSuffix())->then(function (Batch $batch) {
            // すべてのジョブが正常に完了
        })->catch(function (Batch $batch, Throwable $e) {
            // バッチジョブの失敗をはじめて検出
        })->finally(function (Batch $batch) {
            // バッチジョブの完了
            $productBatch = ProductBatch::where('job_batch_id', $batch->id)->first();
            $productBatch->finished_at = now();
            $productBatch->save();
        })->onQueue('exhibit_to_amazon_jp_' . $this->my->getJobSuffix())->allowFailures()->dispatch();

        
        $this->exhibitToAmazonJPProductBatch->job_batch_id = $batch->id;
        $this->exhibitToAmazonJPProductBatch->save();
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
