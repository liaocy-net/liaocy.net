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
            $table->integer('amazon_hope_profit')->after('email_verified_at')->nullable()->default(10000)->comment('Amazon希望利益額');
            $table->integer('amazon_min_profit')->after('email_verified_at')->nullable()->default(10000)->comment('Amazon最低利益額');
            $table->double('amazon_hope_profit_rate')->after('amazon_hope_profit')->nullable()->default(0.3)->comment('Amazon希望利益率');
            $table->double('amazon_min_profit_rate')->after('amazon_hope_profit')->nullable()->default(0.3)->comment('Amazon最低利益率');
            $table->integer('amazon_using_profit')->after('amazon_hope_profit')->nullable()->default(1)->comment('Amazon適用する利益');
            $table->double('amazon_using_sale_commission')->after('amazon_hope_profit')->nullable()->default(0.15)->comment('Amazon販売手数料');
            $table->integer('amazon_stock')->after('amazon_hope_profit')->nullable()->default(10)->comment('Amazon在庫数');
            $table->double('amazon_price_increase_rate')->after('amazon_hope_profit')->nullable()->default(1.8)->comment('Amazon値上げ率');
            $table->integer('amazon_rival')->after('amazon_hope_profit')->nullable()->default(1)->comment('Amazonライバル');
            $table->double('amazon_point_rate')->after('amazon_hope_profit')->nullable()->default(0.03)->comment('Amazonポイント比率');
            $table->integer('amazon_lead_time_less')->after('amazon_hope_profit')->nullable()->default(10)->comment('Amazonリードタイム(XX日未満の場合)');
            $table->integer('amazon_lead_time_more')->after('amazon_hope_profit')->nullable()->default(20)->comment('Amazonリードタイム(XX日以上の場合)');
            $table->integer('amazon_lead_time_prime')->after('amazon_hope_profit')->nullable()->default(8)->comment('Amazonプライムリードタイム');
            $table->integer('amazon_white_list_brand')->after('amazon_hope_profit')->nullable()->default(20)->comment('Amazonホワイトリスト抽出重複ブランド数');
            $table->text('amazon_exhibit_comment_group')->after('amazon_hope_profit')->nullable()->comment('Amazon 出品コメント');
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
            $table->dropColumn("amazon_hope_profit");
            $table->dropColumn("amazon_min_profit");
            $table->dropColumn("amazon_hope_profit_rate");
            $table->dropColumn("amazon_min_profit_rate");
            $table->dropColumn("amazon_using_profit");
            $table->dropColumn("amazon_using_sale_commission");
            $table->dropColumn("amazon_stock");
            $table->dropColumn("amazon_price_increase_rate");
            $table->dropColumn("amazon_rival");
            $table->dropColumn("amazon_point_rate");
            $table->dropColumn("amazon_lead_time_less");
            $table->dropColumn("amazon_lead_time_more");
            $table->dropColumn("amazon_lead_time_prime");
            $table->dropColumn("amazon_white_list_brand");
            $table->dropColumn("amazon_exhibit_comment_group");
        });
    }
};
