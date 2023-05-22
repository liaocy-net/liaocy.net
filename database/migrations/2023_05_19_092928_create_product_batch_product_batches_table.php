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
        Schema::create('product_product_batch', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('product_batch_id')->unsigned()->index();
            $table->foreign('product_batch_id')->references('id')->on('product_batches')->onDelete('cascade');
            $table->bigInteger('product_id')->unsigned()->index();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_product_batch');
    }
};
