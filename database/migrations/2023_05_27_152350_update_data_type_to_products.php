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
            $table->integer('ap_jp')->change();
            $table->integer('cp_jp')->change();
            $table->integer('cp_point')->change();
            $table->double('cp_us')->change();
            $table->integer('maximum_hours_jp')->change();
            $table->integer('maximum_hours_us')->change();
            $table->integer('minimum_hours_jp')->change();
            $table->integer('minimum_hours_us')->change();
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
            $table->text('ap_jp')->change();
            $table->text('cp_jp')->change();
            $table->text('cp_point')->change();
            $table->text('cp_us')->change();
            $table->text('maximum_hours_jp')->change();
            $table->text('maximum_hours_us')->change();
            $table->text('minimum_hours_jp')->change();
            $table->text('minimum_hours_us')->change();
        });
    }
};
