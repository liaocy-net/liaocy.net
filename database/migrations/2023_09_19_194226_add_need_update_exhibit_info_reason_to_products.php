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
            $table->text("amazon_jp_need_update_exhibit_info_reason")->nullable()->comment("AmazonJP出品情報更新要の理由");
            $table->text("yahoo_jp_need_update_exhibit_info_reason")->nullable()->comment("YahooJP出品情報更新要の理由");
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
            $table->dropColumn('amazon_jp_need_update_exhibit_info_reason');
            $table->dropColumn('yahoo_jp_need_update_exhibit_info_reason');
        });
    }
};
