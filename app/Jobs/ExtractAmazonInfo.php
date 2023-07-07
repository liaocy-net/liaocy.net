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
    public function __construct(Product $product)
    {
        $this->product = $product;
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

        foreach ($imageURLs as $url) {
            DownloadAmazonJPProductImages::dispatch($url)->onQueue('download_amazon_jp_product_images'); //キューに追加
        }

        UtilityService::updateJPAmazonInfo($this->product);

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
