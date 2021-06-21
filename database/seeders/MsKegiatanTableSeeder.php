<?php


namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MsKegiatanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('ms_kegiatan')->truncate();

        DB::table('ms_kegiatan')->insert(
            array(
                // 1
                array(
                    'parent_id' => 0,
                    'name' => 'Pendidikan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 2
                array(
                    'parent_id' => 0,
                    'name' => 'PEMBENTUKAN Peraturan Perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 3
                array(
                    'parent_id' => 0,
                    'name' => 'Menyusun Instrumen Hukum',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 4
                array(
                    'parent_id' => 0,
                    'name' => 'Pengembangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 5
                array(
                    'parent_id' => 0,
                    'name' => 'Pendukung Kegiatan Perancang',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 6
                array(
                    'parent_id' => 1,
                    'name' => 'Pendidikan sekolah dan memperoleh gelar/ijazah',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 7
                array(
                    'parent_id' => 1,
                    'name' => 'Pendidikan dan Pelatihan Fungsional dan Teknis di bidang Perancangan Peraturan Perundang-undangan dan mendapat surat tanda tama pendidikan dan latihan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 8
                array(
                    'parent_id' => 1,
                    'name' => 'Pendidikan dan Pelatihan Pra Jabatan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 9
                array(
                    'parent_id' => 2,
                    'name' => 'Perencanaan Penyusunan Peraturan Perundang-Undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 10
                array(
                    'parent_id' => 2,
                    'name' => 'Menyusun rancangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 11
                array(
                    'parent_id' => 2,
                    'name' => 'Membahas RUU/RAPERDA',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 12
                array(
                    'parent_id' => 2,
                    'name' => 'Memberikan tanggapan terhadap peraturan perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 13
                array(
                    'parent_id' => 3,
                    'name' => 'Instruksi Presiden. Menteri. Pimpinan LPND/Lembaga Tinggi Negara. Jaksa Agung. Kepala Kepolisian RI. Panglima TNI. Gubernur dan Bupati/Walikota',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 14
                array(
                    'parent_id' => 3,
                    'name' => 'Surat Edaran',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 15
                array(
                    'parent_id' => 3,
                    'name' => 'Perjanjian Internasional',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 16
                array(
                    'parent_id' => 3,
                    'name' => 'Persetujuan Internasional',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 17
                array(
                    'parent_id' => 3,
                    'name' => 'Kontrak Internasional',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 18
                array(
                    'parent_id' => 3,
                    'name' => 'Kontrak Nasional',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 19
                array(
                    'parent_id' => 3,
                    'name' => 'Gugatan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 20
                array(
                    'parent_id' => 3,
                    'name' => 'Jawaban Gugatan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 21
                array(
                    'parent_id' => 3,
                    'name' => 'Akta',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 22
                array(
                    'parent_id' => 3,
                    'name' => 'Legal Option',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 23
                array(
                    'parent_id' => 4,
                    'name' => 'Melakukan kegiatan karya tulis/karya ilmiah di bidang hukum',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 24
                array(
                    'parent_id' => 4,
                    'name' => 'Menerjemahkan/menyadur dan bahan-bahan lain di bidang hukum',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 25
                array(
                    'parent_id' => 5,
                    'name' => 'Mengajar, melatih, dan atau membimbing pada pendidikan sekolah dan pendidikan latihan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 26
                array(
                    'parent_id' => 5,
                    'name' => 'Mengikuti seminar/lokakarya',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 27
                array(
                    'parent_id' => 5,
                    'name' => 'Menyunting naskah di bidang Hukum dan perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 28
                array(
                    'parent_id' => 5,
                    'name' => 'Berperan serta dalam penyuluhan Hukum',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 29
                array(
                    'parent_id' => 5,
                    'name' => 'Menjadi anggota organisasi profesi',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 30
                array(
                    'parent_id' => 5,
                    'name' => 'Keanggotaan dalam Tim Penilai Jabatan Fungsional Perancang',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 31
                array(
                    'parent_id' => 5,
                    'name' => 'Menjadi anggota delegasi dalam Pertemuan Internasional',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 32
                array(
                    'parent_id' => 5,
                    'name' => 'Memperoleh gelar kesarjanaan lainnya',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 33
                array(
                    'parent_id' => 5,
                    'name' => 'Memperoleh tanda penghargaan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 34
                array(
                    'parent_id' => 6,
                    'name' => 'Doktor',
                    'ak' => 200,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 35
                array(
                    'parent_id' => 6,
                    'name' => 'Magister',
                    'ak' => 150,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 36
                array(
                    'parent_id' => 6,
                    'name' => 'Sarjana / D.IV',
                    'ak' => 100,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 37
                array(
                    'parent_id' => 7,
                    'name' => 'Lamanya lebih dari 960 jam',
                    'ak' => 15,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 38
                array(
                    'parent_id' => 7,
                    'name' => 'Lamanya lebih antara 641 s/d 960 jam',
                    'ak' => 9,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 39
                array(
                    'parent_id' => 7,
                    'name' => 'Lamanya lebih antara 481 s/d 640 jam',
                    'ak' => 6,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 40
                array(
                    'parent_id' => 7,
                    'name' => 'Lamanya lebih antara 161 s/d 480 jam',
                    'ak' => 3,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 41
                array(
                    'parent_id' => 7,
                    'name' => 'Lamanya lebih antara 81 s/d 160 jam',
                    'ak' => 2,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 42
                array(
                    'parent_id' => 7,
                    'name' => 'Lamanya lebih antara 30 s/d 80 jam',
                    'ak' => 1,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 43
                array(
                    'parent_id' => 7,
                    'name' => 'Lamanya lebih antara 81 s/d 160 jam',
                    'ak' => 2,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 44
                array(
                    'parent_id' => 8,
                    'name' => 'Golongan III',
                    'ak' => 2,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 45
                array(
                    'parent_id' => 9,
                    'name' => 'Menyiapkan Konsep Usul Prakarsa Penyusunan Peraturan Perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 46
                array(
                    'parent_id' => 9,
                    'name' => 'Menelaah Usul Penyusunan Peraturan Perundang-Undangan dari Unit Teknis',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 47
                array(
                    'parent_id' => 9,
                    'name' => 'Menyiapkan naskah akademis',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 48
                array(
                    'parent_id' => 9,
                    'name' => 'Membahas naskah akademis',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 49
                array(
                    'parent_id' => 9,
                    'name' => 'Menyempurnakan naskah akademis',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 50
                array(
                    'parent_id' => 9,
                    'name' => 'Menyusun naskah usul prakarsa penyusunan RUU/RAPERDA',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 51
                array(
                    'parent_id' => 9,
                    'name' => 'Meneliti usul prakarsa dari instansi terkait',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 52
                array(
                    'parent_id' => 10,
                    'name' => 'Menyusun kerangka dasar peraturan perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 53
                array(
                    'parent_id' => 10,
                    'name' => 'Merumuskan rancangan peraturan perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 54
                array(
                    'parent_id' => 10,
                    'name' => 'Membahas rancangan peraturan perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 55
                array(
                    'parent_id' => 10,
                    'name' => 'Menyempurnakan rancangan peraturan perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 56
                array(
                    'parent_id' => 10,
                    'name' => 'Membahas kembali rancangan dalam rangka harmonisasi',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 5
                ),
                // 57
                array(
                    'parent_id' => 11,
                    'name' => 'Menyusun keterangan Pemerintah / Pemerintah Daerah',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 58
                array(
                    'parent_id' => 11,
                    'name' => 'Menyusun konsep jawaban Pemerintah / Pemerintah Daerah terhadap pemandangan umum Fraksi',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 59
                array(
                    'parent_id' => 11,
                    'name' => 'Menyiapkan jawaban atas Daftar Inventarisasi masalah DIM',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 60
                array(
                    'parent_id' => 11,
                    'name' => 'Mengikuti Sidang DPR/DPRD',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 61
                array(
                    'parent_id' => 11,
                    'name' => 'Merumuskan hasil sidang pembahasan peraturan perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 62
                array(
                    'parent_id' => 11,
                    'name' => 'Menyiapkan sambutan singkat Menteri / Kepala Daerah dalam sidang PANSUS',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 63
                array(
                    'parent_id' => 11,
                    'name' => 'Menyiapkan sambutan Menteri / Kepala Daerah dalam sidang Paripurna',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 64
                array(
                    'parent_id' => 11,
                    'name' => 'Menelaah peraturan daeerah tingkat I yang di mintakan pengesahan Menteri Dalam Negeri',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 65
                array(
                    'parent_id' => 12,
                    'name' => 'Mengumpulkan bahan',
                    'ak' => 0.06,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 66
                array(
                    'parent_id' => 12,
                    'name' => 'Menyusun konsep tanggapan rancangan peraturan perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 67
                array(
                    'parent_id' => 12,
                    'name' => 'Menelaah konsep tanggapan rancangan peraturan perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 68
                array(
                    'parent_id' => 12,
                    'name' => 'Menyempurnakan konsep tanggapan rancangan peraturan perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 69
                array(
                    'parent_id' => 13,
                    'name' => 'Menyusun konsep instruksi',
                    'ak' => 0.15,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 70
                array(
                    'parent_id' => 13,
                    'name' => 'Menelaah konsep instruksi',
                    'ak' => 0.26,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 71
                array(
                    'parent_id' => 13,
                    'name' => 'Menyempurnakan konsep',
                    'ak' => 0.27,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 72
                array(
                    'parent_id' => 14,
                    'name' => 'Menyusun konsep surat edaran',
                    'ak' => 0.15,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 73
                array(
                    'parent_id' => 14,
                    'name' => 'Menyusun konsep surat edaran',
                    'ak' => 0.26,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 74
                array(
                    'parent_id' => 14,
                    'name' => 'Menyempurnakan konsep edaran',
                    'ak' => 0.27,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 75
                array(
                    'parent_id' => 15,
                    'name' => 'Melakukan persiapan dalam rangka penyusunan Naskah Perjanjian Internasional',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 76
                array(
                    'parent_id' => 15,
                    'name' => 'Menyusun naskah perjanjian',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 77
                array(
                    'parent_id' => 15,
                    'name' => 'Memberikan tanggapan terhadap counter draft',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 78
                array(
                    'parent_id' => 15,
                    'name' => 'Membahas naskah perjanjian',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 79
                array(
                    'parent_id' => 16,
                    'name' => 'Melakukan persiapan dalam rangka penyusunan Naskah Perjanjian Internasional',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 80
                array(
                    'parent_id' => 16,
                    'name' => 'Menyusun naskah persetujuan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 81
                array(
                    'parent_id' => 16,
                    'name' => 'Memberikan tanggapan terhadap counter draft',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 82
                array(
                    'parent_id' => 16,
                    'name' => 'Membahas naskah persetujuan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 83
                array(
                    'parent_id' => 17,
                    'name' => 'Melakukan persiapan dalam rangka penyusunan rancangan kontrak Internasional',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 84
                array(
                    'parent_id' => 17,
                    'name' => 'Menyusun naskah kontrak internasional',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 85
                array(
                    'parent_id' => 17,
                    'name' => 'Memberikan tanggapan terhadap counter draft',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 86
                array(
                    'parent_id' => 17,
                    'name' => 'Membahas rancangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 87
                array(
                    'parent_id' => 18,
                    'name' => 'Melakukan persiapan dalam rangka penyusunan rancangan kontrak Nasional',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 88
                array(
                    'parent_id' => 18,
                    'name' => 'Menyusun naskah kontrak nasional',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 89
                array(
                    'parent_id' => 18,
                    'name' => 'Memberikan tanggapan terhadap counter draft',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 90
                array(
                    'parent_id' => 18,
                    'name' => 'Membahas naskah kontrak nasional',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 91
                array(
                    'parent_id' => 19,
                    'name' => 'Melakukan persiapan dalam rangka penyusunan Naskah gugatan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 92
                array(
                    'parent_id' => 19,
                    'name' => 'Menyusun gugatan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 93
                array(
                    'parent_id' => 19,
                    'name' => 'Mengikuti sidang',
                    'ak' => 0.045,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 94
                array(
                    'parent_id' => 19,
                    'name' => 'Menyusun laporan hasil sidang',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 95
                array(
                    'parent_id' => 20,
                    'name' => 'Melakukan persiapan dalam rangka penyusunan konsep jawaban gugatan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 96
                array(
                    'parent_id' => 20,
                    'name' => 'Menyusun jawaban gugatan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 97
                array(
                    'parent_id' => 20,
                    'name' => 'Mengikuti sidang',
                    'ak' => 0.04,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 98
                array(
                    'parent_id' => 20,
                    'name' => 'Menyusun laporan',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 99
                array(
                    'parent_id' => 21,
                    'name' => 'Melakukan persiapan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 100
                array(
                    'parent_id' => 21,
                    'name' => 'Menyusun konsep akta',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 101
                array(
                    'parent_id' => 21,
                    'name' => 'Memberikan tanggapan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 102
                array(
                    'parent_id' => 22,
                    'name' => 'Menyusun konsep',
                    'ak' => 0.3,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 103
                array(
                    'parent_id' => 22,
                    'name' => 'Menelaah konsep',
                    'ak' => 0.39,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 104
                array(
                    'parent_id' => 22,
                    'name' => 'Menyempurnakan',
                    'ak' => 0.36,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 105
                array(
                    'parent_id' => 23,
                    'name' => 'Hasil penelitian, pengujian, survei, dan evaluasi di bidang hukum yang dipublikasikan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 106
                array(
                    'parent_id' => 23,
                    'name' => 'Karya tulis ilmiah berupa tinjauan atau ulasan ilmiah dengan gagasan sendiri dalam bidang hukum yang dipublikasikan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 107
                array(
                    'parent_id' => 23,
                    'name' => 'Karya tulis ilmiah berupa tinjauan atau ulasan ilmiah dengan gagasan sendiri dalam bidang hukum yang tidak dipublikasikan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 108
                array(
                    'parent_id' => 23,
                    'name' => 'Menyampaikan prasasaran berupa tinjauan, gagasan, atau ulasan ilmiah dalam pertemuan ilmiah',
                    'ak' => 2.5,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 109
                array(
                    'parent_id' => 24,
                    'name' => 'Terjemahan/saduran dalam bidang hukum yang dipublikasikan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 110
                array(
                    'parent_id' => 24,
                    'name' => 'Terjemahan/saduran dalam bidang hukum yang tidak dipublikasikan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 111
                array(
                    'parent_id' => 25,
                    'name' => 'Mengajar, membimbing dan atau melatih pada pendidikan sekolah',
                    'ak' => 0.024,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 112
                array(
                    'parent_id' => 25,
                    'name' => 'Mengajar, membimbing dan atau melatih pada pendidikan luar sekolah',
                    'ak' => 0.024,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 113
                array(
                    'parent_id' => 26,
                    'name' => 'Mengikuti kegiatan seminar/lokakarya sebagai',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 114
                array(
                    'parent_id' => 27,
                    'name' => 'Menyunting naskah di bidang hukum dan perundang-undangan',
                    'ak' => 1,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 115
                array(
                    'parent_id' => 28,
                    'name' => 'Melakukan penyuluhan hukum',
                    'ak' => 1,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 116
                array(
                    'parent_id' => 29,
                    'name' => 'Tingkat Internasional/Nasional sebagai',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 117
                array(
                    'parent_id' => 29,
                    'name' => 'Tingkat Propinsi, sebagai',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 118
                array(
                    'parent_id' => 30,
                    'name' => 'Menjadi anggota Tim Penilai Jabatan Fungsional Perancang secara aktif',
                    'ak' => 0.5,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 119
                array(
                    'parent_id' => 31,
                    'name' => 'Sebagai',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 120
                array(
                    'parent_id' => 32,
                    'name' => 'Memperoleh gelar kesarjanaan yang tidak sesuai dengan tugas pokoknya',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 121
                array(
                    'parent_id' => 33,
                    'name' => 'Tanda jasa dari Pemerintah atas prestasi kerjanya. Tiap tanda jasa, tingkat',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 122
                array(
                    'parent_id' => 33,
                    'name' => 'Gelar Kehormatan Akademis',
                    'ak' => 15,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 123
                array(
                    'parent_id' => 45,
                    'name' => 'Mengumpulkan Data',
                    'ak' => 0.12,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 124
                array(
                    'parent_id' => 45,
                    'name' => 'Menganalisis Konsep Usul Prakarsa',
                    'ak' => 0.22,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 125
                array(
                    'parent_id' => 45,
                    'name' => 'Merumuskan Konsep Awal Usul Prakarsa',
                    'ak' => 0.45,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 126
                array(
                    'parent_id' => 45,
                    'name' => 'Menyempurnakan Konsep Awal Usul Prakarsa',
                    'ak' => 0.27,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 127
                array(
                    'parent_id' => 46,
                    'name' => 'Mengumpulkan Data',
                    'ak' => 0.12,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 128
                array(
                    'parent_id' => 46,
                    'name' => 'Menganalisis Usul Penyusunan Peraturan Perundang-Undangan',
                    'ak' => 0.22,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 129
                array(
                    'parent_id' => 46,
                    'name' => 'Merumuskan Usul Penyusunan Peraturan Perundang-Undangan',
                    'ak' => 0.23,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 130
                array(
                    'parent_id' => 46,
                    'name' => 'Menyempurnakan Naskah Hasil Telaahan Usul Penyusunan Peraturan Perundang-Undangan',
                    'ak' => 0.27,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 131
                array(
                    'parent_id' => 47,
                    'name' => 'Menginventarisasi masalah',
                    'ak' => 0.44,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 132
                array(
                    'parent_id' => 47,
                    'name' => 'Melakukan pengkajian masalah',
                    'ak' => 1.32,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 133
                array(
                    'parent_id' => 47,
                    'name' => 'Merumuskan',
                    'ak' => 0.6,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 134
                array(
                    'parent_id' => 48,
                    'name' => 'Menyajikan Naskah Akademis',
                    'ak' => 0.15,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 135
                array(
                    'parent_id' => 48,
                    'name' => 'Menyajikan Naskah Pembanding',
                    'ak' => 0.6,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 136
                array(
                    'parent_id' => 49,
                    'name' => 'Mengidentifikasi dan mengumpulkan data tambahan',
                    'ak' => 0.08,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 137
                array(
                    'parent_id' => 49,
                    'name' => 'Merumuskan dan menyusun konsep penyempurnaan',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 138
                array(
                    'parent_id' => 50,
                    'name' => 'Mengumpulkan bahan',
                    'ak' => 0.06,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 139
                array(
                    'parent_id' => 50,
                    'name' => 'Menganalisis usul penyusunan usul konsep prakarsa RUU/RAPERDA',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 140
                array(
                    'parent_id' => 50,
                    'name' => 'Merumuskan konsep awal usul prakarsa',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 141
                array(
                    'parent_id' => 50,
                    'name' => 'menyempurnakan konsep awal usul prakarsa',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 142
                array(
                    'parent_id' => 51,
                    'name' => 'Mengumpulkan bahan',
                    'ak' => 0.06,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 143
                array(
                    'parent_id' => 51,
                    'name' => 'Menganalisis dan menyusun jawaban ususl prakarsa',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 144
                array(
                    'parent_id' => 52,
                    'name' => 'Mengumpulkan bahan untuk menyusun kerangka dasar',
                    'ak' => 0.06,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 145
                array(
                    'parent_id' => 52,
                    'name' => 'Menganalisis bahan penyusunan kerangka dasar peraturan perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 146
                array(
                    'parent_id' => 52,
                    'name' => 'Merumuskan kerangka dasar peraturan perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 147
                array(
                    'parent_id' => 52,
                    'name' => 'Menyempurnakan kerangka dasar peraturan perundang-undangan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 148
                array(
                    'parent_id' => 53,
                    'name' => 'Tingkat Kesulitan I',
                    'ak' => 0.2,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 149
                array(
                    'parent_id' => 53,
                    'name' => 'Tingkat Kesulitan II',
                    'ak' => 0.2,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 150
                array(
                    'parent_id' => 53,
                    'name' => 'Tingkat Kesulitan III',
                    'ak' => 0.2,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 151
                array(
                    'parent_id' => 54,
                    'name' => 'Membahas di intern Tim / Panitia',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 152
                array(
                    'parent_id' => 54,
                    'name' => 'Membahas di ekstern',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 153
                array(
                    'parent_id' => 55,
                    'name' => 'Mengidentifikasi dan mengumpulkan data tambahan',
                    'ak' => 0.1,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 154
                array(
                    'parent_id' => 55,
                    'name' => 'Merumuskan dan menyusun konsep penyempurnaan',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 155
                array(
                    'parent_id' => 57,
                    'name' => 'Menyusun konsep',
                    'ak' => 0.44,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 156
                array(
                    'parent_id' => 57,
                    'name' => 'Menelaah konsep',
                    'ak' => 0.39,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 157
                array(
                    'parent_id' => 57,
                    'name' => 'Menyempurnakan konsep',
                    'ak' => 0.36,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 5
                ),
                // 158
                array(
                    'parent_id' => 58,
                    'name' => 'Menyusun konsep',
                    'ak' => 0.44,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 159
                array(
                    'parent_id' => 58,
                    'name' => 'Menelaah konsep',
                    'ak' => 0.39,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 160
                array(
                    'parent_id' => 58,
                    'name' => 'Menyempurnakan konsep',
                    'ak' => 0.36,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 5
                ),
                // 161
                array(
                    'parent_id' => 59,
                    'name' => 'Menyusun konsep',
                    'ak' => 0.4,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 162
                array(
                    'parent_id' => 59,
                    'name' => 'Menelaah konsep jawaban DIM',
                    'ak' => 0.45,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 163
                array(
                    'parent_id' => 59,
                    'name' => 'Menyempurnakan konsep konsep jawaban DIM',
                    'ak' => 0.36,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 5
                ),
                // 164
                array(
                    'parent_id' => 60,
                    'name' => 'Menyiapkan bahan yang akan dibahas',
                    'ak' => 0.06,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 165
                array(
                    'parent_id' => 60,
                    'name' => 'Mengikuti pembahasan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 166
                array(
                    'parent_id' => 60,
                    'name' => 'Membuat laporan hasil sidang',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 167
                array(
                    'parent_id' => 61,
                    'name' => 'Menyusun konsep',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 168
                array(
                    'parent_id' => 61,
                    'name' => 'Menelaah konsep',
                    'ak' => 0.13,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 169
                array(
                    'parent_id' => 61,
                    'name' => 'Menyempurnakan konsep',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 5
                ),
                // 170
                array(
                    'parent_id' => 62,
                    'name' => 'Menyusun konsep',
                    'ak' => 0.26,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 171
                array(
                    'parent_id' => 62,
                    'name' => 'Menelaah konsep',
                    'ak' => 0.27,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 172
                array(
                    'parent_id' => 62,
                    'name' => 'Menyempurnakan konsep',
                    'ak' => 0.135,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 173
                array(
                    'parent_id' => 63,
                    'name' => 'Menyusun konsep',
                    'ak' => 0.045,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 174
                array(
                    'parent_id' => 63,
                    'name' => 'Menelaah konsep',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 175
                array(
                    'parent_id' => 63,
                    'name' => 'Menyempurnakan konsep',
                    'ak' => 0.135,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 176
                array(
                    'parent_id' => 64,
                    'name' => 'Mengumpulkan bahan',
                    'ak' => 0.045,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 177
                array(
                    'parent_id' => 64,
                    'name' => 'Menyusun konsep telaahan',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 178
                array(
                    'parent_id' => 64,
                    'name' => 'Memberikan pertimbangan atas konsep telaahan',
                    'ak' => 0.135,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 179
                array(
                    'parent_id' => 64,
                    'name' => 'Menyempurnakan konsep telaahan',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 180
                array(
                    'parent_id' => 66,
                    'name' => 'Tingkat Kesulitan I',
                    'ak' => 0.13,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 181
                array(
                    'parent_id' => 66,
                    'name' => 'Tingkat Kesulitan II',
                    'ak' => 0.4,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 182
                array(
                    'parent_id' => 66,
                    'name' => 'Tingkat Kesulitan III',
                    'ak' => 0.5,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 183
                array(
                    'parent_id' => 67,
                    'name' => 'Tingkat Kesulitan I',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 184
                array(
                    'parent_id' => 67,
                    'name' => 'Tingkat Kesulitan II',
                    'ak' => 0.39,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 185
                array(
                    'parent_id' => 67,
                    'name' => 'Tingkat Kesulitan III',
                    'ak' => 0.45,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 186
                array(
                    'parent_id' => 68,
                    'name' => 'Tingkat Kesulitan I',
                    'ak' => 0.13,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 187
                array(
                    'parent_id' => 68,
                    'name' => 'Tingkat Kesulitan II',
                    'ak' => 0.21,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 188
                array(
                    'parent_id' => 68,
                    'name' => 'Tingkat Kesulitan III',
                    'ak' => 0.27,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 5
                ),
                // 189
                array(
                    'parent_id' => 75,
                    'name' => 'Menelaah usul dari Unit Teknis tentang penyusunan Perjanjian Internasional',
                    'ak' => 0.6,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 190
                array(
                    'parent_id' => 75,
                    'name' => 'Melakukan studi kelayakan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 191
                array(
                    'parent_id' => 76,
                    'name' => 'Menyusun naskah dasar perjanjian',
                    'ak' => 0.9,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 192
                array(
                    'parent_id' => 76,
                    'name' => 'Menelaah naskah dasar perjanjian',
                    'ak' => 0.9,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 193
                array(
                    'parent_id' => 76,
                    'name' => 'Menyempurnakan naskah dasar perjanjian',
                    'ak' => 0.45,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 194
                array(
                    'parent_id' => 77,
                    'name' => 'Menyiapkan konsep tanggapan',
                    'ak' => 0.8,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 195
                array(
                    'parent_id' => 77,
                    'name' => 'Menelaah konsep tanggapan',
                    'ak' => 0.75,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 196
                array(
                    'parent_id' => 77,
                    'name' => 'Menyempurnakan konsep tanggapan',
                    'ak' => 0.45,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 197
                array(
                    'parent_id' => 78,
                    'name' => 'Mengikuti pembahasan',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 198
                array(
                    'parent_id' => 78,
                    'name' => 'Membuat laporan hasil pembahasan',
                    'ak' => 0.9,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 199
                array(
                    'parent_id' => 78,
                    'name' => 'Menyusun naskah akhir perjanjian',
                    'ak' => 1.35,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 200
                array(
                    'parent_id' => 79,
                    'name' => 'Menelaah usul Unit Teknis tentang penyusunan Persetujuan Internasional',
                    'ak' => 0.6,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 201
                array(
                    'parent_id' => 79,
                    'name' => 'Melakukan studi kelayakan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 202
                array(
                    'parent_id' => 80,
                    'name' => 'Menyusun naskah dasar persetujuan',
                    'ak' => 0.9,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 203
                array(
                    'parent_id' => 80,
                    'name' => 'Menelaah naskah dasar persetujuan',
                    'ak' => 0.9,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 204
                array(
                    'parent_id' => 80,
                    'name' => 'Menyempurnakan naskah persetujuan',
                    'ak' => 0.9,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 205
                array(
                    'parent_id' => 81,
                    'name' => 'Menyiapakan konsep tanggapan',
                    'ak' => 0.8,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 206
                array(
                    'parent_id' => 81,
                    'name' => 'Menelaah konsep tanggapan',
                    'ak' => 0.75,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 207
                array(
                    'parent_id' => 81,
                    'name' => 'Menyempurnakan konsep tanggapan',
                    'ak' => 0.45,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 208
                array(
                    'parent_id' => 82,
                    'name' => 'Mengikuti pembahasan',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 209
                array(
                    'parent_id' => 82,
                    'name' => 'Membuat laporan hasil pembahasan',
                    'ak' => 0.9,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 210
                array(
                    'parent_id' => 82,
                    'name' => 'Menyempurnakan naskah akhir persetujuan',
                    'ak' => 0.14,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 211
                array(
                    'parent_id' => 83,
                    'name' => 'Menelaah usul dari Unit Teknis tentang penyusunan rancangan kontrak Internasional',
                    'ak' => 0.8,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 212
                array(
                    'parent_id' => 83,
                    'name' => 'Melakukan studi kelayakan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 213
                array(
                    'parent_id' => 84,
                    'name' => 'Menyusun naskah dasar kontrak',
                    'ak' => 0.9,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 214
                array(
                    'parent_id' => 84,
                    'name' => 'Menelaah naskah dasar kontrak',
                    'ak' => 0.9,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 215
                array(
                    'parent_id' => 84,
                    'name' => 'Menyempurnakan naskah dasar kontrak',
                    'ak' => 0.45,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 216
                array(
                    'parent_id' => 85,
                    'name' => 'Menyiapakan konsep tanggapan',
                    'ak' => 0.8,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 217
                array(
                    'parent_id' => 85,
                    'name' => 'Menelaah naskah dasar kontrak',
                    'ak' => 0.75,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 218
                array(
                    'parent_id' => 85,
                    'name' => 'Menyempurnakan konsep tanggapan',
                    'ak' => 0.45,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 219
                array(
                    'parent_id' => 86,
                    'name' => 'Mengikuti pembahasan',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 220
                array(
                    'parent_id' => 86,
                    'name' => 'Membuat laporan hasil pembahasan',
                    'ak' => 0.9,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 221
                array(
                    'parent_id' => 86,
                    'name' => 'Menyusun naskah akhir kontrak internasional',
                    'ak' => 1.35,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 222
                array(
                    'parent_id' => 87,
                    'name' => 'Menelaah usul dari Unit Teknis tentang penyusunan rancangan kontrak Nasional',
                    'ak' => 0.36,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 223
                array(
                    'parent_id' => 87,
                    'name' => 'Melakukan studi kelayakan',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 224
                array(
                    'parent_id' => 88,
                    'name' => 'Menyusun naskah dasar kontrak',
                    'ak' => 0.35,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 225
                array(
                    'parent_id' => 88,
                    'name' => 'Menelaah naskah dasar kontrak nasional',
                    'ak' => 0.5,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 226
                array(
                    'parent_id' => 88,
                    'name' => 'Menyempurnakan naskah dasar kontrak nasional',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 227
                array(
                    'parent_id' => 89,
                    'name' => 'Menyiapkan konsep tanggapan',
                    'ak' => 0.3,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 228
                array(
                    'parent_id' => 89,
                    'name' => 'Menelaah naskah dasar kontrak',
                    'ak' => 0.4,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 229
                array(
                    'parent_id' => 89,
                    'name' => 'Menyempurnakan konsep tanggapan',
                    'ak' => 0.12,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 230
                array(
                    'parent_id' => 90,
                    'name' => 'Mengikuti pembahasan',
                    'ak' => 0.135,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 231
                array(
                    'parent_id' => 90,
                    'name' => 'Membuat laporan hasil pembahasan',
                    'ak' => 0.75,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 232
                array(
                    'parent_id' => 90,
                    'name' => 'Menyusun naskah akhir kontrak nasional',
                    'ak' => 1.05,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 233
                array(
                    'parent_id' => 91,
                    'name' => 'Menelaah kasus',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 234
                array(
                    'parent_id' => 91,
                    'name' => 'Mengumpulkan data',
                    'ak' => 0.13,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 235
                array(
                    'parent_id' => 91,
                    'name' => 'Menganalisa data',
                    'ak' => 0.44,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 236
                array(
                    'parent_id' => 92,
                    'name' => 'Menyusun kerangka gugatan',
                    'ak' => 0.44,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 237
                array(
                    'parent_id' => 92,
                    'name' => 'Menelaah kerangka gugatan',
                    'ak' => 0.26,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 238
                array(
                    'parent_id' => 92,
                    'name' => 'Menyempurnakan kerangka gugatan',
                    'ak' => 0.27,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 239
                array(
                    'parent_id' => 95,
                    'name' => 'Menelaah kasus',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 240
                array(
                    'parent_id' => 95,
                    'name' => 'Mengumpulkan data',
                    'ak' => 0.13,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 241
                array(
                    'parent_id' => 95,
                    'name' => 'Menganalisa data',
                    'ak' => 0.44,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 242
                array(
                    'parent_id' => 96,
                    'name' => 'Menyusun kerangka gugatan',
                    'ak' => 0.22,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 243
                array(
                    'parent_id' => 96,
                    'name' => 'Menelaah kerangka jawaban gugatan',
                    'ak' => 0.26,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 244
                array(
                    'parent_id' => 96,
                    'name' => 'Menyempurnakan kerangka jawaban gugatan',
                    'ak' => 0.27,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 245
                array(
                    'parent_id' => 99,
                    'name' => 'Menyusun kerangka dasar akta',
                    'ak' => 0.54,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 246
                array(
                    'parent_id' => 99,
                    'name' => 'Mengumpulkan bahan',
                    'ak' => 0.06,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 247
                array(
                    'parent_id' => 99,
                    'name' => 'Melakukan perundingan',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 248
                array(
                    'parent_id' => 99,
                    'name' => 'Merekam hasil perundingan',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 249
                array(
                    'parent_id' => 99,
                    'name' => 'Merumuskan hasil perundingan',
                    'ak' => 0.39,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 250
                array(
                    'parent_id' => 100,
                    'name' => 'Menyusun kerangka dasar akta',
                    'ak' => 0.39,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 251
                array(
                    'parent_id' => 100,
                    'name' => 'Mengumpulkan bahan',
                    'ak' => 0.06,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 252
                array(
                    'parent_id' => 100,
                    'name' => 'Merancang konsep akta',
                    'ak' => 0,
                    'satuan' => '',
                    'jenjang_perancang_id' => 0
                ),
                // 253
                array(
                    'parent_id' => 101,
                    'name' => 'Menyusun konsep',
                    'ak' => 0.3,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 254
                array(
                    'parent_id' => 101,
                    'name' => 'Menelaah konsep tanggapan',
                    'ak' => 0.4,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 255
                array(
                    'parent_id' => 101,
                    'name' => 'Menyempurnakan konsep akta',
                    'ak' => 0.4,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 256
                array(
                    'parent_id' => 105,
                    'name' => 'Dalam bentuk buku yang diterbitkan dan diedarkan kepada khalayak luas',
                    'ak' => 12.5,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 257
                array(
                    'parent_id' => 105,
                    'name' => 'Dalam majalah ilmiah yang diakui oleh Lembaga Ilmu Pengetahuan Indonesia',
                    'ak' => 6,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 258
                array(
                    'parent_id' => 106,
                    'name' => 'Dalam bentuk buku yang diterbitkan dan diedarkan kepada khalayak luas',
                    'ak' => 8,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 259
                array(
                    'parent_id' => 106,
                    'name' => 'Dalam majalah ilmiah yang diakui oleh Lembaga Ilmu Pengetahuan Indonesia',
                    'ak' => 4,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 260
                array(
                    'parent_id' => 107,
                    'name' => 'Dalam bentuk buku',
                    'ak' => 7,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 261
                array(
                    'parent_id' => 107,
                    'name' => 'Dalam bentuk makalah',
                    'ak' => 3.5,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 262
                array(
                    'parent_id' => 109,
                    'name' => 'Dalam bentuk buku yang diterbitkan dan diedarkan kepada khalayak umum',
                    'ak' => 7,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 263
                array(
                    'parent_id' => 109,
                    'name' => 'Dalam majalah ilmiah yang diakui oleh Lembaga Ilmu Pengetahuan Indonesia',
                    'ak' => 3.5,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 264
                array(
                    'parent_id' => 110,
                    'name' => 'Dalam bentuk buku',
                    'ak' => 3,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 265
                array(
                    'parent_id' => 110,
                    'name' => 'Dalam bentuk majalah',
                    'ak' => 1.5,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 266
                array(
                    'parent_id' => 113,
                    'name' => 'Pemasaran',
                    'ak' => 3,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 267
                array(
                    'parent_id' => 113,
                    'name' => 'Moderator',
                    'ak' => 2,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 268
                array(
                    'parent_id' => 113,
                    'name' => 'Pembahas',
                    'ak' => 2,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 269
                array(
                    'parent_id' => 113,
                    'name' => 'Narasumber',
                    'ak' => 2,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 270
                array(
                    'parent_id' => 113,
                    'name' => 'Peserta',
                    'ak' => 1,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 271
                array(
                    'parent_id' => 116,
                    'name' => 'Pengurus aktif',
                    'ak' => 1,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 272
                array(
                    'parent_id' => 116,
                    'name' => 'Anggota aktif',
                    'ak' => 0.5,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 273
                array(
                    'parent_id' => 117,
                    'name' => 'Pengurus aktif',
                    'ak' => 0.25,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 274
                array(
                    'parent_id' => 117,
                    'name' => 'Anggota aktif',
                    'ak' => 0.15,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 275
                array(
                    'parent_id' => 119,
                    'name' => 'Ketua Delegasi',
                    'ak' => 3,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 276
                array(
                    'parent_id' => 119,
                    'name' => 'Anggota Delegasi',
                    'ak' => 2,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 277
                array(
                    'parent_id' => 120,
                    'name' => 'Doktor',
                    'ak' => 15,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 278
                array(
                    'parent_id' => 120,
                    'name' => 'Pasca Sarjana',
                    'ak' => 10,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 279
                array(
                    'parent_id' => 120,
                    'name' => 'Sarjana',
                    'ak' => 5,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 280
                array(
                    'parent_id' => 121,
                    'name' => 'Nasional/Internasional',
                    'ak' => 3,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 281
                array(
                    'parent_id' => 121,
                    'name' => 'Propinsi',
                    'ak' => 2.5,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 282
                array(
                    'parent_id' => 121,
                    'name' => 'Kabupaten/Kota',
                    'ak' => 2,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 4
                ),
                // 283
                array(
                    'parent_id' => 139,
                    'name' => 'RAPERDA',
                    'ak' => 0.24,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 284
                array(
                    'parent_id' => 139,
                    'name' => 'RUU',
                    'ak' => 0.36,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 285
                array(
                    'parent_id' => 140,
                    'name' => 'RAPERDA',
                    'ak' => 0.1,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 286
                array(
                    'parent_id' => 140,
                    'name' => 'RUU',
                    'ak' => 0.15,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 287
                array(
                    'parent_id' => 141,
                    'name' => 'RAPERDA',
                    'ak' => 0.1,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 288
                array(
                    'parent_id' => 141,
                    'name' => 'RUU',
                    'ak' => 0.15,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 289
                array(
                    'parent_id' => 143,
                    'name' => 'RAPERDA',
                    'ak' => 0.3,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 290
                array(
                    'parent_id' => 143,
                    'name' => 'RUU',
                    'ak' => 0.45,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 291
                array(
                    'parent_id' => 145,
                    'name' => 'Tingkat Kesulitan I',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 292
                array(
                    'parent_id' => 145,
                    'name' => 'Tingkat Kesulitan II',
                    'ak' => 0.44,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 293
                array(
                    'parent_id' => 145,
                    'name' => 'Tingkat Kesulitan III',
                    'ak' => 0.75,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 294
                array(
                    'parent_id' => 146,
                    'name' => 'Tingkat Kesulitan I',
                    'ak' => 0.15,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 295
                array(
                    'parent_id' => 146,
                    'name' => 'Tingkat Kesulitan II',
                    'ak' => 0.36,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 296
                array(
                    'parent_id' => 146,
                    'name' => 'Tingkat Kesulitan III',
                    'ak' => 0.6,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 297
                array(
                    'parent_id' => 147,
                    'name' => 'Tingkat Kesulitan I',
                    'ak' => 0.1,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 298
                array(
                    'parent_id' => 147,
                    'name' => 'Tingkat Kesulitan II',
                    'ak' => 0.26,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 299
                array(
                    'parent_id' => 147,
                    'name' => 'Tingkat Kesulitan III',
                    'ak' => 0.45,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 300
                array(
                    'parent_id' => 151,
                    'name' => 'Menyajikan rancangan',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 301
                array(
                    'parent_id' => 151,
                    'name' => 'Memberikan tanggapan atas rancangan yang disajikan',
                    'ak' => 0.135,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 302
                array(
                    'parent_id' => 152,
                    'name' => 'Menyajikan rancangan',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 303
                array(
                    'parent_id' => 152,
                    'name' => 'Memberikan tanggapan atas rancangan yang disajikan',
                    'ak' => 0.135,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 304
                array(
                    'parent_id' => 165,
                    'name' => 'Tingkat PANSUS',
                    'ak' => 0.24,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 5
                ),
                // 305
                array(
                    'parent_id' => 165,
                    'name' => 'Tingkat PANJA',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 306
                array(
                    'parent_id' => 165,
                    'name' => 'Tingkat TIMUS/STIMCIL',
                    'ak' => 0.12,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 307
                array(
                    'parent_id' => 165,
                    'name' => 'Tingkat Tim Sinkronisasi',
                    'ak' => 0.12,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 308
                array(
                    'parent_id' => 190,
                    'name' => 'Mengumpulkan data',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 309
                array(
                    'parent_id' => 190,
                    'name' => 'Melakukan analisis data dan menyusun laporan hasil studi kelayakan',
                    'ak' => 0.44,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 310
                array(
                    'parent_id' => 201,
                    'name' => 'Mengumpulkan data',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 311
                array(
                    'parent_id' => 201,
                    'name' => 'Melakukan analisis data dan menyusun laporan hasil studi kelayakan',
                    'ak' => 0.44,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 312
                array(
                    'parent_id' => 212,
                    'name' => 'Mengumpulkan data',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 313
                array(
                    'parent_id' => 212,
                    'name' => 'Melakukan analisis data dan menyusun laporan hasil studi kelayakan',
                    'ak' => 0.44,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 314
                array(
                    'parent_id' => 223,
                    'name' => 'Mengumpulkan data',
                    'ak' => 0.06,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 315
                array(
                    'parent_id' => 223,
                    'name' => 'Melakukan analisis data dan menyusun laporan hasil studi kelayakan',
                    'ak' => 0.4,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 316
                array(
                    'parent_id' => 252,
                    'name' => 'Mengolah bahan',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 1
                ),
                // 317
                array(
                    'parent_id' => 252,
                    'name' => 'Membahas konsep',
                    'ak' => 0.09,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 318
                array(
                    'parent_id' => 252,
                    'name' => 'Memadukan (mengintegrasikan) konsep',
                    'ak' => 0.18,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),
                // 319
                array(
                    'parent_id' => 252,
                    'name' => 'Menelaah konsep akta',
                    'ak' => 0.5,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 2
                ),
                // 320
                array(
                    'parent_id' => 252,
                    'name' => 'Menyempurnakan konsep akta',
                    'ak' => 0.08,
                    'satuan' => 'Ijazah',
                    'jenjang_perancang_id' => 3
                ),




            )
        );

    }
}
