<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtdxStore extends Model
{
    use HasFactory;

		protected $fillable = [
			'SOURCE_SYS_ID',
			'FACT_TYPE_CODE',
			'SBMSN_TYPE_CODE',
			'DUE_PERD',
			'TIME_PERD_TYPE_CODE',
			'TIME_PERD_START_DATE',
			'TRADE_CHANNEL_ID',
			'LGCY_STORE_ID',
			'DIST_BRANCH_ID',
			'SUBDISTR_STORE_FLAG',
			'IN_COVERAGE_FLAG',
			'STORE_STATUS_FLAG',
			'GOLDEN_STORE_FLAG',
			'STORE_NAME',
			'Street',
			'Block',
			'County',
			'ZIP_CODE',
			'GENERIC_TEXT_FIELD_1',
			'GENERIC_TEXT_FIELD_2',
			'GENERIC_TEXT_FIELD_3',
			'GENERIC_NUM_FIELD_1',
			'GENERIC_NUM_FIELD_2',
			'CHAIN_DESC',
			'CITY_GEO',
			'SELLER_ID',
			'LATITUDE',
			'LONGITUDE',
		];
}
