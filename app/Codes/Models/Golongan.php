<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    protected $table = 'golongan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'status'
    ];

}
