<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Models\UserSales;
use App\Models\UsersRole;
use App\Services\UserServices;
use Illuminate\Http\Request;

class UsersController extends Controller
{
  public function __construct(UserServices $services)
  {
    $this->service = $services;
  }

  public function index()
  {
    $role_id = auth()->user()->users_role_id;
    $user_id = auth()->user()->id;
    
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

    $row = $this->service->getData();
    $branch = Branch::pluck('title','id');
    $role = UsersRole::pluck('title','id');

    $data = [
      'title' => 'Users Management',
      'assets' => $assets,
      'row' => $row,
      'branch' => $branch,
      'role' => $role,
      'role_id' => $role_id
    ];

    return view('backend.users.index')->with($data);
  }

  public function sales(Request $request)
  {
    $id = $request->id;

    $company = Company::pluck('title','id');
    $row = $this->service->getDataSalesById($id);

    $data = [
      'company' => $company,
      'row' => $row,
      'users_id' => $id
    ];

    return view('backend.users.sales')->with($data);
  }

  public function sales_search(Request $request)
  {
    $get = User::find($request->user);

    $post = [
      'company' => $request->company,
      'branch' => $get->branch_sap
    ]; 

    // dd($post);

    $url = 'http://36.93.82.10/erp-api-lta/api/getSalesByBranch';
    $get = callApiWithPost($url, json_encode($post));

    // dd($get);

    $list = "<option value=''>-- Pilih Sales --</option>";
    if(!empty($get['data']))
    {
      foreach($get['data'] as $val)
      {
        $list .= "<option value='" . $val['U_SALESCODE'] . "'>" . $val['SlpName'] . "</option>";
      }
    }

    $callback = array('listdoc' => $list);
    echo json_encode($callback);
  }

  public function sales_store(Request $request)
  {
    $company = $request->company_id;
    $slpCode = $request->SalesPersonCode;

    $post_sales = [
      'company' => $company,
      'SlpCode' => $slpCode
    ];
    
    $url = 'http://36.93.82.10/erp-api-lta/api/getSalesDetail';
    $get = callApiWithPost($url, json_encode($post_sales));

    $post = [
      'company_id' => $company,
      'SalesPersonCode' => $get['SlpCode'],
      'SalesPersonCodeSfa' => $slpCode,
      'SalesPersonName' => $get['SlpName'],
      'users_id' => $request->users_id
    ];

    UserSales::create($post);

    $alert = array(
      'type' => 'success',
      'message' => 'Sales berhasil di tambahkan !'
    );

    return redirect()->back()->with($alert);
  }

	public function sales_delete($id)
	{
		UserSales::find($id)->delete();

		$alert = array(
      'type' => 'success',
      'message' => 'Sales berhasil di delete !'
    );

    return redirect()->back()->with($alert);
	}

  public function update(Request $request)
  {
    # code...
  }

  public function sap_lta(Request $request)
  {
    $id = $request->id;
  }

  public function sap_taa(Request $request)
  {
    $id = $request->id;
  }
}
