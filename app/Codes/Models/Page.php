<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Page extends Model
{
    protected $table = 'page';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'key',
        'header_image',
        'content',
        'additional',
        'type'
    ];

    protected $appends = [
        'header_image_full'
    ];

    public function getHeaderImageFullAttribute()
    {
        return asset('uploads/page/'.$this->header_image);
    }

    public static function boot()
    {
        parent::boot();

        self::updated(function($model){
            Cache::forget('page_'.$model->key);
        });

        self::deleting(function($model){
            Cache::forget('page_'.$model->key);
        });
    }

}
