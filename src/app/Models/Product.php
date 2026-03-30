<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand_name',
        'image_path',
        'description',
        'price',
        'condition',
        'sold_flg',
    ];

    protected $casts = [
        'sold_flg' => 'boolean',
        'price'    => 'integer',
    ];

    /**
     * 商品を出品したユーザーテーブル結合
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 商品に紐づくカテゴリーテーブル結合（多対多）
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    /**
     * 商品に紐づくいいねテーブル結合
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * 商品に紐づくコメントテーブル結合
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 商品に紐づく購入テーブル結合
     */
    public function order()
    {
        return $this->hasOne(Order::class);
    }

    /**
     * アクセサを追加
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image_path) {
            return asset('images/no-image.png');
        }
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }
        return asset('storage/' . $this->image_path);
    }
}
