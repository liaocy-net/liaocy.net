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
                    <h4 class="fw-bold m-0">ユーザー登録</h4>
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="card">
            <div class="card-header">

            </div>

            <div class="card-body">
                <div class="mb-4">
                    <form id="storeUser" class="mb-3" action="{{route('users.store')}}" method="post">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label" for="last_name">姓</label>
                                <input type="text" id="last_name" name="last_name" class="form-control" require value="姓1">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label" for="first_name">名</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" value="名1">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label" for="email">メールアドレス</label>
                                <input type="email" id="email" name="email" class="form-control" value="001@charing.biz">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label" for="email">パスワード</label>
                                <input type="password" id="password" name="password" class="form-control" value="12345678">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label">ロール</label>
                                <div class="">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role" id="role_1" value="admin">
                                        <label class="form-check-label" for="role_1">管理者</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role" id="role_2" value="user" checked>
                                        <label class="form-check-label" for="role_2">一般ユーザー</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save me-1"></i>登録</button>
                        <a href="{{route('users.index')}}" type="button" class="btn btn-label-secondary waves-effect me-sm-2 me-1">キャンセル</a>
                    </form>
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

