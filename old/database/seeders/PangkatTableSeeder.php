<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PangkatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pangkat')->delete();

        DB::table('pangkat')->insert(
            array(
                array('name' => 'I'),
                array('name' => 'II'),
                array('name' => 'III'),
                array('name' => 'IV')
            )
        );
    }
}
