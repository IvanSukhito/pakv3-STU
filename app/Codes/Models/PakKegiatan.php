<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class PakKegiatan extends Model
{
    protected $table = 'tx_pak_kegiatan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'pak_id',
        'kegiatan_id',
        'top_kegiatan_id',
        'ms_kegiatan_id',
        'message',
        'kredit_ori',
        'kredit_old',
        'kredit_new',
        'status'
    ];

}
