<?php

namespace App\Services;

require_once(__DIR__ . "/../../vendor/autoload.php");

use YConnect\Constant\ResponseType;
use YConnect\Credential\ClientCredential;
use YConnect\YConnectClient;
use App\Models\Product;
use App\Models\AmazonProductImage;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use CURLFile;
use Illuminate\Support\Facades\File;

class YahooService
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function refreshUserAccessToken()
    {
        $yahoo_client_id = $this->user->yahoo_client_id;
        $yahoo_secret = $this->user->yahoo_secret;

        $cred = new ClientCredential($yahoo_client_id, $yahoo_secret);
        $client = new YConnectClient($cred);

        $client->refreshAccessToken($this->user->yahoo_refresh_token);

        $accessToken  = $client->getAccessToken();
        $yahoo_access_token_expires_in = date("Y-m-d H:i:s", $client->getAccessTokenExpiration() + time());
        //トークンの保存
        $this->user->yahoo_access_token = $accessToken;
        $this->user->yahoo_access_token_expires_in = $yahoo_access_token_expires_in;
        $this->user->save();
    }

    public function getUserAccessToken()
    {
        if (strtotime($this->user->yahoo_access_token_expires_in) - 60 < time()) {
            $this->refreshUserAccessToken();
        }
        return $this->user->yahoo_access_token;
    }

    public function uploadProductImages(Product $product)
    {
        $returnStr = "";
        // $api = 'https://test.circus.shopping.yahooapis.jp/ShoppingWebService/V1/uploadItemImage?seller_id='. $this->user->yahoo_store_account;
        $api = 'https://circus.shopping.yahooapis.jp/ShoppingWebService/V1/uploadItemImage?seller_id=' . $this->user->yahoo_store_account;

        $accessToken = $this->getUserAccessToken();

        $header = [
            "Authorization: Bearer " . $accessToken,
            "cache-control: no-cache"
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15', 'Referer: http://someaddress.tld', 'Content-Type: multipart/form-data'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // stop verifying certificate
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); // enable posting
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // if any redirection after upload
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $productImageURLs = $product->getAmazonUSImageURLs();
        for ($i = 0; $i < count($productImageURLs); $i++) {
            $productImageURL = $productImageURLs[$i];
            if ($i == 0) {
                $filename = $this->getItemCode($product) . ".jpg";
            } else {
                $filename = $this->getItemCode($product) . "_" . $i . ".jpg";
            }
            $path = AmazonProductImage::getPathByURL($productImageURL);
            
            $cfile = curl_file_create($path, 'image/jpeg', $filename);
            $imgdata = array('file' => $cfile);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $imgdata); // post images
            $result = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_error($ch);
        }

        curl_close($ch);

        if ($httpcode == 200) {
            $returnStr = "OK";
        } else {
            throw new \Exception("Yahoo uploadProductImages API Error Code: " . $httpcode);
        }
        return $returnStr;
    }

    public function uploadItemImagePack($product)
    {
        $productImageURLs = $product->getAmazonUSImageURLs();
        $zipFileFolder = storage_path() . "/app/yahoo_product_images/";
        if(!File::isDirectory($zipFileFolder))
            File::makeDirectory($zipFileFolder, 0777, true, true);
        $zipFileName = $zipFileFolder . $this->getItemCode($product) . ".zip";
        $zip = new ZipArchive();
        $result = $zip->open($zipFileName, ZipArchive::CREATE);
        if($result === true)
        {
            for ($i = 0; $i < count($productImageURLs); $i++) {
                $productImageURL = $productImageURLs[$i];
                if ($i == 0) {
                    $filename = $this->getItemCode($product) . ".jpg";
                } else {
                    $filename = $this->getItemCode($product) . "_" . $i . ".jpg";
                }
                $path = AmazonProductImage::getPathByURL($productImageURL);
                
                $zip->addFile($path, $filename);
            }
            $zip->close();
        }

        //$api = 'https://test.circus.shopping.yahooapis.jp/ShoppingWebService/V1/uploadItemImagePack?seller_id='.$sellerid;
        $api = 'https://circus.shopping.yahooapis.jp/ShoppingWebService/V1/uploadItemImagePack?seller_id=' . $this->user->yahoo_store_account;
        $accessToken = $this->getUserAccessToken();
        $header = [
            'Content-Type: multipart/form-data',
            "Authorization: Bearer " . $accessToken,
            "cache-control: no-cache"
        ];
        $cfile = new CURLFile($zipFileName);
        $imgdata = array('file' => $cfile);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15', 'Referer: http://someaddress.tld', 'Content-Type: multipart/form-data'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // stop verifying certificate
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); // enable posting
        curl_setopt($ch, CURLOPT_POSTFIELDS, $imgdata); // post images
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // if any redirection after upload
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        if ($httpcode == 200) {
            $returnStr = "OK";
        } else {
            throw new \Exception("Yahoo uploadItemImagePack API Error Code: " . $httpcode);
        }
        //}
        return $returnStr;
    }
    

    private function getItemCode($product)
    {
        $itemCode = $product->asin;
        if (empty($itemCode)) {
            throw new \Exception("Yahoo itemCode is empty");
        }
        return $itemCode;
    }

    public function editItem($product)
    {
        //API DOC: https://developer.yahoo.co.jp/webapi/shopping/editItem.html

        $returnStr = "";
        // $api = 'https://test.circus.shopping.yahooapis.jp/ShoppingWebService/V1/editItem';
        $api = 'https://circus.shopping.yahooapis.jp/ShoppingWebService/V1/editItem';
        $yahooAccessToken = $this->getUserAccessToken();
        $header = [
            'Authorization: Bearer ' . $yahooAccessToken,
        ];
        $params = array(
            'access_token' => $yahooAccessToken,
            'seller_id' => $this->user->yahoo_store_account,
            'item_code' => $this->getItemCode($product),
            'path' => $product->yahoo_jp_path,
            'product_category' => $product->yahoo_jp_product_category,
            'name' => $product->title_jp ? $product->title_jp : $product->title_us,
            'price' => $product->yahoo_jp_latest_exhibit_price,
        );

        if (!empty($this->user->yahoo_exhibit_comment_group)) {
            $params['caption'] = $this->user->yahoo_exhibit_comment_group;
        }

        $ch = curl_init($api);
        curl_setopt_array($ch, array(
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_POSTFIELDS     => http_build_query($params),
        ));
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        var_dump($result);
        if ($httpcode == 200) {
            $xml = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
            $returnStr = "OK";
            if ($xml->Result->Status != "OK") {
                $returnStr = "Yahoo出品エラー";
                if (!empty($xml->Result->Error[0]->Message)) {
                    $returnStr = $xml->Result->Error[0]->Message;
                }
            }
        } else {
            throw new \Exception("Yahoo itemRegist API Error Code: " . $httpcode);
        }
        return $returnStr;
    }

    public function reservePublish()
    {
        $returnStr = "";
        //$api = 'https://test.circus.shopping.yahooapis.jp/ShoppingWebService/V1/reservePublish';
        $api = 'https://circus.shopping.yahooapis.jp/ShoppingWebService/V1/reservePublish';
        $yahooAccessToken = $this->getUserAccessToken();
        $header = [
            'Authorization: Bearer ' . $yahooAccessToken
        ];
        $params = array(
            'seller_id' => $this->user->yahoo_store_account,
            'mode' => 1
        );
        $ch = curl_init($api);
        curl_setopt_array($ch, array(
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_POSTFIELDS     => http_build_query($params),
        ));
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        var_dump($result);

        if ($httpcode == 200) {
            $returnStr = "OK";
        } else {
            throw new \Exception("Yahoo reservePublish API Error Code: " . $httpcode);
        }

        return $returnStr;
    }

    public function deleteItem($sellerid, $yahooAccessToken, $itemcodelist)
    {
        $returnStr = "";
        //$api = 'https://test.circus.shopping.yahooapis.jp/ShoppingWebService/V1/deleteItem';
        $api = 'https://circus.shopping.yahooapis.jp/ShoppingWebService/V1/deleteItem';
        $header = [
            'Authorization: Bearer ' . $yahooAccessToken
        ];
        $itemcodestr = implode(",", $itemcodelist);
        $params = array(
            'seller_id' => $sellerid,
            'item_code' => $itemcodestr
        );
        $ch = curl_init($api);
        curl_setopt_array($ch, array(
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_POSTFIELDS     => http_build_query($params),
        ));
        $result = curl_exec($ch);
        //    var_dump($result);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        if ($httpcode == 200) {
            $returnStr = "OK";
        } else if ($httpcode == 401) {
            $returnStr = "INVALID";
        } else {
            $returnStr = "ERROR";
        }
        return $returnStr;
    }

    public function updateItemsPrice($products)
    {
        //API DOC: https://developer.yahoo.co.jp/webapi/shopping/updateItems.html
        $returnStr = "";
        //$api = 'https://test.circus.shopping.yahooapis.jp/ShoppingWebService/V1/updateItems';
        $api = 'https://circus.shopping.yahooapis.jp/ShoppingWebService/V1/updateItems';
        $yahooAccessToken = $this->getUserAccessToken();
        $header = [
            'Authorization: Bearer ' . $yahooAccessToken
        ];
        $partProducts = array_chunk($products, 100, true);

        foreach ($partProducts as $partProduct) {
            sleep(1);

            $params = array(
                'seller_id' => $this->user->yahoo_store_account,
            );
            $count = 0;
            foreach ($partProduct as $product) {
                $count = $count + 1;
                $params['item' . $count] = 'item_code=' . $this->getItemCode($product) . '&price=' . $product->yahoo_jp_latest_exhibit_price . '&sale_price=';
            }
            $ch = curl_init($api);
            curl_setopt_array($ch, array(
                CURLOPT_POST           => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => $header,
                CURLOPT_POSTFIELDS     => http_build_query($params),
            ));
            $result = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_error($ch);
            var_dump($result);
            curl_close($ch);
            if ($httpcode == 200 || $httpcode == 400) {
                $xml = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
                if ($xml->Status == "OK") {
                    $returnStr = "OK";
                } else {
                    $returnStr = array();
                    $resultarray = json_decode(json_encode($xml), TRUE);
                    foreach ($resultarray as $resultitem) {
                        foreach ((array)$resultitem as $tempitem) {
                            if (array_key_exists('ErrorKey', (array)$tempitem)) {
                                $returnStr[] = $tempitem['ErrorKey'];
                            }
                        }
                    }
                }
            } else {
                throw new \Exception("Yahoo changePrice API Error Code: " . $httpcode);
            }
        }
    }

    public function setStock($products)
    {
        //API DOCS: https://developer.yahoo.co.jp/webapi/shopping/setStock.html

        $returnStr = "";
        //$api = 'https://test.circus.shopping.yahooapis.jp/ShoppingWebService/V1/setStock';
        $api = 'https://circus.shopping.yahooapis.jp/ShoppingWebService/V1/setStock';
        $yahooAccessToken = $this->getUserAccessToken();
        $header = [
            'Authorization: Bearer ' . $yahooAccessToken
        ];

        $partProducts = array_chunk($products, 1000, true);

        foreach ($partProducts as $partProduct) {
            sleep(1);

            $itemCodes = array();
            $quantities = array();
            $allowOverdrafts = array();

            foreach ($partProduct as $product) {
                $itemCodes[] = $this->getItemCode($product);
                $quantities[] = $product->yahoo_jp_latest_exhibit_quantity;
                $allowOverdrafts[] = 0;
            }

            $params = array(
                'seller_id' => $this->user->yahoo_store_account,
                'item_code' => implode(",", $itemCodes),
                'quantity' => implode(",", $quantities),
                'allow_overdraft' => implode(",", $allowOverdrafts)
            );
            $ch = curl_init($api);
            curl_setopt_array($ch, array(
                CURLOPT_POST           => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => $header,
                CURLOPT_POSTFIELDS     => http_build_query($params),
            ));
            $result = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_error($ch);
            curl_close($ch);
            var_dump($result);
            if ($httpcode == 200) {
                $xml = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
                if (isset($xml['ok']) && intval($xml['ok']) > 0) {
                    $returnStr = "OK";
                } else {
                    throw new \Exception("Yahoo setStock API Error Result: " . $xml);
                }
            } else {
                throw new \Exception("Yahoo setStock API Error Code: " . $httpcode);
            }
        }
    }

    public static function getCategoryList($sellerid, $yahooAccessToken, $page_key = "")
    {
        $resultArray = array();
        $api = 'https://circus.shopping.yahooapis.jp/ShoppingWebService/V1/stCategoryList';
        $header = [
            'GET /ShoppingWebService/V1/stCategoryList?seller_id=' . $sellerid . ' HTTP/1.1',
            'Host: circus.shopping.yahooapis.jp',
            'Authorization: Bearer ' . $yahooAccessToken
        ];
        $ch = curl_init($api . '?seller_id=' . $sellerid . '&page_key=' . $page_key);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $header
        ));
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode == 200) {
            $xml = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
            //        var_dump($xml);
            $results = $xml->Result;
            foreach ($results as $result) {
                $tempArray = array();
                $tempArray['name'] = (string)$result->Name;
                $tempArray['pagekey'] = (string)$result->PageKey;
                $resultArray[] = $tempArray;
            }
        }
        return $resultArray;
    }
}
