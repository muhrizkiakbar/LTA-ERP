<?php

namespace App\Services\Sync;

use App\Models\Customer;
use App\Models\Item;
use App\Models\SyncHeader;
use App\Models\SyncLines;
use App\Models\UserSales;
use App\Models\Warehouse;
use App\Models\OrderHeader;
use App\Models\OrderLines;

class PngServices
{
  public function getData()
  {
    $data = [];

    $role = auth()->user()->users_role_id;
    $user_id = auth()->user()->id;

    if($role==1)
    {
      $row = SyncHeader::where('sfa','P&G')
                       ->where('company_id',1)
                       ->whereNull('DocNum')
                       ->orderBy('id','DESC')
                       ->get();

    }
    else
    {
      $user_sales = UserSales::where('users_id',$user_id)->pluck('SalesPersonCodeSfa');
      $row = SyncHeader::where('sfa','P&G')
                       ->where('company_id',1)
                       ->whereNull('DocNum')
                       ->whereIn('SalesPersonCode',$user_sales)
                       ->orderBy('id','DESC')
                       ->get();
    }

    foreach($row as $value)
    {
      $data[] = [
        'id' => $value->id,
        'CardCode' => $value->CardCode,
        'CardName' => $value->CardName,
        'Address' => $value->Address,
        'DocDate' => $value->DocDate,
        'Branch' => getBranch($value->Branch)->title,
        'Sales' => $value->SalesPersonName,
        'Total' => $value->DocTotal,
        'NumAtCard' => $value->NumAtCard
      ];
    }

    return $data;
  }

  public function getDataFromSfa($company, $function, $post)
  {
    $data_sfa = callCompanyApiWithPost($company, $function, json_encode($post));

		// dd($data_sfa);

    // return $data_sfa['data'];

    $no = 1;
    $nox = 0;
    if(isset($data_sfa['data']))
    {
      foreach($data_sfa['data'] as $data_header)
      {
        $cek_header = $this->cekDataSfa($data_header['NumAtCard']);

        if($cek_header==0)
        {
          $customer = $this->getCustomerDetailSfa(1, $data_header['CardCode']);
          if(!empty($customer['U_CLASS']))
          {
            $lines = $this->generateDataLinesSfa($data_header['NumAtCard'],$data_header['CardCode'],$data_header['Lines'],$customer['warehouse'],$customer['U_CLASS']);

            $header = [
              'company_id' => 1,
              'sfa' => 'P&G',
              'NumAtCard' => $data_header['NumAtCard'],
              'Branch' => $data_header['Branch'],
              'SalesPersonCode' => $data_header['SalesPersonCode'],
              'SalesPersonName' => $data_header['SalesPersonName'],
              'CardCode' => $data_header['CardCode'],
              'CardName' => $data_header['CardName'],
              'Address' => $data_header['Address'],
              'DocDate' => $data_header['DocDate'],
              'DocDueDate' => $data_header['DocDueDate'],
              'Comments' => $data_header['Comments'],
              'BPLId' => $customer['BPLId'],
              'U_NOPOLISI' => $customer['NopolMix'],
              'U_NOPOLISI2' => $customer['NopolPng'],
              'U_CLASS' => $customer['U_CLASS'],
              'DocTotal' => $lines['DocTotal'],
              'Lines' => $lines
            ];

            SyncHeader::create($header);

            $nox = $no++;
          }
        }
      }

      // return $data_header;

      if ($nox > 0) 
      {
        $callback = array(
          'message' => 'success'
        );
      } 
      else 
      {
        $callback = array(
          'message' => 'error'
        );
      }
    }
    else
    {
      $callback = array(
        'message' => 'error'
      );
    }

    return $callback;
  }

