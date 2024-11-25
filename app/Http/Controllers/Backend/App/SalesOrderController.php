<?php

namespace App\Http\Controllers\Backend\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\ClosingDate;
use App\Models\Customer;
use App\Models\History;
use App\Models\OrderHeader;
use App\Models\OrderLines;
use App\Models\OrderTemp;
use App\Models\UserSales;
use App\Services\App\SalesOrderServices;
use App\Services\BackendServices;

class SalesOrderController extends Controller
{
  public function __construct(SalesOrderServices $service)
  {
    $this->service = $service;
  }
  
  public function index()
  {
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

    $company = Company::whereIn('id',[1,2,8])->pluck('title','id');

    $status = [
      'O' => 'Open',
      'C' => 'Close'
    ];

    $local_currency = [
      'Local Currency' => 'Local Currency'
    ];

    $date_closing = ClosingDate::where('status',1)
                               ->limit(1)
                               ->first();

    $date = date('Y-m-d');
    $dueDate = date('Y-m-d',strtotime($date . "+1 days"));

    $data = [
      'title' => "Sales Order",
      'company' => $company,
      'status' => $status,
      'local_currency' => $local_currency,
      'date' => $date,
      'dueDate' => $dueDate,
      'closing' => isset($date_closing->date) ? $date_closing->date : '',
      'assets' => $assets,
			'remarks' => 'From ERP 2.0'
    ];

    return view('backend.app.sales_order.create.index')->with($data);
  }

	public function searchCustomerCreate(Request $request)
  {
    $branch_sap = auth()->user()->branch_sap;
    $role_id = auth()->user()->users_role_id;
    
    $company = $request->company;
    $cardName = $request->cardName;

		$function = 'searchCustomer';

    if ($role_id==1) 
    {
      $post = [
        'CardName' => $cardName."%",
        'U_BRANCHCODESFA' => '',
      ];
    }
    else 
    {
      $post = [
        'CardName' => $cardName."%",
				'U_BRANCHCODESFA' => $branch_sap
      ];
    }

    $get = callCompanyApiWithPost($company, $function ,json_encode($post));

    // dd($get);

    $data = [
      'row' => isset($get) ? $get : [],
      'company' => $company
    ];

    return view('backend.app.sales_order.create.customer_view')->with($data);
  }

  public function selectCustomerCreate(Request $request)
  {
		$backendService = new BackendServices;

    $id = $request->id;
    $company = $request->company;

    $get = $backendService->getCustomerDetailSfa($company, $id);

		// dd($get);

    $result = [
      'WhsCode' => $get['warehouse'],
      'CardCode' => $get['CardCode'],
      'CardName' => $get['CardName'],
      'Segment' => $get['Segment'],
      'uclass' => $get['U_CLASS'],
			'Nopol1' => $get['NopolMix'],
			'Nopol2' => $get['NopolPng'],
			'BPLId' => $get['BPLId'],
			'SalesPersonCode' => $get['SalesPersonCode'],
			'SalesPersonName' => $get['SalesPersonName']
    ];

    return response()->json($result);
  }

  public function searchItemCreate(Request $request)
  {
    $company = $request->company;
    $itemName = $request->itemName;
    $whsCode = $request->whsCode;

		$function = "searchItem";

    $post = [
      'ItemName' => $itemName."%",
      'WhsCode' => $whsCode
    ];

    $get = callCompanyApiWithPost($company,$function,json_encode($post));

    // dd($get);

    $data = [
      'row' => isset($get) ? $get : [],
      'company' => $company,
      'whsCode' => $whsCode,
      'U_CLASS' => $request->U_CLASS
    ];

    return view('backend.app.sales_order.create.item_view')->with($data);
  }

