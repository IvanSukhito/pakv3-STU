<?php

namespace App\Codes\Models\V1;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'v1_users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'phone',
        'instagram',
        'no_ktp',
        'date',
        'city',
        'gender',
        'question_progress',
        'answer_progress',
        'activation_code',
        'status'
    ];

    protected $appends = [
        'image_full'
    ];

    public function getImageFullAttribute()
    {
        return strlen($this->image) > 0 ? asset('uploads/users/'.$this->image) : asset('assets/html/images/register.svg');
    }

}
