<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Order;
use App\Models\Comment;
use App\Models\Like;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()/*親・1対1*/
    {
        return $this->hasOne(Profile::class);/*ひとりのユーザーにはひとつのプロフィールしか存在しない*/
    }

    public function items()/*親・1対多*/
    {
        return $this->hasMany(Item::class);/*ひとりのユーザーは複数のアイテムを出品できる*/
    }

    public function orders()/*親・1対多*/
    {
        return $this->hasMany(Order::class);/*ひとりのユーザーは複数のオーダーができる*/
    }

    public function comments()/*親・1対多*/
    {
        return $this->hasMany(Comment::class);/*ひとりのユーザーは複数のコメントができる*/
    }

    public function likedItems()/*親・多対多*/
    {
        return $this->belongsToMany(Item::class, 'likes','user_id', 'item_id')->withTimestamps();/*ひとりのユーザーは複数のいいねができる*/
    }
}