  public function selectItemCreate(Request $request)
  {
		$backendService = new BackendServices;

    $id = $request->id;
    $whsCode = $request->whsCode;
    $cardCode = $request->cardCode;
    $company = $request->company;

    $item = $backendService->syncItemToErp($company, $id);
    $uom = $backendService->getUomDetail($company, $id, $cardCode, $whsCode);
    $available = $backendService->getAvailable($company, $id, $whsCode);

    $satuan = [
      'nisik' => $uom['satuan_kecil'],
      'nisib' => $uom['satuan_besar']
    ];

    $result = [
      'ItemCode' => $id,
      'ItemName' => $item['ItemName'],
      'satuan' => $satuan,
      'whsCode' => $whsCode,
      'stok' => isset($available) ? $available : 0,
      'cardCode' => $cardCode,
      'company' => $company,
      'satuan_kecil' => $uom['satuan_kecil'],
      'satuan_besar' => $uom['satuan_besar'],
      'harga_jual_pcs' => $uom['harga_jual_pcs'],
      'harga_jual_ktn' => $uom['harga_jual_ktn'],
      'item_group' => $uom['item_group'],
      'nisib' => $uom['nisib'],
      'U_CLASS' => $request->U_CLASS
    ];

    // dd($result);

    return view('backend.app.sales_order.create.item_insert')->with($result);
  }

	public function searchItemUpdate(Request $request)
  {
    $company = $request->company;
    $itemName = $request->itemName;
    $whsCode = $request->whsCode;

		$function = "searchItem";

    $post = [
      'ItemName' => $itemName."%",
      'WhsCode' => $whsCode
    ];

    $get = callCompanyApiWithPost($company,$function,json_encode($post));

    // dd($get);

    $data = [
      'row' => isset($get) ? $get : [],
      'company' => $company,
      'whsCode' => $whsCode,
      'U_CLASS' => $request->U_CLASS,
			'DocEntry' => $request->DocEntry 
    ];

    return view('backend.app.sales_order.detail.item_view')->with($data);
  }

  public function selectItemUpdate(Request $request)
  {
		$backendService = new BackendServices;

    $id = $request->id;
    $whsCode = $request->whsCode;
    $cardCode = $request->cardCode;
    $company = $request->company;
		$docEntry = $request->DocEntry;

    $item = $backendService->syncItemToErp($company, $id);
    $uom = $backendService->getUomDetail($company, $id, $cardCode, $whsCode);
    $available = $backendService->getAvailable($company, $id, $whsCode);

		// dd($available);

    $satuan = [
      'nisik' => $uom['satuan_kecil'],
      'nisib' => $uom['satuan_besar']
    ];

    $result = [
      'ItemCode' => $id,
      'ItemName' => $item['ItemName'],
      'satuan' => $satuan,
      'whsCode' => $whsCode,
      'stok' => isset($available) ? $available : 0,
      'cardCode' => $cardCode,
      'company' => $company,
      'satuan_kecil' => $uom['satuan_kecil'],
      'satuan_besar' => $uom['satuan_besar'],
      'harga_jual_pcs' => $uom['harga_jual_pcs'],
      'harga_jual_ktn' => $uom['harga_jual_ktn'],
      'item_group' => $uom['item_group'],
      'nisib' => $uom['nisib'],
      'U_CLASS' => $request->U_CLASS,
			'DocEntry' => $docEntry
    ];

    // dd($result);

    return view('backend.app.sales_order.detail.item_insert')->with($result);
  }

  public function temp_table()
  {
    $users_id = auth()->user()->id;

    $row = $this->service->getTempLines($users_id);
    
    $data = [
      'row' => $row,
    ];

    return view('backend.app.sales_order.create.table')->with($data);
  }

  public function temp_store(Request $request)
  {
    // dd($request->all());
    $users_id = auth()->user()->id;
		$data = $request->all();

		$post = $this->service->insertTemp($users_id, $data);

    if ($post['message'] == 'empty_stok') 
    {
      $callback = array(
        'message' => 'empty_stok'
      );
    }
    else if($post['message'] == 'already')
    {
      $callback = array(
				'message' => 'already'
			);
    }
		else
		{
			$callback = array(
				'message' => 'sukses',
				'totalBefore' => $post['totalBefore'],
				'vatSum' => $post['vatSum'],
				'total' => $post['total']
			);
		}

    echo json_encode($callback);
  }

