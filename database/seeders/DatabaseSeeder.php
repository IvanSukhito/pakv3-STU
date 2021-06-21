<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(\Database\Seeders\InstallingSeeder::class);
        //$this->call(\Database\Seeders\UnitKerjaTableSeeder::class);
        //$this->call(\Database\Seeders\PendidikanTableSeeder::class);
        //$this->call(\Database\Seeders\GenderTableSeeder::class);
        $this->call(\Database\Seeders\GolonganTableSeeder::class);
        $this->call(\Database\Seeders\JabatanPerancangTableSeeder::class);
        $this->call(\Database\Seeders\JenjangPercanangTableSeeder::class);
        //$this->call(\Database\Seeders\SuratPernyataanSeeder::class);
        $this->call(\Database\Seeders\UnitKerjaTableSeeder::class);
     //   $this->call(\Database\Seeders\PendidikanTableSeeder::class);
        $this->call(\Database\Seeders\UsersTableSeeder::class);
        $this->call(\Database\Seeders\UsersStaffTableSeeder::class);
        $this->call(\Database\Seeders\MsKegiatanTableSeeder::class);
        $this->call(\Database\Seeders\JabatanPengusulTableSeeder::class);
    }
}
