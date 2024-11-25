<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MixLines extends Model
{
    protected $table = 'mix_lines';
    protected $fillable = [
        'NumAtCard',
        'ItemCode',
        'ItemName',
        'Quantity',
        'QuantitySfa',
        'QuantitySfaCases',
        'QuantitySfaTotal',
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
        'WarehouseCode'
    ];
}
