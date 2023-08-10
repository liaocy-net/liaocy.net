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
                    <h4 class="fw-bold m-0">価格改定履歴</h4>
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
                            <div class="col-sm-5 mb-2">
                                <label class="form-label" for="search_period">期間</label>
                                <div class="input-group input-daterange" id="search_period">
                                    <input type="text" id="search_period_from" name="search_period_from" placeholder="YYYY-MM-DD" class="form-control flatpickr-input" />
                                    <span class="input-group-text">～</span>
                                    <input type="text" id="search_period_to" name="search_period_to" placeholder="YYYY-MM-DD" class="form-control flatpickr-input" />
                                </div>
                            </div>
                            <div class="col-auto mb-2 d-inline-flex align-items-end">
                                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="refresh();"><i class="fas fa-search me-1"></i>検索</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab_amazon" class="mb-4">
                    <h5 class="fw-bold">Amazon JP 価格改定履歴 （出品中商品数 <span id="span_amazon_exhibiting_product_count">...</span>）</h5>
                    @if ($my->amazon_jp_should_update_price === 1)
                        <span class="text-success">価格自動改定稼働中</span>
                    @elseif ($my->amazon_jp_should_update_price === 0)
                        <span class="text-warning">価格自動改定停止中</span>
                    @else
                        <span class="text-danger">エラーが発生しています。管理者に連絡してください。</span>
                    @endif
                    <div class="table-responsive text-nowrap">
                        <form id="formDeleteAmazonExhibitingProduct" class="mb-3" action="{{route('update_history.delete_exhibiting_products')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="platform" value="amazon"/>
                            <div class="col-sm-6 mb-3">
                                <div class="input-group">
                                    <input type="file" id="asin_file" name="asin_file" class="form-control"/>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fas fa-remove me-1"></i>Amazon JP 出品中から削除</button>
                                </div>
                            </div>
                        </form>
                        <table class="table table-bordered table-striped text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>変更日時</th>
                                    <th>ステータス</th>
                                    <th>価格改定件数</th>
                                    <th>メッセージ</th>
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

                    <div id="nav_amazon" class="pagination-body mt-3">
                    </div>
                </div>

                <div id="tab_yahoo" class="mb-4">
                    <h5 class="fw-bold">Yahoo JP 価格改定履歴 （出品中商品数 <span id="span_yahoo_exhibiting_product_count">...</span>）</h5>
                    @if ($my->yahoo_jp_should_update_price === 1)
                        <span class="text-success">価格自動改定稼働中</span>
                    @elseif ($my->yahoo_jp_should_update_price === 0)
                        <span class="text-warning">価格自動改定停止中</span>
                    @else
                        <span class="text-danger">エラーが発生しています。管理者に連絡してください。</span>
                    @endif
                    <div class="table-responsive text-nowrap">
                        <form id="formDeleteYahooExhibitingProduct" class="mb-3" action="{{route('update_history.delete_exhibiting_products')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="platform" value="yahoo"/>
                            <div class="col-sm-6 mb-3">
                                <div class="input-group">
                                    <input type="file" id="asin_file" name="asin_file" class="form-control"/>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fas fa-remove me-1"></i>Yahoo JP 出品中から削除</button>
                                </div>
                            </div>
                        </form>
                        <table class="table table-bordered table-striped text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>変更日時</th>
                                    <th>ステータス</th>
                                    <th>価格改定件数</th>
                                    <th>メッセージ</th>
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
                    
                    <div id="nav_yahoo" class="pagination-body mt-3">
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
        refresh();
    });

    $(function () {
        
    });

    var showLoading = function(tableId) {
        var loading = '<tr><td>';
        loading += '<div class="spinner-border text-primary" role="status">';
        loading += '<span class="visually-hidden">Loading...</span>';
        loading += '</div>';
        loading += '</td></tr>';
        $('#' + tableId + ' .table tbody').html(loading);
    };

    var refreshAmazon = function(page = 1) {  
        showLoading('tab_amazon');
        
        getData("{{route('update_history.get_update_histories')}}", {
            platform: "amazon",
            page: page,
            period_from: $('#search_period_from').val(),
            period_to: $('#search_period_to').val(),
        }, function(data) {
            $('#span_amazon_exhibiting_product_count').html(data.exhibiting_product_count);
            let html = '';
            data.product_batches.data.forEach(history => {
                html += '<tr>';
                html += '<td>' + (history.start_at ? history.start_at : '-') + '</td>';
                html += '<td>' + history.patch_status + '</td>';
                html += '<td>' + history.products_count + '</td>';
                
                html += '<td>';
                if (history.has_feed_document && history.end_at) {
                    html += '<a href="{{route("exhibit_history.download_batch_feed_document_tsv")}}?product_batch_id=' + history.product_batch_id + '" target="_blank">改定ファイル</a> ';
                }
                if (history.has_message && history.end_at) {
                    html += '<a href="{{route("exhibit_history.product_batch_message")}}?product_batch_id=' + history.product_batch_id + '" target="_blank">詳細</a>';
                }
                html += '</td>';

                html += '</tr>';
            });
            $('#tab_amazon .table tbody').html(html);

            var navAmazon = getNavigator(data, 'refreshAmazon');

            $('#nav_amazon').html(navAmazon);

        }, function() {

        });
    };

    var refreshYahoo = function(page = 1) {  
        showLoading('tab_yahoo');
        
        getData("{{route('update_history.get_update_histories')}}", {
            platform: "yahoo",
            page: page,
            period_from: $('#search_period_from').val(),
            period_to: $('#search_period_to').val(),
        }, function(data) {
            $('#span_yahoo_exhibiting_product_count').html(data.exhibiting_product_count);
            let html = '';
            data.product_batches.data.forEach(history => {
                html += '<tr>';
                html += '<td>' + (history.start_at ? history.start_at : '-') + '</td>';
                html += '<td>' + history.patch_status + '</td>';
                html += '<td>' + history.products_count + '</td>';
                
                html += '<td>';
                // if (history.has_feed_document && history.end_at) {
                //     html += '<a href="{{route("exhibit_history.download_batch_feed_document_tsv")}}?product_batch_id=' + history.product_batch_id + '" target="_blank">改定ファイル</a> ';
                // }
                if (history.has_message && history.end_at) {
                    html += '<a href="{{route("exhibit_history.product_batch_message")}}?product_batch_id=' + history.product_batch_id + '" target="_blank">詳細</a>';
                }
                html += '</td>';

                html += '</tr>';
            });
            $('#tab_yahoo .table tbody').html(html);

            var navYahoo = getNavigator(data, 'refreshYahoo');

            $('#nav_yahoo').html(navYahoo);

        }, function() {

        });
    };

    var refresh = function() {
        refreshAmazon();
        refreshYahoo();
    };
</script>
@endsection

