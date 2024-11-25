<?php

namespace App\Services\Master;

use App\Models\DiscountProgramLta;
use App\Models\DiscountProgramPng;
use Illuminate\Support\Facades\DB;

class DiscountProgramServices 
{
	public function getDataPng()
	{
		$now = date('Y-m-d');

		$data = [];
		$data = DB::table('disc_program_png')
						->whereRaw('? BETWEEN U_FROMDATE AND U_TODATE', [$now])
						->get();

		return $data;
	}

	public function syncDiscPng($date)
	{
		$post = [
			'date' => $date
		];

		$now = date('Y-m-d');

		$function = 'syncDiscountPng';

		$data = callCompanyApiWithPost(1,$function, json_encode($post));

    $no = 0;
    $nox = 0;

    if (!empty($data)) 
    {
			$cek = DB::table('disc_program_png')
							 ->whereRaw('? BETWEEN U_FROMDATE AND U_TODATE', [$now])
							 ->get();

			// dd($cek);

			if (isset($cek)) 
			{
				DB::table('disc_program_png')
					->whereRaw('? BETWEEN U_FROMDATE AND U_TODATE', [$now])
					->delete();
			}
			
			foreach($data as $item)
      {
				$json[] = [
					'Code' => $item['Code'],
					'Object' => $item['Object'],
					'U_INITIATIVEID'  => $item['U_INITIATIVEID'],
					'U_OBJECT' => $item['U_OBJECT'],
					'U_FROMDATE' => $item['U_FROMDATE'],
					'U_TODATE' => $item['U_TODATE'],
					'U_PROMOCODE' => $item['U_PROMOCODE'],
					'U_BUDGETQTY' => $item['U_BUDGETQTY'],
					'SUBSEGMENT' => $item['SUBSEGMENT'],
					'U_FROM' => $item['FROM'],
					'U_TO' => $item['TO'],
					'PROMODISCDET' => $item['PROMODISCDET']
				];
  
				$nox = $no++;
			}

			// dd($json);

			DiscountProgramPng::insert($json);
		}

		if($nox > 0)
    {
      $callback = array(
        'message' => 'sukses'
      );
    }
    else
    {
      $callback = array(
        'message' => 'error'
      );
    }

		return $callback;
	}

	public function getDataLta()
	{		
		$data = [];
		$data = DiscountProgramLta::get();

		return $data;
	}

	public function syncDiscLta()
	{
		$function = 'syncDiscountLta';

		$data = callCompanyApiWithoutPost(1,$function);

    $no = 0;
    $nox = 0;

    if (!empty($data)) 
    {
			foreach($data as $item)
      {
        $cek = DiscountProgramLta::where('Code',$item['Code'])->count();

        if($cek==0)
        {
          $data_uom = [
            'Code' => $item['Code'],
						'U_NMDISCLTA' => $item['U_NMDISCLTA'],
						'U_CDS' => $item['U_CDS'],
						'U_CDB' => $item['U_CDB'],
						'U_DISCOUNT1' => $item['U_DISCOUNT1'],
						'U_DISCOUNT2' => $item['U_DISCOUNT2']
          ];
  
          DiscountProgramLta::create($data_uom);

          $nox = $no++;
        }
      }
		}

		if($nox > 0)
    {
      $callback = array(
        'message' => 'sukses'
      );
    }
    else
    {
      $callback = array(
        'message' => 'error'
      );
    }

		return $callback;
	}
}