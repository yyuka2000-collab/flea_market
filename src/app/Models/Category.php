<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * カテゴリーに紐づく商品テーブル結合（多対多）
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category');
    }
}
