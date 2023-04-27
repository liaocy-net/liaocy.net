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
            $table->string('yahoo_store_account')->after('email_verified_at')->nullable();
            $table->string('yahoo_client_id')->after('email_verified_at')->nullable();
            $table->string('yahoo_secret')->after('email_verified_at')->nullable();
            $table->string('yahoo_access_token')->after('email_verified_at')->nullable();
            $table->datetime('yahoo_access_token_expires_in')->after('email_verified_at')->nullable();
            $table->string('yahoo_refresh_token')->after('email_verified_at')->nullable();
            $table->datetime('yahoo_refresh_token_expires_in')->after('email_verified_at')->nullable();
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
            $table->dropColumn('yahoo_store_account');
            $table->dropColumn('yahoo_client_id');
            $table->dropColumn('yahoo_secret');
            $table->dropColumn('yahoo_access_token');
            $table->dropColumn('yahoo_access_token_expires_in');
            $table->dropColumn('yahoo_refresh_token');
            $table->dropColumn('yahoo_refresh_token_expires_in');
        });
    }
};
