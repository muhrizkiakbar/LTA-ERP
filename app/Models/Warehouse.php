<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'm_warehouse';
    protected $fillable = ['title','code'];
}
