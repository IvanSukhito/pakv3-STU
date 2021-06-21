<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPernyataanInfo extends Model
{
    protected $table = 'surat_keterangan_info';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'ms_kegiatan_id',
        'content1',
        'content2',
        'content3',
        'content4'
    ];

}
