<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;

class ProfileController extends Controller
{
    /**
     * プロフィール画面 (GET /mypage)
     * US007: ユーザーは自身のプロフィールを確認することができる
     */
    public function index(Request $request)
    {
        $user = Auth::user()->load('profile');

        // FN025: 出品した商品一覧・購入した商品一覧
        $tab = $request->query('tab', 'sell');

        if ($tab === 'buy') {
            $products = $user->orders()->with('product')->latest()->get()
                        ->pluck('product');
        } else {
            $products = $user->products()->latest()->get();
        }

        return view('mypage.index', compact('user', 'tab', 'products'));
    }

    /**
     * プロフィール編集画面 (GET /mypage/profile)
     * US008: ユーザーはプロフィールを編集することができる
     */
    public function edit()
    {
        $user = Auth::user()->load('profile');

        return view('mypage.profile', compact('user'));
    }

    /**
     * プロフィール更新処理 (POST /mypage/profile)
     * FN027: ユーザー情報変更機能
     */
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        // ユーザー名を更新
        $user->update([
            'name' => $request->input('name'),
        ]);

        // プロフィール情報を更新（なければ新規作成）
        $profileData = [
            'postal_code' => $request->input('postal_code'),
            'address'     => $request->input('address'),
            'building'    => $request->input('building'),
        ];

        // FN027-1: プロフィール画像のアップロード
        // 該当画像はlaravelのstorageディレクトリに保存（仕様書FN027より）
        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('profiles', 'public');
            $profileData['image_path'] = $path;
        }

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect()->route('product.index')->with('success', 'プロフィールを更新しました。');
    }
}