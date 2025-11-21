<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Condition extends Model
{
    use HasFactory;

    protected $fillable = ['level'];

    public function items()/*親・1対多 */
    {
        return $this->hasMany(Item::class);/*ひとつのコンディションは複数のアイテムに存在する*/
    }
}
