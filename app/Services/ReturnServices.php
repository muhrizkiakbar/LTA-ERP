<?php

namespace App\Services;

use App\Company;
use App\ReturnApproval;
use App\ReturnApprovalLines;
use App\ReturnApprovalMapping;
use App\Warehouse;
use DB;
use Illuminate\Http\Request;

class ReturnServices 
{
  public function post($data, $path)
  {
    $kd = 'RDN'.time();

    $customer = $this->customerDetail($data['CardCode'],$data['company_id']);

    $lines = $this->postLines($kd,$data['Lines'],$data['CardCode'],$customer['whsCode'],$data['company_id']);

    $sales = $this->salesPersonDetail($data['SlpCodeSfa'],$data['company_id']);

    $approval = $this->getApproval($sales['SlpCode']);

    $number = $this->generateNumber($customer['U_CLASS'], $data['date'], $data['company_id']);

    $url = 'approval/return/spv/'.$kd;
    $site = "https://system.laut-timur.tech/";
    $teks_tengah = $site . $url;
    $teks = "Customer *" . $customer['CardName'] . "* melakukan *PENGAJUAN RETURN*. Klik link dibawah ini untuk melakukan approval :";
    $subject = "Approval Return - Supervisor";
    $spv_name = $approval->spv_detail->name;
    $spv_no = $approval->spv_detail->contact;
    $new_message   = 'Halo ' . ucfirst(strtolower($spv_name)) . ', ' . $teks;

    $header = [
      'number' => $number,
      'kd' => $kd,
      'date' => $data['date'],
      'Branch' => $data['Branch'],
      'SlpCode' => $sales['SlpCode'],
      'SlpName' => $sales['SlpName'],
      'SlpCodeSfa' => $data['SlpCodeSfa'],
      'CardCode' => $data['CardCode'],
      'CardName' => $customer['CardName'],
      'lat' => $data['position']['lat'],
      'long' => $data['position']['long'],
      'file' => $path,
      'DocTotal' => $lines['docTotal'],
      'users_spv_id' => $approval->users_spv_id,
      'approval_spv_st' => 0,
      'users_sbh_id' => $approval->users_sbh_id,
      'approval_sbh_st' => 0,
      'NotaReturPajakToko' => $data['NotaReturPajakToko'],
      'NamaPicToko' => $data['NamaPicToko'],
      'NumberPicToko' => $data['NumberPicToko'],
      'U_CLASS' => $customer['U_CLASS'],
      'company_id' => $data['company_id']
    ];

    ReturnApproval::create($header);

    $this->sendToWa2($teks_tengah, $spv_no, $new_message, $subject);

    $result = [
      'message' => 'sukses'
    ];

    return $result;
  }

  public function postLines($kd, $lines, $cardCode, $whsCode, $company)
  {
    foreach ($lines as $key => $value) 
    {
      $item = $this->itemDetail($value['ItemCode'],$cardCode,$whsCode, $company);

      $lineTotal = $value['Quantity'] * $item['harga_jual_pcs'];

      $post[] = [
        'return_approval_kd' => $kd,
        'ItemCode' => $value['ItemCode'],
        'ItemName' => $item['ItemName'],
        'Quantity' => $value['Quantity'],
        'UnitPrice' => $item['harga_jual_pcs'],
        'LineTotal' => $lineTotal,
        'note' => $value['keterangan'],
        'ExpDate' => $value['ExpDate'],
      ];
    }

    ReturnApprovalLines::insert($post);

    $result = [
      'docTotal' => array_sum(array_column($post,'LineTotal'))
    ];

    return $result;
  }

  public function list($kodesls)
  {
    $data = [];
    $get = ReturnApproval::where('SlpCodeSfa',$kodesls)
                         ->OrderBy('date','DESC')
                         ->get();

    foreach ($get as $key => $value)
    {
      if ($value['approval_spv_st']==1 && $value['approval_sbh_st']==1) 
      {
        $status = 'Approve';
      }
      else if($value['approval_spv_st']==2 && $value['approval_sbh_st']==2)
      {
        $status = 'Reject';
      }
      else
      {
        $status = 'Waiting For Approval';
      }
      
      $filex = $value['file'];

      $file = !empty($filex) ? explode('/',$filex) : '';

      $url = isset($file[2]) ? 'http://36.93.82.10/erp-api-lta/public/storage/return/'.$file[2] : '';

      $data[] = [
        'kd' => $value['kd'],
        'date' => $value['date'],
        'CardCode' => $value['CardCode'],
        'CardName' => $value['CardName'],
        'DocTotal' => $value['DocTotal'],
        'status' => $status,
        'file' => isset($filex) ? $url : '-'
      ];
    }

    return $data;
  }

