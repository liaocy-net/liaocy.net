<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ForeignShippingController;
use App\Http\Controllers\WhiteListController;
use App\Http\Controllers\BlackListController;
use App\Http\Controllers\AmazonInfoController;
use App\Services\AmazonService;
use App\Models\User;

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
    /* Amazon情報取得 */
    Route::resource('/amazon_info', AmazonInfoController::class)->except(['create', 'edit', 'update']);

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

    /* ブラックリスト */
    Route::put('/black_list/store_multiple', [BlackListController::class, 'storeMultiple'])->name('black_list.store_multiple');
    Route::get('/black_list/get_blacklists', [BlackListController::class, 'getBlackLists'])->name('black_list.get_blacklists');
    Route::get('/black_list/download_my_csv', [BlackListController::class, 'downloadMyCSV'])->name('black_list.download_my_csv');
    Route::delete('/black_list/delete_multiple', [BlackListController::class, 'destroyMultiple'])->name('black_list.destroy_multiple');
    Route::resource('/black_list', BlackListController::class)->except(['create', 'store', 'show', 'edit', 'update']);

    /* ホワイトリスト */
    Route::put('/white_list/store_multiple', [WhiteListController::class, 'storeMultiple'])->name('white_list.store_multiple');
    Route::get('/white_list/download_my_csv', [WhiteListController::class, 'downloadMyCSV'])->name('white_list.download_my_csv');
    Route::delete('/white_list/delete_multiple', [WhiteListController::class, 'destroyMultiple'])->name('white_list.destroy_multiple');
    Route::resource('/white_list', WhiteListController::class)->except(['create', 'store', 'show', 'edit', 'update']);
    
    

    /* 設定 */
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting/update', [SettingController::class, 'update'])->name('setting.update');
    Route::get('/setting/yahoo/callback', [SettingController::class, 'yahooCallback'])->name('setting.yahoo_callback');
    Route::get('/setting/amazon/callback', [SettingController::class, 'amazonCallback'])->name('setting.amazon_callback');
    Route::get('/setting/download_my_foreign_shippings_csv', [ForeignShippingController::class, 'downloadMyCSV'])->name('setting.download_my_foreign_shippings_csv');

    /* 管理者のみ */
    Route::group(['middleware' => 'admin'], function () {
        /* ユーザー管理 */
        Route::resource('/users', UserController::class);
    });

    /* test */
    Route::get('/test', function () {
        $user = User::find(auth()->id());
        $client_id = env("AMAZON_JP_CLIENT_ID");
        $client_secret = env("AMAZON_JP_CLIENT_SECRET");
        $refresh_token = $user->amazon_jp_refresh_token;
        // return $refresh_token;
        $amazonService = new AmazonService(
            $client_id, 
            $client_secret, 
            $refresh_token, 
            "B07N6Q4KQJ",  //B0BNWFM7MZ
            "jp", 
            $user
        );
        // return $amazonService->getCatalogItem();
        return $amazonService->getProductPricing();
        // return $amazonService->getReport();
    });
});

