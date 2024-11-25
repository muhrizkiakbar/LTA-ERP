<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OmsetDelivery extends Model
{
    protected $table = 'omset_delivery';
    protected $fillable = [
        'Type',
        'DocNum',
        'DocEntry',
        'LineNum',
        'DocDate',
        'BranchCode',
        'OcrCode',
        'CardCode',
        'CardName',
        'Address',
        'City',
        'SupplierName',
        'ItemCode',
        'ItemName',
        'BrandName',
        'Category',
        'Variant',
        'ClassName',
        'Barcode',
        'NISIB',
        'SatuanBesar',
        'SatuanKecil',
        'NW',
        'HargaJual',
        'QtyJual',
        'Konversi',
        'U_DISC1',
        'U_DISC2',
        'U_DISC3',
        'U_DISC4',
        'U_DISC5',
        'U_DISC6',
        'U_DISC7',
        'U_DISC8',
        'U_DISCVALUE',
        'DiscFaktur',
        'SalesCode',
        'SalesName',
        'Supervisor',
        'Bruto',
        'TotalDiscRp',
        'Netto',
        'SKU',
        'SubSegmen',
        'U_CDB',
        'U_CSKU_STAT',
        'U_INITIATIVE1',
        'U_INITIATIVE2',
        'WhsCode',
        'NumAtCard',
        'Keterangan',
        'Hash'
    ];
}
