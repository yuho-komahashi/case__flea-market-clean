<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_image',
        'postcode',
        'address',
        'building'
    ];

    public function user()/*子・1対1*/
    {
        return $this->belongsTo(User::class);/*ひとつのプロフィールはひとりのユーザーに属する*/
    }
}
