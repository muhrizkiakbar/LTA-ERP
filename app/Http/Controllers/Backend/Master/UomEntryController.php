<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Uom;
use App\Services\Master\UomEntryServices;
use Illuminate\Http\Request;

class UomEntryController extends Controller
{
  public function __construct(UomEntryServices $services)
  {
    $this->service = $services;
  }

  public function index()
  {
    $assets = [
      'style' => array(
        'assets/css/loading.css'
      ),
      'script' => array(
        'assets/js/plugins/notifications/sweet_alert.min.js',
        'assets/js/plugins/forms/selects/select2.min.js',
        'assets/js/plugins/tables/datatables/datatables.min.js',
      )
    ];

    $row = $this->service->getData();

    $company = Company::pluck('title','id');

    $data = [
      'title' => 'Uom Entry',
      'assets' => $assets,
      'row' => $row,
      'company' => $company
    ];

    return view('backend.master.uom_entry.index')->with($data);
  }

  public function sync(Request $request)
  {
    $company = $request->company_id;

    $post = [
      'Company' => $company
    ];

    $url = 'http://36.93.82.10/erp-api-lta/api/getUomEntryAll';
    $data = callApiWithPost($url, json_encode($post));

    $no = 0;
    $nox = 0;

    if (!empty($data)) 
    {
      foreach($data as $item)
      {
        $cek = Uom::where('UomEntry',$item['UomEntry'])
                  ->where('company_id',$company)
                  ->count();

        if($cek==0)
        {
          $data_uom = [
            'company_id' => $company,
            'UomEntry' => $item['UomEntry'],
            'UomCode' => $item['UomCode']
          ];
  
          Uom::create($data_uom);

          $nox = $no++;
        }
      }
    }

    if($nox > 0)
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
}
