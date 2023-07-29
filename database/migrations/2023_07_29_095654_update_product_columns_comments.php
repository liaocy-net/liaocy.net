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
        Schema::table('products', function (Blueprint $table) {
            $table->text("sku")->nullable()->comment("AmazonJPでユーザごとに出品を識別するID")->change();
            $table->boolean("cancel_exhibit_to_amazon_jp")->nullable()->comment("AmazonJP出品対象から削除したかどうか")->default(false)->change();
            $table->boolean("cancel_exhibit_to_yahoo_jp")->nullable()->comment("YahooJP出品対象から削除したかどうか")->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) { 
            $table->text("sku")->nullable()->comment("")->change();
            $table->boolean("cancel_exhibit_to_amazon_jp")->nullable()->default(false)->change();
            $table->boolean("cancel_exhibit_to_yahoo_jp")->nullable()->default(false)->change();
        });
    }
};
