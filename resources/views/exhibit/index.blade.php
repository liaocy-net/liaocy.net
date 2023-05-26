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
                    <h4 class="fw-bold m-0">出品</h4>
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-body">
                <form id="storeUser" class="mb-3" action="{{route('exhibit.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('POST') }}
                    <div class="mb-4">
                        <h5 class="fw-bold">出品先</h5>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered table-striped text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        <th>出品先</th>
                                        <th>出品カテゴリー</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input" name="exhibit_to[]" value="amazon"/></td>
                                        <td>Amazon</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input" name="exhibit_to[]" value="yahoo"/></td>
                                        <td>Yahoo!</td>
                                        <td class="text-start">
                                            <select class="form-select select2" id="exhibit_line_2" name="exhibit_yahoo_category" data-placeholder="選択してください">
                                                <option value="1451,レディースファッション　>　レディースウェア"> 1451 ： レディースファッション　&gt;　レディースウェア</option>
                                                <option value="1736,レディースファッション　>　レディースシューズ"> 1736 ： レディースファッション　&gt;　レディースシューズ</option>
                                                <option value="1682,レディースファッション　>　レディースファッション小物"> 1682 ： レディースファッション　&gt;　レディースファッション小物</option>
                                                <option value="1747,メンズファッション　>　メンズシューズ"> 1747 ： メンズファッション　&gt;　メンズシューズ</option>
                                                <option value="4864,メンズファッション　>　メンズウェア"> 4864 ： メンズファッション　&gt;　メンズウェア</option>
                                                <option value="1706,メンズファッション　>　メンズ財布、ファッション小物"> 1706 ： メンズファッション　&gt;　メンズ財布、ファッション小物</option>
                                                <option value="4351,ベビー、キッズ、マタニティ"> 4351 ： ベビー、キッズ、マタニティ</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="">
                        <h5 class="fw-bold">出品用ファイル (.xlsxファイルをアップロードしてください)</h5>
                        <input type="file" id="asin_file" name="asin_file" class="form-control" />
                    </div>
                    <hr class="my-4 mx-n4">
                    <div class="">
                        <div class="row">
                            <div class="col-sm-12 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary me-sm-2 me-1 waves-effect waves-light"><i class="fas fa-save me-1"></i>出品する</button>
                                <a type="button" href="{{route('amazon_info.download_asin_template_xlsx')}}" class="btn btn-info waves-effect waves-light"><i class="fas fa-download me-1"></i>テンプレートダウンロード</a>
                            </div>
                        </div>
                    </div>
                </form>
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

