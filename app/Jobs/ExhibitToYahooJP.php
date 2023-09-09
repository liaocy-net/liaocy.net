<?php

namespace App\Jobs;

use AmazonPHP\SellingPartner\Model\Feeds\Feed;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Services\FeedTypes;
use App\Services\YahooService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ExhibitToYahooJP implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Product $product;
    protected $productBatchId;

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
    public function __construct(Product $product, $productBatchId)
    {
        $this->product = $product;
        $this->productBatchId = $productBatchId;
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

        $user = $this->product->user;

        $yahooService = new YahooService($user);
        
        $productBatch = ProductBatch::where('id', $this->productBatchId)->first();
        if (!isset($productBatch->message)) {
            $productBatch->message = "";
        }

        try {
            $editItemResult = $yahooService->editItem($this->product);
        } catch (Throwable $e) {
            $productBatch->message .= $e->getMessage();
            $productBatch->save();
            throw $e;
        }

        $productBatch->message .= $this->product->asin . ": " . $editItemResult . "\n";
        $productBatch->save();

        if ($editItemResult == 'OK') {
            $yahooService->uploadItemImagePack($this->product);
        }
    }

    /**
     * ジョブを再試行する前に待機する秒数を計算
     *
     * @return array
     */
    public function backoff()
    {
        return [1 * 60, 5 * 60, 10 * 60, 20 * 60, 40 * 60, 60 * 60];
    }

    public function failed($exception)
    {
        if (env('APP_DEBUG', 'false') == 'true') {
            var_dump($exception->getMessage());
        }
    }
}
