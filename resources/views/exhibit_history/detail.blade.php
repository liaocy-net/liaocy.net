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
                    <h4 class="fw-bold m-0">出品履歴詳細</h4>
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
                                <input type="text" id="search_file_name" name="search_file_name" class="form-control" />
                            </div>
                            <div class="col-6 col-sm-3 mb-2">
                                <label class="form-label" for="search_asin">ブランド</label>
                                <input type="text" id="search_file_name" name="search_brand" class="form-control" />
                            </div>
                            <div class="col-6 col-sm-3 mb-2">
                                <label class="form-label" for="search_asin">タイトル</label>
                                <input type="text" id="search_file_name" name="search_title" class="form-control" />
                            </div>
                            <div class="col-6 col-sm-3 mb-2 d-inline-flex align-items-end">
                                <button type="button" class="btn btn-primary waves-effect waves-light"><i class="fas fa-search me-1"></i>検索</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="">
                    <h5 class="fw-bold">商品一覧</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered dataTable  table-striped text-center">
                            <thead class="table-light">
                                <tr>
                                    <th class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all"><input type="checkbox" class="checkbox_all form-check-input"></th>
                                    <th class="sorting sorting_asc">ASIN</th>
                                    <th>画像</th>
                                    <th class="sorting">タイトル</th>
                                    <th class="sorting">出品価格</th>
                                    <th class="sorting">仕入価格</th>
                                    <th class="sorting">サイズ</th>
                                    <th class="sorting">重量</th>
                                    <th>メッセージ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="checkbox" name="detail_id[]" class="sub_checkbox form-check-input" /></td>
                                    <td>B01GRM7LGU</td>
                                    <td><img src="{{asset('ui/assets/img/products/amazon-echo.png')}}" /></td>
                                    <td>商品名</td>
                                    <td>3,040</td>
                                    <td>2,000</td>
                                    <td>25.8 x 24.4 x 17.1 cm</td>
                                    <td>350g</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="detail_id[]" class="sub_checkbox form-check-input" /></td>
                                    <td>B01GRM7LGU</td>
                                    <td><img src="{{asset('ui/assets/img/products/apple-watch.png')}}" /></td>
                                    <td>商品名</td>
                                    <td>3,040</td>
                                    <td>2,000</td>
                                    <td>25.8 x 24.4 x 17.1 cm</td>
                                    <td>350g</td>
                                    <td></td>
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

                <hr class="my-4 mx-n4">
                <div class="">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <button type="button" class="btn btn-danger waves-effect waves-light me-sm-3 mb-2"><i class="fas fa-trash me-1"></i>チェックした商品を削除する</button>
                            <button type="button" class="btn btn-primary waves-effect waves-light mb-2"><i class="fas fa-save me-1"></i>出品する</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
<script src="{{asset('ui/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script>
    $(document).ready(function(){
        'use strict';

    });

    $(function () {
        
    });
</script>
@endsection

