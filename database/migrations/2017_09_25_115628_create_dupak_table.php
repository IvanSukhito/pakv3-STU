<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDupakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dupak', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('verifikasi_sekretariat_id')->default(0);
            $table->integer('verifikasi_tim_penilai_id')->default(0);
            $table->integer('surat_pernyataan_id')->default(0)->nullable();
            $table->text('surat_pernyataan')->nullable();
            $table->text('jabatan_pengusul')->nullable();
            $table->text('jabatan_pengusul_nip')->nullable();
            $table->string('penilaian_tanggal')->nullable();
            $table->text('lampiran')->nullable();
            $table->string('lokasi_tanggal')->nullable();
            $table->text('nomor')->nullable();
            $table->string('pdf')->nullable();
            $table->text('pdf_url')->nullable();
            $table->string('pdf_preview')->nullable();
            $table->text('pdf_preview_url')->nullable();
            $table->float('kredit_lama', 10, 3)->default(0)->nullable();
            $table->float('kredit_baru', 10, 3)->default(0)->nullable();
            $table->float('kredit_total', 10, 3)->default(0)->nullable();
            $table->tinyInteger('approved')->default(0);
            $table->tinyInteger('connect')->default(0);
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
        Schema::dropIfExists('dupak');
    }
}
