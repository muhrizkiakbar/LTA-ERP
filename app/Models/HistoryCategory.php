<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryCategory extends Model
{
    protected $table = 'history_category';
    protected $fillable = ['title'];
}
