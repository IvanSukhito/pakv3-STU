<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class JabatanPengusul extends Model
{
    protected $table = 'jabatan_pengusul';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'status'
    ];

}
