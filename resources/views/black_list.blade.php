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
                    <h5 class="fw-bold">amazon ブラックリスト設定</h5>

                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tab1_amazon" aria-controls="tab1_amazon" aria-selected="true">NG Brand</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab2_amazon" aria-controls="tab2_amazon" aria-selected="false" tabindex="-1">NG Category</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab3_amazon" aria-controls="tab3_amazon" aria-selected="false" tabindex="-1">NG Title</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab4_amazon" aria-controls="tab4_amazon" aria-selected="false" tabindex="-1">NG ASIN</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="tab1_amazon" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値</h6>
                                        <div class="row mb-2">
                                            <div class="col-8">
                                                <input type="text" class="form-control" placeholder="検索...">
                                            </div>
                                            <div class="col-4">
                                                <button class="btn btn-primary">検索</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive text-nowrap mb-2">
                                            <table class="table table-bordered table-striped text-center">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th><input type="checkbox" class="checkbox_all form-check-input"></th>
                                                        <th>Brand</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab1_amazon_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>JOZIRUSHI</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab1_amazon_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>WILD</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab1_amazon_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>PHP</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-danger waves-effect waves-light me-sm-3"><i class="fas fa-trash me-1"></i>削除</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>一括登録</h6>
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control" id="tab1_amazon_file_list" aria-describedby="tab1_amazon_file_list_upload" aria-label="ファイルを選択">
                                            <button class="btn btn-primary btn-sm waves-effect" type="button" id="tab1_amazon_file_list_upload">ファイル読み込み</button>
                                        </div>
                                        <textarea class="form-control mb-2" id="tab1_amazon_list_memo" rows="5"></textarea>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary waves-effect waves-light me-3 "><i class="fas fa-save me-1"></i>登録</button>
                                            <button type="button" class="btn btn-danger waves-effect waves-light">クリア</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値をダウンロード</h6>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download me-1"></i>CSVダウンロード</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>複数ワード 一括登録</h6>
                                        <textarea class="form-control mb-2" id="tab1_amazon_multiple_word" rows="5"></textarea>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save me-1"></i>登録</button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab2_amazon" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値</h6>
                                        <div class="row mb-2">
                                            <div class="col-8">
                                                <input type="text" class="form-control" placeholder="検索...">
                                            </div>
                                            <div class="col-4">
                                                <button class="btn btn-primary">検索</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive text-nowrap mb-2">
                                            <table class="table table-bordered table-striped text-center">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th><input type="checkbox" class="checkbox_all form-check-input"></th>
                                                        <th>Category</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab2_amazon_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>JOZIRUSHI</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab2_amazon_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>WILD</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab2_amazon_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>PHP</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-danger waves-effect waves-light me-sm-3"><i class="fas fa-trash me-1"></i>削除</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>一括登録</h6>
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control" id="tab2_amazon_file_list" aria-describedby="tab2_amazon_file_list_upload" aria-label="ファイルを選択">
                                            <button class="btn btn-primary btn-sm waves-effect" type="button" id="tab2_amazon_file_list_upload">ファイル読み込み</button>
                                        </div>
                                        <textarea class="form-control mb-2" id="tab2_amazon_list_memo" rows="5"></textarea>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary waves-effect waves-light me-3 "><i class="fas fa-save me-1"></i>登録</button>
                                            <button type="button" class="btn btn-danger waves-effect waves-light">クリア</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値をダウンロード</h6>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download me-1"></i>CSVダウンロード</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>複数ワード 一括登録</h6>
                                        <textarea class="form-control mb-2" id="tab2_amazon_multiple_word" rows="5"></textarea>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save me-1"></i>登録</button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab3_amazon" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値</h6>
                                        <div class="row mb-2">
                                            <div class="col-8">
                                                <input type="text" class="form-control" placeholder="検索...">
                                            </div>
                                            <div class="col-4">
                                                <button class="btn btn-primary">検索</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive text-nowrap mb-2">
                                            <table class="table table-bordered table-striped text-center">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th><input type="checkbox" class="checkbox_all form-check-input"></th>
                                                        <th>Title</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab3_amazon_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>JOZIRUSHI</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab3_amazon_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>WILD</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab3_amazon_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>PHP</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-danger waves-effect waves-light me-sm-3"><i class="fas fa-trash me-1"></i>削除</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>一括登録</h6>
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control" id="tab3_amazon_file_list" aria-describedby="tab3_amazon_file_list_upload" aria-label="ファイルを選択">
                                            <button class="btn btn-primary btn-sm waves-effect" type="button" id="tab3_amazon_file_list_upload">ファイル読み込み</button>
                                        </div>
                                        <textarea class="form-control mb-2" id="tab3_amazon_list_memo" rows="5"></textarea>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary waves-effect waves-light me-3 "><i class="fas fa-save me-1"></i>登録</button>
                                            <button type="button" class="btn btn-danger waves-effect waves-light">クリア</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値をダウンロード</h6>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download me-1"></i>CSVダウンロード</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>複数ワード 一括登録</h6>
                                        <textarea class="form-control mb-2" id="tab3_amazon_multiple_word" rows="5"></textarea>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save me-1"></i>登録</button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab4_amazon" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値</h6>
                                        <div class="row mb-2">
                                            <div class="col-8">
                                                <input type="text" class="form-control" placeholder="検索...">
                                            </div>
                                            <div class="col-4">
                                                <button class="btn btn-primary">検索</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive text-nowrap mb-2">
                                            <table class="table table-bordered table-striped text-center">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th><input type="checkbox" class="checkbox_all form-check-input"></th>
                                                        <th>ASIN</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab4_amazon_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>JOZIRUSHI</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab4_amazon_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>WILD</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab4_amazon_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>PHP</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-danger waves-effect waves-light me-sm-3"><i class="fas fa-trash me-1"></i>削除</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>一括登録</h6>
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control" id="tab4_amazon_file_list" aria-describedby="tab4_amazon_file_list_upload" aria-label="ファイルを選択">
                                            <button class="btn btn-primary btn-sm waves-effect" type="button" id="tab4_amazon_file_list_upload">ファイル読み込み</button>
                                        </div>
                                        <textarea class="form-control mb-2" id="tab4_amazon_list_memo" rows="5"></textarea>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary waves-effect waves-light me-3 "><i class="fas fa-save me-1"></i>登録</button>
                                            <button type="button" class="btn btn-danger waves-effect waves-light">クリア</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値をダウンロード</h6>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download me-1"></i>CSVダウンロード</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>複数ワード 一括登録</h6>
                                        <textarea class="form-control mb-2" id="tab4_amazon_multiple_word" rows="5"></textarea>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save me-1"></i>登録</button>
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
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tab1_yahoo" aria-controls="tab1_yahoo" aria-selected="true">NG Brand</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab2_yahoo" aria-controls="tab2_yahoo" aria-selected="false" tabindex="-1">NG Category</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab3_yahoo" aria-controls="tab3_yahoo" aria-selected="false" tabindex="-1">NG Title</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab4_yahoo" aria-controls="tab4_yahoo" aria-selected="false" tabindex="-1">NG ASIN</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="tab1_yahoo" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値</h6>
                                        <div class="row mb-2">
                                            <div class="col-8">
                                                <input type="text" class="form-control" placeholder="検索...">
                                            </div>
                                            <div class="col-4">
                                                <button class="btn btn-primary">検索</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive text-nowrap mb-2">
                                            <table class="table table-bordered table-striped text-center">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th><input type="checkbox" class="checkbox_all form-check-input"></th>
                                                        <th>Brand</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab1_yahoo_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>JOZIRUSHI</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab1_yahoo_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>WILD</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab1_yahoo_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>PHP</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-danger waves-effect waves-light me-sm-3"><i class="fas fa-trash me-1"></i>削除</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>一括登録</h6>
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control" id="tab1_yahoo_file_list" aria-describedby="tab1_yahoo_file_list_upload" aria-label="ファイルを選択">
                                            <button class="btn btn-primary btn-sm waves-effect" type="button" id="tab1_yahoo_file_list_upload">ファイル読み込み</button>
                                        </div>
                                        <textarea class="form-control mb-2" id="tab1_yahoo_list_memo" rows="5"></textarea>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary waves-effect waves-light me-3 "><i class="fas fa-save me-1"></i>登録</button>
                                            <button type="button" class="btn btn-danger waves-effect waves-light">クリア</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値をダウンロード</h6>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download me-1"></i>CSVダウンロード</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>複数ワード 一括登録</h6>
                                        <textarea class="form-control mb-2" id="tab1_yahoo_multiple_word" rows="5"></textarea>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save me-1"></i>登録</button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab2_yahoo" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値</h6>
                                        <div class="row mb-2">
                                            <div class="col-8">
                                                <input type="text" class="form-control" placeholder="検索...">
                                            </div>
                                            <div class="col-4">
                                                <button class="btn btn-primary">検索</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive text-nowrap mb-2">
                                            <table class="table table-bordered table-striped text-center">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th><input type="checkbox" class="checkbox_all form-check-input"></th>
                                                        <th>Category</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab2_yahoo_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>JOZIRUSHI</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab2_yahoo_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>WILD</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab2_yahoo_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>PHP</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-danger waves-effect waves-light me-sm-3"><i class="fas fa-trash me-1"></i>削除</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>一括登録</h6>
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control" id="tab2_yahoo_file_list" aria-describedby="tab2_yahoo_file_list_upload" aria-label="ファイルを選択">
                                            <button class="btn btn-primary btn-sm waves-effect" type="button" id="tab2_yahoo_file_list_upload">ファイル読み込み</button>
                                        </div>
                                        <textarea class="form-control mb-2" id="tab2_yahoo_list_memo" rows="5"></textarea>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary waves-effect waves-light me-3 "><i class="fas fa-save me-1"></i>登録</button>
                                            <button type="button" class="btn btn-danger waves-effect waves-light">クリア</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値をダウンロード</h6>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download me-1"></i>CSVダウンロード</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>複数ワード 一括登録</h6>
                                        <textarea class="form-control mb-2" id="tab2_yahoo_multiple_word" rows="5"></textarea>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save me-1"></i>登録</button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab3_yahoo" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値</h6>
                                        <div class="row mb-2">
                                            <div class="col-8">
                                                <input type="text" class="form-control" placeholder="検索...">
                                            </div>
                                            <div class="col-4">
                                                <button class="btn btn-primary">検索</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive text-nowrap mb-2">
                                            <table class="table table-bordered table-striped text-center">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th><input type="checkbox" class="checkbox_all form-check-input"></th>
                                                        <th>Title</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab3_yahoo_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>JOZIRUSHI</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab3_yahoo_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>WILD</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab3_yahoo_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>PHP</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-danger waves-effect waves-light me-sm-3"><i class="fas fa-trash me-1"></i>削除</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>一括登録</h6>
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control" id="tab3_yahoo_file_list" aria-describedby="tab3_yahoo_file_list_upload" aria-label="ファイルを選択">
                                            <button class="btn btn-primary btn-sm waves-effect" type="button" id="tab3_yahoo_file_list_upload">ファイル読み込み</button>
                                        </div>
                                        <textarea class="form-control mb-2" id="tab3_yahoo_list_memo" rows="5"></textarea>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary waves-effect waves-light me-3 "><i class="fas fa-save me-1"></i>登録</button>
                                            <button type="button" class="btn btn-danger waves-effect waves-light">クリア</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値をダウンロード</h6>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download me-1"></i>CSVダウンロード</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>複数ワード 一括登録</h6>
                                        <textarea class="form-control mb-2" id="tab3_yahoo_multiple_word" rows="5"></textarea>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save me-1"></i>登録</button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab4_yahoo" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値</h6>
                                        <div class="row mb-2">
                                            <div class="col-8">
                                                <input type="text" class="form-control" placeholder="検索...">
                                            </div>
                                            <div class="col-4">
                                                <button class="btn btn-primary">検索</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive text-nowrap mb-2">
                                            <table class="table table-bordered table-striped text-center">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th><input type="checkbox" class="checkbox_all form-check-input"></th>
                                                        <th>ASIN</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab4_yahoo_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>JOZIRUSHI</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab4_yahoo_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>WILD</td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="checkbox" name="tab4_yahoo_id[]" class="sub_checkbox form-check-input" /></td>
                                                        <td>PHP</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-danger waves-effect waves-light me-sm-3"><i class="fas fa-trash me-1"></i>削除</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>一括登録</h6>
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control" id="tab4_yahoo_file_list" aria-describedby="tab4_yahoo_file_list_upload" aria-label="ファイルを選択">
                                            <button class="btn btn-primary btn-sm waves-effect" type="button" id="tab4_yahoo_file_list_upload">ファイル読み込み</button>
                                        </div>
                                        <textarea class="form-control mb-2" id="tab4_yahoo_list_memo" rows="5"></textarea>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary waves-effect waves-light me-3 "><i class="fas fa-save me-1"></i>登録</button>
                                            <button type="button" class="btn btn-danger waves-effect waves-light">クリア</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                        <h6>現在の設定値をダウンロード</h6>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-download me-1"></i>CSVダウンロード</button>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <h6>複数ワード 一括登録</h6>
                                        <textarea class="form-control mb-2" id="tab4_yahoo_multiple_word" rows="5"></textarea>
                                        <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save me-1"></i>登録</button>
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
<script>
    $(document).ready(function(){
        'use strict';

    });

    $(function () {
        
    });
</script>
@endsection

