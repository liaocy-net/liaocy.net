<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\RefreshYahooAPIAuth;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Jobs\UpdateAmazonInfo;

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
}
