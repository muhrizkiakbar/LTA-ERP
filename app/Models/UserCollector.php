<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCollector extends Model
{
    protected $table = 'users_collector';
    protected $fillable = ['users_id','collector_id'];

    public function getCollectorAttribute()
    {
        return Collector::find($this->collector_id);
    }
}
