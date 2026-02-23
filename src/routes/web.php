<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\TradingController;
use App\Http\Controllers\MessageController;


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

/*メール認証*/
//email/verify を表示するルート
Route::get('/email/verify', function(){
    return view('auth.email');
})->middleware('auth')->name('verification.notice');

//メール認証リンククリック時の処理
Route::get('email/verify/{id}/{hash}', function(EmailVerificationRequest $request){
    $request->fulfill();// 認証完了処理
    return redirect('/mypage/profile/create');// 認証後の遷移先
})->middleware(['auth','signed'])->name('verification.verify');

//認証メールの再送処理
Route::post('/email/verification-notification', function(Request $request){
    $request->user()->sendEmailVerificationNotification();
    return Redirect::back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6.1'])->name('verification.send');

/*アクセス時に認証不要ページ*/
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.post');

Route::get('/', [ItemController::class, 'index'])->name('items.index');//商品一覧表示
//Route::get('/item/search', [ItemController::class, 'search'])->name('items.search');//←これ使ってないかも

Route::get('/item/{itemId}', [ItemController::class,'show'])->name('items.show');//商品詳細画面表示
Route::post('/redirect-to-login/{itemId}', [ItemController::class, 'redirectToLogin'])->name('redirect.to.login');

Route::post('/items/{item}/like', [ItemController::class, 'toggleLike'])->name('items.like')->middleware('auth');//いいね機能
Route::post('/item/{item}/comment', [ItemController::class, 'storeComment'])->middleware('auth')->name('comments.store');//コメント送信機能

/*新規会員登録関連*/
Route::middleware('guest')->group(function(){
    Route::get('register', [RegisterController::class, 'create'])->name('users.create');
    Route::post('register', [RegisterController::class, 'store'])->name('users.store');
});

/*認証必須ページ*/
Route::middleware('auth','verified')->group(function(){
    Route::get('/purchase/{item_id}', [PurchaseController::class,'confirm'])->name('purchase.confirm');//商品購入画面表示
    Route::post('/purchase/{item_id}', [PurchaseController::class,'store'])->name('purchase.store');//商品購入

    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');//商品出品画面表示
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');//商品出品

    Route::get('/purchase/address/{item_id}', [PurchaseController::class,'editAddress'])->name('purchase.address.edit');//住所変更ページ表示
    Route::post('/purchase/address/{item_id}', [PurchaseController::class,'updateAddress'])->name('purchase.address.update');//住所変更ページ更新

    Route::get('/mypage', [MypageController::class, 'show'])->name('mypage.show');//プロフィール画面表示
    Route::get('/mypage/profile/create', [MypageController::class, 'create'])->name('mypage.profile.create');//新規プロフィール登録画面表示
    Route::post('/mypage/profile/store', [MypageController::class, 'store'])->name('mypage.profile.store');//新規プロフィール登録
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.profile.edit');//プロフィール編集画面表示
    Route::patch('/mypage/profile/update', [MypageController::class, 'update'])->name('mypage.profile.update');//プロフィール編集画面更新

    Route::get('/mypage/trading/{order}/chat', [TradingController::class, 'show'])->name('trading.show');//取引チャット画面表示
    Route::post('/mypage/trading/{order}/complete', [TradingController::class, 'complete'])->name('trading.complete');//取引完了アクション
    Route::post('/trading/{order}/rating', [TradingController::class, 'rating']) ->name('trading.rating');//評価モーダル送信

    Route::post('/mypage/trading/{order}/chat', [MessageController::class, 'store'])->name('message.store');//新規取引メッセージ送信
    Route::put('/message/{message}', [MessageController::class, 'update'])->name('message.update');//既存メッセージ編集
    Route::delete('/message/{message}', [MessageController::class, 'destroy'])->name('message.destroy');//既存メッセージ削除

    //チャットを入力した状態で他の画面に遷移しても、入力情報を保持できるよう下書き保存
    Route::post('/message/draft/', function (Request $request) {
        session([$request->key => $request->message]);
        return response()->json(['status' => 'ok']);
    })->name('message.draft');

});
