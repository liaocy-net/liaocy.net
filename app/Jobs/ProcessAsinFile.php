<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\User;
use App\Models\YahooJpCategory;
use App\Services\UtilityService;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use SplFileObject;
use Throwable;

class ProcessAsinFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ProductBatch $productBatch;
    protected string $asinFileAbsolutePath;
    protected User $my;
    protected string $extractAmazonInfoQueueName;
    protected ?YahooJpCategory $yahooJpCategory;
    protected $shouldDownloadImages;

    public $maxAsinCount = 20 * 10000;

    public $timeout = 60 * 60;
    public $failOnTimeout = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($asinFileAbsolutePath, $productBatch, $my, $extractAmazonInfoQueueName, $yahooJpCategory, $shouldDownloadImages)
    {
        $this->asinFileAbsolutePath = $asinFileAbsolutePath;
        $this->productBatch = $productBatch;
        $this->my = $my;
        $this->extractAmazonInfoQueueName = $extractAmazonInfoQueueName;
        $this->maxAsinCount = env('MAX_ASIN_COUNT_PER_FILE', 20 * 10000);
        $this->yahooJpCategory = $yahooJpCategory;
        $this->shouldDownloadImages = $shouldDownloadImages;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $asinFileExtension = pathinfo($this->asinFileAbsolutePath, PATHINFO_EXTENSION);
        $asins = array();
        //拡張子がxlsxであるかの確認
        if ($asinFileExtension === "xlsx") {
            //Excelファイルを読み込み
            $reader = IOFactory::createReader("Xlsx");
            $spreadsheet = $reader->load($this->asinFileAbsolutePath);
            //シートの読み込み
            $sheet = $spreadsheet->getSheet(0);
            //最大行数確認
            if ($sheet->getHighestRow() > 1 + $this->maxAsinCount) {
                throw new \Exception("ASIN数が" . $this->maxAsinCount . "を超えてはいけません。");
            }
            $headers = $sheet->rangeToArray('A1:A1', null, true, false);
            if (strcmp($headers[0][0], "ASIN") !== 0) {
                throw new \Exception("EXCELファイルのフォーマットが不適切です。");
            }
            $rows = $sheet->rangeToArray('A2:A' . $sheet->getHighestRow(), null, true, false);
            foreach ($rows as $index => $row) {
                $matches = array();
                preg_match('/^(B[\dA-Z]{9}|\d{9}(X|\d))$/', $row[0], $matches);
                if (count($matches) != 2) {
                    throw new \Exception("ASINのフォーマットが不適切です。" . ($index + 2) . " 行目にある " . $row[0] . " を確認してください。");
                }
                if (!in_array($row[0], $asins)) {
                    array_push($asins, $row[0]);
                }
            }
        } else if ($asinFileExtension === "csv" || $asinFileExtension === "txt") {
            $file = new SplFileObject($this->asinFileAbsolutePath);
            $file->setFlags(SplFileObject::READ_CSV);
            foreach ($file as $rowIndex => $row) {
                if ($rowIndex === 0) {
                } else {
                    if (empty($row[0])) {
                        continue;
                    }
                    $matches = array();
                    preg_match('/^(B[\dA-Z]{9}|\d{9}(X|\d))$/', $row[0], $matches);
                    if (count($matches) != 2) {
                        throw new \Exception("ASINのフォーマットが不適切です。" . ($rowIndex + 1) . " 行目にある " . $row[0] . " を確認してください。");
                    }
                    if (!in_array($row[0], $asins)) {
                        array_push($asins, $row[0]);
                    }
                }
                if ($rowIndex > $this->maxAsinCount) {
                    throw new \Exception("ASIN数が" . $this->maxAsinCount . "を超えてはいけません。");
                }
            }
        } else {
            throw new \Exception("不適切な拡張子 " . $asinFileExtension . " です。CSVを選択してください。");
        }
        $extractAmazonInfos = array();
        foreach ($asins as $asin) {
            $product = new Product();
            $product->user_id = $this->my->id;
            $product->asin = $asin;
            $product->sku = UtilityService::genSKU($product);
            if (isset($this->yahooJpCategory)){
                $product->yahoo_jp_product_category = $this->yahooJpCategory->product_category;
                $product->yahoo_jp_path = $this->yahooJpCategory->path;
            }
            $product->save();
            $product->productBatches()->attach($this->productBatch);
            array_push($extractAmazonInfos, new ExtractAmazonInfo($product, $this->shouldDownloadImages));
        }
        $batch = Bus::batch($extractAmazonInfos)->name($this->extractAmazonInfoQueueName)->then(function (Batch $batch) {
            // すべてのジョブが正常に完了
        })->catch(function (Batch $batch, Throwable $e) {
            // バッチジョブの失敗をはじめて検出
        })->finally(function (Batch $batch) {
            // バッチジョブの完了
            $productBatch = ProductBatch::where('job_batch_id', $batch->id)->first();
            $productBatch->finished_at = now();
            $productBatch->save();
        })->onQueue($this->extractAmazonInfoQueueName . "_" . $this->my->getJobSuffix())->allowFailures()->dispatch();
        
        $this->productBatch->job_batch_id = $batch->id;
        $this->productBatch->save();
    }

    public function failed($exception)
    {
        $this->productBatch->finished_at = now();
        $this->productBatch->message = $exception->getMessage();
        $this->productBatch->save();

        if (env('APP_DEBUG', 'false') == 'true') {
            var_dump($exception->getMessage());
        }
    }
}
