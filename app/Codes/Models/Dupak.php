<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class Dupak extends Model
{
    protected $table = 'dupak';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'verifikasi_sekretariat_id',
        'verifikasi_tim_penilai_id',
        'jabatan_pengusul',
        'jabatan_pengusul_nip',
        'penilaian_tanggal',
        'lampiran',
        'lokasi_tanggal',
        'tanggal',
        'nomor',
        'pdf',
        'pdf_url',
        'pdf_preview',
        'pdf_preview_url',
        'connect',
        'kredit_lama',
        'kredit_baru',
        'kredit_total',
        'approved',
        'file_sp',
        'file_dupak',
        'status_upload',
    ];

    public function getSuratPernyataan()
    {
        return $this->hasMany(SuratPernyataan::class, 'dupak_id', 'id');
    }

    public function getKegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'dupak_id', 'id');
    }

    public function getUser()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id')->select('id', 'name');
    }

}
