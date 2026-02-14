<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_id',
        'reviewee_id',
        'item_id',
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

    public function item()
    {
        return $this->belongsTo(Item::class);//ひとつの評価はひとつの商品に紐づく
    }
}