  public function generateDataLinesSfa($numAtCard, $cardCode, $lines, $whs, $class)
  {
    $data = [];

    foreach($lines as $lines)
    {
      $UomData = $this->getUomDetail(1, $lines['ItemCode'], $cardCode, $whs);

      $jml_order = $lines['QuantitySfa'];
      $jml_order_cases = $lines['QuantitySfaCases'];

      // dd($UomData);

      if (!empty($UomData)) 
      {
        if($jml_order > 0 && $jml_order_cases > 0 )
        {
          $UnitMsr = $UomData['satuan_kecil'];
          $NumPerMsr = 1;
          $UnitPrice = $UomData['harga_jual_pcs'];

          $UnitMsr2 = $UomData['satuan_besar'];
          $NumPerMsr2 = $UomData['nisib'];
          $UnitPrice2 = $UomData['harga_jual_ktn'];

          $Quantity1 = $jml_order;
          $Quantity2 = $jml_order_cases * $NumPerMsr2;
          $Quantity = $Quantity1 + $Quantity2;
          $QuantitySfaTotal = $Quantity; 
        }
        else if($jml_order > 0)
        {
          $UnitMsr = $UomData['satuan_kecil'];
          $NumPerMsr = 1;
          $Quantity = $jml_order;
          $UnitPrice = $UomData['harga_jual_pcs'];

          $UnitMsr2 = $UomData['satuan_besar'];
          $NumPerMsr2 = $UomData['nisib'];
          $UnitPrice2 = $UomData['harga_jual_ktn'];
          $QuantitySfaTotal = $Quantity; 
        }
        else
        {
          $UnitMsr = $UomData['satuan_besar'];
          $NumPerMsr = $UomData['nisib'];
          $Quantity = $jml_order_cases;
          $UnitPrice = $UomData['harga_jual_ktn'];

          $UnitMsr2 = $UomData['satuan_kecil'];
          $NumPerMsr2 = 1;
          $UnitPrice2 = $UomData['harga_jual_pcs'];
          $QuantitySfaTotal = $Quantity * $NumPerMsr; 
        }

        $CostingCode2 = $UomData['item_group'];

        $post3 = $UnitMsr;
        $post4 = $UnitMsr2;
        
        $available = $this->getAvailable(1,$lines['ItemCode'],$whs);
        $stok = isset($available) ? $available : 0;
        
        // dd($stok);

        if ($stok > 0) 
        {
          $qty_real = $Quantity * $NumPerMsr;

          if ($stok > $qty_real) 
          {
            $qty = $qty_real / $NumPerMsr;  //true
          }
          else 
          {
            if ($UnitMsr=="KTN" || $UnitMsr=="CASE" || $UnitMsr=="LSN") 
            {
              $qty = $stok;
              $UnitMsr = $UomData['satuan_kecil'];
              $NumPerMsr = 1;
              $UnitPrice = $UomData['harga_jual_pcs'];

              $UnitMsr2 = $UomData['satuan_besar'];
              $NumPerMsr2 = $UomData['nisib'];
              $UnitPrice2 = $UomData['harga_jual_ktn'];

              $post3 = $UomData['satuan_kecil'];
              $post4 = $UomData['satuan_besar'];
            }
            else
            {
              $qty = $stok / $NumPerMsr;
            }
          }
        }
        else
        {
          $qty = 0;
        }

        $getUomEntry = getUomEntry($post3,1);
        $UomEntry = isset($getUomEntry) ? $getUomEntry : '' ;

        $getUomEntry2 = getUomEntry($post4,1);
        $UomEntry2 = isset($getUomEntry2) ? $getUomEntry2 : '';
        $total_line = $qty * $UnitPrice;

        $this->syncItemToErp(1,$lines['ItemCode'],$lines['ItemName']);
      }
      else
      {
        $UnitMsr = '';
        $UomEntry = '';
        $UnitPrice = 0;
        $NumPerMsr = '';
        $UnitMsr2 = '';
        $UomEntry2 = '';
        $UnitPrice2 = 0;
        $NumPerMsr2 = '';
        $CostingCode2 = '';
        $qty = 0;
        $Quantity = 0;
        $QuantitySfaTotal = $Quantity; 
        $total_line = 0;
      }

      $data[] = [
        'NumAtCard' => $numAtCard,
        'ItemCode' => $lines['ItemCode'],
        'ItemName' => $lines['ItemName'],
        'Quantity' => round($Quantity,0),
        'QuantitySfa' => $jml_order,
        'QuantitySfaCases' => $jml_order_cases,
        'QuantitySfaTotal' => $QuantitySfaTotal,
        'TaxCode' => 'PPNO11',
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
        'CostingCode' => $class,
        'CostingCode2' => $CostingCode2,
        'CostingCode3' => 'SAL',
        'WarehouseCode' => $whs,
        'TotalLine' => $total_line
      ];
    }

    // return $data;

    SyncLines::insert($data);
    $total = array_sum(array_column($data,'TotalLine'));
    $result = [
      'DocTotal' => $total
    ];

    return $result;
  }

