<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLines extends Model
{
    protected $table = 'order_lines';
    protected $fillable = [
        'DocEntry',
        'ItemCode',
        'Dscription',
        'Quantity',
        'TaxCode',
        'UnitPrice',
        'UnitMsr',
        'UomCode',
        'UomEntry',
        'NumPerMsr',
        'CostingCode',
        'CostingCode2',
        'CostingCode3',
        'WarehouseCode',
        'U_DISC1',
        'U_DISCVALUE1',
        'U_DISC2',
        'U_DISCVALUE2',
        'U_DISC3',
        'U_DISCVALUE3',
        'U_DISC4',
        'U_DISCVALUE4',
        'U_DISC5',
        'U_DISCVALUE5',
        'U_DISC6',
        'U_DISCVALUE6',
        'U_DISC7',
        'U_DISCVALUE7',
        'U_DISC8',
        'U_DISCVALUE8',
        'LineNum',
				'DocStatus'
    ];
}
