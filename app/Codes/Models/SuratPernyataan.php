<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPernyataan extends Model
{
    protected $table = 'tx_surat_pernyataan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'upline_id',
        'top_kegiatan_id',
        'tanggal',
        'nomor',
        'tanggal_mulai',
        'tanggal_akhir',
        'pdf',
        'pdf_url',
        'info_surat_pernyataan',
        'status',
        'total_kredit',
        'alasan_menolak'
    ];

}
