<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPernyataan extends Model
{
    protected $table = 'surat_pernyataan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'parent_id',
        'supervisor_id',
        'dupak_id',
        'tanggal',
        'nomor',
        'tanggal_mulai',
        'tanggal_akhir',
        'pdf',
        'pdf_url',
        'connect',
        'total_kredit_lama',
        'total_kredit',
        'total_kredit_baru',
        'lokasi',
        'alasan_menolak'
    ];

    public function getMasterKegiatan() {
        return $this->belongsTo(MsKegiatan::class, 'parent_id', 'id');
    }

    public function getKegiatan() {
        return $this->hasMany(Kegiatan::class, 'sp', 'id');
    }

    public function getUser() {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function getSupervisor() {
        return $this->belongsTo(Users::class, 'supervisor_id', 'id');
    }

    public function getDupak()
    {
        return $this->belongsToMany(Dupak::class, 'dupak_surat_pernyataan', 'dupak_id', 'surat_pernyataan_id');
    }

    public function getDupakOne() {
        return $this->belongsTo(Users::class, 'dupak_id', 'id');
    }

}
