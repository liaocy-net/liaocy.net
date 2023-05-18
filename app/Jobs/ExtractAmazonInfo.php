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

class ExtractAmazonInfo implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    protected function extractUSAmazonInfo()
    {
        $user = $this->product->user;
        
        // extract Amazon US info
        $client_id = env("AMAZON_US_CLIENT_ID"); //must fix on prd
        $client_secret = env("AMAZON_US_CLIENT_SECRET"); //must fix on prd
        $amazon_refresh_token = $user->amazon_us_refresh_token;
        $amazonService = new AmazonService(
            $client_id, 
            $client_secret, 
            $amazon_refresh_token, 
            $this->product->asin,
            "us",  //must fix on prd
            $user
        );

        try{
            $catalogItem = $amazonService->getCatalogItem();
            $productPricing = $amazonService->getProductPricing();
        } catch (ApiException $e) {
            $this->product->is_amazon_us = false;
            $this->product->save();
            throw new \Exception("No such product on Amazon US");
        }
        $this->product->title_us = $catalogItem['title'];
        $this->product->brand_us = $catalogItem['brand'];
        // $this->product->cate_us = $catalogItem['cate'];
        $this->product->color_us = $catalogItem['color'];
        
        $this->product->img_url_01 = $catalogItem['img_url_01'];
        $this->product->img_url_02 = $catalogItem['img_url_02'];
        $this->product->img_url_03 = $catalogItem['img_url_03'];
        $this->product->img_url_04 = $catalogItem['img_url_04'];
        $this->product->img_url_05 = $catalogItem['img_url_05'];
        $this->product->img_url_06 = $catalogItem['img_url_06'];
        $this->product->img_url_07 = $catalogItem['img_url_07'];
        $this->product->img_url_08 = $catalogItem['img_url_08'];
        $this->product->img_url_09 = $catalogItem['img_url_09'];
        $this->product->img_url_10 = $catalogItem['img_url_10'];
        $this->product->material_type_us = $catalogItem['material_type'];
        $this->product->model_us = $catalogItem['model'];
        $this->product->rank_us = $catalogItem['rank'];
        $this->product->size_h_us = $catalogItem['size_h'];
        $this->product->size_l_us = $catalogItem['size_l'];
        $this->product->size_w_us = $catalogItem['size_w'];
        $this->product->size_us = $catalogItem['size'];
        $this->product->weight_us = $catalogItem['weight'];

        
        $this->product->maximum_hours_us = $productPricing['maximum_hours'];
        $this->product->minimum_hours_us = $productPricing['minimum_hours'];
        $this->product->cp_us = $productPricing['cp'];
        $this->product->nc_us = $productPricing['nc'];
        $this->product->pp_us = $productPricing['pp'];
        
        $this->product->is_amazon_us = true;
        $this->product->save();
    }

    protected function extractJPAmazonInfo()
    {
        $user = $this->product->user;

        // extract Amazon JP info
        $client_id = env("AMAZON_JP_CLIENT_ID");
        $client_secret = env("AMAZON_JP_CLIENT_SECRET");
        $amazon_refresh_token = $user->amazon_jp_refresh_token;
        $amazonService = new AmazonService(
            $client_id, 
            $client_secret, 
            $amazon_refresh_token, 
            $this->product->asin,
            "jp", 
            $user
        );
        
        try{
            $catalogItem = $amazonService->getCatalogItem();
            $productPricing = $amazonService->getProductPricing();
        } catch (ApiException $e) {
            $this->product->is_amazon_us = false;
            $this->product->save();
            throw new \Exception("No such product on Amazon JP");
        }
        $this->product->title_jp = $catalogItem['title'];
        $this->product->brand_jp = $catalogItem['brand'];
        $this->product->rank_id_jp = $catalogItem['rank_id'];
        $this->product->rank_jp = $catalogItem['rank'];
        
        $this->product->cp_jp = $productPricing['cp'];
        $this->product->cp_point = $productPricing['cp_point'];
        $this->product->maximum_hours_jp = $productPricing['maximum_hours'];
        $this->product->minimum_hours_jp = $productPricing['minimum_hours'];
        $this->product->nc_jp = $productPricing['nc'];
        $this->product->np_jp = $productPricing['np'];
        $this->product->pp_jp = $productPricing['pp'];
        $this->product->ap_jp = $productPricing['ap'];
        $this->product->seller_id = $productPricing['seller_id'];
        $this->product->shipping_cost = $productPricing['shipping_cost'];

        $this->product->is_amazon_jp = true;
        $this->product->save();
    }

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

        $this->extractUSAmazonInfo();

        $this->extractJPAmazonInfo();
    }

    public function failed($exception)
    {
        if (env('APP_DEBUG', 'false') == 'true') {
            var_dump($exception->getMessage());
        }
    }
}
