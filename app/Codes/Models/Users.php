<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'username',
        'password',
        'email',
        'name',
        'last_login',
        'status',
    ];

    protected $hidden = ['password'];

    public function getStaffs() {
        return $this->hasOne(Staffs::class, 'user_id', 'id');
    }

    public function getDupak() {
        return $this->hasMany(Dupak::class, 'user_id',  'id');
    }

}
