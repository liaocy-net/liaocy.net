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
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td><button type="button" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="pagination-body mt-3">
                        <nav aria-label="Page navigation">
                            @php
                                $paginator = $users
                            @endphp
                            <ul class="pagination justify-content-center pagination-info">
                                @if ($paginator->onFirstPage())
                                    <li class="page-item first disabled">
                                        <a class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevrons-left ti-xs"></i></a>
                                    </li>
                                    <li class="page-item prev disabled">
                                        <a class="page-link waves-effect disable" href="javascript:void(0);"><i class="ti ti-chevron-left ti-xs"></i></a>
                                    </li>
                                @else
                                    <li class="page-item first">
                                        <a class="page-link waves-effect" href="{{ \Request::url().'?page=1' }}"><i class="ti ti-chevrons-left ti-xs"></i></a>
                                    </li>
                                    <li class="page-item prev">
                                        <a class="page-link waves-effect" href="{{ $paginator->previousPageUrl() }}"><i class="ti ti-chevron-left ti-xs"></i></a>
                                    </li>
                                @endif

                                @for ($i = $paginator->currentPage() - 3; $i <= $paginator->currentPage() + 3; $i++)
                                    @if ($i == $paginator->currentPage())
                                        <li class="page-item active">
                                            <a class="page-link waves-effect" href="javascript:void(0);">{{ $i }}</a>
                                        </li>
                                    @elseif ($i > 0 && $i <= $paginator->lastPage())
                                        <li class="page-item">
                                            <a class="page-link waves-effect" href="{{ \Request::url().'?page='.$i }}">{{ $i }}</a>
                                        </li>
                                    @endif
                                @endfor

                                @if ($paginator->onLastPage())
                                    <li class="page-item next disabled">
                                        <a class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevron-right ti-xs"></i></a>
                                    </li>
                                    <li class="page-item last disabled">
                                        <a class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevrons-right ti-xs"></i></a>
                                    </li>
                                @else
                                    <li class="page-item next">
                                        <a class="page-link waves-effect" href="{{ $paginator->nextPageUrl() }}"><i class="ti ti-chevron-right ti-xs"></i></a>
                                    </li>
                                    <li class="page-item last">
                                        <a class="page-link waves-effect" href="{{ \Request::url().'?page='.$paginator->lastPage() }}"><i class="ti ti-chevrons-right ti-xs"></i></a>
                                    </li>
                                @endif
                                
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

