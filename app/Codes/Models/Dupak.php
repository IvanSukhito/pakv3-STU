<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class Dupak extends Model
{
    protected $table = 'tx_dupak';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'upline_id',
        'sekretariat_id',
        'unit_kerja_id',
        'info_dupak',
        'tanggal',
        'nomor',
        'tanggal_mulai',
        'tanggal_akhir',
        'pdf',
        'pdf_url',
        'file_upload_surat_pernyataan',
        'total_kredit',
        'status',
        'alasan_menolak'
    ];

}
