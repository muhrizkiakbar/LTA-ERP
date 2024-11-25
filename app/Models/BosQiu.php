<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BosQiu extends Model
{
    use HasFactory;

    protected $table = 'm_bos_qiu';

    protected $fillable = [
        'Code',
        'Object',
        'U_INITIATIVEID',
        'U_OBJECT',
        'U_FROMDATE',
        'U_TODATE',
        'U_PROMOCODE',
        'U_BUDGETQTY',
        'SUBSEGMENT',
        'FROM',
        'TO',
        'PROMODISCDET'
    ];
}
