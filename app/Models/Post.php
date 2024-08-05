<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    // 入力可能箇所の設定 配列で書く
    // User_idはログインしているユーザーと同じ→user_idにログインユーザーのidが入るようコントローラーに処理を書く
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'image',
    ];

    // 一つの投稿が1人のユーザーに属すためのリレーション設定
    public function user() {
        return $this->belongsTo(User::class);
    }
}
