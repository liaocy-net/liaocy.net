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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger("user_id")->unsigned();
            $table->bigInteger("product_batch_id")->unsigned();
            $table->string("asin")->nullable()->comment('Amazon ASIN');
            $table->text("title_jp")->nullable()->comment('Amazon JP 商品名');
            $table->text("title_us")->nullable()->comment('Amazon US 商品名');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
