<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'm_item';
    protected $fillable = ['code','title','INIT1','INIT2','INIT3','INIT4','INIT5','INIT6','INIT7','CDB','company_id'];
}
