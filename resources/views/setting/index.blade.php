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
                    <form id="formCommonSetting" class="mb-3" action="{{route('setting.update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="row">
                            
                            
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="common_currency_rate">為替</label>
                                <div class="input-group" id="common_currency_rate_group">
                                    <span class="input-group-text col-5">1$ = {{ $my->common_currency_rate }}円</span>
                                    <input type="number" id="common_currency_rate" name="common_currency_rate" class="form-control" min="1" max="9999999" required value="{{ $my->common_currency_rate }}"/>
                                    <span class="input-group-text">円</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="common_country_shipping">国内送料</label>
                                <div class="input-group" id="common_country_shipping_group">
                                    <span class="input-group-text col-5">{{ $my->common_country_shipping }}円</span>
                                    <input type="number" id="common_country_shipping" name="common_country_shipping" class="form-control" min="0" max="999999" required value="{{ $my->common_country_shipping }}"/>
                                    <span class="input-group-text">円</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="common_foreign_shipping_without_weight">重量なしの場合の国際送料</label>
                                <div class="input-group" id="common_foreign_shipping_without_weight_group">
                                    <span class="input-group-text col-5">{{ $my->common_foreign_shipping_without_weight }}円</span>
                                    <input type="number" id="common_foreign_shipping_without_weight" name="common_foreign_shipping_without_weight" class="form-control" min="0" max="999999" required value="{{ $my->common_foreign_shipping_without_weight }}"/>
                                    <span class="input-group-text">円</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="common_customs_tax">関税消費税</label>
                                <div class="input-group" id="common_customs_tax_group">
                                    <span class="input-group-text col-5">{{ $my->common_customs_tax * 100 }}%</span>
                                    <input type="number" id="common_customs_tax" name="common_customs_tax" class="form-control" min="0" max="100" step="0.01" required value="{{ $my->common_customs_tax * 100 }}"/>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="common_purchase_price">仕入れ価格</label>
                                <div class="input-group" id="common_purchase_price">
                                    <span class="input-group-text col-5">{{ $my->common_purchase_price_from }}~{{ $my->common_purchase_price_to }}ドル</span>
                                    <input type="number" id="common_purchase_price_from" name="common_purchase_price_from" min="1" max="999999"  class="form-control" required value="{{ $my->common_purchase_price_from }}"/>
                                    <span class="input-group-text">~</span>
                                    <input type="number" id="common_purchase_price_to" name="common_purchase_price_to"  min="1" max="999999" class="form-control" required value="{{ $my->common_purchase_price_to }}"/>
                                    <span class="input-group-text">ドル</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="common_max_weight">取扱い最大重量</label>
                                <div class="input-group" id="common_max_weight_group">
                                    <span class="input-group-text col-5">{{ $my->common_max_weight }}kg</span>
                                    <input type="number" id="common_max_weight" name="common_max_weight" class="form-control" min="1" max="999999" required value="{{ $my->common_max_weight }}"/>
                                    <span class="input-group-text">kg</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="common_size">サイズ (縦 + 横 + 高さ 合計値)</label>
                                <div class="input-group" id="common_size">
                                    <span class="input-group-text col-5">{{ $my->common_size_from }} ~ {{ $my->common_size_to }}cm</span>
                                    <input type="number" id="common_size_from" name="common_size_from" class="form-control"  min="0" max="999999" required value="{{ $my->common_size_from }}"/>
                                    <span class="input-group-text">~</span>
                                    <input type="number" id="common_size_to" name="common_size_to" class="form-control"  min="1" max="999999" required value="{{ $my->common_size_to }}"/>
                                    <span class="input-group-text">cm</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="common_purchase_mark">仕入先評価</label>
                                <div class="input-group" id="common_purchase_mark_group">
                                    <span class="input-group-text col-5">
                                        @if ($my->common_purchase_mark == 0)
                                            フィルタしない
                                        @elseif ($my->common_purchase_mark == 0.9)
                                            90% 以上でフィルタ実施
                                        @elseif ($my->common_purchase_mark == 0.8)
                                            80% 以上でフィルタ実施
                                        @elseif ($my->common_purchase_mark == 0.7)
                                            70% 以上でフィルタ実施
                                        @elseif ($my->common_purchase_mark == 0.6)
                                            60% 以上でフィルタ実施
                                        @endif
                                    </span>
                                    <select id="common_purchase_mark" name="common_purchase_mark" class="form-select">
                                        <option value="0" @if ($my->common_purchase_mark == 0) selected @endif>フィルタしない</option>
                                        <option value="90" @if ($my->common_purchase_mark == 0.9) selected @endif>90% 以上でフィルタ実施</option>
                                        <option value="80" @if ($my->common_purchase_mark == 0.8) selected @endif>80% 以上でフィルタ実施</option>
                                        <option value="70" @if ($my->common_purchase_mark == 0.7) selected @endif>70% 以上でフィルタ実施</option>
                                        <option value="60" @if ($my->common_purchase_mark == 0.6) selected @endif>60% 以上でフィルタ実施</option>
                                    </select>
                                </div>
                            </div>
                            @if ($my->role === 'admin')
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" for="common_foreign_shipping">【管理者のみ】国際送料 (.xlsx型のExcelファイルをアップロードしてください)</label>
                                    <div class="input-group" id="common_foreign_shipping_group">
                                        <a type="button" href="{{route('setting.download_my_foreign_shippings_xlsx')}}" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download me-1"></i>ダウンロード</a>
                                        <input type="file" id="common_foreign_shipping" name="common_foreign_shipping" class="form-control" />
                                    </div>
                                </div>
                            @endif

                            <div class="col-12 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary me-sm-2 me-1 waves-effect waves-light" name="act" value="common_setting"><i class="fas fa-save me-1"></i>保存</button>
                            </div>
                            
                        </div>
                    </form>
                </div>

                <div id="divAmazonSetting" class="mb-3">
                    <h5 class="fw-bold">Amazon</h5>
                    <form id="formAmazonSetting" class="mb-3" action="{{route('setting.update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="amazon_hope_profit">希望利益額</label>
                                <div class="input-group" id="amazon_hope_profit_group">
                                    <span class="input-group-text col-5">{{ $my->amazon_hope_profit }}円</span>
                                    <input type="number" id="amazon_hope_profit" name="amazon_hope_profit" class="form-control" min="0" max="999999" value="{{ $my->amazon_hope_profit }}"/>
                                    <span class="input-group-text">円</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="amazon_min_profit">最低利益額</label>
                                <div class="input-group" id="amazon_min_profit_group">
                                    <span class="input-group-text col-5">{{ $my->amazon_min_profit }}円</span>
                                    <input type="number" id="amazon_min_profit" name="amazon_min_profit" class="form-control" min="0" max="999999" value="{{ $my->amazon_min_profit }}"/>
                                    <span class="input-group-text">円</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="amazon_hope_profit_rate">希望利益率</label>
                                <div class="input-group" id="amazon_hope_profit_rate_group">
                                    <span class="input-group-text col-5">{{ $my->amazon_hope_profit_rate * 100 }}%</span>
                                    <input type="number" id="amazon_hope_profit_rate" name="amazon_hope_profit_rate" class="form-control" min="0" max="999999" value="{{ $my->amazon_hope_profit_rate * 100 }}"/>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="amazon_min_profit_rate">最低利益率</label>
                                <div class="input-group" id="amazon_min_profit_rate_group">
                                    <span class="input-group-text col-5">{{ $my->amazon_min_profit_rate * 100 }}%</span>
                                    <input type="number" id="amazon_min_profit_rate" name="amazon_min_profit_rate" class="form-control" min="0" max="999999" value="{{ $my->amazon_min_profit_rate * 100 }}"/>
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
                                            <input class="form-check-input" type="radio" name="amazon_using_profit" id="amazon_using_profit_1" value="1" @if ($my->amazon_using_profit === 1) checked @endif>
                                            <label class="form-check-label" for="amazon_using_profit_1">円</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="amazon_using_profit" id="amazon_using_profit_2" value="2" @if ($my->amazon_using_profit === 2) checked @endif>
                                            <label class="form-check-label" for="amazon_using_profit_2">%</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="amazon_using_sale_commission">販売手数料</label>
                                <div class="input-group" id="amazon_using_sale_commission_group">
                                    <span class="input-group-text col-5">{{ $my->amazon_using_sale_commission * 100 }}%</span>
                                    <input type="number" id="amazon_using_sale_commission" name="amazon_using_sale_commission" class="form-control" min="0" max="999999" value="{{ $my->amazon_using_sale_commission * 100 }}"/>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="amazon_stock">在庫数</label>
                                <div class="input-group" id="amazon_stock_group">
                                    <span class="input-group-text col-5">{{ $my->amazon_stock }}個</span>
                                    <input type="number" id="amazon_stock" name="amazon_stock" class="form-control" min="0" max="999999" value="{{ $my->amazon_stock }}"/>
                                    <span class="input-group-text">個</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="amazon_price_increase_rate">値上げ率</label>
                                <div class="input-group" id="amazon_price_increase_rate_group">
                                    <span class="input-group-text col-5">{{ $my->amazon_price_increase_rate * 100 }}%</span>
                                    <input type="number" id="amazon_price_increase_rate" name="amazon_price_increase_rate" class="form-control" min="100" max="9999" value="{{ $my->amazon_price_increase_rate * 100 }}"/>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="amazon_using_sale_commission">値下げ金額</label>
                                <div class="input-group" id="amazon_price_cut_group">
                                    <span class="input-group-text col-5">{{ $my->amazon_price_cut }}円</span>
                                    <input type="number" id="amazon_price_cut" name="amazon_price_cut" class="form-control" value="{{ $my->amazon_price_cut }}" min="1" max="999999"/>
                                    <span class="input-group-text">円</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="amazon_rival">ライバル</label>
                                <div class="input-group" id="amazon_rival_group">
                                    <span class="input-group-text col-5">
                                        @if ($my->amazon_rival === 1)
                                            ON
                                        @elseif ($my->amazon_rival === 2)
                                            OFF
                                        @endif
                                    </span>
                                    <select id="amazon_rival" name="amazon_rival" class="form-select">
                                        <option value="1" @if ($my->amazon_rival === 1) selected @endif>ON</option>
                                        <option value="2" @if ($my->amazon_rival === 2) selected @endif>OFF</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="amazon_point_rate">ポイント比率</label>
                                <div class="input-group" id="amazon_point_rate_group">
                                    <span class="input-group-text col-5">{{ $my->amazon_point_rate * 100 }}%</span>
                                    <input type="number" id="amazon_point_rate" name="amazon_point_rate" class="form-control" min="0" max="999999" value="{{ $my->amazon_point_rate * 100 }}"/>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="amazon_lead_time_prime">プライムリードタイム</label>
                                <div class="input-group" id="amazon_lead_time_prime_group">
                                    <span class="input-group-text col-5">{{ $my->amazon_lead_time_prime }}日</span>
                                    <input type="number" id="amazon_lead_time_prime" name="amazon_lead_time_prime" class="form-control" min="0" max="999999" value="{{ $my->amazon_lead_time_prime }}"/>
                                    <span class="input-group-text">日</span>
                                </div>
                            </div>
                            @php
                                $global_amazon_lead_time = App\Models\Setting::getInt("global_amazon_lead_time", 15);
                            @endphp
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="amazon_lead_time_less">リードタイム({{ $global_amazon_lead_time }}日未満の場合)</label>
                                <div class="input-group" id="amazon_lead_time_less_group">
                                    <span class="input-group-text col-5">{{ $my->amazon_lead_time_less }}日</span>
                                    <input type="number" id="amazon_lead_time_less" name="amazon_lead_time_less" class="form-control" min="0" max="999999" value="{{ $my->amazon_lead_time_less }}"/>
                                    <span class="input-group-text">日</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="amazon_lead_time_more">リードタイム({{ $global_amazon_lead_time }}日以上の場合)</label>
                                <div class="input-group" id="amazon_lead_time_more_group">
                                    <span class="input-group-text col-5">{{ $my->amazon_lead_time_more }}日</span>
                                    <input type="number" id="amazon_lead_time_more" name="amazon_lead_time_more" class="form-control" min="0" max="999999" value="{{ $my->amazon_lead_time_more }}"/>
                                    <span class="input-group-text">日</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="amazon_white_list_brand">ホワイトリスト抽出重複ブランド数</label>
                                <div class="input-group" id="amazon_white_list_brand_group">
                                    <span class="input-group-text col-5">{{ $my->amazon_white_list_brand }}個</span>
                                    <input type="number" id="amazon_white_list_brand" name="amazon_white_list_brand" class="form-control" min="0" max="999999" value="{{ $my->amazon_white_list_brand }}"/>
                                    <span class="input-group-text">個</span>
                                </div>
                            </div>
                            @if ($my->role === 'admin')
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" for="global_amazon_lead_time">【管理者のみ】リードタイム閾値</label>
                                    <div class="input-group" id="global_amazon_lead_time_group">
                                        <span class="input-group-text col-5">{{ $global_amazon_lead_time }}日</span>
                                        <input type="number" id="global_amazon_lead_time" name="global_amazon_lead_time" class="form-control" min="0" max="999999" value="{{ $global_amazon_lead_time }}"/>
                                        <span class="input-group-text">日</span>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-12 mb-3">
                                <label class="form-label" for="amazon_exhibit_comment">Amazon 出品コメント</label>
                                <div class="input-group" id="amazon_exhibit_comment_group">
                                    <span class="input-group-text col-6 text-wrap text-muted text-start">{{ $my->amazon_exhibit_comment_group }}</span>
                                    <textarea class="form-control" id="amazon_exhibit_comment_group" name="amazon_exhibit_comment_group" rows="5" maxlength="2000">{{ $my->amazon_exhibit_comment_group }}</textarea>
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary me-sm-2 me-1 waves-effect waves-light" name="act" value="amazon_setting"><i class="fas fa-save me-1"></i>保存</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="divYahooSetting" class="mb-3">
                    <h5 class="fw-bold">Yahoo!</h5>
                    <form id="formYahooSetting" class="mb-3" action="{{route('setting.update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="yahoo_min_profit">最低利益額</label>
                                <div class="input-group" id="yahoo_min_profit_group">
                                    <span class="input-group-text col-5">{{ $my->yahoo_min_profit }}円</span>
                                    <input type="number" id="yahoo_min_profit" name="yahoo_min_profit" class="form-control" value="{{ $my->yahoo_min_profit }}" min="1" max="999999" />
                                    <span class="input-group-text">円</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="yahoo_profit_rate">利益率</label>
                                <div class="input-group" id="yahoo_profit_rate_group">
                                    <span class="input-group-text col-5">{{ $my->yahoo_profit_rate * 100 }}%</span>
                                    <input type="number" id="yahoo_profit_rate" name="yahoo_profit_rate" class="form-control" value="{{ $my->yahoo_profit_rate * 100 }}" min="0" max="100"/>
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
                                            <input class="form-check-input" type="radio" name="yahoo_using_profit" id="yahoo_using_profit_1" value="1" @if ($my->yahoo_using_profit === 1) checked @endif>
                                            <label class="form-check-label" for="yahoo_using_profit_1">円</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="yahoo_using_profit" id="yahoo_using_profit_2" value="2" @if ($my->yahoo_using_profit === 2) checked @endif>
                                            <label class="form-check-label" for="yahoo_using_profit_2">%</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="yahoo_using_sale_commission">販売手数料</label>
                                <div class="input-group" id="yahoo_using_sale_commission_group">
                                    <span class="input-group-text col-5">{{ $my->yahoo_using_sale_commission * 100 }}%</span>
                                    <input type="number" id="yahoo_using_sale_commission" name="yahoo_using_sale_commission" class="form-control" value="{{ $my->yahoo_using_sale_commission * 100 }}" min="0" max="100"/>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="yahoo_stock">在庫数</label>
                                <div class="input-group" id="yahoo_stock_group">
                                    <span class="input-group-text col-5">{{ $my->yahoo_stock }}個</span>
                                    <input type="number" id="yahoo_stock" name="yahoo_stock" class="form-control" value="{{ $my->yahoo_stock }}" min="0" max="999999"/>
                                    <span class="input-group-text">個</span>
                                </div>
                            </div>
                            @if ($my->role === 'admin')
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" for="yahoo_category">【管理者のみ】カテゴリー (.csv)</label>
                                    <div class="input-group" id="yahoo_category_group">
                                        <a type="button" href="{{route('setting.download_yahoo_jp_category_xlsx')}}" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download me-1"></i>ダウンロード</a>
                                        <input type="file" id="yahoo_category" name="yahoo_category" class="form-control" />
                                    </div>
                                </div>
                            @endif


                            <div class="col-sm-12 mb-3">
                                <label class="form-label" for="yahoo_exhibit_comment">Yahoo 出品コメント</label>
                                <div class="input-group" id="yahoo_exhibit_comment_group">
                                    <span class="input-group-text col-6 text-wrap text-muted text-start">{{ $my->yahoo_exhibit_comment_group }}</span>
                                    <textarea class="form-control" id="yahoo_exhibit_comment_group" name="yahoo_exhibit_comment_group" rows="5" minlength="0" maxlength="2000">{{ $my->yahoo_exhibit_comment_group }}</textarea>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary me-sm-2 me-1 waves-effect waves-light" name="act" value="yahoo_setting"><i class="fas fa-save me-1"></i>保存</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="mb-3">
                    <h5 class="fw-bold">Yahoo API認証</h5>
                    <form id="formAuthentication" class="mb-3" action="{{route('setting.update')}}" method="post">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label" for="yahoo_api_store_account">ストアアカウント</label>
                                <input type="text" id="yahoo_api_store_account" name="yahoo_store_account" class="form-control" value="{{ $my->yahoo_store_account }}"/>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="yahoo_api_client_id">Client ID</label>
                                <input type="text" id="yahoo_api_client_id" name="yahoo_client_id" class="form-control" value="{{ $my->yahoo_client_id }}"/>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="yahoo_api_secret">シークレット</label>
                                <input type="password" id="yahoo_api_secret" name="yahoo_secret" class="form-control" value="{{ $my->yahoo_secret }}"/>
                            </div>
                            <div class="col-12 mb-3">
                                <button type="submit" class="btn btn-danger me-sm-2 me-1 waves-effect waves-light" name="act" value="yahoo_auth">
                                    <i class="ti ti-xs ti-brand-yahoo me-1"></i>ログイン認証</button>
                                @if ($my->yahoo_refresh_token_expires_in && strtotime($my->yahoo_refresh_token_expires_in) < time())
                                    <span class="text-danger">✖有効期限切れ、再認証してください。</span>
                                @elseif ($my->yahoo_refresh_token_expires_in && strtotime($my->yahoo_refresh_token_expires_in) < time() + 86400 * 7)
                                    <span class="text-warning">！有効期限が間もなく切れますから、再認証してください。有効期限:{{$my->yahoo_refresh_token_expires_in}}</span>
                                @elseif ($my->yahoo_refresh_token_expires_in && $my->yahoo_access_token)
                                    <span class="text-success">◯認証済み 有効期限:{{$my->yahoo_refresh_token_expires_in}}</span>
                                @else
                                    <span class="text-danger">✖未認証</span>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="mb-3">
                    <h5 class="fw-bold">Amazon SP API認証</h5>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <a href="https://sellercentral.amazon.co.jp/apps/authorize/consent?application_id={{env("AMAZON_JP_APPLICATION_ID")}}&version=beta&state=jp:{{ $my->user_id }}" class="btn btn-info me-sm-2 me-1 waves-effect waves-light"><i class="ti ti-xs ti-brand-amazon me-1"></i>Amazon JP認証</a>
                            @if ($my->amazon_jp_refresh_token)
                                <span class="text-success">◯認証済み</span>
                            @else
                                <span class="text-danger">✖未認証</span>
                            @endif
                        </div>
                        <div class="col-12 mb-3">
                            <a href="https://sellercentral.amazon.com/apps/authorize/consent?application_id={{env("AMAZON_US_APPLICATION_ID")}}&version=beta&state=us:{{ $my->user_id }}" class="btn btn-info me-sm-2 me-1 waves-effect waves-light"><i class="ti ti-xs ti-brand-amazon me-1"></i>Amazon US認証</a>
                            @if ($my->amazon_us_refresh_token)
                                <span class="text-success">◯認証済み</span>
                            @else
                                <span class="text-danger">✖未認証</span>
                            @endif
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

