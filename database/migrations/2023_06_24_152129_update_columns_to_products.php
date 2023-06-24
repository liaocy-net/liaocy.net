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
            $table->integer("yahoo_jp_product_category")->nullable()->comment("YahooショッピングカテゴリID, 半角数字のみ, 10文字以内");
            $table->string("yahoo_jp_path")->nullable()->comment("ストアカテゴリのパス,カテゴリ名のコロン区切り");
        });
        Schema::table('product_batches', function (Blueprint $table) {
            $table->dropColumn('exhibit_yahoo_category');
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
            $table->dropColumn("yahoo_jp_product_category");
            $table->dropColumn("yahoo_jp_path");
        });
        Schema::table('product_batches', function (Blueprint $table) {
            $table->text('exhibit_yahoo_category')->nullable();
        });
    }
};
