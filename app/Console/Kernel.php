<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\RefreshYahooAPIAuth;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Jobs\UpdateAmazonInfo;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use App\Models\ProductBatch;
use App\Jobs\UpdateAmazonJPExhibit;
use Throwable;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $this->updateAmazonInfo();
        })->hourlyAt(0);

        $schedule->call(function () {
            $this->UpdateAmazonJPExhibit();
        })->hourlyAt(30);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * 24時間以上経過している商品のAmazon情報を更新する
     *
     * @return void
     */
    protected function updateAmazonInfo()
    {
        //チェック対象の商品を取得
        $products = Product::where([
            ["amazon_jp_has_exhibited", true], //AmazonJPへ出品済み
            ["amazon_is_in_checklist", "!=", true], //チェックキューに入っていない
            ["amazon_latest_check_at", "<", Carbon::now()->subHour(24)], //最終チェックから24時間以上経過
            ["is_deleted", "=", false], //削除されていない
        ])->cursor();

        foreach($products as $product) {
            $product->amazon_is_in_checklist = true; //チェックキューに入った
            $product->save();

            UpdateAmazonInfo::dispatch($product)->onQueue('default'); //キューに追加
        }
    }

    /**
     * AmazonJP出品情報改定
     *
     * @return void
     */
    protected function updateAmazonJPExhibit() {
        $product_users = Product::select("user_id")
            ->where([
                ["amazon_jp_need_update_exhibit_info", true], //AmazonJPAmazonJPへ出品情報更新要
            ])->groupBy("user_id")->cursor();

        foreach($product_users as $product_user) {

            $products = Product::where([
                ["user_id", $product_user->user_id],
                ["amazon_jp_need_update_exhibit_info", true],
            ])->limit(1000)->cursor();

            $productBatch = new ProductBatch();
            $productBatch->user_id = auth()->id();
            $productBatch->action = "update_amazon_jp_exhibit";
            $productBatch->save();

            
            foreach($products as $product) {
                $product->amazon_jp_need_update_exhibit_info = false; //AmazonJPへ出品情報更新要フラグをリセット
                $product->save();

                $product->productBatches()->attach($productBatch);
            }

            $updateAmazonJPExhibits = array();
            array_push($updateAmazonJPExhibits, new UpdateAmazonJPExhibit($productBatch));

            $batch = Bus::batch($updateAmazonJPExhibits)->name("update_amazon_jp_exhibit")->then(function (Batch $batch) {
                // すべてのジョブが正常に完了
            })->catch(function (Batch $batch, Throwable $e) {
                // バッチジョブの失敗をはじめて検出
            })->finally(function (Batch $batch) {
                // バッチジョブの完了
                $productBatch = ProductBatch::where('job_batch_id', $batch->id)->first();
                $productBatch->finished_at = now();
                $productBatch->save();
            })->onQueue('high')->allowFailures()->dispatch();

            $productBatch->job_batch_id = $batch->id;
            $productBatch->save();
        }
    }
}
