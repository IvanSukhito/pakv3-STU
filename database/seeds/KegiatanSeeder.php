<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permen')->insertGetId([
            'name' => 'KEPMENPAN No. 41/KEP/M.PAN/12/2000 & PERMENKUMHAM No. M.02.PR.08.10 Tahun 2005',
            'tanggal_start' => '2016-01-01',
            'tanggal_end' => '2020-12-31',
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('permen')->insertGetId([
            'name' => 'PERMENPAN No. 6 Tahun 2016  & PERMENKUMHAM No. 5 Tahun 2020',
            'tanggal_start' => '2021-01-01',
            'tanggal_end' => '2021-12-31',
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

    }
}
