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
            $table->integer('yahoo_min_profit')->after('email_verified_at')->nullable()->default(10000)->comment('Yahoo最低利益額');
            $table->double('yahoo_profit_rate')->after('email_verified_at')->nullable()->default(0.3)->comment('Yahoo利益率');
            $table->integer('yahoo_using_profit')->after('email_verified_at')->nullable()->default(1)->comment('Yahoo適用する利益設定');
            $table->double('yahoo_using_sale_commission')->after('email_verified_at')->nullable()->default(0.15)->comment('Yahoo販売手数料');
            $table->integer('yahoo_stock')->after('email_verified_at')->nullable()->default(10)->comment('Yahoo在庫数');
            $table->text('yahoo_exhibit_comment_group')->after('email_verified_at')->nullable()->comment('Yahoo出品コメント');
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
            $table->dropColumn("yahoo_min_profit");
            $table->dropColumn("yahoo_profit_rate");
            $table->dropColumn("yahoo_using_profit");
            $table->dropColumn("yahoo_using_sale_commission");
            $table->dropColumn("yahoo_stock");
            $table->dropColumn("yahoo_exhibit_comment_group");
        });
    }
};
