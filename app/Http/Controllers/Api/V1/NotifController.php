<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotifController extends Controller
{
	public function send(Request $request)
	{
		$data = $request->all();
		
		$message = sprintf("%s \n\n%s \n\n%s \n\nTerima Kasih", $data['subject'], $data['text'], $data['url']);

		$post = [
			'api_key' => '8T6OC4DZRIIKSOOF',
			'number_key' => '7CKG6Bx84Ng3GGti',
			'phone_no' => $data['number'],
			'message' => $message
		];

		$send = callWhatsapp2(json_encode($post));

		$callback = [
			'success' => true,
			'message' => 'API WA berhasil di kirim !',
			'status_api' => $send['status'],
			'message_api' => $send['message']
		];

		return response()->json($callback);
	}
}