  public function listByCustomer($cardCode)
  {
    $data = [];
    $get = ReturnApproval::where('CardCode',$cardCode)
                         ->OrderBy('date','DESC')
                         ->get();

    foreach ($get as $key => $value)
    {
      if ($value['approval_spv_st']==1 && $value['approval_sbh_st']==1) 
      {
        $status = 'Approve';
      }
      else if($value['approval_spv_st']==2 && $value['approval_sbh_st']==2)
      {
        $status = 'Reject';
      }
      else
      {
        $status = 'Waiting For Approval';
      }

      $file = explode('/',$value['file']);

      $url = 'http://36.93.82.10/erp-api-lta/public/storage/return/'.$file[2];

      $lines = ReturnApprovalLines::where('return_approval_kd',$value['kd'])->get();

      $data_lines = [];
      foreach ($lines as $key => $valuex) 
      {
        $data_lines[] = [
          'ItemCode' => $valuex['ItemCode'],
          'ItemName' => $valuex['ItemName'],
          'Quantity' => $valuex['Quantity'],
          'UnitPrice' => $valuex['UnitPrice'],
          'LineTotal' => $valuex['LineTotal'],
          'Keterangan' => $valuex['note']
        ];
      }

      $data[] = [
        'kd' => $value['kd'],
        'date' => $value['date'],
        'CardCode' => $value['CardCode'],
        'CardName' => $value['CardName'],
        'DocTotal' => $value['DocTotal'],
        'status' => $status,
        'file' => $url,
        'Lines' => $data_lines
      ];
    }

    return $data;
  }

  public function detail($kd)
  {
    $data = [];

    $header = ReturnApproval::where('kd',$kd)->first();

    $file = explode('/',$header->file);

    $url = 'http://36.93.82.10/erp-api-lta/public/storage/return/'.$file[2];

    if (isset($header)) 
    {
      $lines = ReturnApprovalLines::where('return_approval_kd',$kd)->get();

      $data_lines = [];
      foreach ($lines as $key => $value) 
      {
        $data_lines[] = [
          'ItemCode' => $value['ItemCode'],
          'ItemName' => $value['ItemName'],
          'Quantity' => $value['Quantity'],
          'UnitPrice' => $value['UnitPrice'],
          'LineTotal' => $value['LineTotal'],
          'Keterangan' => $value['note']
        ];
      }

      $data = [
        'kd' => $header->kd,
        'CardCode' => $header->CardCode,
        'CardName' => $header->CardName,
        'SpvName' => $header->spv_detail->name,
        'SbhName' => $header->sbh_detail->name,
        'SlpName' => $header->SlpName,
        'Total' => rupiah($header->DocTotal),
        'image_url' => $url,
        'spv_st' => $header->approval_spv_st,
        'sbh_st' => $header->approval_sbh_st,
        'Lines' => $data_lines
      ];
    } 
    
    return $data;
  }

  public function approveReturnSpv($kd)
  {
    $get = ReturnApproval::where('kd',$kd)->first();

    $data = [
      'approval_spv_st' => 1
    ];

    $update = ReturnApproval::where('kd',$kd)->update($data);
    
    if ($update) 
    {
      $url = 'approval/return/sbh/'.$kd;
      $site = "https://system.laut-timur.tech/";
      $teks_tengah = $site . $url;
      $teks = "Customer *" . $get->CardName . "* melakukan *PENGAJUAN RETURN*. Klik link dibawah ini untuk melakukan approval :";
      $subject = "Approval Return - Sub Branch Head";
      $spv_name = $get->sbh_detail->name;
      $spv_no = $get->sbh_detail->contact;
      $new_message   = 'Halo ' . ucfirst(strtolower($spv_name)) . ', ' . $teks;

      $this->sendToWa2($teks_tengah, $spv_no, $new_message, $subject);

      $result = [
        'message' => 'sukses'
      ];
    }
    else
    {
      $result = [
        'message' => 'error'
      ];
    }

    return $result;
  }

