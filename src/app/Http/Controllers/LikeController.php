<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Like;

class LikeController extends Controller
{
    /**
     * いいねトグル
     * POST /like/{item_id}
     */
    public function toggle($item_id)
    {
        $product = Product::findOrFail($item_id);

        $like = Like::where('user_id', auth()->id())
                    ->where('product_id', $product->id)
                    ->first();

        if ($like) {
            // いいね済み → 解除
            $like->delete();
        } else {
            // 未いいね → 追加
            Like::create([
                'user_id'    => auth()->id(),
                'product_id' => $product->id,
            ]);
        }

        return back();
    }
}