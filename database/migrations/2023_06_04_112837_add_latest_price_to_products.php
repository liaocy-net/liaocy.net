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
            $table->integer('amazon_jp_latest_exhibit_price')->nullable()->comment('AmazonJPへの最新出品価格');
            $table->integer('amazon_jp_latest_exhibit_quantity')->nullable()->comment('AmazonJPへの最新出品在庫数');
            $table->boolean('amazon_jp_has_exhibited')->nullable()->comment('AmazonJPへ出品済みかどうか');
            $table->boolean('amazon_jp_need_update_exhibit_info')->nullable()->comment('AmazonJPへ出品情報更新要');
            $table->timestamp('amazon_latest_check_at')->nullable()->comment('Amazon最新チェック日時');
            $table->boolean('amazon_is_in_checklist')->nullable()->comment('Amazon最新チェック中かどうか');
            $table->integer('amazon_jp_leadtime_to_ship')->nullable()->comment('AmazonJPへの出荷までの日数');
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
            $table->dropColumn("amazon_jp_latest_exhibit_price");
            $table->dropColumn("amazon_jp_latest_exhibit_quantity");
            $table->dropColumn("amazon_jp_has_exhibited");
            $table->dropColumn("amazon_jp_need_update_exhibit_info");
            $table->dropColumn("amazon_latest_check_at");
            $table->dropColumn("amazon_is_in_checklist");
            $table->dropColumn("amazon_jp_leadtime_to_ship");
        });
    }
};
