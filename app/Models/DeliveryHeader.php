<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryHeader extends Model
{
    protected $table = 'delivery_header';
    protected $fillable = [
        'Branch',
        'CardCode',
        'DocDueDate',
        'NumAtCard',
        'DocDate',
        'BPLId',
        'SalesPersonCode',
        'U_NOPOLISI',
        'U_NOPOLISI2',
        'Comments',
        'VatSum',
        'DocTotal',
        'DocStatus',
        'DocNum',
        'DocEntry',
        'return_check'
    ];
}
