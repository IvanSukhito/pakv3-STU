<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('golongan')->truncate();

        DB::table('golongan')->insert(
            array(
                array('name' => 'III/a'),
                array('name' => 'III/b'),
                array('name' => 'III/c'),
                array('name' => 'III/d'),
                array('name' => 'IV/a'),
                array('name' => 'IV/b'),
                array('name' => 'IV/c'),
                array('name' => 'IV/d'),
                array('name' => 'IV/e'),
            )
        );

        DB::table('jabatan_perancang')->truncate();

        DB::table('jabatan_perancang')->insert(
            array(
                array('name' => 'Juru Muda'),
                array('name' => 'Juru Muda Tingkat I'),
                array('name' => 'Juru'),
                array('name' => 'Juru Tingkat I'),
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

        DB::table('pangkat')->truncate();

        DB::table('pangkat')->insert(
            array(
                array('name' => 'Perancang'),
                array('name' => 'Pembina'),
                array('name' => 'Sekretariat Daerah'),
                array('name' => 'Sekretariat Pusat'),
                array('name' => 'Tim Penilai'),
            )
        );

        DB::table('pendidikan')->truncate();

        DB::table('pendidikan')->insert(
            array(
                array('name' => 'Sekolah Dasar'),
                array('name' => 'Sekolah Menengah Pertama / Sederajat'),
                array('name' => 'Sekolah Menegah Atas / Sederajat'),
                array('name' => 'Diploma 3 (D3)'),
                array('name' => 'Diploma 4 (D4)'),
                array('name' => 'Sarjana (S1)'),
                array('name' => 'Magister (S2)'),
                array('name' => 'Doktor (S3)')
            )
        );

        DB::table('unit_kerja')->truncate();

        DB::table('unit_kerja')->insert(
            array(
                array('name' => 'Kanwil DKI Jakarta'),
                array('name' => 'Direktorat Jenderal Peraturan Dan Perundang Undangan'),
                array('name' => 'Kanwil Jawa Barat'),
                array('name' => 'Kanwil Aceh'),
                array('name' => 'Kanwil Banten'),
                array('name' => 'Kanwil Sumatera Utara'),
                array('name' => 'Kanwil Sumatera Barat'),
                array('name' => 'Kanwil Kepulauan Riau'),
                array('name' => 'Kanwil Riau'),
                array('name' => 'Kanwil Sumatera Selatan'),
                array('name' => 'Kanwil Bangka Belitung'),
                array('name' => 'Kanwil Bengkulu'),
                array('name' => 'Kanwil Jambi'),
                array('name' => 'Kanwil Lampung'),
                array('name' => 'Kanwil DI Yogjakarta'),
                array('name' => 'Kanwil Jawa Tengah'),
                array('name' => 'Kanwil Jawa Timur'),
                array('name' => 'Kanwil Bali'),
                array('name' => 'Kanwil Nusa Tenggara Barat'),
                array('name' => 'Kanwil Nusa Tenggara Timur'),
                array('name' => 'Kanwil Kalimantan Selatan'),
                array('name' => 'Kanwil Kalimantan Barat'),
                array('name' => 'Kanwil Kalimantan Tenggara'),
                array('name' => 'Kanwil Kalimantan Timur'),
                array('name' => 'Kanwil Sulawesi Selatan'),
                array('name' => 'Kanwil Sulawesi Barat'),
                array('name' => 'Kanwil Sulawesi Tengah'),
                array('name' => 'Kanwil Sulawesi Tenggara'),
                array('name' => 'Kanwil Gorontalo'),
                array('name' => 'Kanwil Sulawesi Utara'),
                array('name' => 'Kanwil Maluku'),
                array('name' => 'Kanwil Maluku Utara'),
                array('name' => 'Kanwil Papua'),
                array('name' => 'Kanwil Papua Barat'),
                array('name' => 'Direktorat Jenderal Administrasi Hukum Umum'),
                array('name' => 'Direktorat Jenderal Imigrasi'),
                array('name' => 'Direktorat Jenderal Hak Kekayaan Intelektual'),
                array('name' => 'Diretorat Jenderal Pemasyarakatan'),
                array('name' => 'Direktorat Jenderal Hak Asasi Manusia'),
                array('name' => 'Badan Pengembangan Sumber Daya Manusia Hukum dan HAM'),
                array('name' => 'Badan Pembinaan Hukum Nasional'),
                array('name' => 'Badan Penelitian dan Pengembangan HAM'),
                array('name' => 'Inspektorat Jenderal Hukum dan HAM'),
                array('name' => 'Sekertariat Jenderal Hukum dan HAM'),
                array('name' => 'Kementerian Dalam Negeri'),
                array('name' => 'Kementerian Luar Negeri Indonesia'),
                array('name' => 'Kementerian Pertahanan Indonesia'),
                array('name' => 'Kementerian Keuangan'),
                array('name' => 'Kementerian Energi dan Sumber Daya Mineral Indonesia'),
                array('name' => 'Kementerian Perindustrian Indonesia'),
                array('name' => 'Kementerian Perdagangan Indonesia'),
                array('name' => 'Kementerian Pertanian Indonesia'),
                array('name' => 'Kementerian Lingkungan Hidup dan Kehutanan Indonesia'),
                array('name' => 'Kementerian Perhubungan Indonesia'),
                array('name' => 'Kementerian Kelautan dan Perikanan Indonesia'),
                array('name' => 'Kementerian Ketenagakerjaan Indonesia'),
                array('name' => 'Kementerian Pekerjaan Umum dan Perumahan Rakyat Indonesia'),
                array('name' => 'Kementerian Kesehatan Indonesia'),
                array('name' => 'Kementerian Pendidikan dan Kebudayaan Indonesia'),
                array('name' => 'Kementerian Sosial Indonesia'),
                array('name' => 'Kementerian Agama Indonesia'),
                array('name' => 'Kementerian Komunikasi dan Informatika Indonesia'),
                array('name' => 'Kementerian Desa, Pembangunan Daerah Tertinggal, dan Transmigrasi Indonesia'),
                array('name' => 'Kementerian Agraria dan Tata Ruang Indonesia'),
                array('name' => 'Kementerian Pendayagunaan Aparatur Negara dan Reformasi Birokrasi Indonesia'),
                array('name' => 'Kementerian Badan Usaha Milik Negara Indonesia'),
                array('name' => 'Kementerian Pemuda dan Olahraga Indonesia'),
                array('name' => 'Kementerian Pariwisata Indonesia'),
                array('name' => 'Kementerian Pemberdayaan Perempuan dan Perlindungan Anak Indonesia')
            )
        );


        DB::table('pendidikan')->truncate();

        DB::table('pendidikan')->insert(
            array(
                array('name' => 'Sekolah Dasar'),
                array('name' => 'Sekolah Menengah Pertama / Sederajat'),
                array('name' => 'Sekolah Menegah Atas / Sederajat'),
                array('name' => 'Diploma 3 (D3)'),
                array('name' => 'Diploma 4 (D4)'),
                array('name' => 'Sarjana (S1)'),
                array('name' => 'Magister (S2)'),
                array('name' => 'Doktor (S3)')
            )
        );

    }
}
