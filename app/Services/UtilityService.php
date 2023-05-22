<?php

namespace App\Services;

use App\Models\Product;

class UtilityService
{
    public function __construct()
    {
        // ...
    }

    public static function genRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function genRandomFileName()
    {
        // datetime str
        return date("YmdHis", time()) . UtilityService::genRandomString(5);
        
    }

    public static function getExtractAmazonInfoPatchStatus($jobBatch)
    {
        if (!empty($jobBatch->finished_at)) {
            return "取得完了";
        } elseif ($jobBatch->total_jobs == $jobBatch->failed_jobs) {
            return "取得完了(全て失敗)";
        } elseif ($jobBatch->pending_jobs != 0 && $jobBatch->pending_jobs == $jobBatch->failed_jobs) {
            return "取得完了(部分失敗)";
        } else {
            return "取得中";
        }
    }

    public static function getExhibitPatchStatus($jobBatch)
    {
        if (!empty($jobBatch->finished_at)) {
            return "出品完了";
        } elseif ($jobBatch->total_jobs == $jobBatch->failed_jobs) {
            return "出品完了(全て失敗)";
        } elseif ($jobBatch->pending_jobs != 0 && $jobBatch->pending_jobs == $jobBatch->failed_jobs) {
            return "出品完了(部分失敗)";
        } else {
            return "出品中";
        }
    }

    public static function getProductsCSV($products) {
        $headers = [
            "asin",
            "ap_jp",
            "title_jp",
            "title_us",
            "brand_jp",
            "brand_us",
            "cate_us",
            "color_us",
            "cp_jp",
            "cp_point",
            "cp_us",
            "imgurl01",
            "imgurl02",
            "imgurl03",
            "imgurl04",
            "imgurl05",
            "imgurl06",
            "imgurl07",
            "imgurl08",
            "imgurl09",
            "imgurl10",
            "isAmazon_jp",
            "isAmazon_us",
            "materialtype_us",
            "maximumHours_jp",
            "maximumHours_us",
            "minimumHours_jp",
            "minimumHours_us",
            "model_us",
            "nc_jp",
            "nc_us",
            "np_jp",
            "np_us",
            "pp_jp",
            "pp_us",
            "rankid_jp",
            "rank_jp",
            "rank_us",
            "sellerFeedbackCount",
            "sellerFeedbackRating",
            "sellerId",
            "shippingcost",
            "size_h_us",
            "size_l_us",
            "size_w_us",
            "size_us",
            "weight_us",
        ];
        $csv = join(',',$headers) . "\n";
        foreach($products as $product){
            $csv .= $product->asin . ",";
            $csv .= $product->ap_jp . ",";
            $csv .= $product->title_jp . ",";
            $csv .= $product->title_us . ",";
            $csv .= $product->brand_jp . ",";
            $csv .= $product->brand_us . ",";
            $csv .= $product->cate_us . ",";
            $csv .= $product->color_us . ",";
            $csv .= $product->cp_jp . ",";
            $csv .= $product->cp_point . ",";
            $csv .= $product->cp_us . ",";
            $csv .= $product->img_url_01 . ",";
            $csv .= $product->img_url_02 . ",";
            $csv .= $product->img_url_03 . ",";
            $csv .= $product->img_url_04 . ",";
            $csv .= $product->img_url_05 . ",";
            $csv .= $product->img_url_06 . ",";
            $csv .= $product->img_url_07 . ",";
            $csv .= $product->img_url_08 . ",";
            $csv .= $product->img_url_09 . ",";
            $csv .= $product->img_url_10 . ",";
            $csv .= $product->is_amazon_jp . ",";
            $csv .= $product->is_amazon_us . ",";
            $csv .= $product->material_type_us . ",";
            $csv .= $product->maximum_hours_jp . ",";
            $csv .= $product->maximum_hours_us . ",";
            $csv .= $product->minimum_hours_jp . ",";
            $csv .= $product->minimum_hours_us . ",";
            $csv .= $product->model_us . ",";
            $csv .= $product->nc_jp . ",";
            $csv .= $product->nc_us . ",";
            $csv .= $product->np_jp . ",";
            $csv .= $product->np_us . ",";
            $csv .= $product->pp_jp . ",";
            $csv .= $product->pp_us . ",";
            $csv .= $product->rank_id_jp . ",";
            $csv .= $product->rank_jp . ",";
            $csv .= $product->rank_us . ",";
            $csv .= $product->seller_feedback_count . ",";
            $csv .= $product->seller_feedback_rating . ",";
            $csv .= $product->seller_id . ",";
            $csv .= $product->shipping_cost . ",";
            $csv .= $product->size_h_us . ",";
            $csv .= $product->size_l_us . ",";
            $csv .= $product->size_w_us . ",";
            $csv .= $product->size_us . ",";
            $csv .= $product->weight_us . ",";
            $csv .= "\n";
        }
        # convert to SHIFT-JIS
        $csv = mb_convert_encoding($csv, 'SJIS', 'UTF-8');
        return $csv;
    }

    public static function calExhibitPrice($user, $product) {
        $exhibitPrice = $product->cp_us;
        // 為替
        $exhibitPrice = $exhibitPrice * $user->common_currency_rate;

        // 切り上げ
        $exhibitPrice = ceil($exhibitPrice);
        return $exhibitPrice;
    }

    public static function genSKU(Product $product)
    {
        return $product->asin;
    }
}