  public function rejectReturnSpv($kd)
  {
    $data = [
      'approval_spv_st' => 2,
      'approval_sbh_st' => 2
    ];

    $update = ReturnApproval::where('kd',$kd)->update($data);
    
    if ($update) 
    {
      $result = [
        'message' => 'sukses'
      ];
    }
    else
    {
      $result = [
        'message' => 'error'
      ];
    }

    return $result;
  }

  public function approveReturnSbh($kd)
  {
    $data = [
      'approval_sbh_st' => 1
    ];

    $update = ReturnApproval::where('kd',$kd)->update($data);
    
    if ($update) 
    {
      $result = [
        'message' => 'sukses'
      ];
    }
    else
    {
      $result = [
        'message' => 'error'
      ];
    }

    return $result;
  }

  public function rejectReturnSbh($kd)
  {
    $data = [
      'approval_sbh_st' => 2,
      'approval_spv_st' => 2
    ];

    $update = ReturnApproval::where('kd',$kd)->update($data);
    
    if ($update) 
    {
      $result = [
        'message' => 'sukses'
      ];
    }
    else
    {
      $result = [
        'message' => 'error'
      ];
    }

    return $result;
  }

  public function customerDetail($cardCode, $company)
  {
    if ($company==1) 
    {
      $post_cust = [
        'CardCode' => $cardCode
      ];
  
      $customer = callSapApiLtaWithPost('getCustomerId',json_encode($post_cust));
    }
    else
    {
      $post_cust = [
        'CardCode' => $cardCode
      ];
  
      $customer = callSapApiTaaWithPost('getCustomerId',json_encode($post_cust));
    }
    

    $whsCode = Warehouse::where('code',$customer['U_CLASS'])->first();
    
    $result = [
      'CardCode' => $customer['CardCode'],
      'CardName' => $customer['CardName'],
      'Address' => $customer['Address'],
      'U_CLASS' => $customer['U_CLASS'],
      'whsCode' => $whsCode->title
    ];
    
    return $result;
  }

  public function itemDetail($itemCode, $cardCode, $whsCode, $company)
  {
    if ($company==1) 
    {
      $post_uom = [
        'ItemNo' => $itemCode,
        'CardCode' => $cardCode,
        'WhsCode' => $whsCode
      ];
  
      $uom = callSapApiLtaWithPost('getItemUomDetail',json_encode($post_uom));
  
      $post_item = [
        'ItemCode' => $itemCode
      ];
  
      $item = callSapApiLtaWithPost('getItemId',json_encode($post_item));
    }
    else
    {
      $post_uom = [
        'ItemNo' => $itemCode,
        'CardCode' => $cardCode,
        'WhsCode' => $whsCode
      ];
  
      $uom = callSapApiTaaWithPost('getItemUomDetail',json_encode($post_uom));
  
      $post_item = [
        'ItemCode' => $itemCode
      ];
  
      $item = callSapApiTaaWithPost('getItemId',json_encode($post_item));
    }
    
    $result = [
      'ItemName' => $item['ItemName'],
      'harga_jual_pcs' => $uom['harga_jual_pcs'],
      'harga_jual_ktn' => $uom['harga_jual_ktn']
    ];

    return $result;
  }

  public function itemDetail2($data)
  {
    $company = $data['company_id'];

    if ($company==1) 
    {
      $post_cust = [
        'ItemCode' => $data['ItemCode']
      ];
  
      $result = callSapApiLtaWithPost('getItemId',json_encode($post_cust));
    }
    else
    {
      $post_cust = [
        'ItemCode' => $data['ItemCode']
      ];
  
      $result = callSapApiTaaWithPost('getItemId',json_encode($post_cust));
    }

    return $result;
  }

