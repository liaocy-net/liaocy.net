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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('common_currency_rate')->after('email_verified_at')->nullable()->default(120)->comment('USD/JPY為替レート');
            $table->integer('common_country_shipping')->after('email_verified_at')->nullable()->default(1500)->comment('国内送料(円)');
            $table->integer('common_foreign_shipping_without_weight')->after('email_verified_at')->nullable()->default(20000)->comment('重量なしの場合の国際送料(円)');
            $table->double('common_customs_tax')->after('email_verified_at')->nullable()->default(0.15)->comment('関税消費税');
            $table->integer('common_purchase_price_from')->after('email_verified_at')->nullable()->default(50)->comment('仕入れ価格(下限)');
            $table->integer('common_purchase_price_to')->after('email_verified_at')->nullable()->default(1000)->comment('仕入れ価格(上限)');
            $table->integer('common_max_weight')->after('email_verified_at')->nullable()->default(100)->comment('取扱い最大重量');
            $table->integer('common_size_from')->after('email_verified_at')->nullable()->default(0)->comment('サイズ (縦 + 横 + 高さ 合計値)(下限)');
            $table->integer('common_size_to')->after('email_verified_at')->nullable()->default(50)->comment('サイズ (縦 + 横 + 高さ 合計値)(上限)');
            $table->double('common_purchase_mark')->after('email_verified_at')->nullable()->default(0)->comment('仕入先評価閾値');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('common_currency_rate');
            $table->dropColumn('common_country_shipping');
            $table->dropColumn('common_foreign_shipping_without_weight');
            $table->dropColumn('common_customs_tax');
            $table->dropColumn('common_purchase_price_from');
            $table->dropColumn('common_purchase_price_to');
            $table->dropColumn('common_max_weight');
            $table->dropColumn('common_size_from');
            $table->dropColumn('common_size_to');
            $table->dropColumn('common_purchase_mark');
        });
    }
};
