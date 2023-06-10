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
            $table->longText('feed_document')->nullable()->comment('Feed Document');
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
            $table->dropColumn('feed_document');
        });
    }
};
