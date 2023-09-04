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
use App\Jobs\DownloadAmazonJPProductImages;

class ExtractAmazonInfo implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;
    protected $shouldDownloadImages;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product, $shouldDownloadImages = true)
    {
        $this->product = $product;
        $this->shouldDownloadImages = $shouldDownloadImages;
    }

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 10;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        UtilityService::updateUSAmazonInfo($this->product);

        $imageURLs = $this->product->getAmazonUSImageURLs();

        if ($this->shouldDownloadImages){
            foreach ($imageURLs as $url) {
                DownloadAmazonJPProductImages::dispatch($url)->onQueue('download_amazon_jp_product_images'); //キューに追加
            }
        }

        UtilityService::updateJPAmazonInfo($this->product);

        $user = $this->product->user;
        $this->product->purchase_price_us = UtilityService::getPurchasePriceUS($this->product);
        // Amazon Price
        $this->product->amazon_jp_hope_price_jpy = UtilityService::calAmazonJPHopePrice($user, $this->product);
        $this->product->amazon_jp_rate_price_jpy = UtilityService::calAmazonJPRatePrice($user, $this->product);
        $this->product->amazon_jp_min_hope_price_jpy = UtilityService::calAmazonJPMinHopePrice($user, $this->product);
        $this->product->amazon_jp_min_rate_price_jpy = UtilityService::calAmazonJPMinRatePrice($user, $this->product);
        $canBeExhibitToAmazonJP = UtilityService::canBeExhibitToAmazonJP($user, $this->product);
        $this->product->can_be_exhibit_to_amazon_jp = $canBeExhibitToAmazonJP["canBeExhibit"];
        $this->product->can_be_exhibit_to_amazon_jp_message = $canBeExhibitToAmazonJP["message"];
        $this->product->can_be_exhibit_to_amazon_jp_price = $canBeExhibitToAmazonJP["exhibitPrice"];
        // Yahoo Price
        $this->product->yahoo_jp_min_hope_price_jpy = UtilityService::calYahooJPMinHopePrice($user, $this->product);
        $this->product->yahoo_jp_min_rate_price_jpy = UtilityService::calYahooJPMinRatePrice($user, $this->product);
        $canBeExhibitToYahooJP = UtilityService::canBeExhibitToYahooJP($user, $this->product);
        $this->product->can_be_exhibit_to_yahoo_jp = $canBeExhibitToYahooJP["canBeExhibit"];
        $this->product->can_be_exhibit_to_yahoo_jp_message = $canBeExhibitToYahooJP["message"];
        $this->product->can_be_exhibit_to_yahoo_jp_price = $canBeExhibitToYahooJP["exhibitPrice"];
        // Amazon Profit
        // AmazonJP希望利益額
        $this->product->amazon_hope_profit = $user->amazon_hope_profit;
        // AmazonJP最低利益額
        $this->product->amazon_min_profit = $user->amazon_min_profit;
        // AmazonJP希望利益率
        $this->product->amazon_hope_profit_rate = $user->amazon_hope_profit_rate;
        // AmazonJP最低利益率
        $this->product->amazon_min_profit_rate = $user->amazon_min_profit_rate;
        // Amazon手数料率
        $this->product->amazon_using_sale_commission = $user->amazon_using_sale_commission;
        // Amazon Point比率
        $this->product->amazon_point_rate = $user->amazon_point_rate;
        // 値下げ額
        $this->product->amazon_price_cut = $user->amazon_price_cut;
        // 値上げ率
        $this->product->amazon_price_increase_rate = $user->amazon_price_increase_rate;
        // Yahoo Profit
        // YahooJP最低利益額
        $this->product->yahoo_min_profit = $user->yahoo_min_profit;
        // 利益率
        $this->product->yahoo_profit_rate = $user->yahoo_profit_rate;
        // 販売手数料
        $this->product->yahoo_using_sale_commission = $user->yahoo_using_sale_commission;
        // Common Profit
        // 為替(円)
        $this->product->common_currency_rate = $user->common_currency_rate;
        // 関税消費税
        $this->product->common_customs_tax = $user->common_customs_tax;
        // 国内送料
        $this->product->common_country_shipping = $user->common_country_shipping;
        // 国際送料
        $this->product->foreign_shipping = UtilityService::calForeignShippingUSD($user, $this->product);

        $this->product->save();
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
