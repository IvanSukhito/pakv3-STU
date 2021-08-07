<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class JabatanPerancang extends Model
{
    protected $table = 'jabatan_perancang';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'status'
    ];

}
