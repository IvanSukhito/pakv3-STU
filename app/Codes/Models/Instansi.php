<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    protected $table = 'instansi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'status'
    ];

}
