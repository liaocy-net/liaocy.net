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
                                <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-search me-1"></i>検索</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="mb-4">
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
                                    <th>メッセージ</th>
                                    <th>詳細</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><a href="#" target="_blank">0325.excel</a></td>
                                    <td>商品情報取得</td>
                                    <td>出品待ち</td>
                                    <td>2023/03/25 09:00:00</td>
                                    <td>2023/03/25 09:01:00</td>
                                    <td>125</td>
                                    <td>125</td>
                                    <td>Amazon</td>
                                    <td>
                                        <a href="{{url('exhibit_history_detail')}}" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-file"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="#" target="_blank">0325.excel</a></td>
                                    <td>商品情報取得</td>
                                    <td>出品待ち</td>
                                    <td>2023/03/25 09:00:00</td>
                                    <td>2023/03/25 09:01:00</td>
                                    <td>125</td>
                                    <td>125</td>
                                    <td>Amazon</td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-file"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-body mt-3">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center pagination-info">
                                <li class="page-item first">
                                    <a class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevrons-left ti-xs"></i></a>
                                </li>
                                <li class="page-item prev">
                                    <a class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevron-left ti-xs"></i></a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link waves-effect" href="javascript:void(0);">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link waves-effect" href="javascript:void(0);">2</a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link waves-effect" href="javascript:void(0);">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link waves-effect" href="javascript:void(0);">4</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link waves-effect" href="javascript:void(0);">5</a>
                                </li>
                                <li class="page-item next">
                                    <a class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevron-right ti-xs"></i></a>
                                </li>
                                <li class="page-item last">
                                    <a class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevrons-right ti-xs"></i></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <div class="">
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
                                    <th>メッセージ</th>
                                    <th>詳細</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><a href="#" target="_blank">0325.excel</a></td>
                                    <td>商品情報取得</td>
                                    <td>出品待ち</td>
                                    <td>2023/03/25 09:00:00</td>
                                    <td>2023/03/25 09:01:00</td>
                                    <td>125</td>
                                    <td>125</td>
                                    <td>Amazon</td>
                                    <td>
                                        <a href="{{url('exhibit_history_detail')}}" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-file"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="#" target="_blank">0325.excel</a></td>
                                    <td>商品情報取得</td>
                                    <td>出品待ち</td>
                                    <td>2023/03/25 09:00:00</td>
                                    <td>2023/03/25 09:01:00</td>
                                    <td>125</td>
                                    <td>125</td>
                                    <td>Amazon</td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-file"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="pagination-body mt-3">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center pagination-info">
                                <li class="page-item first">
                                    <a class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevrons-left ti-xs"></i></a>
                                </li>
                                <li class="page-item prev">
                                    <a class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevron-left ti-xs"></i></a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link waves-effect" href="javascript:void(0);">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link waves-effect" href="javascript:void(0);">2</a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link waves-effect" href="javascript:void(0);">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link waves-effect" href="javascript:void(0);">4</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link waves-effect" href="javascript:void(0);">5</a>
                                </li>
                                <li class="page-item next">
                                    <a class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevron-right ti-xs"></i></a>
                                </li>
                                <li class="page-item last">
                                    <a class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevrons-right ti-xs"></i></a>
                                </li>
                            </ul>
                        </nav>
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

