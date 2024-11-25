<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\History;
use App\Models\Warehouse;
use App\Models\UserSales;
use App\Models\Item;

class BackendServices 
{
  public function getCustomerDetailSfa($company, $cardCode)
  {
    $post = [
      'CardCode' => $cardCode
    ];

    $function = 'getCustomerId';

    $get = callCompanyApiWithPost($company,$function,json_encode($post));

		// dd($get);

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
    $sales = UserSales::where('SalesPersonCodeSfa',$get['U_SALESCODE'])->first();

    $result = [
      'BPLId' => isset($warehouse) ? $warehouse->BPLId : '',
      'warehouse' => isset($warehouse) ? $warehouse->title : '',
      'U_CLASS' => $uclass,
      'NopolMix' => $nopolmix,
      'NopolPng' => $nopolpng,
      'CardCode' => $cardCode,
      'CardName' => $get['CardName'],
      'Segment' => $get['cseg4'],
			'SalesPersonCode' => isset($sales->SalesPersonCode) ? $sales->SalesPersonCode : $get['U_SALESCODE'],
			'SalesPersonName' => isset($sales->SalesPersonName) ? $sales->SalesPersonName : $get['U_SALESNM'] 
    ];

    return $result;
  }

  public function syncItemToErp($company, $itemCode)
  {
    $post = [
      'ItemCode' => $itemCode
    ];

    $function = 'getItemId';

    $get = callCompanyApiWithPost($company,$function,json_encode($post));

    if(isset($get))
    {
      $post_item = [
        'code' => $itemCode,
        'title' => $get['ItemName'],
        'INIT1' => $get['INIT1'],
        'INIT2' => $get['INIT2'],
        'INIT3' => $get['INIT3'],
        'INIT4' => $get['INIT4'],
        'INIT5' => $get['INIT5'],
        'INIT6' => $get['INIT6'],
        'INIT7' => $get['INIT7'],
        'CDB' => $get['CDB'],
      ];

      $cek_item = Item::where('code',$itemCode)->where('company_id',1)->first();

      if(isset($cek_item))
      {
        Item::where('code',$itemCode)->update($post_item);

        $result = [
          'ItemName' => $cek_item->title
        ];
      }
      else
      {
        Item::create($post_item);

        $result = [
          'ItemName' => $get['ItemName']
        ];
      }
    }

    return $result;
  }

  public function getUomDetail($company, $itemCode, $cardCode, $whsCode)
  {

		$post = [
      'ItemNo' => $itemCode,
      'CardCode' => $cardCode,
      'WhsCode' => $whsCode
    ];

    $function = 'getItemUomDetail';

    $get = callCompanyApiWithPost($company,$function,json_encode($post));

    return $get;
  }

  public function getAvailable($company, $itemCode, $whsCode)
  {
    $post = [
      'ItemCode' => $itemCode,
      'WhsCode' => $whsCode
    ];

    $function = 'getAvailable';

    $stok = callCompanyApiWithPost($company,$function,json_encode($post));

    return $stok['available'];
  }

	public function getInStock($company, $itemCode, $whsCode)
  {
		$function = 'getInStock';

    $post = [
      'ItemCode' => $itemCode,
      'WhsCode' => $whsCode
    ];

    $stok = callCompanyApiWithPost($company, $function ,json_encode($post));

    return $stok['stok'];
  }


	public function getHistory()
	{
		$data = [];

		$role_id = auth()->user()->users_role_id;
		$username = auth()->user()->username;

		$toDate = date('Y-m-d');
		

		if ($role_id==1) 
		{
			$top = "-7 days";
			$fromDate = date('Y-m-d', strtotime($top, strtotime($toDate)));

			$get = History::whereDate('created_at','>=',$fromDate)
										->whereDate('created_at','<=',$toDate)
										->orderBy('id','DESC')
										->get();
		}
		else
		{
			$top = "-15 days";
			$fromDate = date('Y-m-d', strtotime($top, strtotime($toDate)));

			$get = History::where('title',$username)
										->whereDate('created_at','>=',$fromDate)
										->whereDate('created_at','<=',$toDate)
										->orderBy('id','DESC')
										->get();
		}

		foreach ($get as $value) 
    {
      $data[] = [
        'time' => $value->created_at,
        'user' => $value->title,
        'action' => isset($value->action) ? $value->action : $value->history_category->title,
        'desc' => $value->desc
      ]; 
    }

		return $data;
	}
}