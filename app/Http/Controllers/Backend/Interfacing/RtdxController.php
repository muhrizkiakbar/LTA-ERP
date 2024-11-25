<?php

namespace App\Http\Controllers\Backend\Interfacing;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Services\Interfacing\RtdxServices;
use Illuminate\Http\Request;

class RtdxController extends Controller
{
	public function __construct(RtdxServices $service)
	{
		$this->service = $service;
	}

	public function storemaster()
	{
		$assets = [
      'style' => array(
        'assets/css/loading.css'
      ),
      'script' => array(
        'assets/js/plugins/notifications/sweet_alert.min.js',
        'assets/js/plugins/forms/selects/select2.min.js',
        'assets/js/plugins/tables/datatables/datatables.min.js',
      )
    ];

    // $row = $this->service->getDataLta();

		$branch = Branch::pluck('title','id');

    $data = [
      'title' => 'Interfacing - Store Master',
      'assets' => $assets,
			'branch' => $branch
    ];

    return view('backend.interfacing.storemaster.index')->with($data);
	}
}
