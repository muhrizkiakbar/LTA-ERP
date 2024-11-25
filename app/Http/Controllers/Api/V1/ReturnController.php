<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ReturnServices;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
  public function __construct(ReturnServices $service)
  {
    $this->service = $service;
  }

  public function post(Request $request)
  {
    $path = '';

    if ($request->hasFile("image")) {
      $image = $request->file('image');
      $path = $image->storeAs("public/return", sprintf("%sRETURN.%s", time(), $image->getClientOriginalExtension()));
    }

    $post = $this->service->post($request->all(), $path);

    if ($post['message']=='sukses') 
    {
      
      return response()->json([
        'success' => true,
        'data' => 'Return save !!!'
      ]);
    }
    else
    {
      return response()->json([
        'success' => false
      ]);
    }
  }

  public function list(Request $request)
  {
    $data = $request->all();

    $get = $this->service->list($data['sales']);

    $model = $get;

    return response()->json($model);
  }

  public function listByCustomer(Request $request)
  {
    $data = $request->all();

    $get = $this->service->listByCustomer($data['CardCode']);

    $model = $get;

    return response()->json($model);
  }

  public function detail(Request $request)
  {
    $data = $request->all();

    $get = $this->service->detail($data['kd']);

    $model = $get;

    return response()->json($model);
  }

  public function customerBySales(Request $request)
  {
    $data = $request->all();

    $get = $this->service->customerBySales($data);

    if (!empty($get)) 
    {
      return response()->json($get);
    }
    else
    {
      return response()->json([]);
    }
  }

  public function customerByBranch(Request $request)
  {
    $data = $request->all();

    // dd($data);

    $get = $this->service->customerByBranch($data);

    // dd($get);

    if (!empty($get)) 
    {
      return response()->json($get);
    }
    else
    {
      return response()->json([]);
    }
  }


  function customerDetail(Request $request)
  {
    $data = $request->all();

    $get = $this->service->customerDetail($data['CardCode'], $data['company_id']);

    return response()->json([
      'success' => true,
      'data' => $get
    ]);
  }


  public function itemSearch(Request $request)
  {
    $data = $request->all();

    $get = $this->service->itemSearch($data);

    return response()->json([
      'success' => true,
      'data' => $get
    ]);
  }

  public function itemDetail(Request $request)
  {
    $data = $request->all();

    $get = $this->service->itemDetail2($data);

    return response()->json([
      'success' => true,
      'data' => $get
    ]);
  }
}