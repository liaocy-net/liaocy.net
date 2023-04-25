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
                <h5 class="fw-bold">プロフィール編集</h5>
                <div class="mb-4">
                    <form id="storeUser" class="mb-3" action="{{route('users.update', $user->id)}}" method="post">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label" for="last_name">姓</label>
                                <input type="text" id="last_name" name="last_name" class="form-control" require value="{{$user->last_name}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label" for="first_name">名</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" value="{{$user->first_name}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label" for="email">メールアドレス</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{$user->email}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label">ロール</label>
                                <div class="">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role" id="role_1" value="admin" @if($user->role == 'admin') checked @endif>
                                        <label class="form-check-label" for="role_1">管理者</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role" id="role_2" value="user" @if($user->role == 'user') checked @endif>
                                        <label class="form-check-label" for="role_2">一般ユーザー</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role" id="role_2" value="banned" @if($user->role == 'banned') checked @endif>
                                        <label class="form-check-label" for="role_2">無効化</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <button type="submit" class="btn btn-primary waves-effect waves-light" name="act" value="update_profile"><i class="fas fa-save me-1"></i>プロフィールを更新</button>
                    </form>
                </div>
                <h5 class="fw-bold">パスワード編集</h5>
                <div class="mb-4">
                    <form id="storeUser" class="mb-3" action="{{route('users.update', $user->id)}}" method="post">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label" for="email">パスワード</label>
                                <input type="password" id="password" name="password" class="form-control">
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <button type="submit" class="btn btn-primary waves-effect waves-light" name="act" value="update_password"><i class="fas fa-save me-1"></i>パスワードを更新</button>
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

