<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Order;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id', // item_id から修正
        'message', // body から修正
        'image',
        'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);//ひとつのメッセージはひとりのユーザーに紐づく
    }

    public function order()// item() から修正
    {
        return $this->belongsTo(Order::class);//ひとつのメッセージはひとつの取引に紐づく
    }
}
