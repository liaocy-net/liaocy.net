<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\AmazonService;
use App\Models\Product;
use AmazonPHP\SellingPartner\Exception\ApiException;
use App\Services\UtilityService;
use Carbon\Carbon;

class UpdateAmazonInfo implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->product->amazon_latest_check_at = Carbon::now(); //最新チェック日時
            $this->product->amazon_is_in_checklist = false;
            $this->product->save();

            UtilityService::updateUSAmazonInfo($this->product);
            UtilityService::updateJPAmazonInfo($this->product);

            $this->product->message = "正常";
            $this->product->is_exhibit_to_amazon_jp = true;
            $this->product->save();

            $user = $this->product->user;

            $canBeExhibitToAmazonJP = UtilityService::canBeExhibitToAmazonJP($user, $this->product);
            if ($canBeExhibitToAmazonJP["canBeExhibit"]) {
                $newPrice = $canBeExhibitToAmazonJP["exhibitPrice"]; //最新出品価格

                if ($newPrice != $this->product->amazon_jp_latest_exhibit_price) {
                    //出品価格が変更されている場合は、出品価格を更新する
                    $this->product->amazon_jp_latest_exhibit_price = $newPrice;
                    $this->product->amazon_jp_need_update_exhibit_info = true;
                    $this->product->save();
                }
            } else {
                //出品できない場合は、在庫を0にする
                $this->product->amazon_jp_latest_exhibit_quantity = 0;
                $this->product->amazon_jp_need_update_exhibit_info = true;
                $this->product->save();
            }
        } catch (\Exception $e) {
            if ($this->attempts() < $this->tries) {
                $this->release(10);
            } else {
                //異常が起こした場合は、在庫を0にする
                $this->product->amazon_jp_latest_exhibit_quantity = 0;
                $this->product->amazon_jp_need_update_exhibit_info = true;
                $this->product->save();
                throw $e;
            }
        }
    }

    public function failed($exception)
    {
        if (env('APP_DEBUG', 'false') == 'true') {
            var_dump($exception->getMessage());
        }
    }
}
