<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersCollectorSales extends Model
{
    protected $table = 'users_collector_sales';

    protected $fillable = ['users_id','users_sales_id','users_sales_name'];
}