  public function temp_delete(Request $request)
  {
		$users_id = auth()->user()->id;
    $id = $request->id;

		$post = $this->service->deleteTemp($users_id, $id);

		if ($post['message'] == 'error') 
    {
      $callback = array(
        'message' => 'empty_stok'
      );
    }
		else
		{
			$callback = array(
				'message' => 'sukses',
				'totalBefore' => $post['totalBefore'],
				'vatSum' => $post['vatSum'],
				'total' => $post['total']
			);
		}

		echo json_encode($callback);
  }

	public function manual(Request $request)
	{
		$data = $request->all();

		$user_lta = auth()->user()->username_sap_lta;
    $pass_lta = auth()->user()->password_sap_lta;

		$user_taa = auth()->user()->username_sap_taa;
    $pass_taa = auth()->user()->password_sap_taa;

		$json = $this->service->insertManual($data);

		// dd($json);

		if($data['company']==1)
		{
			$post = [
				'db' => 'LTALIVE2020',
				'username' => $user_lta,
				'password' => $pass_lta,
				'json' => json_encode($json)
			];
		}
		else
		{
			$post = [
				'db' => 'TAALIVE2021',
				'username' => $user_taa,
				'password' => $pass_taa,
				'json' => json_encode($json)
			];
		}

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
        'card_code' => $data['cardCode'],
        'desc' => 'Error push data Sales Order ke SAP dengan pesan <strong>'.$get['message'].'</strong>'
      ];

