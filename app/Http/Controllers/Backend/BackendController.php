<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;
use App\Models\UserSales;
use App\Services\BackendServices;

class BackendController extends Controller
{
  public function __construct(BackendServices $service)
  {
    $this->service = $service;
  }

  public function index()
  {
    $data = [
      'title' => 'Dashboard'
    ];

    return view('backend.index')->with($data);
  }

  public function logout()
  {
    auth()->guard('web')->logout(); //JADI KITA LOGOUT SESSION DARI GUARD CUSTOMER
    return redirect(route('login'));
  }

	public function history()
	{
		$assets = [
      'script' => array(
        'assets/js/plugins/tables/datatables/datatables.min.js'
      )
    ];

		$row = $this->service->getHistory();
		
		$data = [
      'title' => 'History',
			'row' => $row,
			'assets' => $assets
    ];

    return view('backend.history')->with($data);
	}

  public function searchSalesByCompany(Request $request)
  {
    $users_id = auth()->user()->id;
    $role_id = auth()->user()->users_role_id;

    $company = $request->company;

    if($role_id==1)
    {
      $get = UserSales::where('company_id',$company)
                      ->orderBy('SalesPersonName','ASC')
                      ->get();
    }
    else
    {
      $get = UserSales::where('company_id',$company)
                      ->where('users_id',$users_id)
                      ->orderBy('SalesPersonName','ASC')
                      ->get();
    }

    $list = "<option value=''>-- Pilih Sales --</option>";
    if(!empty($get))
    {
      foreach($get as $val)
      {
        $list .= "<option value='".$val->SalesPersonCodeSfa."'>".$val->SalesPersonName."</option>";
      }
    }

    $callback = array('listdoc' => $list);
    echo json_encode($callback);
  }
}
