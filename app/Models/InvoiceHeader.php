<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceHeader extends Model
{
    protected $table = 'ar_header';
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
        'DocEntry'
    ];
}
