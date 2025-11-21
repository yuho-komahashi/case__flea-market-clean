<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'comment_content'
    ];

    public function user()/*子・1対多*/
    {
        return $this->belongsTo(User::class);/*ひとつのコメントはひとりのユーザーに属する*/
    }

    public function item()/*子・1対多*/
    {
        return $this->belongsTo(Item::class);/*ひとつのコメントはひとつのアイテムに属する*/
    }

}
