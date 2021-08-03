<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    protected $table = 'unit_kerja';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'permen_id',
        'parent_id',
        'ak',
        'satuan',
        'jenjang_perancang_id',
        'status'
    ];

}
