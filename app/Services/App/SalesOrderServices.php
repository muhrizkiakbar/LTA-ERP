<?php

namespace App\Services\App;

use App\Models\Branch;
use App\Models\ClosingDate;
use App\Models\Customer;
use App\Models\DiscountProgramLta;
use App\Models\Item;
use App\Models\OrderHeader;
use App\Models\OrderLines;
use App\Models\OrderTemp;
use App\Models\Uom;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class SalesOrderServices 
{
	public function insertTemp($users_id, $data)
	{
		$itemCode = $data['ItemCode'];
    $itemName = $data['ItemName'];
    $whs = $data['Warehouse'];
    $satuan = $data['Satuan'];
    $jml_order = $data['Quantity'];
    $company = $data['company'];
    $stok = $data['stok'];
    $satuan_kecil = $data['satuan_kecil'];
    $satuan_besar = $data['satuan_besar'];
    $harga_jual_pcs = $data['harga_jual_pcs'];
    $harga_jual_ktn = $data['harga_jual_ktn'];
    $item_group = $data['item_group'];
    $nisib = $data['nisib'];
    $U_CLASS = $data['U_CLASS'];

		if ($stok <= 0) 
    {
      $callback = array(
        'message' => 'empty_stok'
      );
    }
    else 
    {
      if($satuan=="nisik")
      {
        $UnitMsr = $satuan_kecil;
        $NumPerMsr = 1;
        $Quantity = $jml_order;
        $UnitPrice = $harga_jual_pcs;

        $UnitMsr2 = $satuan_besar;
        $NumPerMsr2 = $nisib;
        $UnitPrice2 = $harga_jual_ktn;
      }
      else
      {
        $UnitMsr = $satuan_besar;
        $NumPerMsr = $nisib;
        $Quantity = $jml_order;
        $UnitPrice = $harga_jual_ktn;

        $UnitMsr2 = $satuan_kecil;
        $NumPerMsr2 = 1;
        $UnitPrice2 = $harga_jual_pcs;
      }

      $CostingCode2 = $item_group;

      $getUomEntry = Uom::where('UomCode',$UnitMsr)->where('company_id',$company)->first();
      $UomEntry = $getUomEntry->UomEntry;

      $getUomEntry2 = Uom::where('UomCode',$UnitMsr2)->where('company_id',$company)->first();
      $UomEntry2 = $getUomEntry2->UomEntry;

      $qty_real = $Quantity * $NumPerMsr;

      if ($stok > $qty_real) 
      {
        $qty = $qty_real / $NumPerMsr;
      }
      else 
      {
        if ($UnitMsr=="KTN" || $UnitMsr=="CASE" || $UnitMsr=="LSN") 
        {
          $qty = $stok;
          $UnitMsr = $satuan_kecil;
          $NumPerMsr = 1;
          $UnitPrice = $harga_jual_pcs;

          $UnitMsr2 = $satuan_besar;
          $NumPerMsr2 = $nisib;
          $UnitPrice2 = $harga_jual_ktn;
        }
        else
        {
          $qty = $stok / $NumPerMsr;
        }
      }

      $data2 = [
        'ItemCode' => $itemCode,
        'ItemName' => $itemName,
        'Quantity' => $qty,
        'TaxCode' => "PPNO11",
        'UnitPrice' => $UnitPrice,
        'UnitMsr' => $UnitMsr,
        'UomCode' => $UnitMsr,
        'UomEntry' => $UomEntry,
        'NumPerMsr' => $NumPerMsr,
        'UnitPrice2' => $UnitPrice2,
        'UnitMsr2' => $UnitMsr2,
        'UomCode2' => $UnitMsr2,
        'UomEntry2' => $UomEntry2,
        'NumPerMsr2' => $NumPerMsr2,
        'CostingCode' => $U_CLASS,
        'CostingCode2' => $CostingCode2,
        'CostingCode3' => 'SAL',
        'WarehouseCode' => $whs,
        'users_id' => $users_id
      ]; 

      $cek = OrderTemp::where('ItemCode',$itemCode)->where('users_id',$users_id)->get();
      if(count($cek) == 0)
      {
        $post = OrderTemp::create($data2);

        if ($post) 
        {
          $temp = $this->getTempLines($users_id);
          $totalBefore = array_sum(array_column($temp,'docTotal'));
          $vatSum = $totalBefore * 0.11;
          $total = $totalBefore + $vatSum;
        }

        $callback = array(
          'message' => 'sukses',
          'totalBefore' => rupiah($totalBefore),
          'vatSum' => rupiah($vatSum),
          'total' => rupiah($total)
        );
      }
      else
      {
        $callback = array(
          'message' => 'already'
        );
      }
    }

		return $callback;
	}

	public function deleteTemp($users_id, $id)
	{
		$post = OrderTemp::find($id)->delete();

		if ($post) 
		{
			$temp = $this->getTempLines($users_id);
			$totalBefore = array_sum(array_column($temp,'docTotal'));
			$vatSum = $totalBefore * 0.11;
			$total = $totalBefore + $vatSum;

			$callback = array(
				'message' => 'sukses',
				'totalBefore' => rupiah($totalBefore),
				'vatSum' => rupiah($vatSum),
				'total' => rupiah($total)
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

	public function insertLines($users_id, $data)
	{
		$itemCode = $data['ItemCode'];
    $itemName = $data['ItemName'];
    $whs = $data['Warehouse'];
    $satuan = $data['Satuan'];
    $jml_order = $data['Quantity'];
    $company = $data['company'];
    $stok = $data['stok'];
    $satuan_kecil = $data['satuan_kecil'];
    $satuan_besar = $data['satuan_besar'];
    $harga_jual_pcs = $data['harga_jual_pcs'];
    $harga_jual_ktn = $data['harga_jual_ktn'];
    $item_group = $data['item_group'];
    $nisib = $data['nisib'];
    $U_CLASS = $data['U_CLASS'];
		$DocEntry = $data['DocEntry'];

		if ($stok <= 0) 
    {
      $callback = array(
        'message' => 'empty_stok'
      );
    }
    else 
    {
      if($satuan=="nisik")
      {
        $UnitMsr = $satuan_kecil;
        $NumPerMsr = 1;
        $Quantity = $jml_order;
        $UnitPrice = $harga_jual_pcs;

        $UnitMsr2 = $satuan_besar;
        $NumPerMsr2 = $nisib;
        $UnitPrice2 = $harga_jual_ktn;
      }
      else
      {
        $UnitMsr = $satuan_besar;
        $NumPerMsr = $nisib;
        $Quantity = $jml_order;
        $UnitPrice = $harga_jual_ktn;

        $UnitMsr2 = $satuan_kecil;
        $NumPerMsr2 = 1;
        $UnitPrice2 = $harga_jual_pcs;
      }

      $CostingCode2 = $item_group;

      $getUomEntry = Uom::where('UomCode',$UnitMsr)->where('company_id',$company)->first();
      $UomEntry = $getUomEntry->UomEntry;

      $getUomEntry2 = Uom::where('UomCode',$UnitMsr2)->where('company_id',$company)->first();
      $UomEntry2 = $getUomEntry2->UomEntry;

      $qty_real = $Quantity * $NumPerMsr;

      if ($stok > $qty_real) 
      {
        $qty = $qty_real / $NumPerMsr;
      }
      else 
      {
        if ($UnitMsr=="KTN" || $UnitMsr=="CASE" || $UnitMsr=="LSN") 
        {
          $qty = $stok;
          $UnitMsr = $satuan_kecil;
          $NumPerMsr = 1;
          $UnitPrice = $harga_jual_pcs;

          $UnitMsr2 = $satuan_besar;
          $NumPerMsr2 = $nisib;
          $UnitPrice2 = $harga_jual_ktn;
        }
        else
        {
          $qty = $stok / $NumPerMsr;
        }
      }

      $data2 = [
				'DocEntry' => $DocEntry,
        'ItemCode' => $itemCode,
        'Dscription' => $itemName,
        'Quantity' => $qty,
        'TaxCode' => "PPNO11",
        'UnitPrice' => $UnitPrice,
        'UnitMsr' => $UnitMsr,
        'UomCode' => $UnitMsr,
        'UomEntry' => $UomEntry,
        'NumPerMsr' => $NumPerMsr,
        'UnitPrice2' => $UnitPrice2,
        'UnitMsr2' => $UnitMsr2,
        'UomCode2' => $UnitMsr2,
        'UomEntry2' => $UomEntry2,
        'NumPerMsr2' => $NumPerMsr2,
        'CostingCode' => $U_CLASS,
        'CostingCode2' => $CostingCode2,
        'CostingCode3' => 'SAL',
        'WarehouseCode' => $whs,
        'users_id' => $users_id
      ]; 

      $cek = OrderLines::where('DocEntry',$DocEntry)
											 ->where('ItemCode',$itemCode)
											 ->get();

      if(count($cek) == 0)
      {
				OrderLines::create($data2);
		
				$temp = $this->getTempLines($users_id);
				$totalBefore = array_sum(array_column($temp,'docTotal'));
				$vatSum = $totalBefore * 0.11;
				$total = $totalBefore + $vatSum;

        $callback = array(
          'message' => 'sukses',
          'totalBefore' => rupiah($totalBefore),
          'vatSum' => rupiah($vatSum),
          'total' => rupiah($total)
        );
      }
      else
      {
        $callback = array(
          'message' => 'already'
        );
      }
    }

		return $callback;
	}

	public function updateLines($data)
	{
		$id = $data['id'];
		$jml_order = $data['Quantity'];
		$satuan = $data['Satuan'];
		$itemCode = $data['ItemCode'];
		$company = $data['company'];

		$docEntry = $data['DocEntry'];

		$lines = OrderLines::find($id);

		if ($satuan=='nisik') 
		{
			$UnitMsr = $data['satuan_kecil'];
      $NumPerMsr = 1;
      $Quantity = $jml_order;
      $UnitPrice = $data['harga_jual_pcs'];

      $UnitMsr2 = $data['satuan_besar'];
      $NumPerMsr2 = $data['nisib'];
      $UnitPrice2 = $data['harga_jual_ktn'];
		}
		else
		{
			$UnitMsr = $data['satuan_besar'];
      $NumPerMsr = $data['nisib'];
      $Quantity = $jml_order;
      $UnitPrice = $data['harga_jual_ktn'];

      $UnitMsr2 = $data['satuan_kecil'];
      $NumPerMsr2 = 1;
      $UnitPrice2 = $data['harga_jual_pcs'];
		}

		$CostingCode2 = $data['item_group'];

		$getUomEntry = Uom::where('UomCode',$UnitMsr)->where('company_id',$company)->first();
		$UomEntry = $getUomEntry->UomEntry;

		$getUomEntry2 = Uom::where('UomCode',$UnitMsr2)->where('company_id',$company)->first();
		$UomEntry2 = $getUomEntry2->UomEntry;
		
		$qty_real = $Quantity * $NumPerMsr;

		$disc1 = $lines->U_DISC1;
    $disc2 = $lines->U_DISC2;
    $disc3 = $lines->U_DISC3;
    $disc4 = $lines->U_DISC4;
    $disc5 = $lines->U_DISC5;
    $disc6 = $lines->U_DISC6;
    $disc7 = $lines->U_DISC7;
    $disc8 = $lines->U_DISC8;

		$totalx = $Quantity * $UnitPrice;

		$discx1 = ($disc1 / 100) * $totalx;
		$discx2 = ($disc2 / 100) * ($totalx - $discx1);
		$discx3 = ($disc3 / 100) * ($totalx - $discx1 - $discx2);
		$discx4 = ($disc4 / 100) * ($totalx - $discx1 - $discx2 - $discx3);
		$discx5 = ($disc5 / 100) * ($totalx - $discx1 - $discx2 - $discx3 - $discx4);
		$discx6 = ($disc6 / 100) * ($totalx - $discx1 - $discx2 - $discx3 - $discx4 - $discx5);
		$discx7 = ($disc7 / 100) * ($totalx - $discx1 - $discx2 - $discx3 - $discx4 - $discx5 - $discx6);
		$discx8 = ($disc8 / 100) * ($totalx - $discx1 - $discx2 - $discx3 - $discx4 - $discx5 - $discx6 - $discx7);

		$data2 = [
			'ItemCode' => $itemCode,
			'Quantity' => $qty_real,
			'UnitPrice' => $UnitPrice,
			'UnitMsr' => $UnitMsr,
			'UomCode' => $UnitMsr,
			'UomEntry' => $UomEntry,
			'NumPerMsr' => $NumPerMsr,
			'UnitPrice2' => $UnitPrice2,
			'UnitMsr2' => $UnitMsr2,
			'UomCode2' => $UnitMsr2,
			'UomEntry2' => $UomEntry2,
			'NumPerMsr2' => $NumPerMsr2,
			'U_DISC1' => $disc1,
			'U_DISCVALUE1' => $discx1,
			'U_DISC2' => $disc2,
			'U_DISCVALUE2' => $discx2,
			'U_DISC3' => $disc3,
			'U_DISCVALUE3' => $discx3,
			'U_DISC4' => $disc4,
			'U_DISCVALUE4' => $discx4,
			'U_DISC5' => $disc5,
			'U_DISCVALUE5' => $discx5,
			'U_DISC6' => $disc6,
			'U_DISCVALUE6' => $discx6,
			'U_DISC7' => $disc7,
			'U_DISCVALUE7' => $discx7,
			'U_DISC8' => $disc8,
			'U_DISCVALUE8' => $discx8
		]; 

		// dd($data2);

		OrderLines::find($id)->update($data2);

	}

	public function updateSap($DocEntry, $company)
	{
		$user_lta = auth()->user()->username_sap_lta;
		$pass_lta = auth()->user()->password_sap_lta;

		$user_taa = auth()->user()->username_sap_taa;
		$pass_taa = auth()->user()->password_sap_taa;

		$get = OrderHeader::where('DocEntry',$DocEntry)->first();

		$data = [
      'DocDate' => $get->DocDate,
      'DocDueDate' => $get->DocDueDate,
      'SalesPersonCode' => $get->SlpCode,
      'Comments' => 'Updated - '.$get->Comments,
      'DocumentLines' => $this->jsonLinesSap($DocEntry)
    ];

		if ($company==1) 
		{
			$post = [
				'db' => 'LTALIVE2020',
				'username' => $user_lta,
				'password' => $pass_lta,
				'docentry' => $DocEntry,
				'json' => $data
			];
		}
		else
		{
			$post = [
				'db' => 'TAALIVE2021',
				'username' => $user_taa,
				'password' => $pass_taa,
				'docentry' => $DocEntry,
				'json' => $data
			];
		}

		// dd($post);

		$url = 'http://36.93.82.10/erp-api-lta/api/pushToInsertLinesSalesSap';
		$get = callApiWithPost($url, json_encode($post));

		return $get;
	}

	public function jsonLinesSap($id)
	{
		$data = [];
    $get = OrderLines::where('DocEntry',$id)->get();

    foreach ($get as $value) 
    {
      $total = $value['Quantity'] * $value['UnitPrice'];

      $data[] = [
        'DocEntry' => $value['DocEntry'],
        'ItemCode' => $value['ItemCode'],
        'Quantity' => $value['Quantity'],
        'Price' => $value['UnitPrice'],
        'UnitPrice' => $value['UnitPrice'],
        'CostingCode' => $value['CostingCode'],
        'CostingCode2' => $value['CostingCode2'],
        'CostingCode3' => $value['CostingCode3'],
        'LineTotal' => round($total,2),
        'WarehouseCode' => $value['WarehouseCode'],
        'MeasureUnit' => $value['UnitMsr'],
        'UoMCode' => $value['UomCode'],
        'UoMEntry' => $value['UomEntry'],
        'UnitsOfMeasurment' => $value['NumPerMsr'],
        'TaxCode' => $value['TaxCode']
      ];
    }

    return $data;
	}

	public function deleteLines($id)
	{
		$post = OrderLines::find($id);

		if ($post) 
		{
			OrderLines::find($id)->delete();
			$temp = $this->getLines($post->DocEntry);
			$totalBefore = array_sum(array_column($temp,'docTotal'));
			$vatSum = $totalBefore * 0.11;
			$total = $totalBefore + $vatSum;

			$callback = array(
				'message' => 'sukses',
				'totalBefore' => rupiah($totalBefore),
				'vatSum' => rupiah($vatSum),
				'total' => rupiah($total)
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

	public function insertManual($data)
	{
		$users_id = auth()->user()->id;

		$cardcode = $data['cardCode'];

    $date = $data['docDate'];
    $date_closing = ClosingDate::where('status',1)->get();

    if(isset($date))
    {
      if (count($date_closing)==0) 
      {
        $docDate = date('Y-m-d');
      }
      else
      {
        $docDate = $date;
      }
    }
    else
    {
      $docDate = date('Y-m-d');
    }

    $top = "+1 days";
		$docDueDate= date('Y-m-d', strtotime($top, strtotime($docDate)));

		$push = [
      'CardCode' => $cardcode,
      'DocDueDate' => $docDueDate,
      'DocDate' => $docDate,
      'BPL_IDAssignedToInvoice' => $data['BplId'],
      'SalesPersonCode'=> $data['SalesPersonCode'],
      'NumAtCard' => isset($data['numAtCard']) ? $data['numAtCard'] : 'TEST SO MANUAL ERP 2.0_'.time() ,
      'Comments' => $data['Comments'],
      'U_NOPOLISI' => $data['Nopol1'],
      'U_NOPOLISI2' => $data['Nopol2'],
      'DocumentLines' => $this->getTempLines2($users_id)
    ];

		return $push;
	}

	public function insertOrder($data,$company)
  {
		$branch = Branch::where('BPLid',$data['BPLId'])->first();

    $post = [
      'CardCode' => $data['CardCode'],
      'NumAtCard' => $data['NumAtCard'],
      'DocNum' => $data['DocNum'],
      'DocEntry' => $data['DocEntry'],
      'VatSum' => $data['VatSum'],
      'DocTotal' => $data['DocTotal'],
      'DocStatus' => "O",
			'Printed' => "N",
      'DocDate' => $data['DocDate'],
      'DocDueDate' => $data['DocDueDate'],
      'BPLId' => $data['BPLId'],
      'SalesPersonCode' => $data['SalesPersonCode'],
      'U_NOPOLISI' => $data['U_NOPOLISI'],
      'U_NOPOLISI2' => $data['U_NOPOLISI2'],
      'Comments' => $data['Comments'],
      'company_id' => $company,
      'sfa' => 'MANUAL',
			'Branch' => $branch->id
    ];

    OrderHeader::create($post);
    $this->insertOrderLines($data['Lines'],$company);
  }

	public function insertOrderLines($lines,$company)
	{
		foreach ($lines as $value) 
		{
			$item = Item::where('code',$value['ItemCode'])
									->where('company_id',$company)->first();

			$linesx[] = [
				'DocEntry' => $value['DocEntry'],
				'LineNum' => $value['LineNum'],
				'NumAtCard' => $value['NumAtCard'],
				'ItemCode' => $value['ItemCode'],
				'Dscription' => isset($item->title) ? $item->title : NULL,
				'Quantity' => $value['Quantity'],
				'TaxCode' => $value['TaxCode'],
				'UnitPrice' => $value['UnitPrice'],
				'UnitMsr' => $value['UnitMsr'],
				'UomCode' => $value['UomCode'],
				'UomEntry' => $value['UomEntry'],
				'NumPerMsr' => $value['NumPerMsr'],
				'CostingCode' => $value['CostingCode'],
				'CostingCode2' => $value['CostingCode2'],
				'CostingCode3' => $value['CostingCode3'],
				'WarehouseCode' => $value['WarehouseCode']
			];
		}

		OrderLines::insert($linesx);
	}

	public function getTempLines2($user_id)
	{
		$get = OrderTemp::where('users_id',$user_id)->get();
    $data = [];

		foreach ($get as $value) 
    {
			$data[] = [
        'ItemCode' => $value->ItemCode,
        'Quantity' => $value->Quantity,
        'TaxCode' => $value->TaxCode,
        'UnitPrice' => $value->UnitPrice,
        'CostingCode' => $value->CostingCode,
        'CostingCode2' => $value->CostingCode2,
        'CostingCode3' => $value->CostingCode3,
        'MeasureUnit' => $value->UnitMsr,
        'UoMCode' => $value->UnitMsr,
        'UoMEntry' => $value->UomEntry,
        'UnitsOfMeasurment' => $value->NumPerMsr,
        'WarehouseCode' => $value->WarehouseCode
      ];
		}

		return $data;
	}

  public function getTempLines($user_id)
  {
    $get = OrderTemp::where('users_id',$user_id)->get();
    $data = [];
    
    foreach ($get as $value) 
    {
      $total = 0;
      $totalx = 0;

      $total = $value['Quantity'] * $value['UnitPrice'];

      $data[] = [
        'id' => $value['id'],
        'itemCode' => $value['ItemCode'],
        'itemDesc' => $value['ItemName'],
        'qty' => $value['Quantity'],
        'unitMsr' => $value['UnitMsr'],
        'unitPrice' => $value['UnitPrice'],
        'taxCode' => $value['TaxCode'],
        'whsCode' => $value['WarehouseCode'],
        'cogs' => $value['CostingCode'].';'.$value['CostingCode2'].';'.$value['CostingCode3'],
        'docTotal' => $total
      ];
    }

    return $data;
  }

	public function searchOrdr($company, $sales, $DocNum)
	{
		$data = [];

		$function = 'searchOrdr';

		$post = [
			'SlpName' => $sales,
			'DocNum' => $DocNum
		];

		$row = callCompanyApiWithPost($company, $function, json_encode($post));

		foreach ($row as $value) 
		{
			$docDate = explode(' ',$value['DocDate']);
			$docDueDate = explode(' ',$value['DocDueDate']);

			$data[] = [
				'DocNum' => $value['DocNum'],
				'CardName' => $value['CardName'],
				'Alamat' => $value['Alamat'],
				'DocDate' => $docDate[0],
				'DocDueDate' => $docDueDate[0],
				'SlpName' => $value['SlpName'],
				'Netto' => $value['Netto']
			];
		}

		return $data;
	}

	public function selectOrdr($company, $docnum)
	{
		$cek = OrderHeader::where('DocNum',$docnum)->first();

		if (isset($cek)) 
		{
			OrderHeader::where('DocNum',$docnum)->delete();
			OrderLines::where('DocEntry',$cek->DocEntry)->delete();
		}

		$function = 'selectOrdr';

		$post = [
			'DocNum' => $docnum
		];

		$row = callCompanyApiWithPost($company, $function, json_encode($post));

		// dd($post);

		$branch = Branch::where('BPLid',$row['BPLId'])->first();

		$docDate = explode(' ',$row['DocDate']);
		$docDueDate = explode(' ',$row['DocDueDate']);

		$data = [
			'DocNum' => $row['DocNum'],
			'DocEntry' => $row['DocEntry'],
			'Branch' => $branch->id,
			'BPLId' => $row['BPLId'],
			'NumAtCard' => $row['NumAtCard'],
			'CardCode' => $row['CardCode'],
			'CardName' => $row['CardName'],
			'Alamat' => $row['Alamat'],
			'DocDate' => $docDate[0],
			'DocDueDate' => $docDueDate[0],
			'SlpCode' => $row['SlpCode'],
			'SlpName' => $row['SlpName'],
			'VatSum' => $row['VatSum'],
			'Bruto' => $row['Bruto'],
			'U_NOPOLISI' => $row['U_NOPOLISI'],
			'U_NOPOLISI2' => $row['U_NOPOLISI2'],
			'DocStatus' => $row['DocStatus'],
			'Comments' => $row['Comments'],
			'company_id' => $company
		];

		OrderHeader::create($data);
		$this->insertOrdrLines($row['Lines']);

		$json = [
			'DocNum' => $docnum
		];

		return $json;
	}

	public function insertOrdrLines($lines)
	{
		foreach ($lines as $key => $value) 
		{
			$data[] = [
				'DocEntry' => $value['DocEntry'],
				'LineNum' => $value['LineNum'],
				'ItemCode' => $value['ItemCode'],
				'Dscription' => $value['Dscription'],
				'Quantity' => $value['Quantity'],
				'TaxCode' => $value['TaxCode'],
				'UnitPrice' => $value['UnitPrice'],
				'UnitMsr' => $value['UnitMsr'],
				'UomCode' => $value['UomCode'],
				'UomEntry' => $value['UomEntry'],
				'NumPerMsr' => $value['NumPerMsr'],
				'CostingCode' => $value['CostingCode'],
				'CostingCode2' => $value['CostingCode2'],
				'CostingCode3' => $value['CostingCode3'],
				'WarehouseCode' => $value['WarehouseCode'],
				'U_DISC1' => $value['U_DISC1'],
				'U_DISCVALUE1' => $value['U_DISCVALUE1'],
				'U_DISC2' => $value['U_DISC2'],
				'U_DISCVALUE2' => $value['U_DISCVALUE2'],
				'U_DISC3' => $value['U_DISC3'],
				'U_DISCVALUE3' => $value['U_DISCVALUE3'],
				'U_DISC4' => $value['U_DISC4'],
				'U_DISCVALUE4' => $value['U_DISCVALUE4'],
				'U_DISC5' => $value['U_DISC5'],
				'U_DISCVALUE5' => $value['U_DISCVALUE5'],
				'U_DISC6' => $value['U_DISC6'],
				'U_DISCVALUE6' => $value['U_DISCVALUE6'],
				'U_DISC7' => $value['U_DISC7'],
				'U_DISCVALUE7' => $value['U_DISCVALUE7'],
				'U_DISC8' => $value['U_DISC8'],
				'U_DISCVALUE8' => $value['U_DISCVALUE8'],
				'DocStatus' => $value['DocStatus']
			];
		}

		OrderLines::insert($data);
	}

	public function getLines($docEntry)
	{
		$get = OrderLines::where('DocEntry',$docEntry)->get();

		$data = [];
    
    foreach ($get as $value) 
    {
      $total = 0;
      $totalx = 0;
      $disc_cal = 0;
      $disc_calx = 0;

      $total = $value['Quantity'] * $value['UnitPrice'];

			$disc1 = $value['U_DISC1'];
      $disc2 = $value['U_DISC2'];
      $disc3 = $value['U_DISC3'];
      $disc4 = $value['U_DISC4'];
      $disc5 = $value['U_DISC5'];
      $disc6 = $value['U_DISC6'];
      $disc7 = $value['U_DISC7'];
      $disc8 = $value['U_DISC8'];

      $discx1 = $value['U_DISCVALUE1'];
      $discx2 = $value['U_DISCVALUE2'];
      $discx3 = $value['U_DISCVALUE3'];
      $discx4 = $value['U_DISCVALUE4'];
      $discx5 = $value['U_DISCVALUE5'];
      $discx6 = $value['U_DISCVALUE6'];
      $discx7 = $value['U_DISCVALUE7'];
      $discx8 = $value['U_DISCVALUE8'];

			$disc_cal = $disc1+$disc2+$disc3+$disc4+$disc5+$disc6+$disc7+$disc8;
      $disc_calx = $discx1+$discx2+$discx3+$discx4+$discx5+$discx6+$discx7+$discx8;

      $totalx = $total - $disc_calx;

      $data[] = [
        'id' => $value['id'],
        'itemCode' => $value['ItemCode'],
        'itemDesc' => $value['Dscription'],
        'qty' => $value['Quantity'],
        'unitMsr' => $value['UnitMsr'],
        'unitPrice' => $value['UnitPrice'],
        'taxCode' => $value['TaxCode'],
        'whsCode' => $value['WarehouseCode'],
        'cogs' => $value['CostingCode'].';'.$value['CostingCode2'].';'.$value['CostingCode3'],
        'beforeDiscount' => $total,
        'docTotal' => $totalx,
        'disc_total' => $disc_cal
      ];
    }

    return $data;
	}

	public function getLinesDiscount($id)
	{
		$data = [];
    $get = OrderLines::where('DocEntry',$id)->get();

    $header = OrderHeader::where('DocEntry',$id)->first();

    $customer = $this->getCustomerDetailSfa($header['company_id'], $header['CardCode']);
    
    foreach ($get as $value) 
    {
      $item = $this->syncItemToErp($header['company_id'], $value['ItemCode'], $value['Dscription']);
			$total = $value['Quantity'] * $value['UnitPrice'];

			$arr_diskon[] = [
				'id' => $value['id'],
				'ItemCode' => $value['ItemCode'],
				'ItemName' => $value['Dscription'],
				'CardCode' => $header['CardCode'],
				'Date' => $header['DocDate'],
				'Qty' => $value['Quantity'] * $value['NumPerMsr'],
				'DocEntry' => $value['DocEntry'],
				'SUBSEGMENT' => $customer['segment'],
				'INITIATIVE1' => $item['INIT1'],
				'INITIATIVE2' => $item['INIT2'],
				'INITIATIVE3' => $item['INIT3'],
				'INITIATIVE4' => $item['INIT4'],
				'INITIATIVE5' => $item['INIT5'],
				'INITIATIVE6' => $item['INIT6'],
				'INITIATIVE7' => $item['INIT7'],
				'CDB' => $item['CDB'],
				'CDS' => $customer['CDS'],
				'beforeDiscount' => $total,
			];
    }

    $data = $this->generateDiskon($arr_diskon);

    return $data;
	}

	public function generateDiskon($data)
	{
		$qtyGabungan3 = $this->qtyGabungan($data,'INITIATIVE1');
		$qtyGabungan4 = $this->qtyGabungan($data,'INITIATIVE3');
		$qtyGabungan5 = $this->qtyGabungan($data,'INITIATIVE2');
		$qtyGabungan6 = $this->qtyGabungan($data,'INITIATIVE7');
		$qtyGabungan7 = $this->qtyGabungan($data,'INITIATIVE4');
		$qtyGabungan8 = $this->qtyGabungan($data,'INITIATIVE5');

		foreach ($data as $value) 
		{
			$post_lta = [
				'CDB' => $value['CDB'],
				'CDS' => $value['CDS']
			];

			$discount_lta = $this->getDiskonLta($post_lta);

			$init1 = $value['INITIATIVE1'];
			$init2 = $value['INITIATIVE2'];
			$init3 = $value['INITIATIVE3'];
			$init4 = $value['INITIATIVE4'];
			$init5 = $value['INITIATIVE5'];
			$init7 = $value['INITIATIVE7'];

			$qtyGab3 = isset($value['INITIATIVE1']) ? $qtyGabungan3[$init1] : 0;
			$qtyGab4 = isset($value['INITIATIVE3']) ? $qtyGabungan4[$init3] : 0;
			$qtyGab5 = isset($value['INITIATIVE2']) ? $qtyGabungan5[$init2] : 0;
			$qtyGab6 = isset($value['INITIATIVE7']) ? $qtyGabungan6[$init7] : 0;
			$qtyGab7 = isset($value['INITIATIVE4']) ? $qtyGabungan7[$init4] : 0;
			$qtyGab8 = isset($value['INITIATIVE5']) ? $qtyGabungan8[$init5] : 0;

			$disc3 = $this->getDiskonValue($value['SUBSEGMENT'],$init1,$value['Date'],$qtyGab3);
			$disc4 = $this->getDiskonValue($value['SUBSEGMENT'],$init3,$value['Date'],$qtyGab4);
			$disc5 = $this->getDiskonValue($value['SUBSEGMENT'],$init2,$value['Date'],$qtyGab5);
			$disc6 = $this->getDiskonValue($value['SUBSEGMENT'],$init7,$value['Date'],$qtyGab6);
			$disc7 = $this->getDiskonValue($value['SUBSEGMENT'],$init4,$value['Date'],$qtyGab7);
			$disc8 = $this->getDiskonValue($value['SUBSEGMENT'],$init5,$value['Date'],$qtyGab8);

			$result[] = [
				'id' => $value['id'],
				'itemCode' => $value['ItemCode'],
				'itemName' => $value['ItemName'],
				'Qty' => $value['Qty'],
				'disc1' => $discount_lta['disc1'],
				'disc2' => $discount_lta['disc2'],
				'disc3' => $disc3,
				'disc4' => $disc4,
				'disc5' => $disc5,
				'disc6' => $disc6,
				'disc7' => $disc7,
				'disc8' => $disc8,
				'beforeDiscount' => $value['beforeDiscount']
			];
		}

		return $result;
	}

	public function qtyGabungan($data, $key)
	{
		// inisialisasi array untuk menyimpan hasil
		$initiativeQty = array();
		// loop melalui array data
		foreach ($data as $row) {
			// hanya memproses baris dengan inisiatif yang tidak kosong
			if (!empty($row[$key])) 
			{
				// simpan nilai Qty untuk inisiatif tertentu ke dalam array
				$initiative = $row[$key];
				$qty = $row['Qty'];
				if (!isset($initiativeQty[$initiative])) {
						$initiativeQty[$initiative] = $qty;
				} else {
						$initiativeQty[$initiative] += $qty;
				}
			}
		}

		return $initiativeQty;
	}

	public function getDiskonValue($subsegment,$init,$date,$qty)
	{
		$result = 0;

		$cek = DB::table('disc_program_png')
							->selectRaw('PROMODISCDET')
							->where('SUBSEGMENT',$subsegment)
							->where('U_PROMOCODE',$init)
							->whereRaw('? BETWEEN U_FROMDATE AND U_TODATE', [$date])
							->whereRaw('? BETWEEN U_FROM AND U_TO', [$qty])
							->groupBy('PROMODISCDET')
							->get();
		
		foreach ($cek as $key => $value) 
		{
			$result = $value->PROMODISCDET;
		}
		
		return $result;
	}

	public function getDiskonLta($body)
	{
		$get = DiscountProgramLta::where('U_CDB',$body['CDB'])
														 ->where('U_CDS',$body['CDS'])
														 ->first();

		if (isset($get)) 
		{
			$result = [
				'disc1' => round($get->U_DISCOUNT1,2),
				'disc2' => round($get->U_DISCOUNT2,2)
			];
		}
		else
		{
			$result = [
				'disc1' => 0,
				'disc2' => 0
			];
		}

		return $result;
	}

	public function getCustomerDetailSfa($company, $cardCode)
  {
    $post = [
      'CardCode' => $cardCode
    ];

    $function = 'getCustomerId';

    $get = callCompanyApiWithPost($company,$function,json_encode($post));

    if(isset($get))
    {
      $post_customer = [
        'code' => $cardCode,
        'title' => $get['CardName'],
        'address' => $get['Address'],
        'u_class' => $get['U_CLASS'],
        'tax' => $get['Tax'],
        'nopol_mix' => $get['NopolMix'],
        'nopol_png' => $get['NopolPng'],
        'cseg1' => $get['cseg1'],
        'cseg2' => $get['cseg2'],
        'cseg3' => $get['cseg3'],
        'cseg4' => $get['cseg4'],
        'stat_disc1' => $get['stat_disc1'],
        'stat_disc2' => $get['stat_disc2'],
        'stat_disc3' => $get['stat_disc3'],
        'stat_disc4' => $get['stat_disc4'],
        'stat_disc5' => $get['stat_disc5'],
        'stat_disc6' => $get['stat_disc6'],
        'stat_disc7' => $get['stat_disc7'],
        'stat_disc8' => $get['stat_disc8'],
        'cds' => $get['cds'],
        'limit' => $get['limit'],
        'tax_name' => $get['tax_name'],
        'nik' => $get['nik'],
        'contact_person' => $get['contact_person'],
        'top' => $get['extraDays'],
        'U_SALESCODE' => $get['U_SALESCODE'],
        'company_id' => 1
      ];

      $cek_customer = Customer::where('code',$cardCode)->get();

      if(count($cek_customer) > 0)
      {
        Customer::where('code',$cardCode)->update($post_customer);
      }
      else
      {
        Customer::create($post_customer);
      }
    }

    $uclass = isset($get['U_CLASS']) ? $get['U_CLASS'] : '';
    $nopolmix = isset($get['NopolMix']) ? $get['NopolMix'] : '';
    $nopolpng = isset($get['NopolPng']) ? $get['NopolPng'] : '';
		$segment = isset($get['cseg4']) ? $get['cseg4'] : '';

    $warehouse = Warehouse::where('code',$uclass)->first();

    $result = [
      'BPLId' => isset($warehouse) ? $warehouse->BPLId : '',
      'warehouse' => isset($warehouse) ? $warehouse->title : '',
      'U_CLASS' => $uclass,
      'NopolMix' => $nopolmix,
      'NopolPng' => $nopolpng,
			'segment' => $segment,
			'stat_disc1' => $get['stat_disc1'],
			'stat_disc2' => $get['stat_disc2'],
			'stat_disc3' => $get['stat_disc3'],
			'stat_disc4' => $get['stat_disc4'],
			'stat_disc5' => $get['stat_disc5'],
			'stat_disc6' => $get['stat_disc6'],
			'stat_disc7' => $get['stat_disc7'],
			'stat_disc8' => $get['stat_disc8'],
			'CDS' => $get['cds'],
    ];

    return $result;
  }

	public function syncItemToErp($company, $itemCode, $itemName)
  {
		$result = [];

    $post = [
      'ItemCode' => $itemCode
    ];

		$function = 'getItemId';

    $get = callCompanyApiWithPost($company,$function,json_encode($post));

    if(isset($get))
    {
      $cek_item = Item::where('code',$itemCode)->where('company_id',1)->get();

      if(count($cek_item) > 0)
      {
				$post_item = [
					'INIT1' => $get['INIT1'],
					'INIT2' => $get['INIT2'],
					'INIT3' => $get['INIT3'],
					'INIT4' => $get['INIT4'],
					'INIT5' => $get['INIT5'],
					'INIT6' => $get['INIT6'],
					'INIT7' => $get['INIT7'],
					'CDB' => $get['CDB']
				];

        Item::where('code',$itemCode)->update($post_item);
      }
      else
      {
				$post_item = [
					'code' => $itemCode,
					'title' => $itemName,
					'INIT1' => $get['INIT1'],
					'INIT2' => $get['INIT2'],
					'INIT3' => $get['INIT3'],
					'INIT4' => $get['INIT4'],
					'INIT5' => $get['INIT5'],
					'INIT6' => $get['INIT6'],
					'INIT7' => $get['INIT7'],
					'CDB' => $get['CDB']
				];

        Item::create($post_item);
      }

			$result = [
				'INIT1' => $get['INIT1'],
				'INIT2' => $get['INIT2'],
				'INIT3' => $get['INIT3'],
				'INIT4' => $get['INIT4'],
				'INIT5' => $get['INIT5'],
				'INIT6' => $get['INIT6'],
				'INIT7' => $get['INIT7'],
				'CDB' => $get['CDB']
			];
    }

		return $result;
  }
}