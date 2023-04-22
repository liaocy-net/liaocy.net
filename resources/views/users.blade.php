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
                    <h4 class="fw-bold m-0">ユーザー管理</h4>
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#registModal"><i class="fas fa-plus me-1"></i>ユーザー登録</button>

                <!-- Modal -->
                <div class="modal fade" id="registModal" tabindex="-1" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="registModalTitle">ユーザー登録</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label class="form-label" for="user_name">お名前</label>
                                            <input type="text" id="user_name" name="user_name" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label class="form-label" for="user_email">メールアドレス</label>
                                            <input type="email" id="user_email" name="user_email" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label class="form-label">権限</label>
                                            <div class="">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="user_permission" id="user_permission_1" value="1" checked>
                                                    <label class="form-check-label" for="user_permission_1">管理者</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="user_permission" id="user_permission_2" value="2">
                                                    <label class="form-check-label" for="user_permission_2">一般ユーザー</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary waves-effect me-sm-2 me-1" data-bs-dismiss="modal">キャンセル</button>
                                <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save me-1"></i>登録</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="mb-4">
                    <h5 class="fw-bold">ユーザ一覧</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-striped text-center">
                            <thead class="table-light">
                                <tr>     
                                    <th>ID</th>
                                    <th>お名前</th>
                                    <th>メールアドレス</th>
                                    <th>権限</th>
                                    <th>作成日</th>
                                    <th>削除</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>高木 太郎</td>
                                    <td>taro@aispel.com</td>
                                    <td>管理者</td>
                                    <td>2023/03/22 12:00:00</td>
                                    <td><button type="button" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-trash"></i></button></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>高木 二郎</td>
                                    <td>jiro@aispel.com</td>
                                    <td>一般ユーザー</td>
                                    <td>2023/03/22 12:00:00</td>
                                    <td><button type="button" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-trash"></i></button></td>
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

