<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPriceUpdate extends Model
{
    protected $table = 'user_price_update';
    protected $fillable = ['users_id'];
}
