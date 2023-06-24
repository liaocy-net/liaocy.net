<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_batch_id',
        'asin',
        'title_jp',
        'title_us',
        'ap_jp',
        'brand_jp',
        'brand_us',
        'cate_us',
        'color_us',
        'cp_jp',
        'cp_point',
        'cp_us',
        'img_url_01',
        'img_url_02',
        'img_url_03',
        'img_url_04',
        'img_url_05',
        'img_url_06',
        'img_url_07',
        'img_url_08',
        'img_url_09',
        'img_url_10',
        'is_amazon_jp',
        'is_amazon_us',
        'material_type_us',
        'maximum_hours_jp',
        'maximum_hours_us',
        'minimum_hours_jp',
        'model_us',
        'nc_jp',
        'nc_us',
        'np_jp',
        'np_us',
        'pp_jp',
        'pp_us',
        'rank_id_jp',
        'rank_jp',
        'rank_us',
        'seller_feedback_count',
        'seller_feedback_rating',
        'seller_id',
        'shipping_cost',
        'size_h_us',
        'size_l_us',
        'size_w_us',
        'size',
        'weight_us',
        'amazon_jp_feed_id',
    ];

    public function productBatches()
    {
        return $this->belongsToMany(ProductBatch::class);
    }

    public function productExhibitHistories()
    {
        return $this->hasMany(ProductExhibitHistory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAmazonUSImageURLs()
    {
        $urls = [];
        for ($i = 1; $i <= 10; $i++) {
            $url = $this["img_url_" . str_pad($i, 2, "0", STR_PAD_LEFT)];
            if ($url) {
                array_push($urls, $url);
            }
        }
        return $urls;
    }
}
