<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Order;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_id',
        'reviewee_id',
        'order_id', //item_id から修正
        'score',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');//評価者はユーザーに紐づく
    }

    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');//評価対象者はユーザーに紐づく
    }

    public function order()
    {
        return $this->belongsTo(Order::class);//ひとつの評価はひとつの取引に紐づく
    }
}
