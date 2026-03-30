<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    /**
     * 送付先住所変更画面表示
     */
    public function index($item_id)
    {
        $item = Product::findOrFail($item_id);
        $profile = Auth::user()->profile;

        // 住所変更画面に遷移する時点で支払い方法をセッションに保存
        if (request('payment_method')) {
            session(['purchase_payment_method' => request('payment_method')]);
        }

        return view('address.index', compact('item', 'profile'));
    }

    /**
     * 住所をセッションに保存して購入画面へ戻る
     */
    public function store(AddressRequest $request, $item_id)
    {
        session([
            'shipping_postal_code' => $request->postal_code,
            'shipping_address'     => $request->address,
            'shipping_building'    => $request->building ?? '',
            'purchase_payment_method' => $request->payment_method,
        ]);

        return redirect()->route('purchase.index', $item_id);
    }
}