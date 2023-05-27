<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration
{
    public function getComment()
    {
        $exhibit_comment=<<<EOD
◆新品・未使用【発送方法】海外在庫商品のため、お届けに通常2週間程お時間を頂戴しております。
米国配送センターへ到着後、検品をして発送いたします。また稀に輸送中に外装箱等に傷みが生じる場合がありますが、商品自体問題はございません。
◆【関税について】税関手続き、関税支払い等すべて当方で対応させていただいております。そのため、関税等をお支払いいただくことはありません。
◆【安心安全の返金保障】お届けする商品は十分な検品を実施しておりますが、万が一不備・不具合などございましたら大変お手数ではございますがご連絡ください。
EOD;
        return $exhibit_comment;
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('amazon_exhibit_comment_group', 2500)->default($this->getComment())->change();
            $table->string('yahoo_exhibit_comment_group', 2500)->default($this->getComment())->change();
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
            $table->text('amazon_exhibit_comment_group')->change();
            $table->text('yahoo_exhibit_comment_group')->change();
        });
    }
};
