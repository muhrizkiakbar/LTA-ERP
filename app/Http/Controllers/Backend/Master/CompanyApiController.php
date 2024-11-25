<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyApi;
use App\Services\Master\CompanyApiServices;
use Illuminate\Http\Request;

class CompanyApiController extends Controller
{
	public function __construct(CompanyApiServices $service)
	{
		$this->service = $service;
	}

	public function index()
	{
		$row = $this->service->getData();

		$assets = [
      'style' => array(
        'assets/js/plugins/air-datepicker/css/datepicker.min.css',
        'assets/css/loading.css'
      ),
      'script' => array(
        'assets/js/plugins/notifications/sweet_alert.min.js',
        'assets/js/plugins/forms/selects/select2.min.js',
        'assets/js/plugins/tables/datatables/datatables.min.js',
        'assets/js/plugins/air-datepicker/js/datepicker.min.js',
				'assets/js/plugins/air-datepicker/js/i18n/datepicker.en.js',
      )
    ];

		$company = Company::pluck('title','id');

		$data = [
      'title' => 'Company API Listing URL',
      'assets' => $assets,
      'row' => $row,
			'source' => $company
    ];

		return view('backend.master.company_api.index')->with($data);
	}

	public function store(Request $request)
	{
		$company = Company::find($request->company_id);

		$url = $company->prefix_url.$request->title;

		$data = [
			'title' => $request->title,
			'company_id' => $request->company_id,
			'url' => $url,
			'desc' => $request->desc
 		];

		CompanyApi::create($data);

		$alert = array(
      'type' => 'success',
      'message' => 'Api berhasil di tambahkan !'
    );

		return redirect()->back()->with($alert);
	}

	public function delete($id)
	{
		CompanyApi::find($id)->delete();

		$alert = array(
      'type' => 'success',
      'message' => 'Api berhasil di delete !'
    );

		return redirect()->back()->with($alert);
	}
}
