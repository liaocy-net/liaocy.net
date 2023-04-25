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
                <h5 class="fw-bold">ユーザ詳細情報</h5>
                <div class="mb-4">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="table-light">
                            <tr>     
                                <th>項目</th>
                                <th>値</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ID</td>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <td>お名前</td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td>メールアドレス</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td>権限</td>
                                <td>{{ $user->role }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <a type="button" href="{{route('users.edit', $user->id)}}" class="btn btn-primary waves-effect waves-light"><i class="fas fa-edit me-1"></i>編集</a>
                <a href="{{route('users.index')}}" type="button" class="btn btn-label-secondary waves-effect me-sm-2 me-1">一覧に戻す</a>
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

