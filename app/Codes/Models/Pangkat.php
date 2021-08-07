<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class Pangkat extends Model
{
    protected $table = 'pangkat';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'status'
    ];

}
