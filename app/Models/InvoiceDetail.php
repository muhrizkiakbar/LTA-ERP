<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $table = 'invoice_detail';
    protected $fillable = [
        'doc_entry',
        'line_num',
        'kode_branch',
        'kode_sls_rep',
        'no_invoice',
        'tgl_invoice',
        'kode_retailer',
        'kode_item',
        'jumlah',
        'nilai',
        'nilai_tagihan',
        'order_ref_no'
    ];
}
