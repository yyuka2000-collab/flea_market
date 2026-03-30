<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image_path',
        'postal_code',
        'address',
        'building',
    ];

    /**
     * プロフィールが属するユーザーテーブル結合
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
