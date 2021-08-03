<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class JenjangPerancang extends Model
{
    protected $table = 'jenjang_perancang';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'status',
        'order_high'
    ];

}