      $callback = array(
        'message' => 'error_push'
      );
    }
    else
    {
      $this->service->insertOrder($get['data'],$data['company']);

      $history = [
        'title' => auth()->user()->username,
        'history_category_id' => 1,
        'card_code' => $get['data']['CardCode'],
        'desc' => 'Sukses push data <strong>'.$get['data']['CardCode'].'</strong> Sales Order Manual ke SAP dengan Document Number <strong>'.$get['data']['DocNum'].'</strong>'
      ];

			OrderTemp::where('users_id',auth()->user()->id)->delete();

      $callback = array(
        'message' => 'sukses',
				'docnum' => $get['data']['DocNum']
      );
    }

    History::create($history);

    return response()->json($callback);
	}

	public function search(Request $request)
	{
		$company = $request->company;
		$sales = $request->sales;
		$DocNum = $request->DocNum;

		$getSales = UserSales::where('SalesPersonCodeSfa',$sales)->first();
		$slpName = isset($getSales->SalesPersonName) ? $getSales->SalesPersonName : '';

		$row = $this->service->searchOrdr($company, $slpName, $DocNum);

		$data = [
			'row' => isset($row) ? $row : [],
			'company' => $company
		];

		return view('backend.app.sales_order.search')->with($data);
	}

	public function selectDocument(Request $request)
	{
		$row = $this->service->selectOrdr($request->company, $request->id);

		$callback = array(
			'message' => 'sukses',
			'docnum' => $row['DocNum']
		);

		return response()->json($callback);
	}

	public function detail($DocNum)
	{
		$cek_header = OrderHeader::where('DocNum',$DocNum)->first();

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

    $company = $cek_header->company_id;

    $local_currency = [
      'Local Currency' => 'Local Currency'
    ];

    $date_closing = ClosingDate::where('status',1)
                               ->limit(1)
                               ->first();

		$customer = $this->service->getCustomerDetailSfa($company, $cek_header->CardCode);

    $data = [
      'title' => "Detail - Sales Order",
      'company' => $company,
      'local_currency' => $local_currency,
      'closing' => isset($date_closing->date) ? $date_closing->date : '',
      'assets' => $assets,
			'cardCode' => $cek_header->CardCode,
			'cardName' => $cek_header->CardName,
			'numAtCard' => $cek_header->NumAtCard,
			'segment' => $customer['segment'],
			'warehouse' => $customer['warehouse'],
			'U_CLASS' => $customer['U_CLASS'],
			'DocNum' => $DocNum,
			'DocEntry' => $cek_header->DocEntry,
			'date' => $cek_header->DocDate,
      'dueDate' => $cek_header->DocDueDate,
			'slpName' => $cek_header->SlpName,
			'remarks' => $cek_header->Comments,
			'docTotal' => $cek_header->Bruto,
			'vatSum' => $cek_header->VatSum,
			'total' => $cek_header->Bruto + $cek_header->VatSum
    ];

    return view('backend.app.sales_order.detail.index')->with($data);
	}

	public function lines_table(Request $request)
	{
		$docEntry = $request->docEntry;

		$lines = $this->service->getLines($docEntry);

		$data = [
      'row' => $lines
    ];

    return view('backend.app.sales_order.detail.table')->with($data);
	}

	public function lines_store(Request $request)
	{
		$users_id = auth()->user()->id;
		$data = $request->all();

		// dd($data);

		$post = $this->service->insertLines($users_id, $data);

		// dd($post);

    if ($post['message'] == 'empty_stok') 
    {
      $callback = array(
        'message' => 'empty_stok'
      );
    }
    else if($post['message'] == 'already')
    {
      $callback = array(
				'message' => 'already'
			);
    }
		else
		{
			$callback = array(
				'message' => 'sukses',
				'totalBefore' => $post['totalBefore'],
				'vatSum' => $post['vatSum'],
				'total' => $post['total']
			);
		}

    echo json_encode($callback);
	}

	public function lines_edit(Request $request)
	{
		$backendService = new BackendServices;

    $id = $request->id;
		$orderLines = OrderLines::find($id);

		$orderHeader = OrderHeader::where('DocEntry',$orderLines->DocEntry)->first();

    $whsCode = $orderLines->WarehouseCode;
    $cardCode = $orderHeader->CardCode;
    $company = $orderHeader->company_id;
		$docEntry = $orderLines->DocEntry;

    $uom = $backendService->getUomDetail($company, $orderLines->ItemCode, $cardCode, $whsCode);
    $available = $backendService->getAvailable($company, $orderLines->ItemCode, $whsCode);

		// dd($available);

    $satuan = [
      'nisik' => $uom['satuan_kecil'],
      'nisib' => $uom['satuan_besar']
    ];

    $result = [
      'ItemCode' => $orderLines->ItemCode,
      'ItemName' => $orderLines->Dscription,
      'satuan' => $satuan,
      'whsCode' => $whsCode,
      'stok' => isset($available) ? $available : 0,
      'cardCode' => $cardCode,
      'company' => $company,
      'satuan_kecil' => $uom['satuan_kecil'],
      'satuan_besar' => $uom['satuan_besar'],
      'harga_jual_pcs' => $uom['harga_jual_pcs'],
      'harga_jual_ktn' => $uom['harga_jual_ktn'],
      'item_group' => $uom['item_group'],
      'nisib' => $uom['nisib'],
      'U_CLASS' => $orderLines->CostingCode,
			'DocEntry' => $docEntry,
			'Quantity' => round($orderLines->Quantity,0),
			'id' => $id
    ];

    // dd($result);

    return view('backend.app.sales_order.detail.item_edit')->with($result);
	}

	public function lines_update(Request $request)
	{
		$users_id = auth()->user()->id;
		$data = $request->all();

		// dd($data);

		$post = $this->service->updateLines($data);

		// dd($post);

    if ($post['message'] == 'empty_stok') 
    {
      $callback = array(
        'message' => 'empty_stok'
      );
    }
    else if($post['message'] == 'already')
    {
      $callback = array(
				'message' => 'already'
			);
    }
		else
		{
			$callback = array(
				'message' => 'sukses',
				'totalBefore' => $post['totalBefore'],
				'vatSum' => $post['vatSum'],
				'total' => $post['total']
			);
		}

    echo json_encode($callback);
	}

	public function lines_delete(Request $request)
	{
		$id = $request->id;

		$post = $this->service->deleteLines($id);

		if ($post['message'] == 'error') 
    {
      $callback = array(
        'message' => 'empty_stok'
      );
    }
		else
		{
			$callback = array(
				'message' => 'sukses',
				'totalBefore' => $post['totalBefore'],
				'vatSum' => $post['vatSum'],
				'total' => $post['total']
			);
		}

		echo json_encode($callback);
	}

	public function discount(Request $request)
	{
		$docEntry = $request->id;

		$lines = $this->service->getLinesDiscount($docEntry);

		$data = [
      'row' => $lines,
			'id' => $docEntry
    ];

    return view('backend.app.sales_order.detail.discount')->with($data);
	}

	public function discount_update(Request $request)
	{
		$numAtCard = $request->docEntry;
    $header = OrderHeader::where('DocEntry',$numAtCard)->first();
    $docnum = $header->DocNum;

    $idx = $request->idx;

    $id = $request->id;
    $disc1 = $request->disc1;
    $disc2 = $request->disc2;
    $disc3 = $request->disc3;
    $disc4 = $request->disc4;
    $disc5 = $request->disc5;
    $disc6 = $request->disc6;
    $disc7 = $request->disc7;
    $disc8 = $request->disc8;

    $total = $request->total;

    $totalx2 = 0;

    foreach ($idx as $key) 
    {
      $totalx = $total[$key];
      $discx1 = ($disc1[$key] / 100) * $totalx;
      $discx2 = ($disc2[$key] / 100) * ($totalx - $discx1);
      $discx3 = ($disc3[$key] / 100) * ($totalx - $discx1 - $discx2);
      $discx4 = ($disc4[$key] / 100) * ($totalx - $discx1 - $discx2 - $discx3);
      $discx5 = ($disc5[$key] / 100) * ($totalx - $discx1 - $discx2 - $discx3 - $discx4);
      $discx6 = ($disc6[$key] / 100) * ($totalx - $discx1 - $discx2 - $discx3 - $discx4 - $discx5);
      $discx7 = ($disc7[$key] / 100) * ($totalx - $discx1 - $discx2 - $discx3 - $discx4 - $discx5 - $discx6);
      $discx8 = ($disc8[$key] / 100) * ($totalx - $discx1 - $discx2 - $discx3 - $discx4 - $discx5 - $discx6 - $discx7);

      $disc_calx = $discx1+$discx2+$discx3+$discx4+$discx5+$discx6+$discx7+$discx8;

      $totalxx = $totalx - $disc_calx;

      $data = [
        'U_DISC1' => $disc1[$key],
        'U_DISCVALUE1' => $discx1,
        'U_DISC2' => $disc2[$key],
        'U_DISCVALUE2' => $discx2,
        'U_DISC3' => $disc3[$key],
        'U_DISCVALUE3' => $discx3,
        'U_DISC4' => $disc4[$key],
        'U_DISCVALUE4' => $discx4,
        'U_DISC5' => $disc5[$key],
        'U_DISCVALUE5' => $discx5,
        'U_DISC6' => $disc6[$key],
        'U_DISCVALUE6' => $discx6,
        'U_DISC7' => $disc7[$key],
        'U_DISCVALUE7' => $discx7,
        'U_DISC8' => $disc8[$key],
        'U_DISCVALUE8' => $discx8
      ];

      OrderLines::find($id[$key])->update($data);

      $totalx2 += round($totalxx,2);
    }

    // dd($data);

    $data2 = [
      'Bruto' => $totalx2,
      'VatSum' => ($totalx2 * 0.11)
    ];
    
    OrderHeader::where('DocNum',$docnum)->update($data2);

    $alert = array(
      'type' => 'success',
      'message' => 'Discount calculation berhasil di update !'
    );

    return redirect()->back()->with($alert);
	}

	public function update(Request $request)
	{
		# code...
	}
}
