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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('amazon_min_profit')->default(1000)->change();
            $table->double('amazon_min_profit_rate')->default(0.1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('amazon_min_profit')->default(10000)->change();
            $table->double('amazon_min_profit_rate')->default(0.3)->change();
        });
    }
};
