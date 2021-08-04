<?php

namespace App\Codes\Models\V1;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $table = 'v1_questions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'header_image',
        'date_start',
        'sound',
        'number',
        'number_close',
        'icon'
    ];

    protected $appends = [
        'header_image_full',
        'sound_full',
        'number_full',
        'number_close_full',
        'icon_full'
    ];

    public function getHeaderImageFullAttribute()
    {
        return strlen($this->header_image) > 0 ? asset('uploads/questions/'.$this->header_image) : '';
    }

    public function getSoundFullAttribute()
    {
        return strlen($this->sound) > 0 ? asset('uploads/questions/'.$this->sound) : '';
    }

    public function getNumberFullAttribute()
    {
        return strlen($this->number) > 0 ? asset('uploads/questions/'.$this->number) : '';
    }

    public function getNumberCloseFullAttribute()
    {
        return strlen($this->number_close) > 0 ? asset('uploads/questions/'.$this->number_close) : '';
    }

    public function getIconFullAttribute()
    {
        return strlen($this->icon) > 0 ? asset('uploads/questions/'.$this->icon) : '';
    }

    public function getQuestionDetails()
    {
        return $this->hasMany(QuestionDetails::class, 'question_id', 'id');
    }

}
