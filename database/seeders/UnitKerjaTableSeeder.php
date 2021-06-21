<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitKerjaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unit_kerja')->delete();

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
    }
}
