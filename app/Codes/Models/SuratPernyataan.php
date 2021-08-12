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
        'supervisor_id',
        'dupak_id',
        'tanggal',
        'nomor',
        'tanggal_mulai',
        'tanggal_akhir',
        'pdf',
        'pdf_url',
        'info_surat_pernyataan',
        'status',
        'approved',
        'connect',
        'total_kredit',
        'lokasi',
        'alasan_menolak'
    ];

    public function getUser() {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function getUpline() {
        return $this->belongsTo(Users::class, 'upline_id', 'id');
    }

}
