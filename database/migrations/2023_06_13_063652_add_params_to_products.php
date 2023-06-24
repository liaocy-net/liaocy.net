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
            $table->integer("yahoo_jp_latest_exhibit_price")->nullable()->comment("Yahoo JP最新出品価格");
            $table->integer("yahoo_jp_latest_exhibit_quantity")->nullable()->comment("Yahoo JP最新出品数量");
            $table->boolean("yahoo_jp_has_exhibited")->nullable()->comment("Yahoo JP出品済みかどうか");
            $table->boolean("yahoo_is_in_checklist")->nullable()->comment("Yahoo JP価格改定チェックリストに入っているかどうか");
            $table->timestamp("yahoo_latest_check_at")->nullable()->comment("Yahoo JP価格改定最終チェック日時");
            $table->boolean('yahoo_jp_need_update_exhibit_info')->nullable()->comment('YahooJPへ出品情報更新要');
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
            $table->dropColumn([
                "yahoo_jp_latest_exhibit_price",
                "yahoo_jp_latest_exhibit_quantity",
                "yahoo_jp_has_exhibited",
                "yahoo_is_in_checklist",
                "yahoo_latest_check_at",
                "yahoo_jp_need_update_exhibit_info",
            ]);
        });
    }
};
