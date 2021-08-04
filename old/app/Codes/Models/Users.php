<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'upline_id',
        'pangkat_id',
        'golongan_id',
        'jenjang_perancang_id',
        'pendidikan_id',
        'unit_kerja_id',
        'name',
        'username',
        'password',
        'email',
        'kartu_pegawai',
        'alamat_kantor',
        'jenis_kelamin',
        'tempat_lahir',
        'tmt_kenaikan_jenjang_terakhir',
        'kenaikan_jenjang_terakhir',
        'masa_penilaian_terakhir_awal',
        'masa_penilaian_terakhir_akhir',
        'tahun_diangkat',
        'tahun_pelaksanaan_diklat',
        'tanggal_pak_terakhir',
        'alasan',
        'angka_kredit_terakhir',
        'nomor_pak_terakhir',
        'role_id',
        'last_login',
        'calon_perancang',
        'perancang',
        'atasan',
        'sekretariat',
        'tim_penilai',
        'status',
    ];

    protected $hidden = ['password'];


}
