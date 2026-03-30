<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;


Route::post('/login', function (LoginRequest $request) {
    $user = User::where('email', $request->email)->first();

    if ($user && Hash::check($request->password, $user->password)) {
        Auth::login($user);
        session()->regenerate();
        return redirect()->intended('/');
    }

    throw \Illuminate\Validation\ValidationException::withMessages([
        'email' => 'ログイン情報が登録されていません',
    ]);
});

/*
|--------------------------------------------------------------------------
| メール認証ルート
|--------------------------------------------------------------------------
*/

// メール認証誘導画面
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// メール認証処理（メール内URLのリンク先）認証完了後 → プロフィール設定画面へ遷移
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');

// 認証メール再送
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| 認証不要ルート
|--------------------------------------------------------------------------
*/

// 商品一覧（トップ画面）- 未認証ユーザーにも表示
Route::get('/', [ProductController::class, 'index'])->name('product.index');

// 商品詳細 - 未認証ユーザーにも表示
Route::get('/item/{item_id}', [ProductController::class, 'show'])->name('item.show');

/*
|--------------------------------------------------------------------------
| 認証必須ルート
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // 送付先住所変更
    Route::get('/purchase/address/{item_id}', [AddressController::class, 'index'])->name('purchase.address');
    Route::post('/purchase/address/{item_id}', [AddressController::class, 'store'])->name('address.store');

    // 商品購入
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'index'])->name('purchase.index');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

    // Stripe決済完了・キャンセル
    Route::get('/purchase/{item_id}/success', [PurchaseController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/{item_id}/cancel',  [PurchaseController::class, 'cancel'])->name('purchase.cancel');

    // 商品出品
    Route::get('/sell', [ExhibitionController::class, 'index'])->name('exhibition.index');
    Route::post('/sell', [ExhibitionController::class, 'store'])->name('exhibition.store');

    // プロフィール
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.profile.update');

    // いいね
    Route::post('/like/{item_id}', [LikeController::class, 'toggle'])->name('likes.toggle');

    // コメント
    Route::post('/comment/{item_id}', [CommentController::class, 'store'])->name('comments.store');
});