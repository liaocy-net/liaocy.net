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
                <a type="button" href="{{route('users.create')}}" class="btn btn-primary waves-effect waves-light"><i class="fas fa-plus me-1"></i>ユーザー登録</a>

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
                                    {{-- <th>詳細</th> --}}
                                    <th>編集</th>
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
                                        {{-- <td><a href="{{route('users.show', $user->id)}}" type="button" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-user"></i></a></td> --}}
                                        <td><a href="{{route('users.edit', $user->id)}}" type="button" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-edit"></i></a></td>
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

