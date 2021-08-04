<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class Permen extends Model
{
    protected $table = 'permen';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'tanggal_start',
        'tanggal_end',
        'status'
    ];

    public function getMsKegiatan()
    {
        return $this->hasMany(MsKegiatan::class, 'permen_id', 'id');
    }

    public static function boot() {
        parent::boot();
        static::deleting(function($permen) {
            $permen->getMsKegiatan()->delete();
        });
    }



}