  public function jsonData($id)
  {
    $docDate = date('Y-m-d');
    $top = "+1 days";
    $docDueDate= date('Y-m-d', strtotime($top, strtotime($docDate)));

    $header = SyncHeader::where('NumAtCard',$id)->first();
    $sales = UserSales::where('SalesPersonCodeSfa',$header->SalesPersonCode)->first();

    $lines = $this->jsonDataLines($id);

    $json = [
      'CardCode' => $header->CardCode,
      'DocDueDate' => $docDueDate,
      'DocDate' => $docDate,
      'BPL_IDAssignedToInvoice' => $header->BPLId,
      'SalesPersonCode'=> $sales->SalesPersonCode,
      'NumAtCard' => $header->NumAtCard.'_TEST_ERP2.0',
      'Comments' => $header->Comments.'_TEST_ERP2.0',
      'U_NOPOLISI' => $header->U_NOPOLISI,
      'U_NOPOLISI2' => $header->U_NOPOLISI2,
      'DocumentLines' => $lines
    ];

    return $json;
  }

  public function jsonDataLines($id)
  {
    $data = [];

    $json = SyncLines::where('NumAtCard',$id)
                     ->where('Quantity','>',0)
                     ->orderBy('ItemName','ASC')
                     ->get();

    // return $json;

    foreach($json as $lines)
    {
      $itemCode = $lines->ItemCode;
      $whsCode = $lines->WarehouseCode;

      $available = $this->getAvailable(1,$itemCode,$whsCode);
      $stok = isset($available) ? $available : 0;

      $Quantity = $lines->Quantity;
      $NumPerMsr = $lines->NumPerMsr;
      $UnitMsr = $lines->UnitMsr;

      if ($stok > 0) 
      {
        $qty_real = $Quantity * $NumPerMsr;

        if ($stok > $qty_real) 
        {
          $qty = $qty_real / $NumPerMsr;
          $UnitMsr = $lines->UnitMsr;
          $NumPerMsr = $lines->NumPerMsr;
          $UnitPrice = $lines->UnitPrice;
          $UomEntry = $lines->UomEntry;
        }
        else 
        {
          if ($UnitMsr=="KTN" || $UnitMsr=="CASE" || $UnitMsr=="LSN") 
          {
            $qty = $stok;
            $UnitMsr = $lines->UnitMsr2;
            $NumPerMsr = 1;
            $UnitPrice = $lines->UnitPrice2;
            $UomEntry = $lines->UomEntry2;
          }
          else
          {
            $qty = $stok / $NumPerMsr;
            $UnitMsr = $lines->UnitMsr;
            $NumPerMsr = $lines->NumPerMsr;
            $UnitPrice = $lines->UnitPrice;
            $UomEntry = $lines->UomEntry;
          }
        }

        $data[] = [
          'ItemCode' => $itemCode,
          'Quantity' => $qty,
          'TaxCode' => $lines->TaxCode,
          'UnitPrice' => $UnitPrice,
          'CostingCode' => $lines->CostingCode,
          'CostingCode2' => $lines->CostingCode2,
          'CostingCode3' => $lines->CostingCode3,
          'MeasureUnit' => $UnitMsr,
          'UoMCode' => $UnitMsr,
          'UoMEntry' => $UomEntry,
          'UnitsOfMeasurment' => $NumPerMsr,
          'WarehouseCode' => $whsCode
        ];
      }
    }

    return $data;
  }

  public function cekDataSfa($numAtCard)
  {
    return SyncHeader::where('NumAtCard',$numAtCard)
                     ->where('sfa','P&G')
                     ->where('company_id',1)->count();
  }

  public function getUomDetail($company, $itemCode, $cardCode, $whsCode)
  {
    $post_uom = [
      'ItemNo' => $itemCode,
      'CardCode' => $cardCode,
      'WhsCode' => $whsCode
    ];

		$function = 'getItemUomDetail';

    $UomData = callCompanyApiWithPost($company,$function,json_encode($post_uom));

    return $UomData;
  }

