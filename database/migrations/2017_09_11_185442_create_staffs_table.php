<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_staffs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id');
            $table->tinyInteger('top')->default(0);
            $table->integer('staff_id')->default(0);
            $table->integer('jabatan_perancang_id')->nullable();
            $table->integer('golongan_id')->nullable();
            $table->integer('pendidikan_id')->nullable();
            $table->integer('unit_kerja_id')->nullable();
            $table->integer('jenjang_perancang_id')->nullable();
            $table->integer('gender_id')->nullable();
            $table->text('name')->nullable();
            $table->text('address')->nullable();
            $table->date('birthdate')->nullable();
            $table->text('photo')->nullable();
            $table->string('kartu_pegawai')->nullable();
            $table->string('pob')->nullable();
            $table->string('masa_penilaian')->nullable();
            $table->string('nomor_pak')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('tahun_diangkat')->nullable();
            $table->string('angka_kredit')->nullable();
            $table->string('masa_penilaian_terkahir')->nullable();
            $table->string('masa_kerja_golongan_lama')->nullable();
            $table->string('masa_kerja_golongan_baru')->nullable();
            $table->tinyInteger('perancang')->default(0);
            $table->tinyInteger('atasan')->default(0);
            $table->tinyInteger('sekretariat')->default(0);
            $table->tinyInteger('tim_penilai')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_staffs');
    }
}
