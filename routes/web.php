<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/phpinfo', function () {
    phpinfo();
});

Route::get('/', function() {
    if (Auth::check()) {
        // ユーザーはログイン済み
        return redirect()->route('exhibit');
    } else {
        // ユーザーはログインしていない
        return redirect()->route('login');
    }
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy']);

Route::get('/forgot_password', function () {
    return view('forgot-password');
});

Route::post('/forgot-password', function () {
    return view('forgot-password');
})->name('password.email');

Route::group(['middleware' => ['auth', 'check_banned']], function () {
    /* 出品 */
    Route::get('/exhibit', function () {
        return view('exhibit');
    })->name('exhibit');

    /* 出品履歴 */
    Route::get('/exhibit_history', function () {
        return view('exhibit_history/index');
    });
    Route::get('/exhibit_history_detail', function () {
        return view('exhibit_history/detail');
    });

    /* 価格改定履歴 */
    Route::get('/price_history', function () {
        return view('price_history');
    });

    /* Amazon情報取得 */
    Route::get('/amazon_info', function () {
        return view('amazon_info');
    });

    /* ブラックリスト */
    Route::get('/black_list', function () {
        return view('black_list');
    });

    /* ホワイトリスト */
    Route::get('/white_list', function () {
        return view('white_list');
    });

    /* 設定 */
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting/update', [SettingController::class, 'update'])->name('setting.update');

    /* 管理者のみ */
    Route::group(['middleware' => 'admin'], function () {
        /* ユーザー管理 */
        Route::resource('/users', UserController::class)->middleware(['auth', 'admin']);
    });
});
