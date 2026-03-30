<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'price',
        'payment_method',
        'stripe_payment_id',
        'shipping_postal_code',
        'shipping_address',
        'shipping_building',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    /**
     * 購入したユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 購入された商品
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
