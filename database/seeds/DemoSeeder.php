<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $getPerancangRole = DB::table('role')->insertGetId([
            'name' => 'Perancang',
            'permission_data' =>  '{"role_perancang":1,"kegiatan":{"list":1,"create":1,"edit":1,"show":1},"pemuktahiran-data-diri":{"list":1,"create":1,"show":1},"pemuktahiran-ak":{"list":1,"create":1,"show":1},"surat-pernyataan":{"list":1,"show":1},"dupak":{"list":1,"edit":1,"show":1}}',
            'permission_route' => '["admin.kegiatan.index","admin.kegiatan.dataTable","admin.kegiatan.create","admin.kegiatan.store","admin.kegiatan.submitKegiatan","admin.kegiatan.storeSubmitKegiatan","admin.kegiatan.edit","admin.kegiatan.update","admin.kegiatan.show","admin.pemuktahiran-data-diri.index","admin.pemuktahiran-data-diri.dataTable","admin.pemuktahiran-data-diri.create","admin.pemuktahiran-data-diri.store","admin.pemuktahiran-data-diri.show","admin.pemuktahiran-ak.index","admin.pemuktahiran-ak.dataTable","admin.pemuktahiran-ak.create","admin.pemuktahiran-ak.store","admin.pemuktahiran-ak.show","admin.dupak.index","admin.dupak.dataTable","admin.dupak.edit","admin.dupak.update","admin.dupak.uploadSP","admin.dupak.storeSP","admin.dupak.show","admin.dupak.showPdf","admin.dupak.showDupakPdf","admin.surat-pernyataan.index","admin.surat-pernyataan.dataTable","admin.surat-pernyataan.show","admin.surat-pernyataan.showPdf","admin.surat-pernyataan.showDupakPdf"]',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $getAtasanRole = DB::table('role')->insertGetId([
            'name' => 'Atasan',
            'permission_data' =>  '{"role_atasan":1,"pemuktahiran-data-diri":{"list":1,"create":1,"show":1},"persetujuan-surat-pernyataan":{"list":1,"edit":1,"show":1}}',
            'permission_route' => '["admin.pemuktahiran-data-diri.index","admin.pemuktahiran-data-diri.dataTable","admin.pemuktahiran-data-diri.create","admin.pemuktahiran-data-diri.store","admin.pemuktahiran-data-diri.show","admin.persetujuan-surat-pernyataan.index","admin.persetujuan-surat-pernyataan.dataTable","admin.persetujuan-surat-pernyataan.edit","admin.persetujuan-surat-pernyataan.update","admin.persetujuan-surat-pernyataan.show","admin.persetujuan-surat-pernyataan.showPdf","admin.persetujuan-surat-pernyataan.showDupakPdf"]',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $getSekRole = DB::table('role')->insertGetId([
            'name' => 'Seketariat',
            'permission_data' =>  '{"role_sekretariat":1,"pemuktahiran-data-diri":{"list":1,"create":1,"show":1},"persetujuan-dupak":{"list":1,"edit":1,"show":1}}',
            'permission_route' => '["admin.pemuktahiran-data-diri.index","admin.pemuktahiran-data-diri.dataTable","admin.pemuktahiran-data-diri.create","admin.pemuktahiran-data-diri.store","admin.pemuktahiran-data-diri.show","admin.persetujuan-dupak.index","admin.persetujuan-dupak.dataTable","admin.persetujuan-dupak.edit","admin.persetujuan-dupak.update","admin.persetujuan-dupak.show","admin.persetujuan-dupak.showPdf","admin.persetujuan-dupak.showDupakPdf"]',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $getTimRole = DB::table('role')->insertGetId([
            'name' => 'Tim Penilai',
            'permission_data' =>  '{"role_tim_penilai":1}',
            'permission_route' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        for($i=1; $i<=5; $i++) {
            $getId = DB::table('users')->insertGetId([
                'name' => 'atasan '.$i,
                'username' => 'atasan'.$i,
                'password' => bcrypt('123456'),
                'role_id' => $getAtasanRole,
                'atasan' => 1,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('users')->insertGetId([
                'name' => 'perancang '.$i,
                'username' => 'perancang'.$i,
                'upline_id' => $getId,
                'password' => bcrypt('123456'),
                'role_id' => $getPerancangRole,
                'perancang' => 1,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('users')->insertGetId([
                'name' => 'seketariat '.$i,
                'username' => 'sek'.$i,
                'upline_id' => $getId,
                'password' => bcrypt('123456'),
                'role_id' => $getSekRole,
                'sekretariat' => 1,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('users')->insertGetId([
                'name' => 'tim penilai '.$i,
                'username' => 'tim'.$i,
                'upline_id' => $getId,
                'password' => bcrypt('123456'),
                'role_id' => $getTimRole,
                'tim_penilai' => 1,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

    }
}
