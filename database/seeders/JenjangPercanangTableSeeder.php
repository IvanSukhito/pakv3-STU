<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenjangPercanangTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jenjang_perancang')->truncate();

        DB::table('jenjang_perancang')->insert(
            array(
                array('name' => 'Pertama', 'order_high' => 5),
                array('name' => 'Muda', 'order_high' => 4),
                array('name' => 'Madya', 'order_high' => 3),
                array('name' => 'Semua Jenjang', 'order_high' => 1),
                array('name' => 'Utama', 'order_high' => 2)
            )
        );
    }
}
