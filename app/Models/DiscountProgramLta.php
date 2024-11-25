<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountProgramLta extends Model
{
		protected $table = 'disc_program_lta';
    protected $fillable = [
			'Code',
			'U_NMDISCLTA',
			'U_CDS',
			'U_CDB',
			'U_DISCOUNT1',
			'U_DISCOUNT2'
    ];
}
