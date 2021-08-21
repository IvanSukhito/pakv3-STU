<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class Pak extends Model
{
    protected $table = 'tx_pak';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'upline_id',
        'top_kegiatan_id',
        'tim_penilai_id',
        'unit_kerja_id',
        'dupak_id',
        'info_pak',
        'tanggal',
        'nomor',
        'pdf',
        'pdf_url',
        'file_upload_dupak',
        'total_kredit',
        'status',
        'alasan_menolak'
    ];

}
