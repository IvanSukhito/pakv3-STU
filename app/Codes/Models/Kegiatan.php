<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'parent_id',
        'ms_kegiatan_id',
        'permen_id',
        'tanggal',
        'judul',
        'deskripsi',
        'dokument',
        'dokumen_fisik',
        'kredit_lama',
        'kredit',
        'satuan',
        'pelaksana',
        'pelaksana_id',
        'sp',
        'dupak',
        'approved',
        'connect'
    ];

    public function getUser() {
        return $this->belongsTo(Users::class, 'id', 'user_id');
    }

    public function getMsKegiatan() {
        return $this->belongsTo(MsKegiatan::class, 'ms_kegiatan_id', 'id');
    }

    public function getParentMsKegiatan() {
        return $this->belongsTo(MsKegiatan::class, 'parent_id', 'id');
    }

    public function getSuratPernyataan() {
        return $this->belongsTo(SuratPernyataan::class, 'sp', 'id');
    }

    public function getDupak() {
        return $this->belongsTo(Dupak::class, 'dupak_id', 'id');
    }

}
