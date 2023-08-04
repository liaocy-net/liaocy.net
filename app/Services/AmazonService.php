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
use AmazonPHP\SellingPartner\Model\Feeds\CreateFeedDocumentSpecification;
use \AmazonPHP\SellingPartner\Model\Feeds\CreateFeedSpecification;
use App\Models\Product;
use App\Services\UtilityService;

enum FeedTypes: string
{
    //ref. https://developer-docs.amazon.com/sp-api/docs/feed-type-values
    case POST_PRODUCT_DATA = 'POST_PRODUCT_DATA';
    case POST_INVENTORY_AVAILABILITY_DATA = 'POST_INVENTORY_AVAILABILITY_DATA';
    case POST_PRODUCT_PRICING_DATA = 'POST_PRODUCT_PRICING_DATA';
    case POST_FLAT_FILE_INVLOADER_DATA = 'POST_FLAT_FILE_INVLOADER_DATA';
    case POST_FLAT_FILE_LISTINGS_DATA = 'POST_FLAT_FILE_LISTINGS_DATA';
    case POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA = 'POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA';
}

class AmazonService
{
    protected $client_id;
    protected $client_secret;
    protected $refresh_token;
    protected $product;
    protected $nation;
    protected $user;
    protected $region;
    protected $accessToken;
    protected $marketplace_ids = [];
    protected $feedId;

