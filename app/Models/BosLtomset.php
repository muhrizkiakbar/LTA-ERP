<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BosLtomset extends Model
{
    protected $table = 'bos_ltomset';
    protected $fillable = [
        'DOCENTRY',
        'LINENUM',
        'TGL_JUAL',
        'PERIODE',
        'CABANG',
        'NO_FAKTUR',
        'CKET',
        'CUST_CODE',
        'CUST_NAME',
        'PASAR',
        'KOTA',
        'SUPP_NAME',
        'BRAND_NAME',
        'CATEGORY_N',
        'VARIANT_NA',
        'CLASS_NAME',
        'ITEM_CODE',
        'CODE_BARCODE',
        'ITEM_NAME',
        'ISI_BESAR',
        'SATUAN_BES',
        'SATUAN_KEC',
        'NW',
        'HARGA_JUAL',
        'QTY_JUAL',
        'KONVERSI',
        'DISC_BRG1',
        'DISC_BRG2',
        'DISC_BRG3',
        'DISC_BRG4',
        'DISC_BRG5',
        'DISC_BRG6',
        'DISC_BRG7',
        'DISC_BRG8',
        'VALUE_DISC',
        'DISC_FAKTU',
        'SALES_CODE',
        'SALES_NAME',
        'SUPERVISOR',
        'BRUTTO',
        'TOT_DISCRP',
        'NETTO',
        'PPN',
        'SKU',
        'SUB_SEGMEN',
        'NAMA_SBD',
        'STATUS_SKU',
        'KODE_INI1',
        'KODE_INI2',
        'KETERANGAN',
        'CGUDANG',
        'CUSTREF',
        'GROUP'
    ];
}
