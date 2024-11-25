<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
	public function spv($kd)
	{
		$post = [
			'kd' => $kd
		];

		$url = 'http://36.93.82.10/erp-api-lta/api/getReturnDetail';
		$row = callApiWithPost($url, json_encode($post));

		$data = [
			'title' => 'Return Approval - Supervisor',
			'row' => $row,
			'kd' => $kd
		];

		return view('approval.return.approval_spv')->with($data);
	}

	public function spv_approve($kd)
	{
		$post = [
			'kd' => $kd
		];

		$url = 'http://36.93.82.10/erp-api-lta/api/approveReturnSpv';
		$row = callApiWithPost($url, json_encode($post));

		// dd($row);

		if ($row['message']=='sukses') 
		{
			$alert = array(
				'type' => 'success',
				'message' => 'Sukses, pengajuan berhasil di approve !'
			);
		}

		return redirect()->back()->with($alert);
	}

	public function spv_reject($kd)
	{
		$post = [
			'kd' => $kd
		];

		$url = 'http://36.93.82.10/erp-api-lta/api/rejectReturnSpv';
		$row = callApiWithPost($url, json_encode($post));

		if ($row['message']=='sukses') 
		{
			$alert = array(
				'type' => 'success',
				'message' => 'Sukses, pengajuan berhasil di reject !'
			);
		}

		return redirect()->back()->with($alert);
	}

	public function sbh($kd)
	{
		$post = [
			'kd' => $kd
		];

		$url = 'http://36.93.82.10/erp-api-lta/api/getReturnDetail';
		$row = callApiWithPost($url, json_encode($post));

		$data = [
			'title' => 'Return Approval - Sub Branch Head',
			'row' => $row,
			'kd' => $kd
		];

		return view('approval.return.approval_sbh')->with($data);
	}

	public function sbh_approve($kd)
	{
		$post = [
			'kd' => $kd
		];

		$url = 'http://36.93.82.10/erp-api-lta/api/approveReturnSbh';
		$row = callApiWithPost($url, json_encode($post));

		if ($row['message']=='sukses') 
		{
			$alert = array(
				'type' => 'success',
				'message' => 'Sukses, pengajuan berhasil di approve !'
			);
		}

		return redirect()->back()->with($alert);
	}

	public function sbh_reject($kd)
	{
		$post = [
			'kd' => $kd
		];

		$url = 'http://36.93.82.10/erp-api-lta/api/rejectReturnSbh';
		$row = callApiWithPost($url, json_encode($post));

		if ($row['message']=='sukses') 
		{
			$alert = array(
				'type' => 'success',
				'message' => 'Sukses, pengajuan berhasil di reject !'
			);
		}

		return redirect()->back()->with($alert);
	}


}
