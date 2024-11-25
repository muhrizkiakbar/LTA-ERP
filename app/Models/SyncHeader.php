<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncHeader extends Model
{
    protected $table = 'sync_header';
    protected $fillable = [
        'DocNum',
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
        'CardName',
        'Address',
        'SalesPersonName',
        'company_id',
        'sfa'
    ];
}
