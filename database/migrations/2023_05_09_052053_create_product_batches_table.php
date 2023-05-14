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
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger("user_id")->unsigned();
            $table->string("job_batch_id")->nullable()->comment('LaravelパッチID');
            $table->string("filename")->nullable()->comment('ファイル名');
            $table->string("action")->nullable()->comment('extract_amazon_info|up_for_sale|adjust_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_batches');
    }
};
