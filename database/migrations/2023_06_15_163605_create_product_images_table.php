<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_product_images', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("url", 255)->nullable()->unique()->comment("画像ダウンロード元のURL");
            $table->string("path", 255)->nullable()->comment("画像ローカルの保存パス");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amazon_product_images');
    }
};
