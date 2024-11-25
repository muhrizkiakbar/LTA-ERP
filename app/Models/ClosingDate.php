<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClosingDate extends Model
{
    protected $table = 'closing_date';
    protected $fillable = ['date','status'];
}
