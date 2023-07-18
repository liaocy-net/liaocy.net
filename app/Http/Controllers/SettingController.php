<?php

namespace App\Http\Controllers;

require_once(__DIR__ . "/../../../vendor/autoload.php");

use App\Models\ForeignShipping;
use App\Models\Setting;
use App\Models\User;
use App\Models\YahooJpCategory;
use App\Services\UtilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use SplFileObject;
use YConnect\Constant\OIDConnectDisplay;
use YConnect\Constant\OIDConnectPrompt;
use YConnect\Constant\OIDConnectScope;
use YConnect\Constant\ResponseType;
use YConnect\Credential\ClientCredential;
use YConnect\YConnectClient;

class SettingController extends Controller
{
    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('setting.index', [
            'my' => User::find(auth()->id())
        ]);
    }
    /**
     * Yahoo認証
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        try {
            $params = $request->all();
            $my = User::find(auth()->id());
            if ($params["act"] === "common_setting") {

                $validator = Validator::make($params, [
                    'common_currency_rate' => ['required', 'integer', 'min:1', 'max:999999'],
                    'common_country_shipping' => ['required', 'integer', 'min:0', 'max:999999'],
                    'common_foreign_shipping_without_weight' => ['required', 'integer', 'min:0', 'max:999999'],
                    'common_customs_tax' => ['required', 'numeric', 'min:0', 'max:100'],
                    'common_purchase_price_from' => ['required', 'integer', 'min:1', 'max:999999'],
                    'common_purchase_price_to' => ['required', 'integer', 'min:1', 'max:999999'],
                    'common_max_weight' => ['required', 'integer', 'min:1', 'max:999999'],
                    'common_size_from' => ['required', 'integer', 'min:0', 'max:999999'],
                    'common_size_to' => ['required', 'integer', 'min:1', 'max:999999'],
                    'common_purchase_mark' => ['required', 'numeric', 'min:0', 'max:100']
                ]);

                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }

                $my->common_currency_rate = $params["common_currency_rate"];
                $my->common_country_shipping = $params["common_country_shipping"];
                $my->common_foreign_shipping_without_weight = $params["common_foreign_shipping_without_weight"];
                $my->common_customs_tax = $params["common_customs_tax"] / 100;
                $my->common_purchase_price_from = $params["common_purchase_price_from"];
                $my->common_purchase_price_to = $params["common_purchase_price_to"];
                $my->common_max_weight = $params["common_max_weight"];
                $my->common_size_from = $params["common_size_from"];
                $my->common_size_to = $params["common_size_to"];
                $my->common_purchase_mark = $params["common_purchase_mark"] / 100;
                $my->save();

                if ($request->hasFile('common_foreign_shipping')) {
                    //Adminのみがアップロードできるようにする
                    if (!$my->role === 'admin') {
                        throw new \Exception("不正なアクセスです。");
                    }

                    //拡張子がxlsxであるかの確認
                    if ($request->common_foreign_shipping->getClientOriginalExtension() !== "xlsx") {
                        throw new \Exception("不適切な拡張子です。EXCEL(xlsx)ファイルを選択してください。");
                    }
                    //Excelファイルを読み込み
                    $reader = IOFactory::createReader("Xlsx");
                    $spreadsheet = $reader->load($request->common_foreign_shipping);
                    
                    //シートの読み込み
                    $foreignShippings = array();
                    $sheet = $spreadsheet->getSheet(0);
                    $headers = $sheet->rangeToArray('A1:B1', null, true, false);
                    if (count($headers[0]) != 2 || strcmp($headers[0][0], "重量(lbs)") !== 0 || strcmp($headers[0][1], "費用(USD)") !== 0) {
                        throw new \Exception("EXCELファイルのフォーマットが不適切です。もう一度ダウンロードしてください。");
                    }
                    $rows = $sheet->rangeToArray('A2:B' . $sheet->getHighestRow(), null, true, false);
                    foreach ($rows as $row) {
                        if (count($row) != 2 || !is_numeric($row[0]) || !is_numeric($row[1])) {
                            throw new \Exception("EXCELファイルのフォーマットが不適切です。もう一度ダウンロードしてください。");
                        }
                        $foreignShippings[] = array(
                            "weight_kg" => UtilityService::convertLbsToKg($row[0]),
                            "usd_fee" => $row[1]
                        );
                    }
                    //DBのデータを削除
                    ForeignShipping::truncate();
                    ForeignShipping::insert($foreignShippings);
                }


                return redirect()->route('setting.index')->with('success', '共通設定を更新しました。');
            } elseif ($params["act"] === "amazon_setting") {
                $validator = Validator::make($params, [
                    'amazon_hope_profit' => ['required', 'integer', 'min:0', 'max:999999'],
                    'amazon_min_profit' => ['required', 'integer', 'min:0', 'max:999999', 'lt:amazon_hope_profit'],
                    'amazon_hope_profit_rate' => ['required', 'integer', 'min:0', 'max:500'],
                    'amazon_min_profit_rate' => ['required', 'integer', 'min:0', 'max:500', 'lt:amazon_hope_profit_rate'],
                    'amazon_using_profit' => ['required', 'integer', 'min:1', 'max:2'],
                    'amazon_using_sale_commission' => ['required', 'integer', 'min:0', 'max:999999'],
                    'amazon_stock' => ['required', 'integer', 'min:0', 'max:999999'],
                    'amazon_price_increase_rate' => ['required', 'integer', 'min:100', 'max:9999'],
                    'amazon_rival' => ['required', 'integer', 'min:1', 'max:2'],
                    'amazon_point_rate' => ['required', 'integer', 'min:0', 'max:999999'],
                    'amazon_lead_time_less' => ['required', 'integer', 'min:1', 'max:100'],
                    'amazon_lead_time_more' => ['required', 'integer', 'min:1', 'max:100', 'gte:amazon_lead_time_less'],
                    'amazon_lead_time_prime' => ['required', 'integer', 'min:0', 'max:999999'],
                    'amazon_white_list_brand' => ['required', 'integer', 'min:0', 'max:999999'],
                    'amazon_exhibit_comment_group' => ['nullable', 'string', 'max:99999'],
                    'amazon_price_cut' => ['nullable', 'integer',  'min:1', 'max:99999'],
                    'global_amazon_lead_time' => ['integer',  'min:1', 'max:99999'],
                ]);

                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }

                if($my->role === 'admin' && $params["global_amazon_lead_time"] !== null){
                    Setting::setInt('global_amazon_lead_time', $params["global_amazon_lead_time"]);
                }


                //アマゾン手数料率 - アマゾン手数料率 * アマゾンポイント比率はマイナスにならない
                $amazon_commission_rate = 1 - $params["amazon_using_sale_commission"] / 100 * 1.1;
        
                if ($amazon_commission_rate - $amazon_commission_rate * $params["amazon_point_rate"] / 100 <= 0) {
                    return back()->withErrors(['amazon_using_sale_commission' => 'アマゾン手数料率とアマゾンポイント比率の組み合わせが不適切で修正してください。'])->withInput();
                }

                if ($amazon_commission_rate - $params["amazon_hope_profit_rate"] / 100 - $amazon_commission_rate * $params["amazon_point_rate"] / 100 <= 0) {
                    return back()->withErrors(['amazon_hope_profit_rate' => '希望利益率が適正値ではありませんので修正ください。'])->withInput();
                }

                if ($amazon_commission_rate - $params["amazon_min_profit_rate"] / 100 - $amazon_commission_rate * $params["amazon_point_rate"] / 100 <= 0) {
                    return back()->withErrors(['amazon_hope_profit_rate' => '最低利益率が適正値ではありませんので修正ください。'])->withInput();
                }

                

                $my->amazon_hope_profit = $params["amazon_hope_profit"];
                $my->amazon_min_profit = $params["amazon_min_profit"];
                $my->amazon_hope_profit_rate = $params["amazon_hope_profit_rate"] / 100;
                $my->amazon_min_profit_rate = $params["amazon_min_profit_rate"] / 100;
                $my->amazon_using_profit = $params["amazon_using_profit"];
                $my->amazon_using_sale_commission = $params["amazon_using_sale_commission"] / 100;
                $my->amazon_stock = $params["amazon_stock"];
                $my->amazon_price_increase_rate = $params["amazon_price_increase_rate"] / 100;
                $my->amazon_rival = $params["amazon_rival"];
                $my->amazon_point_rate = $params["amazon_point_rate"] / 100;
                $my->amazon_lead_time_less = $params["amazon_lead_time_less"];
                $my->amazon_lead_time_more = $params["amazon_lead_time_more"];
                $my->amazon_lead_time_prime = $params["amazon_lead_time_prime"];
                $my->amazon_white_list_brand = $params["amazon_white_list_brand"];
                $my->amazon_exhibit_comment_group = $params["amazon_exhibit_comment_group"];
                $my->amazon_price_cut = $params["amazon_price_cut"];
                $my->save();

                return redirect()->route('setting.index', ['#divAmazonSetting'])->with('success', 'Amazon設定を更新しました。');

            } elseif ($params["act"] === "yahoo_setting") {
                $validator = Validator::make($params, [
                    'yahoo_min_profit' => ['required', 'integer', 'min:1', 'max:999999'],
                    'yahoo_profit_rate' => ['required', 'numeric', 'min:0', 'max:100'],
                    'yahoo_using_profit' => ['required', 'integer', 'min:1', 'max:2'],
                    'yahoo_using_sale_commission' => ['required', 'integer', 'min:0', 'max:100'],
                    'yahoo_stock' => ['required', 'integer', 'min:0', 'max:999999'],
                    'yahoo_exhibit_comment_group' => ['required', 'string', 'max:99999'],
                ]);

                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }

                $my->yahoo_min_profit = $params["yahoo_min_profit"];
                $my->yahoo_profit_rate = $params["yahoo_profit_rate"] / 100;
                $my->yahoo_using_profit = $params["yahoo_using_profit"];
                $my->yahoo_using_sale_commission = $params["yahoo_using_sale_commission"] / 100;
                $my->yahoo_stock = $params["yahoo_stock"];
                $my->yahoo_exhibit_comment_group = $params["yahoo_exhibit_comment_group"];
                $my->save();

                if ($request->hasFile('yahoo_category')) {
                    //Adminのみがアップロードできるようにする
                    if (!$my->role === 'admin') {
                        throw new \Exception("不正なアクセスです。");
                    }

                    //拡張子がxlsxであるかの確認
                    if ($request->yahoo_category->getClientOriginalExtension() !== "csv") {
                        throw new \Exception("不適切な拡張子です。EXCEL(xlsx)ファイルを選択してください。");
                    }

                    $fileData = file_get_contents($request->yahoo_category); 
                    $fileData = preg_replace("/\r\n|\r/","\n",$fileData); // 読込み行ごとの改行パターンを置換
                    $encode = mb_detect_encoding($fileData, ['SJIS-win','UTF-8']); // 文字コード判定
                    if($encode == 'SJIS-win'){
                        $fileData = mb_convert_encoding($fileData,'UTF-8','SJIS-win'); // 区別してエンコード
                    }

                    $tmpName = tempnam(sys_get_temp_dir(), "csv"); 
                    file_put_contents($tmpName,$fileData); // $tmpNameにファイルデータを詰めてからSplFileObjectで読込む
                    
                    $file = new SplFileObject($tmpName);
                    $file->setFlags(
                        SplFileObject::READ_CSV |         // CSV 列として行を読み込む
                        SplFileObject::READ_AHEAD |       // 先読み/巻き戻しで読み出す。
                        SplFileObject::SKIP_EMPTY |       // 空行は読み飛ばす
                        SplFileObject::DROP_NEW_LINE      // 行末の改行を読み飛ばす
                    );
                    $yahooJpCategories = array();
                    foreach ($file as $rowIndex => $row) {
                        if ($rowIndex === 0) {
                            if($row[0] !== "id" || $row[2] !== "path_name") {
                                throw new \Exception("不適切なYahoo JP カテゴリファイルフォマットです。");
                            }
                        } else {
                            if (count($row) != 5 || !is_numeric($row[0]) || $row[0] > 99999 || $row[0] < 1 || strlen($row[0]) > 160) {
                                throw new \Exception("不適切なYahoo JP カテゴリファイルフォマットです。" . ($rowIndex + 1) . "行目のidが不正です。" . $row[0]);
                            }
                            $yahooJpCategories[] = array(
                                "product_category" => $row[0],
                                "path" => str_replace(" > ", ":", $row[2]),
                            );
                        }
                    }

                    // Storage::putFile('/yahoo_cagetory_file', $request->yahoo_category);
                    $request->yahoo_category->storeAs(
                        'yahoo_category_file/', 'yahoo_category_file.csv'
                    );
                    
                    //DBのデータを削除
                    YahooJpCategory::truncate();
                    YahooJpCategory::insert($yahooJpCategories);
                }

                return redirect()->route('setting.index', ['#divYahooSetting'])->with('success', 'Yahoo設定を更新しました。');

            } elseif ($params["act"] === "yahoo_auth") {

                $validator = Validator::make($params, [
                    'yahoo_store_account' => ['required', 'string', 'max:255'],
                    'yahoo_client_id' => ['required', 'string', 'max:255'],
                    'yahoo_secret' => ['required', 'string', 'max:255']
                ]);

                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }

                $yahoo_client_id = $params['yahoo_client_id'];
                $yahoo_secret = $params['yahoo_secret'];

                $cred = new ClientCredential($yahoo_client_id, $yahoo_secret);
                $client = new YConnectClient($cred);

                $state = $this->generateRandomString(36);
                $nonce = $this->generateRandomString(52);


                $my->yahoo_store_account = $params['yahoo_store_account'];
                $my->yahoo_client_id = $params['yahoo_client_id'];
                $my->yahoo_secret = $params['yahoo_secret'];
                $my->yahoo_state = $state;
                $my->yahoo_nonce = $nonce;
                $my->save();

                $redirect_uri = route('setting.yahoo_callback');

                $response_type = ResponseType::CODE;
                $scope = array(
                    OIDConnectScope::OPENID,
                    OIDConnectScope::PROFILE,
                    OIDConnectScope::EMAIL,
                    OIDConnectScope::ADDRESS
                );
                $display = OIDConnectDisplay::DEFAULT_DISPLAY;
                $prompt = array(
                    OIDConnectPrompt::DEFAULT_PROMPT
                );

                $client->requestAuth(
                    $redirect_uri,
                    $state,
                    $nonce,
                    $response_type,
                    $scope,
                    $display,
                    $prompt
                );
            }
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function yahooCallback(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'state' => ['required', 'string', 'max:255'],
                'code' => ['required', 'string', 'max:255']
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $params = $request->all();
            $yahoo_state = $params['state'];

            $my = Auth::user();

            $yahoo_client_id = $my->yahoo_client_id;
            $yahoo_secret = $my->yahoo_secret;

            $cred = new ClientCredential($yahoo_client_id, $yahoo_secret);
            $client = new YConnectClient($cred);

            $state = $my->yahoo_state;
            $nonce = $my->yahoo_nonce;
            $redirect_uri = route('setting.yahoo_callback');

            $code_result = $client->getAuthorizationCode($state);
            if( $code_result ) {
                // Tokenエンドポイントにリクエスト
                $client->requestAccessToken(
                    $redirect_uri,
                    $code_result
                );
                // IDトークンを検証(4)
                $accessToken  = $client->getAccessToken();
                $client->verifyIdToken($nonce, $accessToken);
                $refreshToken = $client->getRefreshToken();
                $yahoo_access_token_expires_in = date("Y-m-d H:i:s", $client->getAccessTokenExpiration() + time());
                $yahoo_refresh_token_expires_in = date("Y-m-d H:i:s", strtotime("+25 day"));
                //トークンの保存
                $my->yahoo_access_token = $accessToken;
                $my->yahoo_refresh_token = $refreshToken;
                $my->yahoo_access_token_expires_in = $yahoo_access_token_expires_in;
                $my->yahoo_refresh_token_expires_in = $yahoo_refresh_token_expires_in;
                $my->save();

                return redirect()->route('setting.index')->with('success', 'Yahoo認証しました。');
                
            }
        } catch (\Exception $e) {
            $my->yahoo_access_token = null;
            $my->yahoo_refresh_token = null;
            $my->yahoo_access_token_expires_in = null;
            $my->yahoo_refresh_token_expires_in = null;
            $my->save();
            return redirect()->route('setting.index')->withErrors('success', 'Yahoo認証が失敗しました。もう一度お試しください。');
        }
    }

    public function amazonCallback(Request $request)
    {
        try {
            $params = $request->all();

            $validator = Validator::make($params, [
                'spapi_oauth_code' => ['required', 'string'],
                'selling_partner_id' => ['required', 'string'],
                'state' => ['required', 'string']
            ]);

            if ($validator->fails()) {
                throw new \Exception("Amazon認証が失敗しました。もう一度お試しください。");
            }

            $my = Auth::user();

            

            $spapi_oauth_code = $params['spapi_oauth_code'];
            $selling_partner_id = $params['selling_partner_id'];
            $state = $params['state'];
            $states = explode(':',$state);
            if ($states[0] == "jp") {
                $region = 'jp';
                $client_id = env("AMAZON_JP_CLIENT_ID");
                $client_secret = env("AMAZON_JP_CLIENT_SECRET");
            } elseif ($states[0] == "us") {
                $region = 'us';
                $client_id = env("AMAZON_US_CLIENT_ID");
                $client_secret = env("AMAZON_US_CLIENT_SECRET");
            } else {
                throw new \Exception("Amazon認証が失敗しました。もう一度お試しください。");
            }

            
            
            $client = new \GuzzleHttp\Client();
            $headers = [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'charset' => 'UTF-8'
            ];
            $res = $client->request('POST',
                "https://api.amazon.com/auth/o2/token?grant_type=authorization_code&code=".$spapi_oauth_code."&redirect_uri=".route("setting.index")."&client_id=".$client_id."&client_secret=".$client_secret, $headers);
            $res = json_decode($res->getBody()->getContents(), true);

            if(is_array($res) && array_key_exists('refresh_token',$res)){
                if ($region == "jp") {
                    $my->amazon_jp_refresh_token = $res['refresh_token'];
                    $my->amazon_jp_access_token = $res['access_token'];
                    $my->amazon_jp_access_token_expires_in = date("Y-m-d H:i:s", $res['expires_in'] + time());
                    $my->amazon_jp_selling_partner_id = $selling_partner_id;
                    $my->save();
                } elseif ($region == "us") {
                    $my->amazon_us_refresh_token = $res['refresh_token'];
                    $my->amazon_us_access_token = $res['access_token'];
                    $my->amazon_us_access_token_expires_in = date("Y-m-d H:i:s", $res['expires_in'] + time());
                    $my->amazon_us_selling_partner_id = $selling_partner_id;
                    $my->save();
                }
            } else {
                throw new \Exception("Amazon認証が失敗しました。もう一度お試しください。");
            }
            return redirect()->route('setting.index')->with('success', 'Amazon ' . strtoupper($region) . 'を認証しました。');
        } catch (\Exception $e) {
            return redirect()->route('setting.index')->withErrors('success', $e->getMessage());
        }
    }
}
