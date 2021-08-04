<?php

namespace App\Codes\Models\V1;

use Illuminate\Database\Eloquent\Model;

class GameDetails extends Model
{
    protected $table = 'v1_game_questions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'game_id',
        'question_detail_id',
        'answer',
        'answer_link',
        'score_detail',
        'status_game_detail'
    ];

    public function getGame()
    {
        return $this->belongsTo(Games::class, 'game_id', 'id');
    }

}
