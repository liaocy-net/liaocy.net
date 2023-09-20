<?php

namespace App\Models;

use App\Services\UtilityService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_batch_id',
        'asin',
        'title_jp',
        'title_us',
        'ap_jp',
        'brand_jp',
        'brand_us',
        'cate_us',
        'color_us',
        'cp_jp',
        'cp_point',
        'cp_us',
        'img_url_01',
        'img_url_02',
        'img_url_03',
        'img_url_04',
        'img_url_05',
        'img_url_06',
        'img_url_07',
        'img_url_08',
        'img_url_09',
        'img_url_10',
        'is_amazon_jp',
        'is_amazon_us',
        'material_type_us',
        'maximum_hours_jp',
        'maximum_hours_us',
        'minimum_hours_jp',
        'model_us',
        'nc_jp',
        'nc_us',
        'np_jp',
        'np_us',
        'pp_jp',
        'pp_us',
        'rank_id_jp',
        'rank_jp',
        'rank_us',
        'seller_feedback_count',
        'seller_feedback_rating',
        'seller_id',
        'shipping_cost',
        'size_h_us',
        'size_l_us',
        'size_w_us',
        'size',
        'weight_us',
        'amazon_jp_feed_id',
    ];

    public function productBatches()
    {
        return $this->belongsToMany(ProductBatch::class);
    }

    public function productExhibitHistories()
    {
        return $this->hasMany(ProductExhibitHistory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAmazonUSImageURLs()
    {
        $urls = [];
        for ($i = 1; $i <= 10; $i++) {
            $url = $this["img_url_" . str_pad($i, 2, "0", STR_PAD_LEFT)];
            if ($url) {
                array_push($urls, $url);
            }
        }
        return $urls;
    }

    public function refreshAdditionalInfo()
    {
        $user = $this->user;
        $this->purchase_price_us = UtilityService::getPurchasePriceUS($this);
        // Amazon Price
        $this->amazon_jp_hope_price_jpy = UtilityService::calAmazonJPHopePrice($user, $this);
        $this->amazon_jp_rate_price_jpy = UtilityService::calAmazonJPRatePrice($user, $this);
        $this->amazon_jp_min_hope_price_jpy = UtilityService::calAmazonJPMinHopePrice($user, $this);
        $this->amazon_jp_min_rate_price_jpy = UtilityService::calAmazonJPMinRatePrice($user, $this);
        $canBeExhibitToAmazonJP = UtilityService::canBeExhibitToAmazonJP($user, $this);
        $this->can_be_exhibit_to_amazon_jp = $canBeExhibitToAmazonJP["canBeExhibit"];
        $this->can_be_exhibit_to_amazon_jp_message = $canBeExhibitToAmazonJP["message"];
        $this->can_be_exhibit_to_amazon_jp_price = $canBeExhibitToAmazonJP["exhibitPrice"];
        // Yahoo Price
        $this->yahoo_jp_min_hope_price_jpy = UtilityService::calYahooJPMinHopePrice($user, $this);
        $this->yahoo_jp_min_rate_price_jpy = UtilityService::calYahooJPMinRatePrice($user, $this);
        $canBeExhibitToYahooJP = UtilityService::canBeExhibitToYahooJP($user, $this);
        $this->can_be_exhibit_to_yahoo_jp = $canBeExhibitToYahooJP["canBeExhibit"];
        $this->can_be_exhibit_to_yahoo_jp_message = $canBeExhibitToYahooJP["message"];
        $this->can_be_exhibit_to_yahoo_jp_price = $canBeExhibitToYahooJP["exhibitPrice"];
        // Amazon Profit
        // AmazonJP希望利益額
        $this->amazon_hope_profit = $user->amazon_hope_profit;
        // AmazonJP最低利益額
        $this->amazon_min_profit = $user->amazon_min_profit;
        // AmazonJP希望利益率
        $this->amazon_hope_profit_rate = $user->amazon_hope_profit_rate;
        // AmazonJP最低利益率
        $this->amazon_min_profit_rate = $user->amazon_min_profit_rate;
        // Amazon手数料率
        $this->amazon_using_sale_commission = $user->amazon_using_sale_commission;
        // Amazon Point比率
        $this->amazon_point_rate = $user->amazon_point_rate;
        // 値下げ額
        $this->amazon_price_cut = $user->amazon_price_cut;
        // 値上げ率
        $this->amazon_price_increase_rate = $user->amazon_price_increase_rate;
        // Yahoo Profit
        // YahooJP最低利益額
        $this->yahoo_min_profit = $user->yahoo_min_profit;
        // 利益率
        $this->yahoo_profit_rate = $user->yahoo_profit_rate;
        // 販売手数料
        $this->yahoo_using_sale_commission = $user->yahoo_using_sale_commission;
        // Common Profit
        // 為替(円)
        $this->common_currency_rate = $user->common_currency_rate;
        // 関税消費税
        $this->common_customs_tax = $user->common_customs_tax;
        // 国内送料
        $this->common_country_shipping = $user->common_country_shipping;
        // 国際送料
        $this->foreign_shipping = UtilityService::calForeignShippingUSD($user, $this);

        $this->save();
    }
}