  public function getAvailable($company, $itemCode, $whsCode)
  {
		$post = [
      'ItemCode' => $itemCode,
      'WhsCode' => $whsCode
    ];

		$function = 'getAvailable';

    $get = callCompanyApiWithPost($company,$function,json_encode($post));

		$stok = isset($get['available']) ? $get['available'] : 0;

    // $result = isset($stok['stok']) ? $stok['stok'] : '0';

    return $stok;
  }

  public function syncItemToErp($company, $itemCode, $itemName)
  {
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
					'INIT1' => isset($get['INIT1']) ? $get['INIT1'] : '',
					'INIT2' => isset($get['INIT2']) ? $get['INIT2'] : '',
					'INIT3' => isset($get['INIT3']) ? $get['INIT3'] : '',
					'INIT4' => isset($get['INIT4']) ? $get['INIT4'] : '',
					'INIT5' => isset($get['INIT5']) ? $get['INIT5'] : '',
					'INIT6' => isset($get['INIT6']) ? $get['INIT6'] : '',
					'INIT7' => isset($get['INIT7']) ? $get['INIT7'] : '',
					'CDB' => isset($get['CDB']) ? $get['CDB'] : ''
				];

        Item::where('code',$itemCode)->update($post_item);
      }
      else
      {
				$post_item = [
					'code' => $itemCode,
					'title' => $itemName,
					'INIT1' => isset($get['INIT1']) ? $get['INIT1'] : '',
					'INIT2' => isset($get['INIT2']) ? $get['INIT2'] : '',
					'INIT3' => isset($get['INIT3']) ? $get['INIT3'] : '',
					'INIT4' => isset($get['INIT4']) ? $get['INIT4'] : '',
					'INIT5' => isset($get['INIT5']) ? $get['INIT5'] : '',
					'INIT6' => isset($get['INIT6']) ? $get['INIT6'] : '',
					'INIT7' => isset($get['INIT7']) ? $get['INIT7'] : '',
					'CDB' => isset($get['CDB']) ? $get['CDB'] : ''
				];

        Item::create($post_item);
      }
    }
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

    $warehouse = Warehouse::where('code',$uclass)->first();

    $result = [
      'BPLId' => isset($warehouse) ? $warehouse->BPLId : '',
      'warehouse' => isset($warehouse) ? $warehouse->title : '',
      'U_CLASS' => $uclass,
      'NopolMix' => $nopolmix,
      'NopolPng' => $nopolpng 
    ];

    return $result;
  }

  public function getDetailLines($id)
  {
    $data = [];

    $get = SyncLines::where('NumAtCard',$id)
                    ->where('TotalLine','!=',0)
                    ->orderBy('ItemName','ASC')
                    ->get();

    foreach($get as $val)
    {
      $data[] = [
        'ItemCode' => $val->ItemCode,
        'ItemName' => $val->ItemName,
        'Qty' => $val->Quantity,
        'Satuan' => $val->UnitMsr,
        'UnitPrice' => $val->UnitPrice,
        'Total' => $val->TotalLine
      ];
    }

    $result = [
      'row' => $data,
      'total' => array_sum(array_column($data,'Total'))
    ];

    return $result;
  }

  public function insertOrder($data,$id)
  {
    $get_header = SyncHeader::where('NumAtCard',$id)->first();

    $post = [
      'CardCode' => $data['CardCode'],
      'NumAtCard' => $data['NumAtCard'],
      'DocNum' => $data['DocNum'],
      'DocEntry' => $data['DocEntry'],
      'VatSum' => $data['VatSum'],
      'Bruto' => $data['DocTotal'],
      'DocStatus' => "O",
      'DocDate' => $data['DocDate'],
      'DocDueDate' => $data['DocDueDate'],
      'BPLId' => $data['BPLId'],
      'SalesPersonCode' => $data['SalesPersonCode'],
      'U_NOPOLISI' => $data['U_NOPOLISI'],
      'U_NOPOLISI2' => $data['U_NOPOLISI2'],
      'Comments' => $data['Comments'],
      'company_id' => $get_header->company_id,
      'sfa' => $get_header->sfa
    ];

    OrderHeader::create($post);
    OrderLines::insert($data['Lines']);
  }
}