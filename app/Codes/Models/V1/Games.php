<?php

namespace App\Codes\Models\V1;

use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    protected $table = 'v1_games';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'question_id',
        'question_count',
        'finish',
        'score',
        'status_game'
    ];

    public function getGameDetails()
    {
        return $this->hasMany(GameDetails::class, 'game_id', 'id');
    }

}
