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
            $table->text('ap_jp')->nullable()->comment("ポイント数日本");
            $table->text('brand_jp')->nullable()->comment("日本のブランド名");
            $table->text('brand_us')->nullable()->comment("米国ブランド");
            $table->text('cate_us')->nullable()->comment("米国カテゴリー");
            $table->text('color_us')->nullable()->comment("米国色");
            $table->text('cp_jp')->nullable()->comment("カート価格日本");
            $table->text('cp_point')->nullable()->comment("カート価格のポイント数（日本）");
            $table->text('cp_us')->nullable()->comment("カート価格米国");
            $table->text('img_url_01')->nullable()->comment("画像1");
            $table->text('img_url_02')->nullable()->comment("画像2");
            $table->text('img_url_03')->nullable()->comment("画像3");
            $table->text('img_url_04')->nullable()->comment("画像4");
            $table->text('img_url_05')->nullable()->comment("画像5");
            $table->text('img_url_06')->nullable()->comment("画像6");
            $table->text('img_url_07')->nullable()->comment("画像7");
            $table->text('img_url_08')->nullable()->comment("画像8");
            $table->text('img_url_09')->nullable()->comment("画像9");
            $table->text('img_url_10')->nullable()->comment("画像10");
            $table->boolean('is_amazon_jp')->nullable()->comment("Amazon販売かどうか（日本）");
            $table->boolean('is_amazon_us')->nullable()->comment("Amazon販売かどうか（アメリカ）");
            $table->text('material_type_us')->nullable()->comment("米国材料");
            $table->text('maximum_hours_jp')->nullable()->comment("リードタイム最大時間（日本）");
            $table->text('maximum_hours_us')->nullable()->comment("リードタイム最大時間（米国）");
            $table->text('minimum_hours_jp')->nullable()->comment("リードタイム最小時間（日本）");
            $table->text('minimum_hours_us')->nullable()->comment("リードタイム最小時間（米国）");
            $table->text('model_us')->nullable()->comment("米国モデル");
            $table->integer('nc_jp')->nullable()->comment("新品出品者数日本");
            $table->integer('nc_us')->nullable()->comment("新品出品者数米国");
            $table->double('np_jp')->nullable()->comment("新品最低価格日本");
            $table->double('np_us')->nullable()->comment("新品最低価格米国");
            $table->double('pp_jp')->nullable()->comment("日本のプライム配送の価格");
            $table->double('pp_us')->nullable()->comment("米国のプライム配送の価格");
            $table->text('rank_id_jp')->nullable()->comment("ランキング順位がある場合のカテゴリ名");
            $table->integer('rank_jp')->nullable()->comment("日本ランキング");
            $table->integer('rank_us')->nullable()->comment("米国ランキング");
            $table->integer('seller_feedback_count')->nullable()->comment("評価の数");
            $table->double('seller_feedback_rating')->nullable()->comment("評価％");
            $table->text('seller_id')->nullable()->comment("セラーID");
            $table->double('shipping_cost')->nullable()->comment("送料（日米） カート価格とプライム価格と自社出荷");
            $table->integer('size_h_us')->nullable()->comment("米国高さ");
            $table->integer('size_l_us')->nullable()->comment("米国長さ");
            $table->integer('size_w_us')->nullable()->comment("米国幅");
            $table->text('size_us')->nullable()->comment("米国サイズ");
            $table->double('weight_us')->nullable()->comment("米国重量");
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
            $table->dropColumn('ap_jp');
            $table->dropColumn('brand_jp');
            $table->dropColumn('brand_us');
            $table->dropColumn('cate_us');
            $table->dropColumn('color_us');
            $table->dropColumn('cp_jp');
            $table->dropColumn('cp_point');
            $table->dropColumn('cp_us');
            $table->dropColumn('img_url_01');
            $table->dropColumn('img_url_02');
            $table->dropColumn('img_url_03');
            $table->dropColumn('img_url_04');
            $table->dropColumn('img_url_05');
            $table->dropColumn('img_url_06');
            $table->dropColumn('img_url_07');
            $table->dropColumn('img_url_08');
            $table->dropColumn('img_url_09');
            $table->dropColumn('img_url_10');
            $table->dropColumn('is_amazon_jp');
            $table->dropColumn('is_amazon_us');
            $table->dropColumn('material_type_us');
            $table->dropColumn('maximum_hours_jp');
            $table->dropColumn('maximum_hours_us');
            $table->dropColumn('minimum_hours_jp');
            $table->dropColumn('minimum_hours_us');
            $table->dropColumn('model_us');
            $table->dropColumn('nc_jp');
            $table->dropColumn('nc_us');
            $table->dropColumn('np_jp');
            $table->dropColumn('np_us');
            $table->dropColumn('pp_jp');
            $table->dropColumn('pp_us');
            $table->dropColumn('rank_id_jp');
            $table->dropColumn('rank_jp');
            $table->dropColumn('rank_us');
            $table->dropColumn('seller_feedback_count');
            $table->dropColumn('seller_feedback_rating');
            $table->dropColumn('seller_id');
            $table->dropColumn('shipping_cost');
            $table->dropColumn('size_h_us');
            $table->dropColumn('size_l_us');
            $table->dropColumn('size_w_us');
            $table->dropColumn('size_us');
            $table->dropColumn('weight_us');
        });
    }
};
