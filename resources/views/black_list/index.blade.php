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
                    <h4 class="fw-bold m-0">ブラックリスト</h4>
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="fw-bold">Amazon ブラックリスト設定</h5>

                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" onclick="changeAmazonOn('brand')">NG Brand</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" onclick="changeAmazonOn('category')">NG Category</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" onclick="changeAmazonOn('title')">NG Title</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" onclick="changeAmazonOn('asin')">NG ASIN</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="tab_amazon" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <form id="formAmazonDeleteBlackLists" class="mb-3" action="{{route('black_list.destroy_multiple')}}" method="DELETE">
                                            <h6>現在の設定値</h6>
                                            <div class="row mb-2">
                                                <div class="col-8">
                                                    <input id="inputAmazonSearch" type="text" class="form-control" placeholder="検索...">
                                                </div>
                                                <div class="col-4">
                                                    <button type="button" class="btn btn-primary" onclick="refreshAmazon()">検索</button>
                                                </div>
                                            </div>
                                            <div class="table-responsive text-nowrap mb-2">
                                                <table class="table table-bordered table-striped text-center">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th><input type="checkbox" class="checkbox_all form-check-input"></th>
                                                            <th id="tabAmazonTitle">Brand</th>
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
                                            <input type="hidden" name="platform" value="amazon">
                                            <input type="hidden" id="inputDeleteAmazonOn" name="on" value="brand">
                                            <button type="submit" class="btn btn-danger waves-effect waves-light me-sm-3"><i class="fas fa-trash me-1"></i>削除</button>
                                        </form>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <form id="formAmazonAddBlackLists" class="mb-3" action="{{route('black_list.store_multiple')}}" method="PUT">
                                            {{ method_field('PUT') }}
                                            <h6>ファイルで一括登録</h6>
                                            <div class="input-group mb-2">
                                                <input type="file" class="form-control" id="tab_amazon_file_list" aria-describedby="tab_amazon_file_list_upload" aria-label="ファイルを選択">
                                                <button class="btn btn-primary btn-sm waves-effect" type="button" id="tab_amazon_file_list_upload" onclick="loadExcelToTextarea('tab_amazon_file_list', 'tab_amazon_list_memo')">ファイル読み込み</button>
                                            </div>
                                            <input type="hidden" name="platform" value="amazon">
                                            <input type="hidden" id="inputStoreAmazonOn" name="on" value="brand">
                                            <textarea class="form-control mb-2" id="tab_amazon_list_memo" name="values" rows="5" required></textarea>
                                            <div class="d-flex">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light me-3"><i class="fas fa-save me-1"></i>登録</button>
                                                <button type="button" class="btn btn-danger waves-effect waves-light" onclick="cleanTextarea('tab_amazon_list_memo')">クリア</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <form id="formAmazonDownloadBlackLists" class="mb-3" action="{{route('black_list.download_my_excel')}}" method="get">
                                            <h6>現在の設定値をダウンロード</h6>
                                            <input type="hidden" name="platform" value="amazon">
                                            <input type="hidden" id="inputDownloadAmazonOn" name="on" value="brand">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download me-1"></i>Excelダウンロード</button>
                                        </form>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <form id="formAmazonAddBlackListsMultiple" class="mb-3" action="{{route('black_list.store_multiple')}}" method="PUT">
                                            {{ method_field('PUT') }}
                                            <h6>複数ワード 一括登録</h6>
                                            <input type="hidden" name="platform" value="amazon">
                                            <input type="hidden" id="inputStoreAmazonMultipleOn" name="on" value="brand">
                                            <textarea class="form-control mb-2" id="tab_amazon_multiple_word" name="values" rows="5" required></textarea>
                                            <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save me-1"></i>登録</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <h5 class="fw-bold">Yahoo! ブラックリスト設定</h5>
                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" onclick="changeYahooOn('brand')">NG Brand</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" onclick="changeYahooOn('category')">NG Category</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" onclick="changeYahooOn('title')">NG Title</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" onclick="changeYahooOn('asin')">NG ASIN</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="tab_yahoo" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <form id="formYahooDeleteBlackLists" class="mb-3" action="{{route('black_list.destroy_multiple')}}" method="DELETE">
                                            <h6>現在の設定値</h6>
                                            <div class="row mb-2">
                                                <div class="col-8">
                                                    <input id="inputYahooSearch" type="text" class="form-control" placeholder="検索...">
                                                </div>
                                                <div class="col-4">
                                                    <button type="button" class="btn btn-primary" onclick="refreshYahoo()">検索</button>
                                                </div>
                                            </div>
                                            <div class="table-responsive text-nowrap mb-2">
                                                <table class="table table-bordered table-striped text-center">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th><input type="checkbox" class="checkbox_all form-check-input"></th>
                                                            <th id="tabYahooTitle">Brand</th>
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
                                            <input type="hidden" name="platform" value="yahoo">
                                            <input type="hidden" id="inputDeleteYahooOn" name="on" value="brand">
                                            <button type="submit" class="btn btn-danger waves-effect waves-light me-sm-3"><i class="fas fa-trash me-1"></i>削除</button>
                                        </form>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <form id="formYahooAddBlackLists" class="mb-3" action="{{route('black_list.store_multiple')}}" method="PUT">
                                            {{ method_field('PUT') }}
                                            <h6>ファイルで一括登録</h6>
                                            <div class="input-group mb-2">
                                                <input type="file" class="form-control" id="tab_yahoo_file_list" aria-describedby="tab_yahoo_file_list_upload" aria-label="ファイルを選択">
                                                <button class="btn btn-primary btn-sm waves-effect" type="button" id="tab_yahoo_file_list_upload" onclick="loadExcelToTextarea('tab_yahoo_file_list', 'tab_yahoo_list_memo')">ファイル読み込み</button>
                                            </div>
                                            <input type="hidden" name="platform" value="yahoo">
                                            <input type="hidden" id="inputStoreYahooOn" name="on" value="brand">
                                            <textarea class="form-control mb-2" id="tab_yahoo_list_memo" name="values" rows="5" required></textarea>
                                            <div class="d-flex">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light me-3"><i class="fas fa-save me-1"></i>登録</button>
                                                <button type="button" class="btn btn-danger waves-effect waves-light" onclick="cleanTextarea('tab_yahoo_list_memo')">クリア</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <form id="formYahooDownloadBlackLists" class="mb-3" action="{{route('black_list.download_my_excel')}}" method="get">
                                            <h6>現在の設定値をダウンロード</h6>
                                            <input type="hidden" name="platform" value="yahoo">
                                            <input type="hidden" id="inputDownloadYahooOn" name="on" value="brand">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download me-1"></i>Excelダウンロード</button>
                                        </form>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <form id="formYahooAddBlackListsMultiple" class="mb-3" action="{{route('black_list.store_multiple')}}" method="PUT">
                                            {{ method_field('PUT') }}
                                            <h6>複数ワード 一括登録</h6>
                                            <input type="hidden" name="platform" value="yahoo">
                                            <input type="hidden" id="inputStoreYahooMultipleOn" name="on" value="brand">
                                            <textarea class="form-control mb-2" id="tab_yahoo_multiple_word" name="values" rows="5" required></textarea>
                                            <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save me-1"></i>登録</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script>
    window.params = {};
    window.params.amazonOn = 'brand';
    window.params.yahooOn = 'brand';

    $(document).ready(function(){
        'use strict';

    });

    $(function () {
        submitForm("formAmazonAddBlackLists", function(data){
            refreshAmazon();
        }, function(){
            showLoading('tab_amazon');
        });
        submitForm("formYahooAddBlackLists", function(data){
            refreshYahoo();
        }, function(){
            showLoading('tab_yahoo');
        });
        submitForm("formAmazonDeleteBlackLists", function(data){
            refreshAmazon();
        }, function(){
            showLoading('tab_amazon');
        });
        submitForm("formYahooDeleteBlackLists", function(data){
            refreshYahoo();
        }, function(){
            showLoading('tab_yahoo');
        });
        submitForm("formAmazonAddBlackListsMultiple", function(data){
            refreshAmazon();
        }, function(){
            showLoading('tab_amazon');
        });
        submitForm("formYahooAddBlackListsMultiple", function(data){
            refreshYahoo();
        }, function(){
            showLoading('tab_yahoo');
        });
        refreshAmazon();
        refreshYahoo();
    });

    var changeAmazonOn = function(on) {
        window.params.amazonOn = on;
        $('#inputStoreAmazonOn').val(on);
        $('#inputDeleteAmazonOn').val(on);
        $('#inputDownloadAmazonOn').val(on);
        $('#inputStoreAmazonMultipleOn').val(on);
        $('#tabAmazonTitle').html(on.toUpperCase());
        refreshAmazon();
    };

    var changeYahooOn = function(on) {
        window.params.yahooOn = on;
        $('#inputStoreYahooOn').val(on);
        $('#inputDeleteYahooOn').val(on);
        $('#inputDownloadYahooOn').val(on);
        $('#inputStoreYahooMultipleOn').val(on);
        $('#tabYahooTitle').html(on.toUpperCase());
        refreshYahoo();
    };

    var showLoading = function(tableId) {
        var loading = '<tr><td>';
        loading += '<div class="spinner-border text-primary" role="status">';
        loading += '<span class="visually-hidden">Loading...</span>';
        loading += '</div>';
        loading += '</td></tr>';
        $('#' + tableId + ' .table tbody').html(loading);
    };

    var refreshAmazon = function() {  
        showLoading('tab_amazon');
        
        getData("{{route('black_list.get_blacklists')}}", {
            platform: "amazon", 
            on: window.params.amazonOn,
            q: $('#inputAmazonSearch').val()
        }, function(data) {
            let html = '';
            data.blackLists.forEach(blackList => {
                html += '<tr>';
                html += '<td><input type="checkbox" name="black_list_value[]" class="sub_checkbox form-check-input" value="' + blackList.value + '"/></td>';
                html += '<td>' + blackList.value + '</td>';
                html += '</tr>';
            });
            $('#tab_amazon .table tbody').html(html);
        }, function() {

        });
    };

    var refreshYahoo = function() {  
        showLoading('tab_yahoo');
        
        getData("{{route('black_list.get_blacklists')}}", {
            platform: "yahoo", 
            on: window.params.yahooOn,
            q: $('#inputYahooSearch').val()
        }, function(data) {
            let html = '';
            data.blackLists.forEach(blackList => {
                html += '<tr>';
                html += '<td><input type="checkbox" name="black_list_value[]" class="sub_checkbox form-check-input" value="' + blackList.value + '"/></td>';
                html += '<td>' + blackList.value + '</td>';
                html += '</tr>';
            });
            $('#tab_yahoo .table tbody').html(html);
        }, function() {

        });
    };


    var cleanTextarea = function (textareaId) {
        document.getElementById(textareaId).value = '';
    };
    var loadExcelToTextarea = function (fileInputId, textareaId) {
        
        let fileInput = document.getElementById(fileInputId);
        let file = fileInput.files[0];
        if (typeof file === 'undefined') {
            alert('ファイルを選択してください。');
            return;
        }
        if (file.type != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            alert('.xlsx型のExcelファイルを選択してください。');
            return;
        }
        let fileReader = new FileReader();
        fileReader.readAsBinaryString(file);
        fileReader.onload = (e) => {
            var data = e.target.result;
            var workbook = XLSX.read(data, {
                type: 'binary'
            });

            // Load Excel Sheet to CSV String
            var csv_content = XLSX.utils.sheet_to_csv(workbook.Sheets[workbook.SheetNames[0]]);

            let fileResult = csv_content.replace(/\r\n/g, '\n').split('\n');
            let brandAmount = [];
            fileResult.forEach(brand => {
                if (typeof brandAmount[brand] === 'undefined') {
                    brandAmount[brand] = 1;
                } else {
                    brandAmount[brand] += 1;
                }
            });
            cleanTextarea(textareaId);
            for (const brand in brandAmount) {
                if (brand === '') {
                    continue;
                }
                document.getElementById(textareaId).value += brand + '\n';
            }
        };
    };
    
</script>
@endsection
