<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'm_customer';
    protected $fillable = [
        'code',
        'title',
        'address',
        'u_class',
        'cseg1',
        'cseg2',
        'cseg3',
        'cseg4',
        'nopol_mix',
        'nopol_png',
        'tax',
        'stat_disc1',
        'stat_disc2',
        'stat_disc3',
        'stat_disc4',
        'stat_disc5',
        'stat_disc6',
        'stat_disc7',
        'stat_disc8',
        'cds',
        'limit',
        'tax_name',
        'nik',
        'contact_person',
        'top',
        'U_SALESCODE',
        'company_id'
    ];
}
