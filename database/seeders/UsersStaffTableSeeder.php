<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersStaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_staffs')->delete();

        DB::table('user_staffs')->insert(
            array(
                array(
                    'name' => 'Jabatan Tertinggi',
                    'user_id' => 2,
                    'top' => 1,
                    'staff_id' => 0,
                    'jabatan_perancang_id' => 17,
                    'golongan_id' => 1,
                    'pendidikan_id' => 8,
                    'unit_kerja_id' => 1,
                    'jenjang_perancang_id' => 5,
                    'gender_id' => 1,
                    'address' => 'Testing',
                    'birthdate' => date('Y-m-d'),
                    'kartu_pegawai' => '12732137897123',
                    'pob' => 'Jakarta',
                    'masa_penilaian' => 'abcd',
                    'nomor_pak' => '12345',
                    'tanggal' => date('Y-m-d'),
                    'tahun_diangkat' => date('Y-m-d'),
                    'angka_kredit' => '123273',
                    'masa_penilaian_terkahir' => 'asdj asd',
                    'perancang' => 0,
                    'atasan' => 0,
                    'sekretariat' => 0,
                    'tim_penilai' => 0
                ),
                array(
                    'name' => 'Atasan',
                    'user_id' => 3,
                    'top' => 0,
                    'staff_id' => 0,
                    'jabatan_perancang_id' => 17,
                    'golongan_id' => 1,
                    'pendidikan_id' => 8,
                    'unit_kerja_id' => 1,
                    'jenjang_perancang_id' => 1,
                    'gender_id' => 1,
                    'address' => 'Testing',
                    'birthdate' => date('Y-m-d'),
                    'kartu_pegawai' => '12732137897123',
                    'pob' => 'Jakarta',
                    'masa_penilaian' => 'abcd',
                    'nomor_pak' => '12345',
                    'tanggal' => date('Y-m-d'),
                    'tahun_diangkat' => date('Y-m-d'),
                    'angka_kredit' => '123273',
                    'masa_penilaian_terkahir' => 'asdj asd',
                    'perancang' => 0,
                    'atasan' => 1,
                    'sekretariat' => 0,
                    'tim_penilai' => 0
                ),
                array(
                    'name' => 'Perancang',
                    'user_id' => 4,
                    'top' => 0,
                    'staff_id' => 2,
                    'jabatan_perancang_id' => 17,
                    'golongan_id' => 1,
                    'pendidikan_id' => 8,
                    'unit_kerja_id' => 1,
                    'jenjang_perancang_id' => 1,
                    'gender_id' => 1,
                    'address' => 'Testing',
                    'birthdate' => date('Y-m-d'),
                    'kartu_pegawai' => '12732137897123',
                    'pob' => 'Jakarta',
                    'masa_penilaian' => 'abcd',
                    'nomor_pak' => '12345',
                    'tanggal' => date('Y-m-d'),
                    'tahun_diangkat' => date('Y-m-d'),
                    'angka_kredit' => '123273',
                    'masa_penilaian_terkahir' => 'asdj asd',
                    'perancang' => 1,
                    'atasan' => 0,
                    'sekretariat' => 0,
                    'tim_penilai' => 0
                ),
                array(
                    'name' => 'Seketariat',
                    'user_id' => 5,
                    'top' => 0,
                    'staff_id' => 0,
                    'jabatan_perancang_id' => 17,
                    'golongan_id' => 1,
                    'pendidikan_id' => 8,
                    'unit_kerja_id' => 1,
                    'jenjang_perancang_id' => 1,
                    'gender_id' => 1,
                    'address' => 'Testing',
                    'birthdate' => date('Y-m-d'),
                    'kartu_pegawai' => '12732137897123',
                    'pob' => 'Jakarta',
                    'masa_penilaian' => 'abcd',
                    'nomor_pak' => '12345',
                    'tanggal' => date('Y-m-d'),
                    'tahun_diangkat' => date('Y-m-d'),
                    'angka_kredit' => '123273',
                    'masa_penilaian_terkahir' => 'asdj asd',
                    'perancang' => 0,
                    'atasan' => 0,
                    'sekretariat' => 1,
                    'tim_penilai' => 0
                ),
                array(
                    'name' => 'Penilai',
                    'user_id' => 6,
                    'top' => 0,
                    'staff_id' => 0,
                    'jabatan_perancang_id' => 17,
                    'golongan_id' => 1,
                    'pendidikan_id' => 8,
                    'unit_kerja_id' => 1,
                    'jenjang_perancang_id' => 1,
                    'gender_id' => 1,
                    'address' => 'Testing',
                    'birthdate' => date('Y-m-d'),
                    'kartu_pegawai' => '12732137897123',
                    'pob' => 'Jakarta',
                    'masa_penilaian' => 'abcd',
                    'nomor_pak' => '12345',
                    'tanggal' => date('Y-m-d'),
                    'tahun_diangkat' => date('Y-m-d'),
                    'angka_kredit' => '123273',
                    'masa_penilaian_terkahir' => 'asdj asd',
                    'perancang' => 0,
                    'atasan' => 0,
                    'sekretariat' => 0,
                    'tim_penilai' => 1
                )
            )
        );

    }
}
