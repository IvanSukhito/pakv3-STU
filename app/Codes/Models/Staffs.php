<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class Staffs extends Model
{
    protected $table = 'user_staffs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'user_register_id',
        'top',
        'staff_id',
        'sk_sekretariat',
        'sk_tim_penilai',
        'jabatan_perancang_id',
        'golongan_id',
        'tmt_golongan',
        'pendidikan_id',
        'unit_kerja_id',
        'jenjang_perancang_id',
        'gender_id',
        'name',
        'address',
        'birthdate',
        'kartu_pegawai',
        'pob',
        'nomor_pak',
        'tanggal',
        'tahun_pelaksaan_diklat',
        'tahun_diangkat',
        'angka_kredit',
        'masa_penilaian_terkahir_start',
        'masa_penilaian_terkahir_end',
        'mk_golongan_baru_bulan',
        'mk_golongan_baru_tahun',
        'mk_golongan_lama_bulan',
        'mk_golongan_lama_tahun',
        'status_diangkat',
        'perancang',
        'atasan',
        'sekretariat',
        'tim_penilai',
        'register_id',
        'kenaikan_jenjang_terakhir',
        'status',
        'file_sk_pengangkatan_perancang',
        'sk_sekretariat',
        'sk_tim_penilai',
        'file_sk_pegangkatan_terakhir',
        'file_kartu_pegawai',
        'file_seluruh_pak',
        'file_ijazah',
        'file_sttpl',
        'unit',
        'sekertariat_id',
        'penilai_id',
    ];


    public function getGender()
    {
        return $this->belongsTo(Gender::class, 'gender_id', 'id');
    }

    public function getJabatanPerancang()
    {
        return $this->belongsTo(JabatanPerancang::class, 'jabatan_perancang_id', 'id');
    }

    public function getJenjangPerancang()
    {
        return $this->belongsTo(JenjangPerancang::class, 'jenjang_perancang_id', 'id');
    }

    public function getGolongan()
    {
        return $this->belongsTo(Golongan::class, 'golongan_id', 'id');
    }

    public function getPendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_id', 'id');
    }

    public function getUnitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id', 'id');
    }

    public function getUsers() {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function getAtasan() {
        return $this->belongsTo(Staffs::class, 'staff_id', 'id');
    }

    public function getBawahan() {
        return $this->hasMany(Staffs::class, 'staff_id', 'id');
    }

}
