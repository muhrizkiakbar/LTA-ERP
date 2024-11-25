<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpsCompliance extends Model
{
    protected $table = 'gps_compliance';
    protected $fillable = [
        'distributor_name',
        'branch_code',
        'branch_name',
        'visit_date',
        'supervisor_code',
        'supervisor_name',
        'seller_code',
        'seller_name',
        'seller_type',
        'store_code',
        'store_name',
        'store_chanel',
        'visit_frequency',
        'planned_sequence',
        'visit_sequence',
        'off_route',
        'sales_obj',
        'sales_act',
        'master_coordinate',
        'actual_coordinate',
        'compliance',
        'distance_m',
        'time_in',
        'time_out',
        'duration',
        'file',
        'hash',
        'temuan',
        'principal'
    ];
}
