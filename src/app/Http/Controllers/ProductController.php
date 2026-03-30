<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * 商品一覧画面（トップ画面）
     * GET /
     * GET /?tab=mylist
     * GET /?keyword=xxx
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $tab     = $request->input('tab');

        if ($tab === 'mylist') {
            if (!auth()->check()) {
                return view('item.index', ['products' => collect(), 'tab' => $tab]);
            }

            $products = Product::query()
                ->whereHas('likes', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->when($keyword, function ($query) use ($keyword) {
                    $query->whereRaw(
                        'name COLLATE utf8mb4_bin LIKE ?',
                        ['%' . $keyword . '%']
                    );
                })
                ->get();
        } else {
            $products = Product::query()
                ->when(auth()->check(), function ($query) {
                    $query->where('user_id', '!=', auth()->id());
                })
                ->when($keyword, function ($query) use ($keyword) {
                    $query->whereRaw(
                        'name COLLATE utf8mb4_bin LIKE ?',
                        ['%' . $keyword . '%']
                    );
                })
                ->get();
        }

        return view('item.index', compact('products', 'tab'));
    }

    /**
     * 商品詳細画面
     * GET /item/{item_id}
     */
    public function show($item_id)
    {
        $item = Product::with(['categories', 'comments.user', 'likes'])
            ->withCount(['likes', 'comments'])
            ->findOrFail($item_id);

        $isLiked = auth()->check()
            ? $item->likes->contains('user_id', auth()->id())
            : false;

        return view('item.show', compact('item', 'isLiked'));
    }
}