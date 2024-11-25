<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountProgramPng extends Model
{
		protected $table = 'disc_program_png';
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
