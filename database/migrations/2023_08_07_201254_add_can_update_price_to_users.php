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
            $table->boolean("amazon_jp_should_update_price")->nullable()->default(true)->comment("AmazonJP価格改定するか");
            $table->boolean("yahoo_jp_should_update_price")->nullable()->default(true)->comment("YahooJP価格改定するか");
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
            $table->dropColumn("amazon_jp_should_update_price");
            $table->dropColumn("yahoo_jp_should_update_price");
        });
    }
};
