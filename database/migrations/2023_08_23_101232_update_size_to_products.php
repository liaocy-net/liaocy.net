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
            $table->double('size_h_us')->comment("米国高さ(cm)")->change();
            $table->double('size_l_us')->comment("米国長さ(cm)")->change();
            $table->double('size_w_us')->comment("米国幅(cm)")->change();
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
            $table->integer('size_h_us')->comment("米国高さ")->change();
            $table->integer('size_l_us')->comment("米国長さ")->change();
            $table->integer('size_w_us')->comment("米国幅")->change();
        });
    }
};
