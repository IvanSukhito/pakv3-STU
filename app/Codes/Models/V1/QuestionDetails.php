<?php

namespace App\Codes\Models\V1;

use Illuminate\Database\Eloquent\Model;

class QuestionDetails extends Model
{
    protected $table = 'v1_question_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'question_id',
        'question',
        'hint_header',
        'hint_image',
        'hint_link',
        'question_sound',
        'orders'
    ];

    protected $appends = [
        'hint_header_full',
        'hint_image_full',
        'question_sound_full'
    ];

    public function getHintHeaderFullAttribute()
    {
        return strlen($this->hint_header) > 0 ? asset('uploads/questions/'.$this->hint_header) : '';
    }

    public function getHintImageFullAttribute()
    {
        $getData = json_decode($this->hint_image, true);
        if ($getData) {
            $temp = [];
            foreach ($getData as $list) {
                $temp[] = asset('uploads/questions/'.$list);
            }
            return $temp;
        }
        return [];
    }

    public function getQuestionSoundFullAttribute()
    {
        return strlen($this->question_sound) > 0 ? asset('uploads/questions/'.$this->question_sound) : '';
    }

    public function getQuestion()
    {
        return $this->belongsTo(Questions::class, 'question_id', 'id');
    }

}
