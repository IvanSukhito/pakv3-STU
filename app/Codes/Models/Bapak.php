<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class Bapak extends Model
{
    protected $table = 'bapak';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'owner_id',
        'dupak_id',
        'ketua_id',
        'wakil_ketua_id',
        'sekretariat_id',
        'berita_acara',
        'pdf',
        'pdf_url',
        'pak_pdf',
        'pak_pdf_url',
        'unsur_utama',
        'unsur_penunjang'
    ];

    function getKetua() {
        return $this->belongsTo(Users::class, 'ketua_id', 'id');
    }

    function getWakilKetua() {
        return $this->belongsTo(Users::class, 'wakil_ketua_id', 'id');
    }

    function getSekretariat() {
        return $this->belongsTo(Users::class, 'sekretariat_id', 'id');
    }

    function getDupak() {
        return $this->belongsTo(Dupak::class, 'dupak_id', 'id');
    }

    function getAnggota() {
        return $this->belongsToMany(Users::class, 'bapak_anggota', 'bapak_id', 'user_id');
    }

}
