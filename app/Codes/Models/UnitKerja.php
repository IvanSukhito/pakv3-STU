<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    protected $table = 'unit_kerja';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'status'
    ];

}
