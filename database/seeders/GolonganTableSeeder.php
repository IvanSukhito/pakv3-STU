<?php


namespace Database\Seeders;
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
                array('name' => 'I'),
                array('name' => 'II'),
                array('name' => 'III'),
                array('name' => 'IV')
            )
        );
    }
}
