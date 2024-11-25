<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserCollector;
use App\Models\UserSales;

class UserServices 
{
  public function getData()
  {
    $data = [];

    $role = auth()->user()->users_role_id;
    $branch = auth()->user()->branch_sap;

    if ($role==1) 
    {
      $get = User::orderBy('id','DESC')->get();
    }
    else
    {
      $get = User::where('branch_sap',$branch)
                 ->whereIn('users_role_id',['4','5'])
                 ->orderBy('id','DESC')->get();
    }

    foreach ($get as $key => $value) 
    {
      if ($value->users_role_id==4) 
      {
        $role = $value->role->title.' ('.$this->getCategoryCollector($value->id).')';
      }
      else
      {
        $role = $value->role->title;
      }

      $data[] = [
        'id' => $value->id,
        'nama' => $value->name,
        'username' => $value->username,
        'branch' => isset($value->branch) ? $value->branch->title : '',
        'role' => $role,
        'role_id' => $value->users_role_id
      ];
    }

    return $data;
  }

  public function getDataSalesById($id)
  {
    $data = [];

    $get = UserSales::where('users_id',$id)->get();

    foreach($get as $value)
    {
      $data[] = [
        'id' => $value->id,
        'company' => isset($value->company) ? $value->company->title : '',
        'SalesPersonName' => $value->SalesPersonName
      ];
    }

    return $data;
  }

  public function getCategoryCollector($id)
  {
    $get = UserCollector::where('users_id',$id)->first();

    if (!empty($get)) 
    {
      $return = $get->collector->title;
    } 
    else 
    {
      $return = '';
    }
    
    return $return;
  }
}