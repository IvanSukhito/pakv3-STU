<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'tx_kegiatan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'upline_id',
        'ms_kegiatan_id',
        'permen_id',
        'user_jenjang_id',
        'kegiatan_jenjang_id',
        'user_name',
        'upline_name',
        'tanggal',
        'judul',
        'deskripsi',
        'dokument_pendukung',
        'dokument_fisik',
        'kredit_ori',
        'kredit',
        'satuan',
        'status',
        'message',
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
        return $this->belongsTo(SuratPernyataan::class, 'surat_pernyataan_id', 'id');
    }

    public function getDupak() {
        return $this->belongsTo(Dupak::class, 'dupak_id', 'id');
    }

}
