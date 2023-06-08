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
    public $tries = 3;

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

        try {
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
                $feed_id = $results->getFeedId();
                $this->productBatch->feed_id = $feed_id;
                $this->productBatch->save();
                var_dump($this->productBatch->feed_id);
            }

            $feed_id = $this->productBatch->feed_id;
            $this->productBatch->message = "Amazon出品状態確認がタイムアウトしました。Amazonセーラーコンソールで確認してください。";
            $this->productBatch->save();

            $url = $amazonService->getFeedDocument($feed_id)->getUrl();
            $message = file_get_contents($url);
            $message = mb_convert_encoding($message,"utf-8","sjis");
            var_dump($message);
            $this->productBatch->message = $message;
            $this->productBatch->save();

        } catch (Throwable $e) {
            $attempts = $this->attempts();
            var_dump($attempts);
            if ($attempts < $this->tries) {
                $this->release(5 * 60 + ($attempts - 1) * 10 * 60);
            } else {
                throw $e;
            }
        }
    }

    public function failed($exception)
    {
        if (env('APP_DEBUG', 'false') == 'true') {
            var_dump($exception->getMessage());
        }
    }
}
