<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHeader extends Model
{
    protected $table = 'order_header';
    protected $fillable = [
        'Branch',
        'CardCode',
				'CardName',
        'DocDueDate',
        'NumAtCard',
        'DocDate',
        'BPLId',
        'SlpCode',
				'SlpName',
        'U_NOPOLISI',
        'U_NOPOLISI2',
        'Comments',
        'VatSum',
        'Bruto',
        'DocStatus',
        'DocNum',
        'DocEntry',
        'sfa',
        'company_id',
				'Comments'
    ];
}
