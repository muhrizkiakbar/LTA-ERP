<?php

use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyApi;
use App\Models\Uom;

function callApiWithPost($url,$post)
{
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,// your preferred link
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_TIMEOUT => 30000,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_POSTFIELDS => $post,
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

function callCompanyApiWithPost($company,$function,$post)
{
	$company_api = CompanyApi::where('company_id',$company)
													 ->where('title',$function)
													 ->first();

	$url = $company_api->url;

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,// your preferred link
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_TIMEOUT => 30000,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_POSTFIELDS => $post,
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


function callApiWithoutPost($url)
{
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,// your preferred link
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_TIMEOUT => 30000,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
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

function callCompanyApiWithoutPost($company,$function)
{
	$company_api = CompanyApi::where('company_id',$company)
													 ->where('title',$function)
													 ->first();

	$url = $company_api->url;
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,// your preferred link
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_TIMEOUT => 30000,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
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

function rupiah($angka) {
  $hasil = 'IDR ' . number_format($angka, 2, ",", ".");
  return $hasil;
}

function rupiahnon($angka) {
  $hasil = number_format($angka, 0, ",", ".");
  return $hasil;
}

function rupiahnon2($angka) {
  $hasil = number_format($angka, 2, ",", ".");
  return $hasil;
}

function rupiahnon3($angka) {
  $hasil = number_format($angka, 2, ".", "");
  return $hasil;
}

function penyebut($nilai) {
  $nilai = abs($nilai);
  $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  $temp = "";
  if ($nilai < 12) {
    $temp = " ". $huruf[$nilai];
  } else if ($nilai <20) {
    $temp = penyebut($nilai - 10). " belas";
  } else if ($nilai < 100) {
    $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
  } else if ($nilai < 200) {
    $temp = " seratus" . penyebut($nilai - 100);
  } else if ($nilai < 1000) {
    $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
  } else if ($nilai < 2000) {
    $temp = " seribu" . penyebut($nilai - 1000);
  } else if ($nilai < 1000000) {
    $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
  } else if ($nilai < 1000000000) {
    $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
  } else if ($nilai < 1000000000000) {
    $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
  } else if ($nilai < 1000000000000000) {
    $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
  }     
  return $temp;
}

function getBranch($id)
{
  return Branch::find($id);
}

function getCompany($id)
{
  return Company::find($id);
}

function getUomEntry($code,$company)
{
  $data = Uom::where('UomCode',$code)
             ->where('company_id',$company)
             ->first();

  return $data->UomEntry;
}

function callWhatsapp($post)
{
	$url = 'http://139.180.217.180:3005/api/send-message';
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,// your preferred link
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_TIMEOUT => 30000,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_POSTFIELDS => $post,
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

function callWhatsapp2($post)
{
	$url = 'https://api.watzap.id/v1/send_message';
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,// your preferred link
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_TIMEOUT => 30000,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_POSTFIELDS => $post,
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