    public function __construct($client_id, $client_secret, $refresh_token, $user, $nation)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->refresh_token = $refresh_token;
        $this->user = $user;
        $this->nation = $nation;
    }
    
    protected function getSDK() : SellingPartnerSDK
    {
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

    public function getCatalogItem(Product $product)
    {
        $sdk = $this->getSDK();
        $item = $sdk->catalogItem()->getCatalogItem(
            $this->accessToken,
            $this->region,
            $product->asin,
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

        // product_type	プロダクトタイプ cateとして利用する
        if (
            !empty($item->getProductTypes()) && 
            count($item->getProductTypes()) > 0 &&
            !empty($item->getProductTypes()[0]->getProductType())
        ) {
            $result['product_type'] = $item->getProductTypes()[0]->getProductType();
        } else {
            $result['product_type'] = null;
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
            } else if ($item->getAttributes()['item_package_weight'][0]->unit == 'grams') {
                $result['weight'] = $item->getAttributes()['item_package_weight'][0]->value / 1000;
            } else {
                $result['weight'] = null;
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

    public function getProductPricing(Product $product, $skipSellerId = null)
    {
        $sdk = $this->getSDK();
        //AccessToken $accessToken, string $region, string $marketplace_id, string $item_condition, string $asin, ?string $customer_type = null
        $item = $sdk->productPricing()->getItemOffers(
            accessToken: $this->accessToken,
            region: $this->region,
            marketplace_id: $this->marketplace_ids[0],
            item_condition: 'New',
            asin: $product->asin
        );

        $result = array();

        // nc: 新品出品者数
        $offices = $item->getPayload()->getOffers();
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
        $result['nc'] = count($offices);
        $result['seller_id'] = null;
        $result['is_amazon'] = null;
        $result['availability_type'] = null;
        $result['maximum_hours'] = null;
        $result['minimum_hours'] = null;
        $result['is_prime'] = null;
        $result['shipping_cost'] = null;
        $result['ap'] = null;
        $result['cp_point'] = null;
        $result['pp'] = null;

        $result['cp'] = null;
        if (
            !is_null($item->getPayload()->getSummary()->getBuyBoxPrices())
            && count($item->getPayload()->getSummary()->getBuyBoxPrices()) > 0
            && strtolower($item->getPayload()->getSummary()->getBuyBoxPrices()[0]->getCondition()) === 'new'
        ) {
            $result['cp'] = $item->getPayload()->getSummary()->getBuyBoxPrices()[0]->getLandedPrice()->getAmount();
        }

        $result['np'] = null;
        if (
            !is_null($item->getPayload()->getSummary()->getLowestPrices())
            && count($item->getPayload()->getSummary()->getLowestPrices()) > 0
            && strtolower($item->getPayload()->getSummary()->getLowestPrices()[0]->getCondition()) === 'new'
        ) {
            $result['np'] = $item->getPayload()->getSummary()->getLowestPrices()[0]->getLandedPrice()->getAmount();
        }

        $offices = $item->getPayload()->getOffers();
        foreach ($offices as $office) {
            if ($office->getSubCondition() != 'new' && $office->getSubCondition() != 'New') {
                continue;
            }
            if ($skipSellerId != null && $office->getSellerId() == $skipSellerId) {
                continue;
            }

            $result['seller_id'] = $office->getSellerId();
            $result['is_amazon'] = $office->getIsFulfilledByAmazon();
            $result['availability_type'] = $office->getShippingTime() ? $office->getShippingTime()->getAvailabilityType() : null;
            $result['maximum_hours'] = $office->getShippingTime() ? $office->getShippingTime()->getMaximumHours() : null;
            $result['minimum_hours'] = $office->getShippingTime() ? $office->getShippingTime()->getMinimumHours() : null;
            $result['is_prime'] = $office->getPrimeInformation() ? $office->getPrimeInformation()->getIsPrime() : null;
            $result['shipping_cost'] = $office->getShipping()->getAmount();
            if ($this->nation == 'jp') {
                if ($result['cp'] == null && $office->getListingPrice() != null && $office->getListingPrice()->getAmount() != null) {
                    $result['cp'] = $office->getListingPrice()->getAmount();
                }

                if($office->getPoints() != null) {
                    $result['ap'] = $office->getPoints()->getPointsNumber();
                    $result['cp_point'] = $office->getPoints()->getPointsNumber();
                } else {
                    $result['ap'] = 0;
                    $result['cp_point'] = 0;
                }
            } else if ($this->nation == 'us') {
                $result['ap'] = 0;
                $result['cp_point'] = 0;
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

    public function getFeedDocument($feed_id)
    {
        // if(empty($this->feedId)) {
        //     throw new \Exception("feedId is not available.");
        // }

        $sdk = $this->getSDK();

        //ref. https://developer-docs.amazon.com/sp-api/docs/feeds-api-v2021-06-30-use-case-guide
        //Step 5. Confirm feed processing
        $results = $sdk->feeds()->getFeed(
            $this->accessToken,
            $this->region,
            $feed_id,
        );

        if ($results->getProcessingStatus() == 'DONE') {
            //Step 6. Download the feed
            $results = $sdk->feeds()->getFeedDocument(
                $this->accessToken,
                $this->region,
                $results->getResultFeedDocumentId()
            );
        }

        return $results;
    }

    public function genPostFlatFileListingsData($products)
    {
        $tsv = "";
        $headers = [
            ["TemplateType=Offer",
            "Version=2020.000",
            "",
            "",
            "",
            "",
            "",
            "",
            ""],
            ["商品管理番号",
            "販売価格",
            "在庫数",
            "商品コード(JANコード等)",
            "商品コードのタイプ",
            "商品のコンディション",
            "商品のコンディション説明",
            "リードタイム(出荷までにかかる作業日数)"],
            ["sku",
            "price",
            "quantity",
            "product-id",
            "product-id-type",
            "condition-type",
            "condition-note",
            "leadtime-to-ship"]
        ];
        foreach ($headers as $header) {
            $tsv .= join("\t", $header) . "\n";
        }
        $coditionNote = $this->user->amazon_exhibit_comment_group;
        // remove newline
        $coditionNote = str_replace(array("\r\n", "\r", "\n", "\t"), '', $coditionNote);
        foreach ($products as $product) {
            $contents = [
                $product->sku,
                $product->amazon_jp_latest_exhibit_price,
                $product->amazon_jp_latest_exhibit_quantity,
                $product->asin,
                "ASIN",
                "New",
                $coditionNote,
                $product->amazon_jp_leadtime_to_ship,
            ];
            $tsv .= join("\t", $contents) . "\n";
        }
        var_dump($tsv);
        return $tsv;
    }

    public function genPostFlatFilePriceandquantityonlyUpdateData($products)
    {
        $tsv = "";
        $headers = [
            [
                "TemplateType=PriceInventory",
                "Version=2018.0924",
                "",
            ],
            [
                "商品管理番号",
                "販売価格",
                "在庫数",
            ],
            [
                "sku",
                "price",
                "quantity",
            ]
        ];
        foreach ($headers as $header) {
            $tsv .= join("\t", $header) . "\n";
        }
        foreach ($products as $product) {
            $contents = [
                $product->sku,
                $product->amazon_jp_latest_exhibit_price,
                $product->amazon_jp_latest_exhibit_quantity,
            ];
            $tsv .= join("\t", $contents) . "\n";
        }
        return $tsv;
    }

    public function CreateFeedWithFile($products, $feedType = FeedTypes::POST_FLAT_FILE_LISTINGS_DATA)
    {
        $sdk = $this->getSDK();

        //ref. https://developer-docs.amazon.com/sp-api/docs/feeds-api-v2021-06-30-use-case-guide
        //Step 1. Create a feed document
        $createFeedDocumentSpecification = new CreateFeedDocumentSpecification(
            data: array(
                'content_type' => 'text/txt; charset=UTF-8',
            )
        );
        $results = $sdk->feeds()->createFeedDocument(
            $this->accessToken,
            $this->region,
            $createFeedDocumentSpecification
        );
        $feedDocumentId = $results->getFeedDocumentId();
        $feedURL = $results->getUrl();

        //Step 2. Construct a feed
        if ($feedType == FeedTypes::POST_FLAT_FILE_LISTINGS_DATA) {
            $feedDocument = $this->genPostFlatFileListingsData($products);
        } else if ($feedType == FeedTypes::POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA) {
            $feedDocument = $this->genPostFlatFilePriceandquantityonlyUpdateData($products);
        } else {
            throw new \Exception("feedType is not available.");
        }

        //Step 3. Upload the feed
        $options = [
            'headers' => ['Content-Type' => 'text/txt; charset=UTF-8'],
            'body' => $feedDocument,
        ];
        $client =new \GuzzleHttp\Client();
        $client->request('PUT', $feedURL, $options);

        //Step 4. Create a feed
        $createFeedSpecification = new CreateFeedSpecification(
            data: array(
                'feed_type' => $feedType->value,
                'marketplace_ids' => $this->marketplace_ids,
                'input_feed_document_id' => $feedDocumentId,
            )
        );

        $results = $sdk->feeds()->createFeed(
            $this->accessToken,
            $this->region,
            $createFeedSpecification
        );

        return array(
            'feedResults' => $results,
            'feedDocument' => $feedDocument,
        );
    }
}
