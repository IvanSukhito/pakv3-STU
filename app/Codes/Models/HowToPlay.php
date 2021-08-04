<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class HowToPlay extends Model
{
    protected $table = 'how_to_play';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'icon',
        'content',
        'orders'
    ];

    protected $appends = [
        'icon_full'
    ];

    public function getIconFullAttribute()
    {
        return asset('uploads/how_to_play/'.$this->icon);
    }

    public static function boot()
    {
        parent::boot();

        self::updated(function($model){
            Cache::forget('how_to_play');
        });

        self::deleting(function($model){
            Cache::forget('how_to_play');
        });
    }

}
