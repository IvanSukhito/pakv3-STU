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
        'jabatan_perancang_id',
        'pendidikan_id',
        'instansi_id',
        'unit_kerja_id',
        'name',
        'username',
        'password',
        'email',
        'gelar_akademik',
        'kartu_pegawai',
        'tempat_lahir',
        'tgl_lahir',
        'gender',
        'alamat_kantor',
        'tmt_pangkat',
        'tmt_jabatan',
        'masa_penilaian_terakhir_awal',
        'masa_penilaian_terakhir_akhir',
        'tahun_diangkat_jabatan',
        'tahun_berakhir_jabatan',
        'tahun_pelaksanaan_diklat',
        'alasan',
        'angka_kredit_terakhir',
        'role_id',
        'last_login',
        'calon_perancang',
        'perancang',
        'atasan',
        'sekretariat',
        'tim_penilai',
        'progress',
        'status',
    ];

    protected $hidden = ['password'];

    public function getRole()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

}
