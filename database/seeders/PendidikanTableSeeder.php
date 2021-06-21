<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PendidikanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pendidikan')->delete();

        DB::table('pendidikan')->insert(
            array(
                array('name' => 'Sekolah Dasar'),
                array('name' => 'Sekolah Menengah Pertama / Sederajat'),
                array('name' => 'Sekolah Menegah Atas / Sederajat'),
                array('name' => 'Diploma 3 (D3)'),
                array('name' => 'Diploma 4 (D4)'),
                array('name' => 'Sarjana (S1)'),
                array('name' => 'Magister (S2)'),
                array('name' => 'Doktor (S3)')
            )
        );
    }
}
