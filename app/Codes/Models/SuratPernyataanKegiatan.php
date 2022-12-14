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
        'message',
        'status'
    ];

}
