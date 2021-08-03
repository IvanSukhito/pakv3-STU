<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GolonganTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('golongan')->delete();

        DB::table('golongan')->insert(
            array(
                array('name' => 'Penata Muda'),
                array('name' => 'Penata Muda Tingkat I'),
                array('name' => 'Penata'),
                array('name' => 'Penata Tingkat I'),
                array('name' => 'Pembina'),
                array('name' => 'Pembina Tingkat I'),
                array('name' => 'Pembina Utama Muda'),
                array('name' => 'Pembina Utama Madya'),
                array('name' => 'Pembina Utama'),
            )
        );
    }
}
