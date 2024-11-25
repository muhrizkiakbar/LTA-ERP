<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArCollectorHeader extends Model
{
    use SoftDeletes;
    
    protected $table = 'ar_collector_header';
    protected $fillable = ['kd','users_collector_id','branch_code','category','date','users_admin_id','users_admin_st','users_collector_st','company_id'];

    public function getUserCollectorAttribute()
    {
        return User::find($this->users_collector_id);
    }

    public function getUserAdminAttribute()
    {
        return User::find($this->users_admin_id);
    }

    public function getCompanyAttribute()
    {
        return Company::find($this->company_id);
    }
}
