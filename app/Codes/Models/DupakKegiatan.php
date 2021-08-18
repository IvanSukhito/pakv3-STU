<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class DupakKegiatan extends Model
{
    protected $table = 'tx_dupak_kegiatan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'dupak_id',
        'kegiatan_id',
        'ms_kegiatan_id',
        'message',
        'status'
    ];

}
