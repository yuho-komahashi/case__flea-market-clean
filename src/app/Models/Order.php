<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'item_id',
        'payment_method',
        'shipping_postcode',
        'shipping_address',
        'shipping_building',
        'status',
    ];

    public function buyer()/*子・1対多*/
    {
        return $this->belongsTo(User::class);/*1件のオーダーはひとりのユーザーに属する*/
    }

    public function item()/*子・1対1*/
    {
        return $this->belongsTo(Item::class);/*1件のオーダーは1件のアイテムに属する（1点もの）*/
    }

    //payment_methodはリレーション不要（他のモデルに属してないので）

}
