@extends('layouts.auth')

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Login -->
                <div class="card">
                    <div class="card-body">
                        @if (request('status'))
                            <div class="alert alert-success" role="alert">
                                {{ request('status') }}
                            </div>
                        @endif
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <a href="" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-body fw-bold ms-1">{{env('APP_NAME')}}</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h5 class="mb-3 pt-2">ログイン</h5>
                        {{--  <form id="formAuthentication" class="mb-3" action="" method="POST">  --}}

                        <form id="formAuthentication" class="mb-3" action="{{url('/login')}}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">メールアドレス</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="メールアドレス" autofocus/>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                @if (request('error'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ request('error') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">パスワード</label>
                                    {{--  @if (Route::has('password.request'))
                                        <a href="{{route('password.request')}}">
                                            <small>パスワードをお忘れの方</small>
                                        </a>
                                    @endif  --}}
                                    <a href="{{url('/forgot-password')}}">
                                        <small>パスワードをお忘れの方</small>
                                    </a>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password"/>
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" value="1"/>
                                    <label class="form-check-label" for="remember-me">次回から自動ログイン</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">ログイン</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection