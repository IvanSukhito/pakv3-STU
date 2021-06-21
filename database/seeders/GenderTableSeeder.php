<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gender')->delete();

        DB::table('gender')->insert(
            array(
                array('name' => 'Pria'),
                array('name' => 'Wanita')
            )
        );
    }
}
