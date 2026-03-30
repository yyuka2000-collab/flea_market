<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    /**
     * コメント送信
     * POST /comment/{item_id}
     */
    public function store(CommentRequest $request, $item_id)
    {
        $product = Product::findOrFail($item_id);

        Comment::create([
            'user_id'    => auth()->id(),
            'product_id' => $product->id,
            'comment'    => $request->input('comment'),
        ]);

        return back();
    }
}