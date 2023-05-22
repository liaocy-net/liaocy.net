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
        Schema::table('product_batches', function (Blueprint $table) {
            $table->text('action')->comment('extract_amazon_infomation|exhibit|adjust_price')->nullable()->change();
            $table->boolean('is_exhibit_to_amazon')->nullable();
            $table->boolean('is_exhibit_to_yahoo')->nullable();
            $table->text('exhibit_yahoo_category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_batches', function (Blueprint $table) {
            $table->text('action')->comment('extract_amazon_infomation|up_for_sale|adjust_price')->nullable()->change();
            $table->dropColumn('is_exhibit_to_amazon');
            $table->dropColumn('is_exhibit_to_yahoo');
            $table->dropColumn('exhibit_yahoo_category');
        });
    }
};
