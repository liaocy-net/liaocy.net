@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('ui/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('ui/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('ui/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('ui/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <style>

    </style>
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 px-0">
        <!-- Content Header -->
        <div class="content-header card pt-3 pb-3 px-4">
            <div class="row content-header-title">
                <div class="col-12 d-flex align-items-center">
                    <h4 class="fw-bold m-0">Yahoo JP 出品履歴詳細</h4>
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-body">
                <div class="mb-4">
                    <form>
                        <div class="row mb-3">
                            <div class="col-6 col-sm-3 mb-2">
                                <label class="form-label" for="search_asin">ASIN</label>
                                <input type="text" id="search_asin" name="search_asin" class="form-control" />
                            </div>
                            <div class="col-6 col-sm-3 mb-2">
                                <label class="form-label" for="search_asin">ブランド</label>
                                <input type="text" id="search_file_brand" name="search_brand" class="form-control" />
                            </div>
                            <div class="col-6 col-sm-3 mb-2">
                                <label class="form-label" for="search_asin">タイトル</label>
                                <input type="text" id="search_file_title" name="search_title" class="form-control" />
                            </div>
                            <div class="col-6 col-sm-3 mb-2 d-inline-flex align-items-end">
                                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="refresh()"><i class="fas fa-search me-1"></i>検索</button>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6 col-sm-3 mb-2">
                                <label class="form-label" for="search_sort_column">ソートカラム</label>
                                <select id="search_sort_column" name="search_sort_column" class="form-select">
                                    <option value="created">作成順</option>
                                    <option value="price">仕入価格</option>
                                    <option value="asin">ASIN</option>
                                    <option value="title">タイトル</option>
                                    <option value="weight">重量</option>
                                </select>
                            </div>
                            <div class="col-6 col-sm-3 mb-2">
                                <label class="form-label" for="search_sort_order">高い順/低い順</label>
                                <select id="search_sort_order" name="search_sort_order" class="form-select">
                                    <option value="asc">低い順</option>
                                    <option value="desc">高い順</option>
                                </select>
                            </div>
                            <div class="col-6 col-sm-3 mb-2 d-inline-flex align-items-end">
                                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="refresh()"><i class="fas fa-refresh me-1"></i>更新</button>
                            </div>
                        </div>
                    </form>
                </div>
                <form id="formProductsList" class="mb-3" action="{{route('exhibit_history.process_products')}}" method="POST">
                    <div class="">
                        <h5 class="fw-bold">商品一覧</h5>
                        <input type="checkbox" id="donotShowProductCannotExhibit" name="donotShowProductCannotExhibit" class="form-check-input" checked onchange="refresh()" /> 出品可能商品のみ表示
                        <div id="tab_products" class="table-responsive text-nowrap">
                            <table id="tableProductsList" class="table table-bordered dataTable table-striped text-center text-wrap" style="font-size:12px">
                                <thead class="table-light">
                                    <tr>
                                        <th style="font-size: 9px;" class="dt-checkboxes-cell dt-checkboxes-select-all"><input type="checkbox" class="checkbox_all form-check-input"></th>
                                        <th style="font-size: 9px;">画像</th>
                                        <th style="font-size: 9px;" class="">タイトル</th>
                                        <th style="font-size: 9px;" class="">ブランド(US)</th>
                                        <th style="font-size: 9px;" class="">ASIN</th>
                                        <th style="font-size: 9px;" class="">US価格($)</th>
                                        <th style="font-size: 9px;" class="">最低利益額(円)</th>
                                        <th style="font-size: 9px;" class="">最低利益率(%)</th>
                                        <th style="font-size: 9px;" class="">為替(円)</th>
                                        <th style="font-size: 9px;" class="">関税消費税率</th>
                                        <th style="font-size: 9px;" class="">国内送料(円)</th>
                                        <th style="font-size: 9px;" class="">国際送料($)</th>
                                        <th style="font-size: 9px;" class="">手数料率(%)</th>
                                        <th style="font-size: 9px;" class="">最低利益計算価格(円)</th>
                                        <th style="font-size: 9px;" class="">最低利益計算率価格(円)</th>
                                        <th style="font-size: 9px;" class="">最終出品価格(JPY)</th>
                                        <th style="font-size: 9px;" class="">サイズ(cm)</th>
                                        <th style="font-size: 9px;" class="">重量(kg)</th>
                                        <th style="font-size: 9px;">メッセージ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div id="nav_product" class="pagination-body mt-3">
                        </div>
                    </div>

                    <hr class="my-4 mx-n4">
                    <div class="">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <input type="hidden" name="product_batch_id" value="{{ request()->input('product_batch_id') }}" />
                                <input id="formact" type="hidden" name="act" value="" />
                                @if ($has_exhibited_to_yahoo_jp)
                                    <p class="text-danger">※上記商品はYahooJPへ出品済みです。</p>
                                    <button id="btn_delete_product" type="button" class="btn btn-danger waves-effect waves-light me-sm-3 mb-2" disabled><i class="fas fa-trash me-1"></i>チェックした商品を削除する</button>
                                    <button id="btn_exhibit_to_yahoo_jp" type="button" class="btn btn-primary waves-effect waves-light mb-2" disabled><i class="fas fa-save me-1"></i>出品する</button>
                                @else
                                    <button id="btn_delete_product" type="submit" onclick="$(this).closest('form').find('#formact').val('cancel_exhibit_to_yahoo_jp');" class="btn btn-danger waves-effect waves-light me-sm-3 mb-2"><i class="fas fa-trash me-1"></i>チェックした商品を削除する</button>
                                    <button id="btn_exhibit_to_yahoo_jp" type="submit" onclick="$(this).closest('form').find('#formact').val('exhibit_to_yahoo_jp');" class="btn btn-primary waves-effect waves-light mb-2"><i class="fas fa-save me-1"></i>出品する</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@php
@endphp

@section('script')
<script src="{{asset('ui/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script>
    $(document).ready(function(){
        'use strict';

        // var table = new DataTable('#myTable', {
        //     language: {
        //         url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ja.json',
        //     },
        // });

        refresh();

        submitForm("formProductsList", function(data){
            console.log(data);
            if (data["act"] == "exhibit_to_yahoo_jp") {
                alert("出品リストに追加しました。");
                location.reload();
            }
            refresh();
        }, function(){
            showLoading('tab_products');
        });
    });

    $(function () {
        
    });

    // var sort = function(sortBy) {
    //     if (window.sortBy == sortBy) {
    //         window.sortOrder = window.sortOrder == 'asc' ? 'desc' : 'asc';
    //     } else {
    //         window.sortOrder = 'asc';
    //     }
    //     window.sortBy = sortBy;
    //     refresh();
    // };

    var showLoading = function(tableId) {
        var loading = '<tr><td>';
        loading += '<div class="spinner-border text-primary" role="status">';
        loading += '<span class="visually-hidden">Loading...</span>';
        loading += '</div>';
        loading += '</td></tr>';
        $('#' + tableId + ' .table tbody').html(loading);
    };

    var refresh = function(page = 1) {
        showLoading('tab_products');
        getData("{{route('exhibit_history.get_products')}}", {
            product_batch_id: {{ request()->input('product_batch_id') }},
            page: page,
            exhibit_to: 'yahoo_jp',
            asin: $('#search_asin').val(),
            brand: $('#search_file_brand').val(),
            title: $('#search_file_title').val(),
            search_sort_column: $('#search_sort_column').val(),
            search_sort_order: $('#search_sort_order').val(),
            donot_show_product_cannot_exhibit: $('#donotShowProductCannotExhibit').prop('checked') ? 1 : 0,
        }, function(data) {
            let html = '';
            data.data.forEach(product => {
                if (product.can_be_exhibit_to_yahoo_jp) {
                    html += '<tr>';
                } else {
                    html += '<tr class="bg-secondary bg-gradient">';
                }
                
                html += '<td>';
                if (product.can_be_exhibit_to_yahoo_jp) {
                    html += '<input type="checkbox" name="product_ids[]" value="' + product.id + '" class="sub_checkbox form-check-input" />';
                }
                html += '</td>';
                
                                html += '<td><img style="max-width:50px;" src="' + product.img_url_01 + '" /></td>';
                html += '<td>' + (product.title_jp ? product.title_jp : product.title_us) + '</div></td>';
                html += '<td>' + (product.brand_us ? product.brand_us : '-') + '</div></td>';
                html += '<td>' + product.asin + '<br />' + '<a target="_blank" href="https://www.amazon.com/dp/' + product.asin + '">US</a> <a target="_blank" href="https://www.amazon.co.jp/dp/' + product.asin + '">JP</a>' + '</td>';

                // 仕上げ価格($)
                html += '<td>' + (product.purchase_price_us ? product.purchase_price_us : '-') + '</td>';
                // 最低利益額(円)
                html += '<td>' + product.yahoo_min_profit + '</td>';
                // 最低利益率(%)
                html += '<td>' + product.yahoo_profit_rate * 100 + '</td>';

                // 為替(円)
                html += '<td>' + product.common_currency_rate + '</td>';
                // 関税消費税率
                html += '<td>' + product.common_customs_tax * 100 + '</td>';
                // 国内送料(円)
                html += '<td>' + product.common_country_shipping + '</td>';
                // 国際送料($)
                html += '<td>' + product.foreign_shipping + '</td>';
                // 手数料率(%)
                html += '<td>' + product.yahoo_using_sale_commission * 100 + '</td>';

                // 最低利益計算価格(円)
                html += '<td>' + (product.yahoo_jp_min_hope_price_jpy ? product.yahoo_jp_min_hope_price_jpy : '-') + '</td>';
                // 最低利益計算率価格(円)
                html += '<td>' + (product.yahoo_jp_min_rate_price_jpy ? product.yahoo_jp_min_rate_price_jpy : '-') + '</td>';
                // 最終出品価格(JPY)
                if (product.can_be_exhibit_to_yahoo_jp_price) {
                    html += '<td>' + product.can_be_exhibit_to_yahoo_jp_price + '</td>';
                } else {
                    html += '<td>-</td>';
                }
                // サイズ(CM)
                html += '<td>' + (product.size_l_us ? product.size_l_us : '-') + '<br />' + (product.size_w_us ? product.size_w_us : '-') + '<br />' + (product.size_h_us ? product.size_h_us : '-') + '</td>';
                // 重量(KG)
                html += '<td>' + (product.weight_us ? (Math.round(product.weight_us * 10) / 10 ) : '-') + '</td>';
                // メッセージ
                html += '<td>' + product.can_be_exhibit_to_yahoo_jp_message + '</td>';
                html += '</tr>';
            });
            $('#tab_products .table tbody').html(html);

            var navProduct = getNavigator(data, 'refresh');

            $('#nav_product').html(navProduct);

        }, function() {

        });
    };
</script>
@endsection

