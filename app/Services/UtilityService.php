<?php

namespace App\Services;

use App\Models\Product;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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

    public static function getProductsExcel($products) {
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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('ASIN');

        $sheet->fromArray($headers, null, 'A1');

        foreach($products as $index => $product){
            $sheet->setCellValue('A' . ($index + 2), $product->asin);
            $sheet->setCellValue('B' . ($index + 2), $product->ap_jp);
            $sheet->setCellValue('C' . ($index + 2), $product->title_jp);
            $sheet->setCellValue('D' . ($index + 2), $product->title_us);
            $sheet->setCellValue('E' . ($index + 2), $product->brand_jp);
            $sheet->setCellValue('F' . ($index + 2), $product->brand_us);
            $sheet->setCellValue('G' . ($index + 2), $product->cate_us);
            $sheet->setCellValue('H' . ($index + 2), $product->color_us);
            $sheet->setCellValue('I' . ($index + 2), $product->cp_jp);
            $sheet->setCellValue('J' . ($index + 2), $product->cp_point);
            $sheet->setCellValue('K' . ($index + 2), $product->cp_us);
            $sheet->setCellValue('L' . ($index + 2), $product->img_url_01);
            $sheet->setCellValue('M' . ($index + 2), $product->img_url_02);
            $sheet->setCellValue('N' . ($index + 2), $product->img_url_03);
            $sheet->setCellValue('O' . ($index + 2), $product->img_url_04);
            $sheet->setCellValue('P' . ($index + 2), $product->img_url_05);
            $sheet->setCellValue('Q' . ($index + 2), $product->img_url_06);
            $sheet->setCellValue('R' . ($index + 2), $product->img_url_07);
            $sheet->setCellValue('S' . ($index + 2), $product->img_url_08);
            $sheet->setCellValue('T' . ($index + 2), $product->img_url_09);
            $sheet->setCellValue('U' . ($index + 2), $product->img_url_10);
            $sheet->setCellValue('V' . ($index + 2), $product->is_amazon_jp);
            $sheet->setCellValue('W' . ($index + 2), $product->is_amazon_us);
            $sheet->setCellValue('X' . ($index + 2), $product->material_type_us);
            $sheet->setCellValue('Y' . ($index + 2), $product->maximum_hours_jp);
            $sheet->setCellValue('Z' . ($index + 2), $product->maximum_hours_us);
            $sheet->setCellValue('AA' . ($index + 2), $product->minimum_hours_jp);
            $sheet->setCellValue('AB' . ($index + 2), $product->minimum_hours_us);
            $sheet->setCellValue('AC' . ($index + 2), $product->model_us);
            $sheet->setCellValue('AD' . ($index + 2), $product->nc_jp);
            $sheet->setCellValue('AE' . ($index + 2), $product->nc_us);
            $sheet->setCellValue('AF' . ($index + 2), $product->np_jp);
            $sheet->setCellValue('AG' . ($index + 2), $product->np_us);
            $sheet->setCellValue('AH' . ($index + 2), $product->pp_jp);
            $sheet->setCellValue('AI' . ($index + 2), $product->pp_us);
            $sheet->setCellValue('AJ' . ($index + 2), $product->rank_id_jp);
            $sheet->setCellValue('AK' . ($index + 2), $product->rank_jp);
            $sheet->setCellValue('AL' . ($index + 2), $product->rank_us);
            $sheet->setCellValue('AM' . ($index + 2), $product->seller_feedback_count);
            $sheet->setCellValue('AN' . ($index + 2), $product->seller_feedback_rating);
            $sheet->setCellValue('AO' . ($index + 2), $product->seller_id);
            $sheet->setCellValue('AP' . ($index + 2), $product->shipping_cost);
            $sheet->setCellValue('AQ' . ($index + 2), $product->size_h_us);
            $sheet->setCellValue('AR' . ($index + 2), $product->size_l_us);
            $sheet->setCellValue('AS' . ($index + 2), $product->size_w_us);
            $sheet->setCellValue('AT' . ($index + 2), $product->size_us);
            $sheet->setCellValue('AU' . ($index + 2), $product->weight_us);
        }
        return $spreadsheet;
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
