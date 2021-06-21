<?php


namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanPengusulTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jabatan_pengusul')->delete();

        DB::table('jabatan_pengusul')->insert(
            array(
                array('name' => 'Direktur'),
                array('name' => 'Pejabat'),
                array('name' => 'Kakanwil')
            )
        );
    }
}
