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
        Schema::create('product_exhibit_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger("product_batch_id")->unsigned()->comment('products.id');
            $table->bigInteger("user_id")->unsigned()->comment('users.id');
            $table->bigInteger("product_id")->unsigned()->comment('products.id');
            $table->string('action')->nullable()->comment('exhibit_to_amazon_jp');
            $table->string('amazon_jp_sku')->nullable()->comment('商品管理番号');
            $table->integer('amazon_jp_price')->nullable()->comment('販売価格');
            $table->integer('amazon_jp_quantity')->nullable()->comment('在庫数');
            $table->string('amazon_jp_product_id')->nullable()->comment('商品コード(JANコード等)');
            $table->string('amazon_jp_product_id_type')->nullable()->comment('商品コードのタイプ');
            $table->string('amazon_jp_condition_type')->nullable()->comment('商品のコンディション');
            $table->text('amazon_jp_condition_note')->nullable()->comment('商品のコンディションの説明');
            $table->integer('amazon_jp_leadtime_to_ship')->nullable()->comment('リードタイム(出荷までにかかる作業日数)');
            $table->text('message')->nullable()->comment('出品メッセージ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_exhibit_histories');
    }
};
