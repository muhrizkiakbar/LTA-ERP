<?php

namespace App\Services\App;

class ReturnServices 
{
	public function return()
	{
		// URL Service Layer di SAP
		$url = 'https://<SAP_Server>:<port>/b1s/v1/Returns';

		$request = new Request('POST', $url, [
				'Content-Type' => 'application/json',
				'Authorization' => 'Basic <Base64-encoded-username-and-password>'
		]);

		// Data untuk membuat Return Delivery
		$data = [
				"CardCode" => "C20000", // Kode pelanggan
				"DocDueDate" => "2022-01-31", // Tanggal jatuh tempo dokumen
				"DeliveryRefNumber" => 123, // Nomor referensi dokumen delivery
				"DocumentLines" => [
						[
								"ItemCode" => "A00001", // Kode item
								"Quantity" => 10, // Jumlah barang yang diretur
								"WarehouseCode" => "WH01" // Kode gudang
						],
						[
								"ItemCode" => "B00001",
								"Quantity" => 5,
								"WarehouseCode" => "WH01"
						]
				]
		];

		// Menambahkan data ke permintaan
		$request->getBody()->write(json_encode($data));

		try {
				// Mengirim permintaan ke SAP Service Layer
				$response = $client->send($request);
				
				// Mendapatkan kode status dan respons SAP
				$statusCode = $response->getStatusCode();
				$body = $response->getBody()->getContents();
				
				// Menampilkan respons dari SAP
				echo "Return Delivery berhasil dibuat. Response: " . $body;
		} catch (RequestException $e) {
				// Menampilkan pesan error jika terjadi kesalahan dalam pengiriman permintaan
				echo "Terjadi kesalahan: " . $e->getMessage();
		}
	}
}