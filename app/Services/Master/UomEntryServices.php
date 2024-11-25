<?php

namespace App\Services\Master;

use App\Models\Uom;

class UomEntryServices 
{
  public function getData()
  {
    $data = [];

    $get = Uom::get();

    foreach($get as $item)
    {
      $data[] = [
        'company' => getCompany($item->company_id)->title,
        'UomEntry' => $item->UomEntry,
        'UomCode' => $item->UomCode
      ];
    }

    return $data;
  }
}