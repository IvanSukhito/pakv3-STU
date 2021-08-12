<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPernyataanKegiatan extends Model
{
    protected $table = 'tx_surat_pernyataan_kegiatan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'surat_pernyataan_id',
        'kegiatan_id',
        'ms_kegiatan_id',
        'status'
    ];

    public function getMasterKegiatan() {
        return $this->belongsTo(MsKegiatan::class, 'ms_kegiatan_id', 'id');
    }

    public function getKegiatan() {
        return $this->hasMany(Kegiatan::class, 'kegiatan_id', 'id');
    }

    public function getSuratPernyataan() {
        return $this->belongsTo(SuratPernyataan::class, 'surat_pernyataan_id', 'id');
    }

}
