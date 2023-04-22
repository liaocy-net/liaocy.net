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
                    <h4 class="fw-bold m-0">Amazon情報取得</h4>
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="fw-bold">ASINファイル</h5>
                    <input type="file" id="asin_file" name="asin_file" class="form-control" />
                </div>

                <div class="">
                    <h5 class="fw-bold">Amazon情報取得履歴</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-striped text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>ステータス</th>
                                    <th>開始時間</th>
                                    <th>終了時間</th>
                                    <th>ASIN数</th>
                                    <th>成功件数</th>
                                    <th>結果ファイル</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>完了</td>
                                    <td>2023/03/25 09:00:00</td>
                                    <td>2023/03/25 09:01:00</td>
                                    <td>125</td>
                                    <td>125</td>
                                    <td><button type="button" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-download"></i></button></td>
                                </tr>
                                <tr>
                                    <td>完了</td>
                                    <td>2023/03/25 09:00:00</td>
                                    <td>2023/03/25 09:01:00</td>
                                    <td>125</td>
                                    <td>125</td>
                                    <td><button type="button" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-download"></i></button></td>
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

