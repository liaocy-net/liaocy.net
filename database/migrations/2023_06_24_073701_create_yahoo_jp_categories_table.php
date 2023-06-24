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
        Schema::create('yahoo_jp_categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('product_category')->nullable()->comment('YahooショッピングカテゴリID, （半角数字のみ。10文字以内）');
            $table->string('path')->nullable()->comment('ストアカテゴリのパス,（カテゴリ名のコロン区切り）');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yahoo_jp_categories');
    }
};
