<?php

namespace App\Http\Controllers\Backend\Sync;

use App\Http\Controllers\Controller;
use App\Models\SyncHeader;
use App\Models\UserSales;
use App\Services\Sync\MixServices;
use Illuminate\Http\Request;
use App\Models\History;

class MixController extends Controller
{
  public function __construct(MixServices $service)
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

    if($role_id!=1)
    {
      $sales = UserSales::where('company_id',1)
                        ->where('users_id',$users_id)
                        ->orderBy('SalesPersonName','ASC')
                        ->pluck('SalesPersonName','SalesPersonCodeSfa');
    }
    else
    {
      $sales = [];
    }

    $data = [
      'title' => 'Syncronize Data - SFA MIX',
      'assets' => $assets,
      'row' => $row,
      'sales' => $sales,
      'role' => $role_id
    ];

    return view('backend.sync.mix.index')->with($data);
  }

  public function sync(Request $request)
  {
    $post = [
      'tgl_order' => $request->date,
      'kode_slp_rep' => $request->sales
    ];

    $url = 'http://sfa.laut-timur.com/sfaerp/api/order_detail_sales';
    $get = $this->service->getDataFromSfa($url,json_encode($post));

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

    return view('backend.sync.mix.detail')->with($data);
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

    // dd(json_encode($json));

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
