<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category_name'];

    public function items()/*親・1対多*/
    {
        return $this->belongsToMany(Item::class);/*ひとつのカテゴリーは複数のアイテムに存在する*/
    }
}
