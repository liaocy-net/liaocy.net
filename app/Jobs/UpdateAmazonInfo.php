<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\UtilityService;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateAmazonInfo implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 2;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 10;

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
            $this->product->refresh(); //最新のDB情報を取得する

            if ($this->product->amazon_is_in_checklist) {
                $this->product->amazon_latest_check_at = Carbon::now(); //最新チェック日時
                $this->product->amazon_is_in_checklist = false;
                $this->product->save();
                if ($this->product->amazon_jp_has_exhibited == 0 || $this->product->cancel_exhibit_to_amazon_jp == 1) { // AmazonJPから削除した場合は、チェックしない
                    Log::debug("Skip UpdateAmazonInfo for Amazon JP product " . $this->product->sku . " " . $this->product->amazon_jp_has_exhibited . " " . $this->product->cancel_exhibit_to_amazon_jp);
                    return;
                }
            }

            if ($this->product->yahoo_is_in_checklist) {
                $this->product->yahoo_latest_check_at = Carbon::now(); //最新チェック日時
                $this->product->yahoo_is_in_checklist = false;
                $this->product->save();
                if ($this->product->yahoo_jp_has_exhibited == 0 || $this->product->cancel_exhibit_to_yahoo_jp == 1) { // YahooJPから削除した場合は、チェックしない
                    Log::debug("Skip UpdateAmazonInfo for Yahoo JP product " . $this->product->item_code . " " . $this->product->yahoo_jp_has_exhibited . " " . $this->product->cancel_exhibit_to_yahoo_jp);
                    return;
                }
            }

            $user = $this->product->user;

            UtilityService::updateUSAmazonInfo($this->product); //どの場合でも、USAmazonの情報を更新する
            if ($this->product->yahoo_jp_has_exhibited) {
                if ($user->yahoo_jp_should_update_price) {
                    Log::debug("Check UpdateAmazonInfo for Yahoo JP product " . $this->product->item_code);
                    //YahooJP出品可能かどうかをチェックする
                    $canBeExhibitToYahooJP = UtilityService::canBeExhibitToYahooJP($user, $this->product);
                    if ($canBeExhibitToYahooJP["canBeExhibit"]) {
                        $newPrice = $canBeExhibitToYahooJP["exhibitPrice"]; //最新出品価格

                        if ($newPrice != $this->product->yahoo_jp_latest_exhibit_price) {
                            //出品価格が変更されている場合は、出品価格を更新する
                            $this->product->yahoo_jp_latest_exhibit_price = $newPrice;
                            $this->product->yahoo_jp_latest_exhibit_quantity = $user->yahoo_stock;
                            $this->product->yahoo_jp_need_update_exhibit_info = true;
                            $this->product->yahoo_jp_need_update_exhibit_info_reason = "価格改定: " . $canBeExhibitToYahooJP["message"];
                            $this->product->save();
                        }
                    } else {
                        //出品できない場合は、在庫を0にする
                        $this->product->yahoo_jp_latest_exhibit_quantity = 0;
                        $this->product->yahoo_jp_need_update_exhibit_info = true;
                        $this->product->yahoo_jp_need_update_exhibit_info_reason = "在庫切れ: " . $canBeExhibitToYahooJP["message"];
                        $this->product->save();
                    }
                }
            }

            if ($this->product->amazon_jp_has_exhibited) {
                if ($user->amazon_jp_should_update_price) {
                    Log::debug("Check UpdateAmazonInfo for Amazon JP product " . $this->product->sku);
                    UtilityService::updateJPAmazonInfo($this->product); //AmazonJP更新要の場合のみ、AmazonJPの情報を更新する
                    //AmazonJP出品可能かどうかをチェックする
                    $canBeExhibitToAmazonJP = UtilityService::canBeExhibitToAmazonJP($user, $this->product);
                    if ($canBeExhibitToAmazonJP["canBeExhibit"]) {
                        $newPrice = $canBeExhibitToAmazonJP["exhibitPrice"]; //最新出品価格
                        if ($newPrice != $this->product->amazon_jp_latest_exhibit_price) {
                            //出品価格が変更されている場合は、出品価格を更新する
                            $this->product->amazon_jp_latest_exhibit_price = $newPrice;
                            $this->product->amazon_jp_latest_exhibit_quantity = $user->amazon_stock;
                            $this->product->amazon_jp_need_update_exhibit_info = true;
                            $this->product->amazon_jp_need_update_exhibit_info_reason = "価格改定: " . $canBeExhibitToAmazonJP["message"];
                            $this->product->save();
                        }
                    } else {
                        //出品できない場合は、在庫を0にする
                        $this->product->amazon_jp_latest_exhibit_quantity = 0;
                        $this->product->amazon_jp_need_update_exhibit_info = true;
                        $this->product->amazon_jp_need_update_exhibit_info_reason = "在庫切れ: " . $canBeExhibitToAmazonJP["message"];
                        $this->product->save();
                    }
                }
            }
        } catch (\Exception $e) {
            if ($this->attempts() < $this->tries) {
                $this->release(10);
            } else {
                //異常が起こした場合は、在庫を0にする
                $this->product->amazon_jp_latest_exhibit_quantity = 0;
                $this->product->amazon_jp_need_update_exhibit_info = true;
                $this->product->amazon_jp_need_update_exhibit_info_reason = "異常: " . $e->getMessage();
                $this->product->yahoo_jp_latest_exhibit_quantity = 0;
                $this->product->yahoo_jp_need_update_exhibit_info = true;
                $this->product->yahoo_jp_need_update_exhibit_info_reason = "異常: " . $e->getMessage();
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
