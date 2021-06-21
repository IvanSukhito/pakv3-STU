<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class UserRegister extends Model
{
    protected $table = 'user_register';
    protected $primaryKey = 'id';
    protected $guarded = ['id', 'created_at', 'updated_at'];



//    public function getStaffs() {
//        return $this->hasOne(Staffs::class, 'user_id', 'id');
//    }

}

