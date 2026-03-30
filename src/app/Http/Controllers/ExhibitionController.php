<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Product;
use App\Models\Category;

class ExhibitionController extends Controller
{
    /**
     * 商品出品画面を表示する
     */
    public function index()
    {
        $categories = Category::all();

        return view('sell.index', compact('categories'));
    }

    /**
     * 出品商品情報を登録する
     */
    public function store(ExhibitionRequest $request)
    {
        // 商品画像をstorageに保存
        $imagePath = $request->file('image')->store('products', 'public');

        // 商品を登録
        $product = Product::create([
            'user_id'     => auth()->id(),
            'name'        => $request->name,
            'brand_name'  => $request->brand_name,
            'description' => $request->description,
            'image_path'  => $imagePath,
            'condition'   => $request->condition,
            'price'       => $request->price,
            'sold_flg'    => false,
        ]);

        // カテゴリーを紐づける（中間テーブル）
        $product->categories()->attach($request->category_ids);

        return redirect()->route('product.index')->with('success', '商品を出品しました');
    }
}