<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class SeluruhPak extends Model
{
    protected $table = 'seluruh_pak';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'file',
    ];


}
