<?php

namespace App\Codes\Models\V1;

use Illuminate\Database\Eloquent\Model;

class Mantra extends Model
{
    protected $table = 'v1_mantra';
    protected $primaryKey = 'id';
    protected $fillable = [
        'mantra'
    ];

    protected $appends = [
        'mantra_full'
    ];

    public function getMantraFullAttribute()
    {
        return strlen($this->mantra) > 0 ? asset('uploads/mantra/'.$this->mantra) : '';
    }

}
