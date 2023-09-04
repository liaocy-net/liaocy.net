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
            $table->double('purchase_price_us')->nullable()->comment('購入価格(USD)');
            $table->double('amazon_jp_hope_price_jpy')->nullable()->comment('希望利益計算価格(円)');
            $table->double('amazon_jp_min_hope_price_jpy')->nullable()->comment('最低利益計算価格(円)');
            $table->double('amazon_jp_rate_price_jpy')->nullable()->comment('利益率計算価格(円)');
            $table->double('amazon_jp_min_rate_price_jpy')->nullable()->comment('最低利益率計算価格(円)');
            $table->boolean('can_be_exhibit_to_amazon_jp')->nullable()->comment('AmazonJPに出品可能か');
            $table->string('can_be_exhibit_to_amazon_jp_message', 500)->nullable()->comment('AmazonJPに出品可能かのメッセージ');
            $table->double('can_be_exhibit_to_amazon_jp_price')->nullable()->comment('AmazonJPに出品の価格');
            $table->double('yahoo_jp_min_hope_price_jpy')->nullable()->comment('YahooJP最低利益計算価格(円)');
            $table->double('yahoo_jp_min_rate_price_jpy')->nullable()->comment('YahooJP最低利益率計算価格(円)');
            $table->boolean('can_be_exhibit_to_yahoo_jp')->nullable()->comment('YahooJPに出品可能か');
            $table->string('can_be_exhibit_to_yahoo_jp_message', 500)->nullable()->comment('YahooJPに出品可能かのメッセージ');
            $table->double('can_be_exhibit_to_yahoo_jp_price')->nullable()->comment('YahooJPに出品の価格');

            $table->double('amazon_hope_profit')->nullable()->comment('AmazonJP希望利益額');
            $table->double('amazon_min_profit')->nullable()->comment('AmazonJP最低利益額');
            $table->double('amazon_hope_profit_rate')->nullable()->comment('AmazonJP希望利益率');
            $table->double('amazon_min_profit_rate')->nullable()->comment('AmazonJP最低利益率');
            $table->double('amazon_using_sale_commission')->nullable()->comment('Amazon手数料率');
            $table->double('amazon_point_rate')->nullable()->comment('Amazon Point比率');
            $table->double('amazon_price_cut')->nullable()->comment('AmazonJP値下げ額');
            $table->double('amazon_price_increase_rate')->nullable()->comment('AmazonJP値上げ率');

            $table->double('yahoo_min_profit')->nullable()->comment('YahooJP最低利益額');
            $table->double('yahoo_profit_rate')->nullable()->comment('YahooJP利益率');
            $table->double('yahoo_using_sale_commission')->nullable()->comment('YahooJP販売手数料');

            $table->double('common_currency_rate')->nullable()->comment('為替(円)');
            $table->double('common_customs_tax')->nullable()->comment('関税消費税');
            $table->double('common_country_shipping')->nullable()->comment('国内送料');
            $table->double('foreign_shipping')->nullable()->comment('国際送料');
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
            $table->dropColumn('purchase_price_us');
            $table->dropColumn('amazon_jp_hope_price_jpy');
            $table->dropColumn('amazon_jp_min_hope_price_jpy');
            $table->dropColumn('amazon_jp_rate_price_jpy');
            $table->dropColumn('amazon_jp_min_rate_price_jpy');
            $table->dropColumn('can_be_exhibit_to_amazon_jp');
            $table->dropColumn('can_be_exhibit_to_amazon_jp_message');
            $table->dropColumn('can_be_exhibit_to_amazon_jp_price');
            $table->dropColumn('yahoo_jp_min_hope_price_jpy');
            $table->dropColumn('yahoo_jp_min_rate_price_jpy');
            $table->dropColumn('can_be_exhibit_to_yahoo_jp');
            $table->dropColumn('can_be_exhibit_to_yahoo_jp_message');
            $table->dropColumn('can_be_exhibit_to_yahoo_jp_price');

            $table->dropColumn('amazon_hope_profit');
            $table->dropColumn('amazon_min_profit');
            $table->dropColumn('amazon_hope_profit_rate');
            $table->dropColumn('amazon_min_profit_rate');
            $table->dropColumn('amazon_using_sale_commission');
            $table->dropColumn('amazon_point_rate');
            $table->dropColumn('amazon_price_cut');
            $table->dropColumn('amazon_price_increase_rate');

            $table->dropColumn('yahoo_min_profit');
            $table->dropColumn('yahoo_profit_rate');
            $table->dropColumn('yahoo_using_sale_commission');

            $table->dropColumn('common_currency_rate');
            $table->dropColumn('common_customs_tax');
            $table->dropColumn('common_country_shipping');
            $table->dropColumn('foreign_shipping');
        });
    }
};
