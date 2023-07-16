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
            $table->string('availability_type_us')->nullable()->comment('Amazon US 商品の可用性タイプ(NOW|FUTURE_WITHOUT_DATE|FUTURE_WITH_DATE)');
            $table->string('availability_type_jp')->nullable()->comment('Amazon JP 商品の可用性タイプ(NOW|FUTURE_WITHOUT_DATE|FUTURE_WITH_DATE)');
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
            $table->dropColumn('availability_type_us');
            $table->dropColumn('availability_type_jp');
        });
    }
};
