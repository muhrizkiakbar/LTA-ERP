<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTemp extends Model
{
    protected $table = 'order_temp';
    protected $fillable = [
        'ItemCode',
        'ItemName',
        'Quantity',
        'TaxCode',
        'UnitPrice',
        'UnitMsr',
        'UomCode',
        'UomEntry',
        'NumPerMsr',
        'UnitPrice2',
        'UnitMsr2',
        'UomCode2',
        'UomEntry2',
        'NumPerMsr2',
        'CostingCode',
        'CostingCode2',
        'CostingCode3',
        'WarehouseCode',
        'users_id'
    ];
}
