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
            $table->string('yahoo_state')->after('email_verified_at')->nullable();
            $table->string('yahoo_nonce')->after('email_verified_at')->nullable();
            $table->string('yahoo_code')->after('email_verified_at')->nullable();
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
            $table->dropColumn('yahoo_state');
            $table->dropColumn('yahoo_nonce');
            $table->dropColumn('yahoo_code');
        });
    }
};
