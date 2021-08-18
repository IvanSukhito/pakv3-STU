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
        'verifikasi_sekretariat_id',
        'verifikasi_tim_penilai_id',
        'surat_pernyataan_id',
        'surat_pernyataan',
        'jabatan_pengusul',
        'jabatan_pengusul_nip',
        'penilaian_tanggal',
        'lampiran',
        'lokasi_tanggal',
        'nomor',
        'pdf',
        'pdf_url',
        'file_sp',
        'file_dupak',
        'status_upload',
        'sent_status',
        'pdf_preview',
        'pdf_preview_url',
        'kredit_lama',
        'kredit_baru',
        'kredit_total',
        'tanggal',
    ];

}
