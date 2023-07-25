<?php

namespace App\Jobs;

use AmazonPHP\SellingPartner\Model\Feeds\Feed;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\AmazonService;
use App\Services\FeedTypes;
use App\Models\ProductBatch;
use Throwable;

class ExhibitToAmazonJP implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ProductBatch $productBatch;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

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

        // Exhibit and save feed_id
        if (empty($this->productBatch->feed_id)) { // has not exhibited yet
            $products = $this->productBatch->products;

            $results = $amazonService->CreateFeedWithFile($products, FeedTypes::POST_FLAT_FILE_LISTINGS_DATA);
            $feedId = $results["feedResults"]->getFeedId();
            $this->productBatch->feed_id = $feedId;
            $this->productBatch->feed_document = $results["feedDocument"];
            $this->productBatch->save();
            var_dump($this->productBatch->feed_id);
        }

        $feedId = $this->productBatch->feed_id;
        $this->productBatch->message = "AmazonJP出品状態確認がタイムアウトしました。Amazonセーラーコンソールで確認してください。";
        $this->productBatch->save();

        $url = $amazonService->getFeedDocument($feedId)->getUrl();
        $message = file_get_contents($url);
        $message = mb_convert_encoding($message,"utf-8","sjis");
        var_dump($message);
        $this->productBatch->message = $message;
        $this->productBatch->save();
    }

    /**
     * ジョブを再試行する前に待機する秒数を計算
     *
     * @return array
     */
    public function backoff()
    {
        return [1 * 60, 2 * 60, 5 * 60, 10 * 60, 20 * 60, 40 * 60, 60 * 60];
    }

    public function failed($exception)
    {
        if (env('APP_DEBUG', 'false') == 'true') {
            var_dump($exception->getMessage());
        }
    }
}
