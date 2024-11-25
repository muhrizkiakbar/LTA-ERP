<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpsComplianceRemarks extends Model
{
    protected $table = 'gps_compliance_remarks';
    protected $fillable = [
        'supervisor_code',
        'supervisor_name',
        'seller_code',
        'seller_name',
        'date',
        'remarks'
    ];
}