  public function salesPersonDetail($slpCodeSfa, $company)
  {
    if ($company==1) 
    {
      $post = [
        'U_SALESCODE' => $slpCodeSfa
      ];
  
      $sales = callSapApiLtaWithPost('getSalesDetail',json_encode($post));
    }
    else
    {
      $post = [
        'U_SALESCODE' => $slpCodeSfa
      ];
  
      $sales = callSapApiTaaWithPost('getSalesDetail',json_encode($post));
    }
    

    return $sales;
  }

  public function getApproval($slpCode)
  {
    return ReturnApprovalMapping::where('SlpCode',$slpCode)->first();
  }

  function sendToWa2($url, $no_hp, $text, $subject)
  {
    $post = [
      'number' => $no_hp,
      'subject' => $subject,
      'text' => $text,
      'url' => $url
    ];

    $url = 'https://system.erplta.com/api/notification/send';
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,// your preferred link
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_TIMEOUT => 30000,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_POSTFIELDS => json_encode($post),
      CURLOPT_HTTPHEADER => array(
          // Set here requred headers
          "accept: */*",
          "accept-language: en-US,en;q=0.8",
          "content-type: application/json",
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    // $data = [];

    if ($err) 
    {
      $data = [];
    } 
    else 
    {
      $data = json_decode($response,TRUE);
    }

    return $data;
  }

  public function generateNumber($branch, $date, $company)
  {
    $exp_join = explode('-', $date);

    $select_kode = DB::raw('LEFT(return_approvals.number,3) as kode');
    $query = DB::table('return_approvals')
      ->select($select_kode)
      ->whereYear('date', $exp_join[0])
      ->whereMonth('date', $exp_join[1])
      ->where('U_CLASS', $branch)
      ->where('company_id',$company)
      ->orderBy('id', 'DESC')
      ->limit(1);
    $get = $query->get();
    $count = count($get);

    foreach ($get as $get) {
      $data = $get->kode;
    }

    if ($count <> 0) {
      $kode = intval($data) + 1;
    } else {
      $kode = 1;
    }

    $company_code = Company::find($company)->code;

    $blnRomawi = getRomawiBln($exp_join[1]);
    $kode_area = $branch.'-RTR';

    $yy = date('Y', strtotime($date));

    $kodemax = str_pad($kode, 3, "0", STR_PAD_LEFT); 
    $kodejadi = $kodemax.'/'.$company_code.'/'.$kode_area.'/'.$blnRomawi.'/'.$yy;
    return $kodejadi;
  }

  public function customerBySales($data)
  {
    $company = $data['company_id'];

    if ($company==1) 
    {
      $post_cust = [
        'U_SALESCODE' => $data['U_SALESCODE']
      ];
  
      $result = callSapApiLtaWithPost('getCustomerBySales',json_encode($post_cust));
    }
    else
    {
      $post_cust = [
        'U_SALESCODE' => $data['U_SALESCODE']
      ];
  
      $result = callSapApiTaaWithPost('getCustomerBySales',json_encode($post_cust));
    }

    return $result;
  }

  public function customerByBranch($data)
  {
    $company = $data['company_id'];

    if ($company==1) 
    {
      $post_cust = [
        'CardName' => "%".$data['CardName']."%",
        'U_BRANCHCODESFA' => $data['U_BRANCHCODESFA']
      ];
  
      $result = callSapApiLtaWithPost('getCustomerByBranch',json_encode($post_cust));
    }
    else
    {
      $post_cust = [
        'CardName' => "%".$data['CardName']."%",
        'U_BRANCHCODESFA' => $data['U_BRANCHCODESFA']
      ];
  
      $result = callSapApiTaaWithPost('getCustomerByBranch',json_encode($post_cust));
    }

    return $result;
  }

  public function itemSearch($data)
  {
    $company = $data['company_id'];

    if ($company==1) 
    {
      $post_cust = [
        'ItemName' => "%".$data['ItemName']."%"
      ];
  
      $result = callSapApiLtaWithPost('getItemNameNonBonus',json_encode($post_cust));
    }
    else
    {
      $post_cust = [
        'ItemName' => "%".$data['ItemName']."%"
      ];
  
      $result = callSapApiTaaWithPost('getItemNameNonBonus',json_encode($post_cust));
    }

    return $result;
  }
}