<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('upline_id')->default(0);
            $table->unsignedBigInteger('pangkat_id')->default(0);
            $table->unsignedBigInteger('golongan_id')->default(0);
            $table->unsignedBigInteger('jenjang_perancang_id')->default(0);
            $table->unsignedBigInteger('pendidikan_id')->default(0);
            $table->unsignedBigInteger('instansi_id')->default(0);
            $table->unsignedBigInteger('unit_kerja_id')->default(0);
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('email')->nullable();
            $table->string('kartu_pegawai')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->integer('gender')->default(1);
            $table->string('alamat_kantor')->nullable();
            $table->date('tmt_kenaikan_jenjang_terakhir')->nullable();
            $table->date('kenaikan_jenjang_terakhir')->nullable();
            $table->date('masa_penilaian_terakhir_awal')->nullable();
            $table->date('masa_penilaian_terakhir_akhir')->nullable();
            $table->date('tahun_diangkat')->nullable();
            $table->date('tahun_pelaksanaan_diklat')->nullable();
            $table->date('tanggal_pak_terakhir')->nullable();
            $table->string('alasan')->nullable();
            $table->decimal('angka_kredit_terakhir')->nullable();
            $table->string('nomor_pak_terakhir')->nullable();
            $table->unsignedBigInteger('role_id')->default(0);
            $table->dateTime('last_login')->nullable();
            $table->tinyInteger('calon_perancang')->default(0);
            $table->tinyInteger('perancang')->default(0);
            $table->tinyInteger('atasan')->default(0);
            $table->tinyInteger('sekretariat')->default(0);
            $table->tinyInteger('tim_penilai')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->index(['id', 'username']);
            $table->foreign('role_id')
                ->references('id')->on('role')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
