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
                    <h4 class="fw-bold m-0">ホワイトリスト</h4>
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-body">

                <div class="mb-4">
                    <h5 class="fw-bold">Amazon ホワイトリスト設定</h5>

                    <div class="">
                        <div class="row mb-3">
                            <div class="col-sm-4 mb-3">
                                <h6>現在の設定値</h6>
                                <form action="{{route('white_list.index')}}" method="get">
                                    <div class="row mb-2">
                                        
                                            <div class="col-8">
                                                <input type="text" class="form-control" name="q" placeholder="検索..." value="{{ Request::get('q') }}">
                                            </div>
                                            <div class="col-4">
                                                <button type="submit" class="btn btn-primary">検索</button>
                                            </div>
                                        
                                    </div>
                                </form>
                                <form id="formDeleteBrands" class="mb-3" action="{{route('white_list.destroy_multiple')}}" method="post">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <div class="table-responsive text-nowrap mb-2">
                                        <table class="table table-bordered table-striped text-center">
                                            <thead class="table-light">
                                                <tr>
                                                    <th><input type="checkbox" class="checkbox_all form-check-input"></th>
                                                    <th>Brand</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($whiteLists as $whiteList)
                                                    <tr>
                                                        <td><input type="checkbox" name="tab1_amazon_id[]" class="sub_checkbox form-check-input" value="{{ $whiteList->brand }}"/></td>
                                                        <td>{{ $whiteList->brand }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="submit" class="btn btn-danger waves-effect waves-light me-sm-3"><i class="fas fa-trash me-1"></i>削除</button>
                                </form>
                            </div>
                            
                            <div class="col-sm-8 mb-3">
                                <form id="formPutBrandsFromFile" class="mb-3" action="{{route('white_list.store_multiple')}}" method="post">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <h6>ホワイトリストへの重複ブランド登録</h6>
                                    <p class="small text-muted">ホワイトリスト抽出重複ブランド数の設定にしたがって重複ブランドをチェックします。抽出された設定値以上の重複ブランドのみ、ホワイトリストへ登録します。 チェックするファイルをアップロードしてください。</p>
                                    <span class="small">現在の設定値：{{ $my->amazon_white_list_brand }}</span>
                                    <div class="input-group mb-2">
                                        <input type="file" class="form-control" id="tab1_amazon_file_list" aria-describedby="tab1_amazon_file_list_upload" aria-label="ファイルを選択">
                                        <button class="btn btn-primary btn-sm waves-effect" type="button" id="tab1_amazon_file_list_upload" onclick="loadCSVtoTextarea('tab1_amazon_file_list', 'tab1_amazon_list_memo', {{ $my->amazon_white_list_brand }})">ファイル読み込み</button>
                                    </div>
                                    <textarea class="form-control mb-2" id="tab1_amazon_list_memo" name="brands" rows="5" required></textarea>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light me-3 "><i class="fas fa-save me-1"></i>登録</button>
                                        <button type="button" class="btn btn-danger waves-effect waves-light" onclick="cleanTextarea('tab1_amazon_list_memo')">クリア</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 mb-3">
                                <h6>現在の設定値をダウンロード</h6>
                                <a type="button" class="btn btn-primary waves-effect waves-light" href="{{route('white_list.download_my_csv')}}"><i class="fas fa-download me-1"></i>CSVダウンロード</a>
                            </div>
                            <div class="col-sm-8 mb-3">
                                <form id="formPutBrandsManual" class="mb-3" action="{{route('white_list.store_multiple')}}" method="post">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <h6>複数ワード 一括登録</h6>
                                    <textarea class="form-control mb-2" id="tab1_amazon_multiple_word" name="brands" rows="5" required></textarea>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save me-1"></i>登録</button>
                                </form>
                            </div>
                        </div>
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
    var cleanTextarea = function (textareaId) {
        document.getElementById(textareaId).value = '';
    };
    var loadCSVtoTextarea = function (fileInputId, textareaId, amazonWhiteListBrand) {
        
        let fileInput = document.getElementById(fileInputId);
        let file = fileInput.files[0];
        if (typeof file === 'undefined') {
            alert('ファイルを選択してください。');
            return;
        }
        if (file.type != "text/plain") {
            alert('CSVファイルを選択してください。');
            return;
        }
        let fileReader = new FileReader();
        fileReader.readAsText(file, "UTF-8");
        fileReader.onload = () => {
            let fileResult = fileReader.result.replace(/\r\n/g, '\n').split('\n');
            let brandAmount = [];
            fileResult.forEach(brand => {
                if (typeof brandAmount[brand] === 'undefined') {
                    brandAmount[brand] = 1;
                } else {
                    brandAmount[brand] += 1;
                }
            });
            cleanTextarea(textareaId);
            for (const brand in brandAmount) {
                if (brand === '') {
                    continue;
                }
                if (brandAmount[brand] >= amazonWhiteListBrand) {
                    document.getElementById(textareaId).value += brand + '\n';
                }
            }
        };
    };
</script>
@endsection

