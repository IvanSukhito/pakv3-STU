<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class Pak extends Model
{
    protected $table = 'pak';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'owner_id',
        'dupak_id',
        'bapak_id',
        'point1a1_lama',
        'point1a1_baru',
        'point1a1_total',
        'point1a2_lama',
        'point1a2_baru',
        'point1a2_total',
        'point1b_lama',
        'point1b_baru',
        'point1b_total',
        'point1c_lama',
        'point1c_baru',
        'point1c_total',
        'total_point_1_lama',
        'total_point_1_baru',
        'total_point_1',
        'point2_lama',
        'point2_baru',
        'point2_total',
        'total_point_2_lama',
        'total_point_2_baru',
        'total_point_2',
        'total_point_lama',
        'total_point_baru',
        'total_point'
    ];

    public function __get($key)
    {
        if (is_string($this->getAttribute($key))) {
            return strtoupper( $this->getAttribute($key) );
        } else {
            return $this->getAttribute($key);
        }
    }

}
