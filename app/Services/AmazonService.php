<?php

namespace App\Services;

require_once(__DIR__ . "/../../vendor/autoload.php");

use AmazonPHP\SellingPartner\Marketplace;
use AmazonPHP\SellingPartner\AccessToken;
use AmazonPHP\SellingPartner\SellingPartnerSDK;
use Buzz\Client\Curl;
use AmazonPHP\SellingPartner\Configuration;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientExceptionInterface;


class AmazonService
{
    protected $client_id;
    protected $client_secret;
    protected $refresh_token;
    protected $asin;
    protected $nation;
    protected $user;
    protected $region;
    protected $accessToken;
    protected $marketplace_ids = [];

    public function __construct($client_id, $client_secret, $refresh_token, $asin, $nation, $user)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->refresh_token = $refresh_token;
        $this->asin = $asin;
        $this->nation = $nation;
        $this->user = $user;
    }
    
    protected function getSDK() : SellingPartnerSDK
    {
        sleep(0.3);
        $factory = new Psr17Factory();
        $client = new Curl($factory);
        $configuration = Configuration::forIAMUser(
            $this->client_id,
            $this->client_secret,
            env("IAM_ACCESS_KEY"),
            env("IAM_SECRECT_KEY")
        );
        $logger = new Logger('amazon_info');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../../../logs/sp-api-php.log', Logger::DEBUG));
        $sdk = SellingPartnerSDK::create($client, $factory, $factory, $configuration, $logger);

        if ($this->nation == 'us') {
            $this->marketplace_ids = [Marketplace::US()->id()];
            $this->region = Marketplace::US()->region();

            $this->accessToken = new AccessToken(
                $this->user->amazon_us_access_token,
                $this->user->amazon_us_refresh_token,
                'bearer',
                strtotime($this->user->amazon_us_access_token_expires_in) - time(),
                'refresh_token'
            );

            if (strtotime($this->user->amazon_us_access_token_expires_in) - 60 < time()) {
                $this->accessToken = $sdk->oAuth()->exchangeRefreshToken($this->refresh_token);
                $this->user->amazon_us_access_token = $this->accessToken->token();
                $this->user->amazon_us_access_token_expires_in = date('Y-m-d H:i:s', $this->accessToken->expiresIn() + time());
                $this->user->save();
            }

        } elseif ($this->nation == 'jp') {
            $this->marketplace_ids = [Marketplace::JP()->id()];
            $this->region = Marketplace::JP()->region();

            $this->accessToken = new AccessToken(
                $this->user->amazon_jp_access_token,
                $this->user->amazon_jp_refresh_token,
                'bearer',
                strtotime($this->user->amazon_jp_access_token_expires_in) - time(),
                'refresh_token'
            );

            if (strtotime($this->user->amazon_jp_access_token_expires_in) - 60 < time()) {
                $this->accessToken = $sdk->oAuth()->exchangeRefreshToken($this->refresh_token);
                $this->user->amazon_jp_access_token = $this->accessToken->token();
                $this->user->amazon_jp_access_token_expires_in = date('Y-m-d H:i:s', $this->accessToken->expiresIn() + time());
                $this->user->save();
            }
        } else {
            throw new \Exception("nation is not available.");
        }
        return $sdk;
    }

    public function getCatalogItem()
    {
        sleep(0.3);
        $sdk = $this->getSDK();
        $item = $sdk->catalogItem()->getCatalogItem(
            $this->accessToken,
            $this->region,
            $this->asin,
            $this->marketplace_ids,
            // https://developer-docs.amazon.com/sp-api/docs/catalog-items-api-v2020-12-01-reference#includeddata-subgroup-1
            ['attributes', 'identifiers', 'images', 'productTypes', 'salesRanks', 'summaries']
        );

        // return $item;

        $result = array();
        // get title
        if (count($item->getSummaries()) > 0) {
            $result['title'] = $item->getSummaries()[0]->getItemName();
        } else {
            throw new \Exception("title is not available.");
        }

        // get brand
        // get color
        // model モデル
        if (!empty($item->getSummaries()) && count($item->getSummaries()) > 0)
        {
            $result['brand'] = $item->getSummaries()[0]->getBrand();
            $result['color'] = $item->getSummaries()[0]->getColor();
            $result['size'] = $item->getSummaries()[0]->getSize();
            $result['model'] = $item->getSummaries()[0]->getModelNumber();
        } else {
            $result['brand'] = null;
            $result['color'] = null;
            $result['size'] = null;
            $result['model'] = null;
        }

        // rank_jp	日本ランキング
        // rank_id_jp	ランキング順位がある場合のカテゴリ名
        if (
            !empty($item->getSalesRanks()) && 
            count($item->getSalesRanks()) > 0 &&
            !empty($item->getSalesRanks()[0]->getDisplayGroupRanks()) && 
            count($item->getSalesRanks()[0]->getDisplayGroupRanks()) > 0
        ) {
            $result['rank'] = $item->getSalesRanks()[0]->getDisplayGroupRanks()[0]->getRank();
            $result['rank_id'] = $item->getSalesRanks()[0]->getDisplayGroupRanks()[0]->getTitle();
        } else {
            $result['rank'] = null;
            $result['rank_id'] = null;
        }

        // get images
        if (count($item->getImages()) > 0) {
            $imagearray = $item->getImages()[0]->getImages();

            for ($i = 0 ; $i < 10; $i++) {
                if (count($imagearray) > $i) {
                    $result[sprintf('img_url_%02d', $i + 1)] = $imagearray[$i]->getLink();
                } else {
                    $result[sprintf('img_url_%02d', $i + 1)] = null;
                }
            }

        } else {
            throw new \Exception("images is not available.");
        }

        // get size
        if (
            !empty($item->getAttributes()) && 
            array_key_exists('item_package_dimensions', $item->getAttributes()) &&
            count($item->getAttributes()['item_package_dimensions']) > 0
        ) {
            $result['size_l'] = ceil($item->getAttributes()['item_package_dimensions'][0]->length->value);
            $result['size_w'] = ceil($item->getAttributes()['item_package_dimensions'][0]->width->value);
            $result['size_h'] = ceil($item->getAttributes()['item_package_dimensions'][0]->height->value);
        } else {
            $result['size_l'] = null;
            $result['size_w'] = null;
            $result['size_h'] = null;
        }

        // get weight
        if (
            !empty($item->getAttributes()) && 
            array_key_exists('item_package_weight', $item->getAttributes()) &&
            count($item->getAttributes()['item_package_weight']) > 0 &&
            !empty($item->getAttributes()['item_package_weight'][0]->value)
        ) {
            if ($item->getAttributes()['item_package_weight'][0]->unit == 'kilograms') {
                $result['weight'] = $item->getAttributes()['item_package_weight'][0]->value;
            } if ($item->getAttributes()['item_package_weight'][0]->unit == 'grams') {
                $result['weight'] = $item->getAttributes()['item_package_weight'][0]->value / 1000;
            }
        } else {
            $result['weight'] = null;
        }

        // materialtype_us	米国材料
        if (
            !empty($item->getAttributes()) && 
            array_key_exists('material', $item->getAttributes()) &&
            count($item->getAttributes()['material']) > 0 &&
            !empty($item->getAttributes()['material'][0]->value)
        ) {
            $result['material_type'] = $item->getAttributes()['material'][0]->value;
        } else {
            $result['material_type'] = null;
        }
        // $result['material_type'] = null;

        return $result;
    }

    public function getProductPricing()
    {
        $sdk = $this->getSDK();
        //AccessToken $accessToken, string $region, string $marketplace_id, string $item_condition, string $asin, ?string $customer_type = null
        $item = $sdk->productPricing()->getItemOffers(
            accessToken: $this->accessToken,
            region: $this->region,
            marketplace_id: $this->marketplace_ids[0],
            item_condition: 'New',
            asin: $this->asin
        );

        $result = array();

        // nc: 新品出品者数
        $offices = $item->getPayload()->getOffers();
        $result['nc'] = count($offices);

        if ($result['nc'] == 0) {
            throw new \Exception("No seller on Amazon " . $this->nation);
        }

        // np: 新品最低価格
        // pp: プライム価格
        // cp_point: カート価格のポイント数
        // isAmazon: Amazon販売かどうか
        // maximumHours: リードタイム 最大配送時間
        // minimumHours: リードタイム 最小配送時間
        // cp: カート価格
        // shippingcost: 配送料
        // ap: ポイント数
        // sellerId: 出品者ID
        $offices = $item->getPayload()->getOffers();
        foreach ($offices as $office) {
            if ($office->getSubCondition() != 'new' && $office->getSubCondition() != 'New') {
                continue;
            }
            $result['np'] = $office->getListingPrice()->getAmount();
            $result['seller_id'] = $office->getSellerId();
            $result['is_amazon'] = $office->getIsFulfilledByAmazon();
            $result['maximum_hours'] = $office->getShippingTime()->getMaximumHours();
            $result['minimum_hours'] = $office->getShippingTime()->getMinimumHours();
            $result['is_prime'] = $office->getPrimeInformation()->getIsPrime();
            $result['shipping_cost'] = $office->getShipping()->getAmount();
            if ($this->nation == 'jp') {
                $result['ap'] = $office->getPoints()->getPointsNumber();
                $result['cp_point'] = $office->getPoints()->getPointsNumber();
                $result['cp'] = $result['np'] - $result['cp_point'] + $result['shipping_cost'];
            } else if ($this->nation == 'us') {
                $result['ap'] = 0;
                $result['cp_point'] = 0;
                $result['cp'] = $result['np'] + $result['shipping_cost'];
            }
            
            if ($result['is_prime']) {
                $result['pp'] = $result['np'];
            } else {
                $result['pp'] = null;
            }
            break;
        }

        return $result;
    }
}
