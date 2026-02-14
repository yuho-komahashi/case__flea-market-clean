<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'body',
        'image',
        'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);//ひとつのメッセージはひとりのユーザーに紐づく
    }

    public function item()
    {
        return $this->belongsTo(Item::class);//ひとつのメッセージはひとつの商品に紐づく
    }
}
