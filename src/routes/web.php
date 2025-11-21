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

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/search', [ItemController::class, 'search'])->name('items.search');

Route::get('/item/{itemId}', [ItemController::class,'show'])->name('items.show');
Route::post('/redirect-to-login/{itemId}', [ItemController::class, 'redirectToLogin'])->name('redirect.to.login');

Route::post('/items/{item}/like', [ItemController::class, 'toggleLike'])->name('items.like')->middleware('auth');
Route::post('/item/{item}/comment', [ItemController::class, 'storeComment'])->middleware('auth')->name('comments.store');

/*新規会員登録関連*/
Route::middleware('guest')->group(function(){
    Route::get('register', [RegisterController::class, 'create'])->name('users.create');
    Route::post('register', [RegisterController::class, 'store'])->name('users.store');
});

/*認証必須ページ*/
Route::middleware('auth','verified')->group(function(){
    Route::get('/purchase/{item_id}', [PurchaseController::class,'confirm'])->name('purchase.confirm');
    Route::post('/purchase/{item_id}', [PurchaseController::class,'store'])->name('purchase.store');

    Route::get('/purchase/address/{item_id}', [PurchaseController::class,'editAddress'])->name('purchase.address.edit');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class,'updateAddress'])->name('purchase.address.update');

    Route::get('/mypage', [MypageController::class, 'show'])->name('mypage.show');
    Route::get('/mypage/profile/create', [MypageController::class, 'create'])->name('mypage.profile.create');
    Route::post('/mypage/profile/store', [MypageController::class, 'store'])->name('mypage.profile.store');
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.profile.edit');
    Route::patch('/mypage/profile/update', [MypageController::class, 'update'])->name('mypage.profile.update');

    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');
});
