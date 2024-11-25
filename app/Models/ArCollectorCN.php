<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArCollectorCN extends Model
{
    protected $table = 'ar_collector_cn';
    protected $fillable = [
        'DocEntry',
        'DocNum',
        'CardCode',
        'CardName',
        'DocDueDate',
        'DocDate',
        'NumAtCard',
        'DocTotal',
        'PaidToDate',
        'BalanceDue',
        'status'
    ];
}
