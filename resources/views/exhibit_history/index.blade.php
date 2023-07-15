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
                    <h4 class="fw-bold m-0">出品履歴</h4>
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
                            <div class="col-sm-4 mb-2">
                                <label class="form-label" for="search_file_name">ファイル名</label>
                                <input type="text" id="search_file_name" name="search_file_name" class="form-control" />
                            </div>
                            <div class="col-sm-5 mb-2">
                                <label class="form-label" for="search_period">期間</label>
                                <div class="input-group input-daterange" id="search_period">
                                    <input type="text" id="search_period_from" name="search_period_from" placeholder="YYYY-MM-DD" class="form-control flatpickr-input" />
                                    <span class="input-group-text">～</span>
                                    <input type="text" id="search_period_to" name="search_period_to" placeholder="YYYY-MM-DD" class="form-control flatpickr-input" />
                                </div>
                            </div>
                            <div class="col-auto mb-2 d-inline-flex align-items-end">
                                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="refresh()"><i class="fas fa-search me-1"></i>検索</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="tab_amazon" class="mb-4">
                    <h5 class="fw-bold">アマゾン出品履歴</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-striped text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>ファイル名</th>
                                    <th>アクション</th>
                                    <th>ステータス</th>
                                    <th>開始時間</th>
                                    <th>終了時間</th>
                                    <th>ASIN数</th>
                                    <th>成功件数</th>
                                    <th>失敗件数</th>
                                    <th>メッセージ</th>
                                    <th>詳細</th>
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

                <div id="tab_yahoo" class="">
                    <h5 class="fw-bold">Yahoo出品履歴</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-striped text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>ファイル名</th>
                                    <th>アクション</th>
                                    <th>ステータス</th>
                                    <th>開始時間</th>
                                    <th>終了時間</th>
                                    <th>ASIN数</th>
                                    <th>成功件数</th>
                                    <th>失敗件数</th>
                                    <th>メッセージ</th>
                                    <th>詳細</th>
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

    var getHistoryTableHTML = function(data, platform){
        let html = '';
        data.data.forEach(history => {
            html += '<tr>';
            html += '<td><a href="/amazon_info/' + history.product_batch_id + '">' + history.filename + '</a></td>';
            if (history.action == 'extract_amazon_info_for_exhibit') {
                html += '<td>商品情報取得</td>';
            } else if (history.action == 'exhibit_to_amazon_jp') {
                html += '<td>AmazonJP出品</td>';
            }
            if (history.patch_status == '取得中') {
                html += '<td>取得中 <button onclick="cancelBatch(\'' + history.id + '\')">取得停止</button></td>';
            } else {
                html += '<td>' + history.patch_status + '</td>';
            }
            html += '<td>' + (history.start_at ? history.start_at : '-') + '</td>';
            html += '<td>' + (history.end_at ? history.end_at : '-') + '</td>';
            
            if (history.action == 'extract_amazon_info_for_exhibit') {
                html += '<td>' + history.total_jobs + '</td>';
                if (history.patch_status == '取得停止') {
                    html += '<td>取得停止済み</td>';
                } else {
                    html += '<td>' + (history.total_jobs - history.pending_jobs) + '</td>';
                }
                html += '<td>' + history.failed_jobs + '</td>';
            } else {
                html += '<td>-</td>';
                html += '<td>-</td>';
                html += '<td>-</td>';
            }
            html += '<td>';
            if (history.has_feed_document) {
                html += '<a href="{{route("exhibit_history.download_batch_feed_document_tsv")}}?product_batch_id=' + history.product_batch_id + '" target="_blank">出品ファイル</a> ';
            }
            if (history.has_message && history.end_at) {
                html += '<br /><a href="{{route("exhibit_history.product_batch_message")}}?product_batch_id=' + history.product_batch_id + '" target="_blank">出品結果</a>';
            }
            html += '</td>';
            
            if (platform == "amazon"){
                if (history.action == 'extract_amazon_info_for_exhibit' && history.end_at) {
                    html += '<td><a href="{{route("exhibit_history.detail_amazon_jp")}}?product_batch_id=' + history.product_batch_id + '" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-file"></i></a></td>';
                } else if (history.action == 'exhibit_to_amazon_jp' && history.end_at){
                    html += '<td><a href="{{route("exhibit_history.detail_amazon_jp")}}?product_batch_id=' + history.product_batch_id + '" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-file"></i></a></td>';
                } else {
                    html += '<td></td>';
                }
            }
            if (platform == "yahoo"){
                if (history.action == 'extract_amazon_info_for_exhibit' && history.end_at) {
                    html += '<td><a href="{{route("exhibit_history.detail_yahoo_jp")}}?product_batch_id=' + history.product_batch_id + '" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-file"></i></a></td>';
                } else if (history.action == 'exhibit_to_yahoo_jp' && history.end_at){
                    html += '<td><a href="{{route("exhibit_history.detail_yahoo_jp")}}?product_batch_id=' + history.product_batch_id + '" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-file"></i></a></td>';
                } else {
                    html += '<td></td>';
                }
            }
            
            html += '</tr>';
        });
        return html;
    };

    var refreshAmazon = function(page = 1) {  
        showLoading('tab_amazon');
        
        getData("{{route('exhibit_history.get_exhibit_histories')}}", {
            platform: "amazon",
            page: page,
            filename: $('#search_file_name').val(),
            period_from: $('#search_period_from').val(),
            period_to: $('#search_period_to').val(),
        }, function(data) {

            var html = getHistoryTableHTML(data, 'amazon');
            
            $('#tab_amazon .table tbody').html(html);

            var navAmazon = getNavigator(data, 'refreshAmazon');

            $('#nav_amazon').html(navAmazon);

        }, function() {

        });
    };

    var refreshYahoo = function(page = 1) {  
        showLoading('tab_yahoo');
        
        getData("{{route('exhibit_history.get_exhibit_histories')}}", {
            platform: "yahoo",
            page: page,
            filename: $('#search_file_name').val(),
            period_from: $('#search_period_from').val(),
            period_to: $('#search_period_to').val(),
        }, function(data) {
            var html = getHistoryTableHTML(data, 'yahoo');

            $('#tab_yahoo .table tbody').html(html);

            var navYahoo = getNavigator(data, 'refreshYahoo');

            $('#nav_yahoo').html(navYahoo);

        }, function() {

        });
    };

    var cancelBatch = function(productBatchId){
        getData("{{route('exhibit.cancel_exhibit_batch')}}", {
            product_batch_id: productBatchId,
        } , function(data) {
            refresh();
        }, function() {
            showLoading('tab_yahoo');
            showLoading('tab_amazon');
        });
    };

    var refresh = function() {
        refreshAmazon();
        refreshYahoo();
    };
</script>
@endsection

