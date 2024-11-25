<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLines extends Model
{
    protected $table = 'ar_lines';
    protected $fillable = [
        'NumAtCard',
        'ItemCode',
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
        'WhsCode',
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
        'BaseEntry',
        'BaseType',
        'BaseLine',
        'DocEntry'
    ];
}
