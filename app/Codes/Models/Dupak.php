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
        'top_kegiatan_id',
        'sekretariat_id',
        'unit_kerja_id',
        'surat_pernyataan_id',
        'info_dupak',
        'tanggal',
        'nomor',
        'pdf',
        'pdf_url',
        'file_upload_surat_pernyataan',
        'total_kredit',
        'status',
        'alasan_menolak'
    ];

}
