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
            $table->boolean("cancel_exhibit_to_amazon_jp")->nullable()->default(false);
            $table->boolean("cancel_exhibit_to_yahoo_jp")->nullable()->default(false);
            $table->dropColumn("is_deleted");
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
            $table->dropColumn("cancel_exhibit_to_amazon_jp");
            $table->dropColumn("cancel_exhibit_to_yahoo_jp");
            $table->boolean("is_deleted")->nullable()->default(false);
        });
    }
};
