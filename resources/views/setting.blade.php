@extends('layouts.app')

@section('style')
    <style>

    </style>
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 px-0">
        <!-- Content Header -->
        <div class="content-header card pt-3 pb-3 px-4">
            <div class="row content-header-title">
                <div class="col-12 d-flex align-items-center">
                    <h4 class="fw-bold m-0">設定</h4>
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-body">

                <div class="mb-3">
                    <h5 class="fw-bold">共通</h5>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="common_currency_rate">為替</label>
                            <div class="input-group" id="common_currency_rate_group">
                                <span class="input-group-text col-5">1$ = 120円</span>
                                <input type="number" id="common_currency_rate" name="common_currency_rate" class="form-control" />
                                <span class="input-group-text">円</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="common_country_shipping">国内送料</label>
                            <div class="input-group" id="common_country_shipping_group">
                                <span class="input-group-text col-5">1500円</span>
                                <input type="number" id="common_country_shipping" name="common_country_shipping" class="form-control" />
                                <span class="input-group-text">円</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="common_foreign_shipping_without_weight">重量なしの場合の国際送料</label>
                            <div class="input-group" id="common_foreign_shipping_without_weight_group">
                                <span class="input-group-text col-5">20000円</span>
                                <input type="number" id="common_foreign_shipping_without_weight" name="common_foreign_shipping_without_weight" class="form-control" />
                                <span class="input-group-text">円</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="common_customs_tax">関税消費税</label>
                            <div class="input-group" id="common_customs_tax_group">
                                <span class="input-group-text col-5">15%</span>
                                <input type="number" id="common_customs_tax" name="common_customs_tax" class="form-control" />
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="common_purchase_price">仕入れ価格</label>
                            <div class="input-group" id="common_purchase_price">
                                <span class="input-group-text col-5">50~1000ドル</span>
                                <input type="number" id="common_purchase_price_from" name="common_purchase_price_from" class="form-control" />
                                <span class="input-group-text">~</span>
                                <input type="number" id="common_purchase_price_to" name="common_purchase_price_to" class="form-control" />
                                <span class="input-group-text">ドル</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="common_max_weight">取扱い最大重量</label>
                            <div class="input-group" id="common_max_weight_group">
                                <span class="input-group-text col-5">100kg</span>
                                <input type="number" id="common_max_weight" name="common_max_weight" class="form-control" />
                                <span class="input-group-text">kg</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="common_size">サイズ (縦 + 横 + 高さ 合計値)</label>
                            <div class="input-group" id="common_size">
                                <span class="input-group-text col-5">0 ~ 50cm</span>
                                <input type="number" id="common_size_from" name="common_size_from" class="form-control" />
                                <span class="input-group-text">~</span>
                                <input type="number" id="common_size_to" name="common_size_to" class="form-control" />
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="common_purchase_mark">仕入先評価</label>
                            <div class="input-group" id="common_purchase_mark_group">
                                <span class="input-group-text col-5">フィルタしない</span>
                                <select id="common_purchase_mark" name="common_purchase_mark" class="form-select">
                                    <option value="0">変更する場合は選択</option>
                                    <option value="0">フィルタしない</option>
                                    <option value="90">90% 以上でフィルタ実施</option>
                                    <option value="80">80% 以上でフィルタ実施</option>
                                    <option value="70">70% 以上でフィルタ実施</option>
                                    <option value="60">60% 以上でフィルタ実施</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="common_foreign_shipping">国際送料</label>
                            <div class="input-group" id="common_foreign_shipping_group">
                                <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download me-1"></i>ダウンロード</button>
                                <input type="file" id="common_foreign_shipping" name="common_foreign_shipping" class="form-control" />
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1 waves-effect waves-light"><i class="fas fa-save me-1"></i>保存</button>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="fw-bold">amazon</h5>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="amazon_hope_profit">希望利益額</label>
                            <div class="input-group" id="amazon_hope_profit_group">
                                <span class="input-group-text col-5">10000円</span>
                                <input type="number" id="amazon_hope_profit" name="amazon_hope_profit" class="form-control" />
                                <span class="input-group-text">円</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="amazon_min_profit">最低利益額</label>
                            <div class="input-group" id="amazon_min_profit_group">
                                <span class="input-group-text col-5">10000円</span>
                                <input type="number" id="amazon_min_profit" name="amazon_min_profit" class="form-control" />
                                <span class="input-group-text">円</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="amazon_hope_profit_rate">希望利益率</label>
                            <div class="input-group" id="amazon_hope_profit_rate_group">
                                <span class="input-group-text col-5">30%</span>
                                <input type="number" id="amazon_hope_profit_rate" name="amazon_hope_profit_rate" class="form-control" />
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="amazon_min_profit_rate">最低利益率</label>
                            <div class="input-group" id="amazon_min_profit_rate_group">
                                <span class="input-group-text col-5">30%</span>
                                <input type="number" id="amazon_min_profit_rate" name="amazon_min_profit_rate" class="form-control" />
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="amazon_using_profit">適用する利益設定</label>
                            <p class="small text-muted">※注意：利益率(％)を選択した場合でも、最低利益額（円）は保持されます。</p>
                            <div class="input-group" id="amazon_using_profit_group">
                                <span class="input-group-text col-5">「 円 」が設定</span>
                                <div class="col-form-label input-group-text col-7">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="amazon_using_profit" id="amazon_using_profit_1" value="1" checked>
                                        <label class="form-check-label" for="amazon_using_profit_1">円</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="amazon_using_profit" id="amazon_using_profit_2" value="2">
                                        <label class="form-check-label" for="amazon_using_profit_2">%</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="amazon_using_sale_commission">販売手数料</label>
                            <div class="input-group" id="amazon_using_sale_commission_group">
                                <span class="input-group-text col-5">15%</span>
                                <input type="number" id="amazon_using_sale_commission" name="amazon_using_sale_commission" class="form-control" />
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="amazon_stock">在庫数</label>
                            <div class="input-group" id="amazon_stock_group">
                                <span class="input-group-text col-5">10個</span>
                                <input type="number" id="amazon_stock" name="amazon_stock" class="form-control" />
                                <span class="input-group-text">個</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="amazon_price_increase_rate">値上げ率</label>
                            <div class="input-group" id="amazon_price_increase_rate_group">
                                <span class="input-group-text col-5">180%</span>
                                <input type="number" id="amazon_price_increase_rate" name="amazon_price_increase_rate" class="form-control" />
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="amazon_rival">ライバル</label>
                            <div class="input-group" id="amazon_rival_group">
                                <span class="input-group-text col-5">ON</span>
                                <select id="amazon_rival" name="amazon_rival" class="form-select">
                                    <option value="0">変更する場合は選択</option>
                                    <option value="1">ON</option>
                                    <option value="2">OFF</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="amazon_point_rate">ポイント比率</label>
                            <div class="input-group" id="amazon_point_rate_group">
                                <span class="input-group-text col-5">3%</span>
                                <input type="number" id="amazon_point_rate" name="amazon_point_rate" class="form-control" />
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="amazon_lead_time_less">リードタイム(XX日未満の場合)</label>
                            <div class="input-group" id="amazon_lead_time_less_group">
                                <span class="input-group-text col-5">10日</span>
                                <input type="number" id="amazon_lead_time_less" name="amazon_lead_time_less" class="form-control" />
                                <span class="input-group-text">日</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="amazon_lead_time_more">リードタイム(XX日以上の場合)</label>
                            <div class="input-group" id="amazon_lead_time_more_group">
                                <span class="input-group-text col-5">20日</span>
                                <input type="number" id="amazon_lead_time_more" name="amazon_lead_time_more" class="form-control" />
                                <span class="input-group-text">日</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="amazon_lead_time_prime">プライムリードタイム</label>
                            <div class="input-group" id="amazon_lead_time_prime_group">
                                <span class="input-group-text col-5">8日</span>
                                <input type="number" id="amazon_lead_time_prime" name="amazon_lead_time_prime" class="form-control" />
                                <span class="input-group-text">日</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="amazon_white_list_brand">ホワイトリスト抽出重複ブランド数</label>
                            <div class="input-group" id="amazon_white_list_brand_group">
                                <span class="input-group-text col-5">10個</span>
                                <input type="number" id="amazon_white_list_brand" name="amazon_white_list_brand" class="form-control" />
                                <span class="input-group-text">個</span>
                            </div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <label class="form-label" for="amazon_exhibit_comment">amazon 出品コメント</label>
                            <div class="input-group" id="amazon_exhibit_comment_group">
                                <span class="input-group-text col-6 text-wrap text-muted text-start">◆新品・未使用【発送方法】海外在庫商品のため、お届けに通常2週間程お時間を頂戴しております。<br/>米国配送センターへ到着後、検品をして発送いたします。また稀に輸送中に外装箱等に傷みが生じる場合がありますが、商品自体問題はございません。<br/>◆【関税について】税関手続き、関税支払い等すべて当方で対応させていただいております。そのため、関税等をお支払いいただくことはありません。<br/>◆【安心安全の返金保障】お届けする商品は十分な検品を実施しておりますが、万が一不備・不具合などございましたら大変お手数ではございますがご連絡ください。</span>
                                <textarea class="form-control" id="amazon_exhibit_comment_group" name="amazon_exhibit_comment_group" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1 waves-effect waves-light"><i class="fas fa-save me-1"></i>保存</button>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="fw-bold">Yahoo!</h5>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="yahoo_min_profit">最低利益額</label>
                            <div class="input-group" id="yahoo_min_profit_group">
                                <span class="input-group-text col-5">10000円</span>
                                <input type="number" id="yahoo_min_profit" name="yahoo_min_profit" class="form-control" />
                                <span class="input-group-text">円</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="yahoo_profit_rate">利益率</label>
                            <div class="input-group" id="yahoo_profit_rate_group">
                                <span class="input-group-text col-5">30%</span>
                                <input type="number" id="yahoo_profit_rate" name="yahoo_profit_rate" class="form-control" />
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="yahoo_using_profit">適用する利益設定</label>
                            <p class="small text-muted">※注意：利益率(％)を選択した場合でも、最低利益額（円）は保持されます。</p>
                            <div class="input-group" id="yahoo_using_profit_group">
                                <span class="input-group-text col-5">「 円 」が設定</span>
                                <div class="col-form-label input-group-text col-7">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="yahoo_using_profit" id="yahoo_using_profit_1" value="1" checked>
                                        <label class="form-check-label" for="yahoo_using_profit_1">円</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="yahoo_using_profit" id="yahoo_using_profit_2" value="2">
                                        <label class="form-check-label" for="yahoo_using_profit_2">%</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="yahoo_using_sale_commission">販売手数料</label>
                            <div class="input-group" id="yahoo_using_sale_commission_group">
                                <span class="input-group-text col-5">15%</span>
                                <input type="number" id="yahoo_using_sale_commission" name="yahoo_using_sale_commission" class="form-control" />
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="yahoo_stock">在庫数</label>
                            <div class="input-group" id="yahoo_stock_group">
                                <span class="input-group-text col-5">10個</span>
                                <input type="number" id="yahoo_stock" name="yahoo_stock" class="form-control" />
                                <span class="input-group-text">個</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="yahoo_category">カテゴリー</label>
                            <div class="input-group" id="yahoo_category_group">
                                <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download me-1"></i>ダウンロード</button>
                                <input type="file" id="yahoo_category" name="yahoo_category" class="form-control" />
                            </div>
                        </div>


                        <div class="col-sm-12 mb-3">
                            <label class="form-label" for="yahoo_exhibit_comment">Yahoo 出品コメント</label>
                            <div class="input-group" id="yahoo_exhibit_comment_group">
                                <span class="input-group-text col-6 text-wrap text-muted text-start">◆新品・未使用【発送方法】海外在庫商品のため、お届けに通常2週間程お時間を頂戴しております。<br/>米国配送センターへ到着後、検品をして発送いたします。また稀に輸送中に外装箱等に傷みが生じる場合がありますが、商品自体問題はございません。<br/>◆【関税について】税関手続き、関税支払い等すべて当方で対応させていただいております。そのため、関税等をお支払いいただくことはありません。<br/>◆【安心安全の返金保障】お届けする商品は十分な検品を実施しておりますが、万が一不備・不具合などございましたら大変お手数ではございますがご連絡ください。</span>
                                <textarea class="form-control" id="yahoo_exhibit_comment_group" name="yahoo_exhibit_comment_group" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1 waves-effect waves-light"><i class="fas fa-save me-1"></i>保存</button>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="fw-bold">Yahoo API認証</h5>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label" for="yahoo_api_store_account">ストアアカウント</label>
                            <input type="text" id="yahoo_api_store_account" name="yahoo_api_store_account" class="form-control" />
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="yahoo_api_client_id">Client ID</label>
                            <input type="text" id="yahoo_api_client_id" name="yahoo_api_client_id" class="form-control" />
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="yahoo_api_secret">シークレット</label>
                            <input type="text" id="yahoo_api_secret" name="yahoo_api_secret" class="form-control" />
                        </div>
                        <div class="col-12 mb-3">
                            <button type="button" class="btn btn-danger me-sm-2 me-1 waves-effect waves-light">
                                <i class="ti ti-xs ti-brand-yahoo me-1"></i>ログイン認証</button>
                            <span class="text-warning">✖未認証</span>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <h5 class="fw-bold">Amazon SP API認証</h5>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <button type="button" class="btn btn-info me-sm-2 me-1 waves-effect waves-light"><i class="ti ti-xs ti-brand-amazon me-1"></i>Amazon JP認証</button>
                            <span class="text-danger">✖認証失敗（error : メッセージ）</span>
                        </div>
                        <div class="col-12 mb-3">
                            <button type="button" class="btn btn-info me-sm-2 me-1 waves-effect waves-light"><i class="ti ti-xs ti-brand-amazon me-1"></i>Amazon US認証</button>
                            <span class="text-success">◯認証済</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        'use strict';

    });

    $(function () {
        
    });
</script>
@endsection

