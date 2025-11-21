<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Order;
use App\Models\Like;
use App\Models\Comment;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'item_image',
        'category_id',
        'condition_id',
        'item_name',
        'brand',
        'description',
        'price',
        'item_status',
    ];

    public function seller()/*子・1対多*/
    {
        return $this->belongsTo(User::class);/*ひとつのアイテムはひとりのユーザーに出品される*/
    }

    public function categories()/*子・1対多*/
    {
        return $this->belongsToMany(Category::class);/*ひとつのアイテムは複数のカテゴリーに属する*/
    }

    public function condition()/*子・1対多*/
    {
        return $this->belongsTo(Condition::class);/*ひとつのアイテムはひとつの状態に属する*/
    }

    public function order()/*親・1対1*/
    {
        return $this->hasOne(Order::class);/*ひとつのアイテムにひとつのオーダーが紐づく*/
    }

    public function likedByUsers()/*親・1対多*/
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();/*ひとつのアイテムに複数のいいねが存在する?*/
    }

    public function comments()/*親・1対多*/
    {
        return $this->hasMany(Comment::class);/*ひとつのアイテムに複数のコメントが存在する*/
    }
}
