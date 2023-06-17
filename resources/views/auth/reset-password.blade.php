@extends('layouts.auth')

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Login -->
                <div class="card">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <a href="" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-body fw-bold ms-1">Charing</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h5 class="mb-3 pt-2">パスワード再設定</h5>
                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf
                            <!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label">メールアドレス</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="メールアドレス" value="{{ Request::get('email') }}" autofocus/>
                            </div>
                    
                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">パスワード</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="パスワード" required/>
                            </div>
                    
                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">パスワード確認</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="パスワード確認" required/>
                            </div>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">パスワードをリセット</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
