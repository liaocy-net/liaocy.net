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
            $table->text('amazon_jp_refresh_token')->nullable();
            $table->text('amazon_jp_access_token')->nullable();
            $table->datetime('amazon_jp_access_token_expires_in')->nullable();
            $table->text('amazon_us_refresh_token')->nullable();
            $table->text('amazon_us_access_token')->nullable();
            $table->datetime('amazon_us_access_token_expires_in')->nullable();
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
            $table->dropColumn('amazon_jp_refresh_token');
            $table->dropColumn('amazon_jp_access_token');
            $table->dropColumn('amazon_jp_access_token_expires_in');
            $table->dropColumn('amazon_us_refresh_token');
            $table->dropColumn('amazon_us_access_token');
            $table->dropColumn('amazon_us_access_token_expires_in');
        });
    }
};
