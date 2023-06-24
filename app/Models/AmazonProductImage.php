<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AmazonProductImage extends Model
{
    use HasFactory;

    protected $table = "amazon_product_images";

    protected $fillable = [
        "url",
        "path",
    ];

    public static function getPathByURL($url) 
    {
        $image = AmazonProductImage::where("url", $url)->first();
        if ($image) {
            return $image->path;
        } else {
            $amazonProductImage = new AmazonProductImage();
            $contents = file_get_contents($url);
            $name = md5($url) . '.' . substr($url, strrpos($url, '.') + 1);
            $result = Storage::put("amazon_product_images/" . $name, $contents);
            if ($result) {
                $amazonProductImage->path = storage_path() . "/app/amazon_product_images/" . $name;
                $amazonProductImage->url = $url;
                $amazonProductImage->save();
                return $amazonProductImage->path;
            } else {
                throw new \Exception("画像のダウンロードに失敗しました。");
            }
        }
        return null;
    }
}
