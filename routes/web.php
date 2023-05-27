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
use App\Models\Product;
use App\Services\AmazonService;
use App\Models\User;
use App\Services\FeedTypes;
use App\Http\Controllers\ExhibitController;
use App\Http\Controllers\ExhibitHistoryController;

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
        return redirect()->route('exhibit.index');
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
    Route::get('/amazon_info/download_asin_template_xlsx', [AmazonInfoController::class, 'downloadASINTemplateXLSX'])->name('amazon_info.download_asin_template_xlsx');
    Route::resource('/amazon_info', AmazonInfoController::class)->except(['create', 'edit', 'update']);

    /* 出品 */
    Route::resource('/exhibit', ExhibitController::class)->except(['create', 'edit', 'update']);

    /* 出品履歴 */
    Route::get('/exhibit_history/get_exhibit_histories', [ExhibitHistoryController::class, 'getExhibitHistories'])->name('exhibit_history.get_exhibit_histories');
    Route::get('/exhibit_history/get_products', [ExhibitHistoryController::class, 'getProducts'])->name('exhibit_history.get_products');
    Route::get('/exhibit_history/detail', [ExhibitHistoryController::class, 'detail'])->name('exhibit_history.detail');
    Route::get('/exhibit_history/product_batch_message', [ExhibitHistoryController::class, 'getProductBatchMessage'])->name('exhibit_history.product_batch_message');
    Route::post('/exhibit_history/process_products', [ExhibitHistoryController::class, 'processProducts'])->name('exhibit_history.process_products');
    Route::resource('/exhibit_history', ExhibitHistoryController::class);

    /* 価格改定履歴 */
    Route::get('/price_history', function () {
        return view('price_history');
    });

    /* ブラックリスト */
    Route::put('/black_list/store_multiple', [BlackListController::class, 'storeMultiple'])->name('black_list.store_multiple');
    Route::get('/black_list/get_blacklists', [BlackListController::class, 'getBlackLists'])->name('black_list.get_blacklists');
    Route::get('/black_list/download_my_excel', [BlackListController::class, 'downloadMyExcel'])->name('black_list.download_my_excel');
    Route::delete('/black_list/delete_multiple', [BlackListController::class, 'destroyMultiple'])->name('black_list.destroy_multiple');
    Route::resource('/black_list', BlackListController::class)->except(['create', 'store', 'show', 'edit', 'update']);

    /* ホワイトリスト */
    Route::put('/white_list/store_multiple', [WhiteListController::class, 'storeMultiple'])->name('white_list.store_multiple');
    Route::get('/white_list/download_my_excel', [WhiteListController::class, 'downloadMyExcel'])->name('white_list.download_my_excel');
    Route::delete('/white_list/delete_multiple', [WhiteListController::class, 'destroyMultiple'])->name('white_list.destroy_multiple');
    Route::resource('/white_list', WhiteListController::class)->except(['create', 'store', 'show', 'edit', 'update']);
    
    

    /* 設定 */
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting/update', [SettingController::class, 'update'])->name('setting.update');
    Route::get('/setting/yahoo/callback', [SettingController::class, 'yahooCallback'])->name('setting.yahoo_callback');
    Route::get('/setting/amazon/callback', [SettingController::class, 'amazonCallback'])->name('setting.amazon_callback');
    Route::get('/setting/download_my_foreign_shippings_xlsx', [ForeignShippingController::class, 'downloadMyXLSX'])->name('setting.download_my_foreign_shippings_xlsx');

    /* 管理者のみ */
    Route::group(['middleware' => 'admin'], function () {
        /* ユーザー管理 */
        Route::resource('/users', UserController::class);
    });

    /* test */
    Route::get('/test', function () {
        $user = User::find(auth()->id());
        // $client_id = env("AMAZON_US_CLIENT_ID");
        // $client_secret = env("AMAZON_US_CLIENT_SECRET");
        // $refresh_token = $user->amazon_us_refresh_token;
        // $product = new Product();
        // $product->asin = "B09TZWLFLY";
        
        // $amazonService = new AmazonService(
        //     $client_id,
        //     $client_secret,
        //     $refresh_token,
        //     $user,
        //     "us",
        // );
        // return $amazonService->getCatalogItem($product);
        // return $amazonService->getProductPricing();
        // $feedType = FeedTypes::POST_PRODUCT_PRICING_DATA;
        // return $amazonService->createFeed($feedType);
        // return $amazonService->CreateFeedWithFile();

        $client_id = env("AMAZON_US_CLIENT_ID");
        $client_secret = env("AMAZON_US_CLIENT_SECRET");
        $refresh_token = $user->amazon_us_refresh_token;
        $product = new Product();
        $product->asin = "B09TZWLFLY";
        $amazonService = new AmazonService(
            $client_id,
            $client_secret,
            $refresh_token,
            $user,
            "us",
        );
        return $amazonService->getCatalogItem($product);

        // $user = User::find(auth()->id());
        // $client_id = env("AMAZON_JP_CLIENT_ID");
        // $client_secret = env("AMAZON_JP_CLIENT_SECRET");
        // $refresh_token = $user->amazon_jp_refresh_token;
        // $product = new Product();
        // $product->asin = "B09TZWLFLY";
        
        // $amazonService = new AmazonService(
        //     $client_id,
        //     $client_secret,
        //     $refresh_token,
        //     $user,
        //     "jp",
        // );
        // return $amazonService->getFeedDocument("50080019497");
    });
});

