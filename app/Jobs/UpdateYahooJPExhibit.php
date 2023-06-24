<?php

namespace App\Jobs;

use AmazonPHP\SellingPartner\Model\Feeds\Feed;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\YahooService;
use App\Services\FeedTypes;
use App\Models\ProductBatch;
use Throwable;

class UpdateYahooJPExhibit implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ProductBatch $productBatch;

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
    public function __construct(ProductBatch $productBatch)
    {
        $this->productBatch = $productBatch;
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

        $user = $this->productBatch->user;        
        $products = $this->productBatch->products->all();
        
        $yahooService = new YahooService($user);
        $yahooService->updateItemsPrice($products);
        $yahooService->setStock($products);
        

        var_dump('UpdateYahooJPExhibit: ' . $this->productBatch->id);
    }

    /**
     * ジョブを再試行する前に待機する秒数を計算
     *
     * @return array
     */
    public function backoff()
    {
        return [1, 5, 10];
    }

    public function failed($exception)
    {
        if (env('APP_DEBUG', 'false') == 'true') {
            var_dump($exception->getMessage());
        }
    }
}
