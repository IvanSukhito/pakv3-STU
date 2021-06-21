<?php


namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        DB::table('users')->insert(
            array(
                array(
                    'name' => 'Admin',
                    'username' => 'admin',
                    'email' => 'hendratjua@gmail.com',
                    'superadmin' => 1,
                    'password' => Hash::make('admin')
                ),
                array(
                    'name' => 'Jabatan Tertinggi',
                    'username' => 'tinggi',
                    'email' => 'tinggi@gmail.com',
                    'superadmin' => 0,
                    'password' => Hash::make('admin')
                ),
                array(
                    'name' => 'Atasan',
                    'username' => 'atasan',
                    'email' => 'atasan@gmail.com',
                    'superadmin' => 0,
                    'password' => Hash::make('admin')
                ),
                array(
                    'name' => 'Perancang',
                    'username' => 'perancang',
                    'email' => 'perancanghendratjua@gmail.com',
                    'superadmin' => 0,
                    'password' => Hash::make('admin')
                ),
                array(
                    'name' => 'Seketariat',
                    'username' => 'seketariat',
                    'email' => 'seketariat@gmail.com',
                    'superadmin' => 0,
                    'password' => Hash::make('admin')
                ),
                array(
                    'name' => 'Penilai',
                    'username' => 'penilai',
                    'email' => 'penilai@gmail.com',
                    'superadmin' => 0,
                    'password' => Hash::make('admin')
                )
            )
        );

    }
}
