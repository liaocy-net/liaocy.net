<?php

namespace App\Services;

use App\Models\ForeignShipping;
use App\Models\Product;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\WhiteList;
use App\Models\BlackList;
use AmazonPHP\SellingPartner\Exception\ApiException;
use Illuminate\Support\Facades\Log;

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
        if (!empty($jobBatch->cancelled_at)){
            return "取得停止";
        } elseif (!empty($jobBatch->finished_at)) {
            return "取得完了";
        } elseif ($jobBatch->total_jobs == $jobBatch->failed_jobs) {
            return "取得完了(全て失敗)";
        } elseif ($jobBatch->pending_jobs != 0 && $jobBatch->pending_jobs == $jobBatch->failed_jobs) {
            return "取得完了(部分成功)";
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
            return "出品完了(部分成功)";
        } else {
            return "出品中";
        }
    }

    public static function getUpdatePatchStatus($jobBatch)
    {
        if (!empty($jobBatch->finished_at)) {
            return "改定完了";
        } elseif ($jobBatch->total_jobs == $jobBatch->failed_jobs) {
            return "改定完了(全て失敗)";
        } elseif ($jobBatch->pending_jobs != 0 && $jobBatch->pending_jobs == $jobBatch->failed_jobs) {
            return "改定完了(部分成功)";
        } else {
            return "改定中";
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
        $sheet->setTitle('Amazon商品情報');

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
            $sheet->setCellValue('AD' . ($index + 2), is_null($product->nc_jp) ? -1 : $product->nc_jp);
            $sheet->setCellValue('AE' . ($index + 2), is_null($product->nc_us) ? -1 : $product->nc_us);
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
            $sheet->setCellValue('AQ' . ($index + 2), is_null($product->size_h_us) ? null : self::convertCmToInch($product->size_h_us));
            $sheet->setCellValue('AR' . ($index + 2), is_null($product->size_l_us) ? null : self::convertCmToInch($product->size_l_us));
            $sheet->setCellValue('AS' . ($index + 2), is_null($product->size_w_us) ? null : self::convertCmToInch($product->size_w_us));
            $sheet->setCellValue('AT' . ($index + 2), $product->size_us);
            $sheet->setCellValue('AU' . ($index + 2), is_null($product->weight_us) ? null : self::convertKgToLbs($product->weight_us));
        }
        return $spreadsheet;
    }

    // AmazonJP利益額Ver価格計算
    public static function getHopePriceAmazonJP($user, $product) {
        // AmazonJPの利益額価格
        $amazonJPHopePrice = self::calAmazonJPHopePrice($user, $product);
        if ($amazonJPHopePrice == null) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'Amazon JPの希望利益価格が算出できない'
            );
        }

        // AmazonJPの最低利益額価格
        $amazonJPMinHopePrice = self::calAmazonJPMinHopePrice($user, $product);
        if ($amazonJPMinHopePrice == null) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'Amazon JPの最低販売価格が算出できない'
            );
        }

        // ライバルフラグ
        if ($user->amazon_rival == 1) { // ライバルフラグがON
            if ($product->nc_jp > 0) { //ライバル存在
                if ($amazonJPMinHopePrice > $product->cp_jp) { // AmazonJPの最低希望販売価格 > ライバル出品価格
                    return array(
                        'canBeExhibit' => false,
                        'exhibitPrice' => null,
                        'message' => '最低利益価格がライバル価格より高いため'
                    );
                } else { // AmazonJPの最低希望販売価格 <= ライバル出品価格
                    $price_cut = $product->cp_jp - $user->amazon_price_cut; // ライバル最低価格から値下げした価格
                    if ($price_cut >= $amazonJPMinHopePrice) {
                        return array(
                            'canBeExhibit' => true,
                            'exhibitPrice' => $price_cut,
                            'message' => '出品可能（利益額Verでライバル価格から値下げして出品）'
                        );
                    } else {
                        return array(
                            'canBeExhibit' => false,
                            'exhibitPrice' => null,
                            'message' => '最低利益率価格へ値下げてもライバルより高い'
                        );
                    }                    
                }
            } else { //ライバル存在しない
                return array(
                    'canBeExhibit' => true,
                    'exhibitPrice' => ceil($amazonJPHopePrice * $user->amazon_price_increase_rate),
                    'message' => '出品可能（ライバルフラグがON、ライバルなし、希望利益価格+値上げで出品）'
                );
            }
        } else {
            if ($product->nc_jp > 0) { //ライバル存在
                return array(
                    'canBeExhibit' => false,
                    'exhibitPrice' => null,
                    'message' => 'ライバルフラグがOFFなのにライバルが存在する'
                );
            } else { //ライバル存在しない
                return array(
                    'canBeExhibit' => true,
                    'exhibitPrice' => $amazonJPHopePrice,
                    'message' => '出品可能（ライバルフラグがOFFでライバルがない）'
                );
            }
        }
    }

    // YahooJP利益額Ver価格計算
    public static function getHopePriceYahooJP($user, $product) {

        // YahooJPの最低利益額価格
        $yahooJPMinHopePrice = self::calYahooJPMinHopePrice($user, $product);
        if ($yahooJPMinHopePrice == null) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'Yahoo JPの最低販売価格が算出できない'
            );
        }

        return array(
            'canBeExhibit' => true,
            'exhibitPrice' => $yahooJPMinHopePrice,
            'message' => '出品可能（最低利益額価格で出品）'
        );
    }

    // AmazonJP利益率Ver価格計算
    public static function getRatePriceAmazonJP($user, $product) {
        // AmazonJPの利益額価格
        $amazonJPHopePrice = self::calAmazonJPHopePrice($user, $product);
        if ($amazonJPHopePrice == null) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'Amazon JPの希望利益価格が算出できない'
            );
        }

        // AmazonJPの最低利益額価格
        $amazonJPMinHopePrice = self::calAmazonJPMinHopePrice($user, $product);
        if ($amazonJPMinHopePrice == null) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'Amazon JPの最低販売価格が算出できない'
            );
        }

        // AmazonJPの利益率価格
        $amazonJPRatePrice = self::calAmazonJPRatePrice($user, $product);
        if ($amazonJPRatePrice == null) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'Amazon JPの希望利益率価格が算出できない'
            );
        }

        // AmazonJPの最低希望利益率価格
        $amazonJPMinRatePrice = self::calAmazonJPMinRatePrice($user, $product);
        if ($amazonJPMinRatePrice == null) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'Amazon JPの最低希望利益率価格が算出できない'
            );
        }

        if ($amazonJPMinRatePrice > $amazonJPMinHopePrice) { // 最低希望利益率価格 > 最低希望利益価格
            // ライバルフラグ
            if ($user->amazon_rival == 1) { // ライバルフラグがON
                if ($product->nc_jp > 0) { //ライバル存在
                    if ($amazonJPMinRatePrice > $product->cp_jp) { // AmazonJPの最低希望販売価格 > ライバル出品価格
                        return array(
                            'canBeExhibit' => false,
                            'exhibitPrice' => null,
                            'message' => '最低利益率価格がライバル価格より高いため'
                        );
                    } else {
                        if ($amazonJPRatePrice < $product->cp_jp) {
                            return array(
                                'canBeExhibit' => true,
                                'exhibitPrice' => $amazonJPRatePrice,
                                'message' => '出品可能（希望利益率価格で出品）'
                            );
                        }

                        $price_cut = $product->cp_jp - $user->amazon_price_cut;
                        if ($price_cut >= $amazonJPMinRatePrice) {
                            return array(
                                'canBeExhibit' => true,
                                'exhibitPrice' => $price_cut,
                                'message' => '出品可能（利益率Verでライバル最低価格から値下げして出品）'
                            );
                        } else {
                            return array(
                                'canBeExhibit' => false,
                                'exhibitPrice' => null,
                                'message' => '最低利益率価格へ値下げてもライバルより高い'
                            );
                        }
                    }
                } else { //ライバル存在しない
                    return array(
                        'canBeExhibit' => true,
                        'exhibitPrice' => ceil($amazonJPRatePrice * $user->amazon_price_increase_rate),
                        'message' => '出品可能（ライバルフラグがON、ライバルなし、希望利益率価格+値上げで出品）'
                    );
                }
            } else { // ライバルフラグがOFF
                if ($product->nc_jp > 0) { //ライバル存在
                    return array(
                        'canBeExhibit' => false,
                        'exhibitPrice' => null,
                        'message' => 'ライバルフラグがOFFなのにライバルが存在する'
                    );
                } else { //ライバル存在しない
                    return array(
                        'canBeExhibit' => true,
                        'exhibitPrice' => $amazonJPRatePrice,
                        'message' => '出品可能（ライバルフラグがOFF、ライバルなし、希望額で出品）'
                    );
                }
            }
        } else {
            return self::getHopePriceAmazonJP($user, $product);
        }
    }

    // YahooJP利益率Ver価格計算
    public static function getRatePriceYahooJP($user, $product) {
        // YahooJPの最低利益額価格
        $yahooJPMinRatePrice = self::calYahooJPMinRatePrice($user, $product);
        if ($yahooJPMinRatePrice == null) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'Yahoo JPの最低販売価格が算出できない'
            );
        }

        // YahooJPの利益率価格
        return array(
            'canBeExhibit' => true,
            'exhibitPrice' => $yahooJPMinRatePrice,
            'message' => '出品可能（最低利益率価格で出品）'
        );
    }

    // AmazonJPで出品できるか
    public static function canBeExhibitToAmazonJP($user, $product){

        if($product->is_amazon_jp == false){
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'Amazon JPから情報を取得できない'
            );
        }
        if($product->is_amazon_us == false){
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'Amazon USから情報を取得できない'
            );
        }

        // 削除されました。
        if($product->cancel_exhibit_to_amazon_jp){
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'AmazonJP出品から削除されました'
            );
        }
        
        // ブランドがホワイトリストに入っているか
        $whiteListCount = WhiteList::where('user_id', $user->id)->count();
        $whiteList = WhiteList::where('user_id', $user->id)->where('brand', $product->brand_jp)->first();
        if ($whiteListCount > 0 && !$whiteList) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'ブランドがホワイトリストに入っていません'
            );
        }

        // ASINがブラックリストに入っているか
        $asinBlackList = BlackList::where('user_id', $user->id)->where('platform', 'amazon')->where('on', 'asin')->where('value', $product->asin)->first();
        if ($asinBlackList) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'ASINがブラックリストにブロックされています'
            );
        }
        // ブランドがブラックリストに入っているか
        $brandBlackList = BlackList::where('user_id', $user->id)->where('platform', 'amazon')->where('on', 'brand')->where('value', $product->brand_jp)->first();
        if ($brandBlackList) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'ブランドがブラックリストにブロックされています'
            );
        }
        // カテゴリがブラックリストに入っているか
        $categoryBlackList = BlackList::where('user_id', $user->id)->where('platform', 'amazon')->where('on', 'category')->where('value', $product->cate_us)->first();
        if ($categoryBlackList) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'カテゴリがブラックリストにブロックされています'
            );
        }
        // タイトルがブラックリストに入っているか
        $titleBlackList = BlackList::where('user_id', $user->id)->where('platform', 'amazon')->where('on', 'title')->where('value', $product->title_jp)->first();
        if ($titleBlackList) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'タイトルがブラックリストにブロックされています'
            );
        }


        // 仕入れ価格が取得できません
        if ($product->cp_us == null && $product->np_us == null) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => '仕入れ価格が取得できません'
            );
        }

        // 仕入れ価格よりも低い
        if ($product->cp_us < $user->common_purchase_price_from) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => '仕入れ価格下限よりも低い'
            );
        }

        // 仕入れ価格よりも高い
        if ($product->cp_us > $user->common_purchase_price_to) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => '仕入れ価格上限よりも高い'
            );
        }

        // 取扱い最大重量よりも重い
        if ($product->weight_us > $user->common_max_weight) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => '取扱い最大重量よりも重い'
            );
        }

        if ($product->size_h_us && $product->size_l_us && $product->size_w_us) {
            $sum_size_cm = $product->size_h_us + $product->size_l_us + $product->size_w_us;
            // サイズの下限よりも小さい
            if ($sum_size_cm < $user->common_size_from) {
                return array(
                    'canBeExhibit' => false,
                    'exhibitPrice' => null,
                    'message' => 'サイズの下限よりも小さい'
                );
            }

            // サイズの上限よりも大きい
            if ($sum_size_cm > $user->common_size_to) {
                return array(
                    'canBeExhibit' => false,
                    'exhibitPrice' => null,
                    'message' => 'サイズの上限よりも大きい'
                );
            }
        }

        //価格計算
        if ($user->amazon_using_profit == 1) { // 利益額Ver
            return self::getHopePriceAmazonJP($user, $product);
        } else if ($user->amazon_using_profit == 2) { // 利益率Ver
            return self::getRatePriceAmazonJP($user, $product);
        } else {
            throw new \Exception('amazon_using_profit is invalid');
        }

        return array(
            'canBeExhibit' => false,
            'exhibitPrice' => null,
            'message' => '未知エラーが起こるため出品不可'
        );
    }

    // Yahoo Japan で出品できるか
    public static function canBeExhibitToYahooJP($user, $product){

        if($product->is_amazon_us == false){
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'Amazon USから情報を取得できない'
            );
        }

        // 削除されました。
        if($product->cancel_exhibit_to_yahoo_jp){
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'YahooJP出品から削除されました'
            );
        }

        // ASINがブラックリストに入っているか
        $asinBlackList = BlackList::where('user_id', $user->id)->where('platform', 'yahoo')->where('on', 'asin')->where('value', $product->asin)->first();
        if ($asinBlackList) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'ASINがブラックリストにブロックされています'
            );
        }
        // ブランドがブラックリストに入っているか
        $brandBlackList = BlackList::where('user_id', $user->id)->where('platform', 'yahoo')->where('on', 'brand')->where('value', $product->brand_jp)->first();
        if ($brandBlackList) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'ブランドがブラックリストにブロックされています'
            );
        }
        // カテゴリがブラックリストに入っているか
        $categoryBlackList = BlackList::where('user_id', $user->id)->where('platform', 'yahoo')->where('on', 'category')->where('value', $product->cate_us)->first();
        if ($categoryBlackList) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'カテゴリがブラックリストにブロックされています'
            );
        }
        // タイトルがブラックリストに入っているか
        $titleBlackList = BlackList::where('user_id', $user->id)->where('platform', 'yahoo')->where('on', 'title')->where('value', $product->title_jp)->first();
        if ($titleBlackList) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => 'タイトルがブラックリストにブロックされています'
            );
        }


        // 仕入れ価格が取得できません
        if ($product->cp_us == null) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => '仕入れ価格が取得できません'
            );
        }

        // 仕入れ価格よりも低い
        if ($product->cp_us < $user->common_purchase_price_from) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => '仕入れ価格下限よりも低い'
            );
        }

        // 仕入れ価格よりも高い
        if ($product->cp_us > $user->common_purchase_price_to) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => '仕入れ価格上限よりも高い'
            );
        }

        // 取扱い最大重量よりも重い
        if ($product->weight_us > $user->common_max_weight) {
            return array(
                'canBeExhibit' => false,
                'exhibitPrice' => null,
                'message' => '取扱い最大重量よりも重い'
            );
        }

        if ($product->size_h_us && $product->size_l_us && $product->size_w_us) {
            $sum_size_cm = $product->size_h_us + $product->size_l_us + $product->size_w_us;
            // サイズの下限よりも小さい
            if ($sum_size_cm < $user->common_size_from) {
                return array(
                    'canBeExhibit' => false,
                    'exhibitPrice' => null,
                    'message' => 'サイズの下限よりも小さい'
                );
            }

            // サイズの上限よりも大きい
            if ($sum_size_cm > $user->common_size_to) {
                return array(
                    'canBeExhibit' => false,
                    'exhibitPrice' => null,
                    'message' => 'サイズの上限よりも大きい'
                );
            }
        }

        //価格計算
        if ($user->yahoo_using_profit == 1) { // 利益額Ver
            return self::getHopePriceYahooJP($user, $product);
        } else if ($user->yahoo_using_profit == 2) { // 利益率Ver
            return self::getRatePriceYahooJP($user, $product);
        } else {
            throw new \Exception('yahoo_using_profit is invalid');
        }

        return array(
            'canBeExhibit' => false,
            'exhibitPrice' => null,
            'message' => '未知エラーが起こるため出品不可'
        );
    }

    // 国際送料計算
    public static function calForeignShipping($user, $product) {
        $weightKG = $product->weight_us;
        if ($weightKG) {
            $foreignShipping = ForeignShipping::where('weight_kg', '>=', $weightKG)->orderBy('weight_kg', 'asc')->first();
            if ($foreignShipping) {
                return $foreignShipping->usd_fee * $user->common_currency_rate;
            }
        }
        return $user->common_foreign_shipping_without_weight;
    }

    // 【希望】Amazon（利益額Ver）
    public static function calAmazonJPHopePrice($user, $product) {
        // + 希望利益額
        $numerator = $user->amazon_hope_profit;
        // + US価格（※） * 為替 * 関税消費税率
        // 関税消費税率 = 1 + 関税消費税率設定値（%）
        $numerator += self::getPurchasePriceUS($product) * $user->common_currency_rate * (1 + $user->common_customs_tax);
        // + 国際送料
        $numerator += self::calForeignShipping($user, $product);
        // + 国内送料
        $numerator += $user->common_country_shipping;
        
        // アマゾン手数料率 = 1 - (Amazon手数料率設定値% * 1.1)
        $amazon_commission_rate = 1 - $user->amazon_using_sale_commission * 1.1;
        // アマゾン手数料率 - アマゾン手数料率 * アマゾンポイント比率
        $denominator = $amazon_commission_rate - $amazon_commission_rate * $user->amazon_point_rate;

        return ceil($numerator / $denominator);
    }

    // 【最低】Amazon（利益額Ver）
    public static function calAmazonJPMinHopePrice($user, $product) {
        // + 最低利益額
        $numerator = $user->amazon_min_profit;
        // + US価格（※） * 為替 * 関税消費税率
        // 関税消費税率 = 1 + 関税消費税率設定値（%）
        $numerator += self::getPurchasePriceUS($product) * $user->common_currency_rate * (1 + $user->common_customs_tax);
        // + 国際送料
        $numerator += self::calForeignShipping($user, $product);
        // + 国内送料
        $numerator += $user->common_country_shipping;
        
        // アマゾン手数料率 = 1 - (Amazon手数料率設定値% * 1.1)
        $amazon_commission_rate = 1 - $user->amazon_using_sale_commission * 1.1;
        // アマゾン手数料率 - アマゾン手数料率 * アマゾンポイント比率
        $denominator = $amazon_commission_rate - $amazon_commission_rate * $user->amazon_point_rate;

        return ceil($numerator / $denominator);
    }

    // 【希望】Amazon（利益率Ver）
    public static function calAmazonJPRatePrice($user, $product) {
        // US価格（※） * 為替 * 関税消費税率
        // 関税消費税率 = 1 + 関税消費税率設定値（%）
        $numerator = self::getPurchasePriceUS($product) * $user->common_currency_rate * (1 + $user->common_customs_tax);
        // + 国際送料
        $numerator += self::calForeignShipping($user, $product);
        // + 国内送料
        $numerator += $user->common_country_shipping;
        
        // アマゾン手数料率 = 1 - (Amazon手数料率設定値% * 1.1)
        $amazon_commission_rate = 1 - $user->amazon_using_sale_commission * 1.1;
        // アマゾン手数料率 - 希望利益率 - アマゾン手数料率 * アマゾンポイント比率
        $denominator = $amazon_commission_rate - $user->amazon_hope_profit_rate - $amazon_commission_rate * $user->amazon_point_rate;

        return ceil($numerator / $denominator);
    }

    // 【最低】Amazon（利益率Ver）
    public static function calAmazonJPMinRatePrice($user, $product) {
        // US価格（※） * 為替 * 関税消費税率
        // 関税消費税率 = 1 + 関税消費税率設定値（%）
        $numerator = self::getPurchasePriceUS($product) * $user->common_currency_rate * (1 + $user->common_customs_tax);
        // + 国際送料
        $numerator += self::calForeignShipping($user, $product);
        // + 国内送料
        $numerator += $user->common_country_shipping;
        
        // アマゾン手数料率 = 1 - (Amazon手数料率設定値% * 1.1)
        $amazon_commission_rate = 1 - $user->amazon_using_sale_commission * 1.1;
        // アマゾン手数料率 - 最低利益率 - アマゾン手数料率 * アマゾンポイント比率
        $denominator = $amazon_commission_rate - $user->amazon_min_profit_rate - $amazon_commission_rate * $user->amazon_point_rate;

        return ceil($numerator / $denominator);
    }

    // 【最低】Amazon（利益額Ver）
    public static function calYahooJPMinHopePrice($user, $product) {
        // + 最低利益額
        $numerator = $user->yahoo_min_profit;
        // + US価格（※） * 為替 * 関税消費税率
        // 関税消費税率 = 1 + 関税消費税率設定値（%）
        $numerator += self::getPurchasePriceUS($product) * $user->common_currency_rate * (1 + $user->common_customs_tax);
        // + 国際送料
        $numerator += self::calForeignShipping($user, $product);
        // + 国内送料
        $numerator += $user->common_country_shipping;
        
        // アマゾン手数料率 = 1 - (Amazon手数料率設定値% * 1.1)
        $denominator = 1 - $user->yahoo_using_sale_commission * 1.1;

        return ceil($numerator / $denominator);
    }

    // 【最低】Amazon（利益率Ver）
    public static function calYahooJPMinRatePrice($user, $product) {

        // US価格（※） * 為替 * 関税消費税率
        // 関税消費税率 = 1 + 関税消費税率設定値（%）
        $numerator = self::getPurchasePriceUS($product) * $user->common_currency_rate * (1 + $user->common_customs_tax);
        // + 国際送料
        $numerator += self::calForeignShipping($user, $product);
        // + 国内送料
        $numerator += $user->common_country_shipping;
        
        // アマゾン手数料率 = 1 - (Amazon手数料率設定値% * 1.1) - Yahoo利益率
        $denominator = (1 - $user->amazon_using_sale_commission * 1.1) - $user->yahoo_profit_rate;

        return ceil($numerator / $denominator);
    }

    public static function getPurchasePriceUS($product)
    {
        $purchase_price = null;
        if (!is_null($product->cp_us)) {
            $purchase_price = $product->cp_us;
        } else if (!is_null($product->np_us)) {
            $purchase_price = $product->np_us;
        }
        return $purchase_price;
    }

    public static function genSKU(Product $product)
    {
        return $product->asin;
    }

    public static function updateUSAmazonInfo($product)
    {
        $user = $product->user;

        // extract Amazon US info
        $client_id = env("AMAZON_US_CLIENT_ID"); //must fix on prd
        $client_secret = env("AMAZON_US_CLIENT_SECRET"); //must fix on prd
        $amazon_refresh_token = $user->amazon_us_refresh_token;
        $amazonService = new AmazonService(
            $client_id,
            $client_secret,
            $amazon_refresh_token,
            $user,
            "us",
        );

        try {
            // https://developer-docs.amazon.com/sp-api/lang-ja_JP/docs/catalog-items-api-v2022-04-01-reference
            $catalogItem = $amazonService->getCatalogItem($product);
        } catch (ApiException $e) {
            $product->is_amazon_us = false;
            $product->save();
            if ($e->getCode() == 404){
                Log::info("No such product " . $product->asin . " on Amazon US : getCatalogItem " . $e->getCode() . " " . $e->getMessage());
                return;
            } else if ($e->getCode() == 429){
                throw new \Exception("Too many requests " . $product->asin . " on Amazon US : getCatalogItem " . $e->getCode() . " " . $e->getMessage());
            } else {
                throw new \Exception("Exception when getCatalogItem on Amazon US : getCatalogItem " . $e->getCode() . " " . $e->getMessage());
            }
        }

        try {
            sleep(env("AMAZON_API_SLEEP_BEFORE_GET_PRODUCT_PRICING", 0.8));
            // https://developer-docs.amazon.com/sp-api/docs/product-pricing-api-v0-reference
            $productPricing = $amazonService->getProductPricing($product);
        } catch (ApiException $e) {
            $product->is_amazon_us = false;
            $product->save();
            if ($e->getCode() == 404){
                Log::info("No such product " . $product->asin . " on Amazon US : getCatalogItem " . $e->getCode() . " " . $e->getMessage());
                return;
            } else if ($e->getCode() == 429){
                throw new \Exception("Too many requests " . $product->asin . " on Amazon US : getCatalogItem " . $e->getCode() . " " . $e->getMessage());
            } else {
                throw new \Exception("Exception when getCatalogItem on Amazon US " . $e->getCode() . " " . $e->getMessage());
            }
        }

        

        $product->title_us = $catalogItem['title'];
        $product->brand_us = $catalogItem['brand'];
        // $product->cate_us = $catalogItem['cate'];
        $product->color_us = $catalogItem['color'];

        $product->img_url_01 = $catalogItem['img_url_01'];
        $product->img_url_02 = $catalogItem['img_url_02'];
        $product->img_url_03 = $catalogItem['img_url_03'];
        $product->img_url_04 = $catalogItem['img_url_04'];
        $product->img_url_05 = $catalogItem['img_url_05'];
        $product->img_url_06 = $catalogItem['img_url_06'];
        $product->img_url_07 = $catalogItem['img_url_07'];
        $product->img_url_08 = $catalogItem['img_url_08'];
        $product->img_url_09 = $catalogItem['img_url_09'];
        $product->img_url_10 = $catalogItem['img_url_10'];
        $product->material_type_us = $catalogItem['material_type'];
        $product->model_us = $catalogItem['model'];
        $product->rank_us = $catalogItem['rank'];
        $product->size_h_us = $catalogItem['size_h'];
        $product->size_l_us = $catalogItem['size_l'];
        $product->size_w_us = $catalogItem['size_w'];
        $product->size_us = $catalogItem['size'];
        $product->weight_us = $catalogItem['weight'];


        $product->maximum_hours_us = $productPricing['maximum_hours'];
        $product->minimum_hours_us = $productPricing['minimum_hours'];
        $product->cp_us = $productPricing['cp'];
        $product->nc_us = $productPricing['nc'];
        $product->pp_us = $productPricing['pp'];
        $product->np_us = $productPricing['np'];

        $product->is_amazon_us = true;
        $product->save();
    }

    public static function updateJPAmazonInfo($product)
    {
        $user = $product->user;

        // extract Amazon JP info
        $client_id = env("AMAZON_JP_CLIENT_ID");
        $client_secret = env("AMAZON_JP_CLIENT_SECRET");
        $amazon_refresh_token = $user->amazon_jp_refresh_token;
        $amazonService = new AmazonService(
            $client_id,
            $client_secret,
            $amazon_refresh_token,
            $user,
            "jp",
        );

        try {
            // https://developer-docs.amazon.com/sp-api/lang-ja_JP/docs/catalog-items-api-v2022-04-01-reference
            $catalogItem = $amazonService->getCatalogItem($product);
        } catch (ApiException $e) {
            $product->is_amazon_jp = false;
            $product->save();
            if ($e->getCode() == 404){
                Log::info("No such product " . $product->asin . " on Amazon JP : getCatalogItem " . $e->getCode() . " " . $e->getMessage());
                return;
            } else if ($e->getCode() == 429){
                throw new \Exception("Too many requests " . $product->asin . " on Amazon JP : getCatalogItem " . $e->getCode() . " " . $e->getMessage());
            } else {
                throw new \Exception("Exception when getCatalogItem on Amazon JP : getCatalogItem " . $e->getCode() . " " . $e->getMessage());
            }
        }

        try {
            sleep(env("AMAZON_API_SLEEP_BEFORE_GET_PRODUCT_PRICING", 0.8));
            // https://developer-docs.amazon.com/sp-api/docs/product-pricing-api-v0-reference
            $productPricing = $amazonService->getProductPricing($product);
        } catch (ApiException $e) {
            $product->is_amazon_jp = false;
            $product->save();
            if ($e->getCode() == 404){
                Log::info("No such product " . $product->asin . " on Amazon JP : getCatalogItem " . $e->getCode() . " " . $e->getMessage());
                return;
            } else if ($e->getCode() == 429){
                throw new \Exception("Too many requests " . $product->asin . " on Amazon JP : getCatalogItem " . $e->getCode() . " " . $e->getMessage());
            } else {
                throw new \Exception("Exception when getCatalogItem on Amazon JP " . $e->getCode() . " " . $e->getMessage());
            }
        }

        $product->title_jp = $catalogItem['title'];
        $product->brand_jp = $catalogItem['brand'];
        $product->rank_id_jp = $catalogItem['rank_id'];
        $product->rank_jp = $catalogItem['rank'];

        $product->cp_jp = $productPricing['cp'];
        $product->cp_point = $productPricing['cp_point'];
        $product->maximum_hours_jp = $productPricing['maximum_hours'];
        $product->minimum_hours_jp = $productPricing['minimum_hours'];
        $product->nc_jp = $productPricing['nc'];
        $product->np_jp = $productPricing['np'];
        $product->pp_jp = $productPricing['pp'];
        $product->ap_jp = $productPricing['ap'];
        $product->seller_id = $productPricing['seller_id'];
        $product->shipping_cost = $productPricing['shipping_cost'];

        $product->is_amazon_jp = true;
        $product->save();
    }

    public static function convertLbsToKg($lbs, $precision = 3)
    {
        return round($lbs * 0.45359237, $precision);
    }

    public static function convertKgToLbs($kg, $precision = 3)
    {
        return round($kg / 0.45359237, $precision);
    }

    public static function convertInchToCm($inch, $precision = 3)
    {
        return round($inch * 2.54, $precision);
    }

    public static function convertCmToInch($cm, $precision = 3)
    {
        return round($cm / 2.54, $precision);
    }
}
