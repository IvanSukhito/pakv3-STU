<?php


namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanPerancangTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jabatan_perancang')->delete();

        DB::table('jabatan_perancang')->insert(
            array(
                array('name' => 'Juru Muda'),
                array('name' => 'Juru Muda Tingkat I'),
                array('name' => 'Juru'),
                array('name' => 'Juru Tingkat I'),
                array('name' => 'Pengatur Muda'),
                array('name' => 'Pengatur Muda Tingkat I'),
                array('name' => 'Pengatur'),
                array('name' => 'Pengatur Tingkat I'),
                array('name' => 'Penata Muda'),
                array('name' => 'Penata Muda Tingkat I'),
                array('name' => 'Penata'),
                array('name' => 'Penata Tingkat I'),
                array('name' => 'Pembina'),
                array('name' => 'Pembina Tingkat I'),
                array('name' => 'Pembina Utama Muda'),
                array('name' => 'Pembina Utama Madya'),
                array('name' => 'Pembina Utama')
            )
        );
    }
}
