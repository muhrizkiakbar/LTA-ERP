<?php

namespace App\Http\Controllers\Backend\Sync;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\SyncHeader;
use App\Models\UserSales;
use App\Services\Sync\PngServices;
use Illuminate\Http\Request;
use App\Models\History;
use App\Models\Warehouse;

class PngController extends Controller
{
  public function __construct(PngServices $service)
  {
    $this->service = $service;
  }

  public function index()
  {
    $users_id = auth()->user()->id;
    $role_id = auth()->user()->users_role_id;

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

		$toDate = date('Y-m-d');
		$top = "-1 day";
		$fromDate = date('Y-m-d', strtotime($top, strtotime($toDate)));

    if($role_id!=1)
    {
      $sales = UserSales::where('company_id',1)
                        ->where('users_id',$users_id)
                        ->orderBy('SalesPersonName','ASC')
                        ->pluck('SalesPersonName','SalesPersonCodeSfa');
    }
    else
    {
      $sales = Branch::pluck('title','id');
    }

    $data = [
      'title' => 'Syncronize Data - SFA PNG',
      'assets' => $assets,
      'row' => $row,
      'sales' => $sales,
      'role' => $role_id,
			'minDate' => $fromDate
    ];

    return view('backend.sync.png.index')->with($data);
  }

  public function sync(Request $request)
  {
		$role_id = auth()->user()->users_role_id;

		$company = 3;
		

		if ($role_id==1) 
		{
			$function = 'order_detail_branch';

			$post = [
				'tgl_order' => $request->date,
				'kode_branch' => $request->sales
			];
		}
		else
		{
			$function = 'order_detail_sales2';

			$post = [
				'tgl_order' => $request->date,
				'kode_slp_rep' => $request->sales
			];
		}

    $get = $this->service->getDataFromSfa($company, $function, $post);

    // dd($get);

    $message = $get['message'];

    if($message=='success')
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

  public function detail(Request $request)
  {
    $id = $request->id;

    $lines = $this->service->getDetailLines($id);

    $data = [
      'title' => 'Syncronize Data - Detail',
      'id' => $id,
      'lines' => $lines
    ];

    // dd($data);

    return view('backend.sync.png.detail')->with($data);
  }

  public function push(Request $request)
  {
    $id = $request->id;
    $username = auth()->user()->username_sap_lta;
    $password = auth()->user()->password_sap_lta;

    $json = $this->service->jsonData($id);

    $post = [
      'db' => 'LTALIVE2020_TEST',
      'username' => $username,
      'password' => $password,
      'json' => json_encode($json)
    ];

    // dd($post);

    $url = 'http://36.93.82.10/erp-api-lta/api/pushToSalesSap';
    $get = callApiWithPost($url, json_encode($post));

    // dd($get);

    if($get['notif']=='error_session_sap')
    {
      $history = [
        'title' => auth()->user()->username,
        'action' => '<label class="badge badge-danger">LOGIN ERROR SAP</label>',
        'desc' => 'Error login ke SAP dengan pesan <strong>'.$get['message'].'</strong>'
      ];

      $callback = array(
        'message' => 'error_session_sap'
      );
    }
    elseif ($get['notif']=='error_push') 
    {
      $history = [
        'title' => auth()->user()->username,
        'history_category_id' => 1,
        'card_code' => $id,
        'desc' => 'Error push data Sales Order ke SAP dengan pesan <strong>'.$get['message'].'</strong>'
      ];

      $callback = array(
        'message' => 'error_push'
      );
    }
    else
    {
      $this->service->insertOrder($get['data'],$id);

      $history = [
        'title' => auth()->user()->username,
        'history_category_id' => 1,
        'card_code' => $get['data']['CardCode'],
        'desc' => 'Sukses push data <strong>'.$get['data']['CardCode'].'</strong> Sales Order MMR ke SAP dengan Document Number <strong>'.$get['data']['DocNum'].'</strong>'
      ];

      $update_header = [
        'DocNum' => $get['data']['DocNum']
      ];

      SyncHeader::where('NumAtCard',$id)->update($update_header);

      $callback = array(
        'message' => 'sukses'
      );
    }

    History::create($history);

    return response()->json($callback);
  }

  public function close(Request $request)
  {
    $id = $request->id;

    $close = [
      'DocNum' => 911
    ];

    SyncHeader::where('NumAtCard',$id)->update($close);

    $callback = array(
      'message' => 'sukses'
    );

    return response()->json($callback);
  }
}
