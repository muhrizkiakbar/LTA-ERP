<?php

namespace App\Services\Master;

use App\Models\CompanyApi;

class CompanyApiServices 
{
	public function getData()
	{
		$data = [];

		$row = CompanyApi::orderBy('company_id','ASC')
										 ->orderBy('title','ASC')
										 ->get();

		foreach ($row as $value) 
		{
			$data[] = [
				'id' => $value->id,
				'company' => $value->company->title,
				'function' => $value->title,
				'url' => $value->url,
				'desc' => $value->desc
			];
		}

		return $data;
	}
}