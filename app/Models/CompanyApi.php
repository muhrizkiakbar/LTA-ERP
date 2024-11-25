<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyApi extends Model
{
    protected $table = 'company_api';
    protected $fillable = ['title','company_id','url','desc'];

    public function getCompanyAttribute()
    {
        return Company::find($this->company_id);
    }
}
