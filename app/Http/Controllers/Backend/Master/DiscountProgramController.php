<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\DiscountProgramServices;
use Illuminate\Http\Request;

class DiscountProgramController extends Controller
{
	public function __construct(DiscountProgramServices $services)
	{
		$this->service = $services;
	}

	public function png()
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

    $row = $this->service->getDataPng();

    $data = [
      'title' => 'Discount Program - P&G',
      'assets' => $assets,
      'row' => $row
    ];

    return view('backend.master.discount_program.png')->with($data);
	}

	public function png_sync(Request $request)
	{
		$date = $request->date;

		$json = $this->service->syncDiscPng($date);

		if ($json['message']=='sukses') 
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

		return response()->json($callback);
	}

	public function lta()
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

    $row = $this->service->getDataLta();

    $data = [
      'title' => 'Discount Program - LTA',
      'assets' => $assets,
      'row' => $row
    ];

    return view('backend.master.discount_program.lta')->with($data);
	}

	public function lta_sync(Request $request)
	{
		$json = $this->service->syncDiscLta();

		if ($json['message']=='sukses') 
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

		return response()->json($callback);
	}
}
