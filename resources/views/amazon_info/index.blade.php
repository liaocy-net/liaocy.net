@extends('layouts.app')

@inject('UtilityService', '\App\Services\UtilityService')

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
                    <form id="storeUser" class="mb-3" action="{{route('amazon_info.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('POST') }}
                        <div class="col-sm-12 mb-3">
                            <h5 class="fw-bold">ASINファイル</h5>
                            <input type="file" id="asin_file" name="asin_file" class="form-control" />
                        </div>
                        <div class="col-12 d-flex">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1 waves-effect waves-light" name="act" value="upload_asin_file"><i class="fas fa-save me-1"></i>アップロード</button>
                            <a type="button" href="{{route('amazon_info.download_asin_template_xlsx')}}" class="btn btn-info waves-effect waves-light"><i class="fas fa-download me-1"></i>テンプレートダウンロード</a>
                        </div>
                    </form>
                </div>

                <div class="">
                    <h5 class="fw-bold">Amazon情報取得履歴</h5>
                    <p class="text-muted">１回最大 3000 ASINを登録してください。</p>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-striped text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>ステータス</th>
                                    <th>開始時間</th>
                                    <th>終了時間</th>
                                    <th>ASIN数</th>
                                    <th>成功件数</th>
                                    <th>失敗件数</th>
                                    <th>結果ファイル</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($batches as $batch)
                                    <tr>
                                        <td>{{ $batch->status }}</td>
                                        <td>{{ date('Y-m-d H:i:s', $batch->created_at) }}</td>
                                        <td>
                                            @if ($batch->product_batch_finished_at)
                                                {{ date('Y-m-d H:i:s', strtotime($batch->product_batch_finished_at)) }}
                                            @endif
                                        </td>
                                        <td>{{ $batch->total_jobs }}</td>
                                        <td>{{ $batch->total_jobs - $batch->pending_jobs }}</td>
                                        <td>{{ $batch->failed_jobs }}</td>
                                        <td><a href="{{route('amazon_info.show', $batch->product_batch_id)}}" class="btn btn-icon btn-sm btn-primary"><i class="fas fa-download"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-body mt-3">
                        <nav aria-label="Page navigation">
                            @php
                                $paginator = $batches
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

