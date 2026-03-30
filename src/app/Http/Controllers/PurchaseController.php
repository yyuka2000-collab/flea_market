<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PurchaseController extends Controller
{
    /**
     * 商品購入画面表示
     */
    public function index($item_id)
    {
        $item    = Product::findOrFail($item_id);
        $profile = Auth::user()->profile;

        // previousURLのパス部分のみ取得（クエリパラメータを除外）
        $previousPath = parse_url(url()->previous(), PHP_URL_PATH);
        $addressPath  = parse_url(route('purchase.address', $item_id), PHP_URL_PATH);

        // 住所変更画面以外から来た場合はセッションをクリア
        if ($previousPath !== $addressPath) {
            session()->forget(['shipping_postal_code', 'shipping_address', 'shipping_building', 'purchase_payment_method']);
        }

        return view('purchase.index', compact('item', 'profile'));
    }

    /**
     * 購入処理
     */
    public function store(PurchaseRequest $request, $item_id)
    {
        $user = Auth::user();

        Stripe::setApiKey(config('services.stripe.secret'));

        // 悲観的ロックで sold_flg を立てる（コンビニ・カード共通）
        try {
            DB::transaction(function () use ($item_id) {
                $lockedItem = Product::lockForUpdate()->findOrFail($item_id);

                if ($lockedItem->sold_flg) {
                    throw new \App\Exceptions\AlreadySoldException();
                }

                $lockedItem->update(['sold_flg' => true]);
            });
        } catch (\App\Exceptions\AlreadySoldException $e) {
            return redirect()->route('purchase.index', $item_id)
                ->withErrors(['error' => 'この商品はすでに購入済みです。']);
        } catch (\Exception $e) {
            \Log::error('sold_flg update error: ' . $e->getMessage());
            return redirect()->route('purchase.index', $item_id)
                ->withErrors(['error' => '購入処理に失敗しました。もう一度お試しください。']);
        }

        // sold_flg を立てた後、Stripe画面へ
        $item = Product::findOrFail($item_id);

        // コンビニ払い
        if ($request->payment_method === 'convenience') {
            $this->createOrder($item, $user, 'convenience', null);

            $checkoutSession = StripeSession::create([
                'payment_method_types' => ['konbini'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'jpy',
                        'unit_amount'  => $item->price,
                        'product_data' => [
                            'name' => $item->name,
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode'        => 'payment',
                'success_url' => route('product.index'),
                'cancel_url'  => route('purchase.cancel', $item_id),
            ]);

            return redirect($checkoutSession->url);
        }

        // カード払い
        $checkoutSession = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'jpy',
                    'unit_amount'  => $item->price,
                    'product_data' => [
                        'name' => $item->name,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode'        => 'payment',
            'success_url' => route('purchase.success', $item_id) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('purchase.cancel', $item_id),
            'metadata'    => [
                'item_id'        => $item_id,
                'user_id'        => $user->id,
                'payment_method' => 'card',
            ],
        ]);

        session(['purchase_payment_method' => 'card']);

        return redirect($checkoutSession->url);
    }

    /**
     * 決済成功（カード払いのみ）→ orders保存
     */
    public function success($item_id)
    {
        $item = Product::findOrFail($item_id);
        $user = Auth::user();

        $stripeSessionId = request('session_id');
        $postalCode      = session('shipping_postal_code', $user->profile->postal_code ?? '');
        $address         = session('shipping_address',     $user->profile->address ?? '');
        $building        = session('shipping_building',    $user->profile->building ?? '');
        $paymentMethod   = session('purchase_payment_method', 'card');

        // orders保存（sold_flg はすでに store() で立て済み）
        Order::create([
            'user_id'              => $user->id,
            'product_id'           => $item->id,
            'price'                => $item->price,
            'payment_method'       => $paymentMethod,
            'stripe_payment_id'    => $stripeSessionId,
            'shipping_postal_code' => $postalCode,
            'shipping_address'     => $address,
            'shipping_building'    => $building,
        ]);

        session()->forget(['shipping_postal_code', 'shipping_address', 'shipping_building', 'purchase_payment_method']);

        return redirect()->route('product.index')->with('success', '商品を購入しました');
    }

    /**
     * 決済キャンセル → sold_flg を戻して購入画面に戻る
     */
    public function cancel($item_id)
    {
        // キャンセル時は sold_flg を戻して他のユーザーが買えるようにする
        Product::where('id', $item_id)->update(['sold_flg' => false]);

        return redirect()->route('purchase.index', $item_id)
            ->withErrors(['error' => '決済がキャンセルされました。']);
    }

    /**
     * orders保存・sold_flg更新（コンビニ払い用）
     */
    private function createOrder($item, $user, $paymentMethod, $stripeSessionId)
    {
        $postalCode = session('shipping_postal_code', $user->profile->postal_code ?? '');
        $address    = session('shipping_address',     $user->profile->address ?? '');
        $building   = session('shipping_building',    $user->profile->building ?? '');

        Order::create([
            'user_id'              => $user->id,
            'product_id'           => $item->id,
            'price'                => $item->price,
            'payment_method'       => $paymentMethod,
            'stripe_payment_id'    => $stripeSessionId,
            'shipping_postal_code' => $postalCode,
            'shipping_address'     => $address,
            'shipping_building'    => $building,
        ]);

        session()->forget(['shipping_postal_code', 'shipping_address', 'shipping_building', 'purchase_payment_method']);
    }
}