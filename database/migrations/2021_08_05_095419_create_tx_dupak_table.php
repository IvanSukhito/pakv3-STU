<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxDupakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_dupak', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('verifikasi_sekretariat_id')->default(0);
            $table->bigInteger('verifikasi_tim_penilai_id')->default(0);
            $table->bigInteger('surat_pernyataan_id')->default(0)->nullable();
            $table->text('surat_pernyataan')->nullable();
            $table->text('jabatan_pengusul')->nullable();
            $table->text('jabatan_pengusul_nip')->nullable();
            $table->string('penilaian_tanggal')->nullable();
            $table->text('lampiran')->nullable();
            $table->string('lokasi_tanggal')->nullable();
            $table->text('nomor')->nullable();
            $table->string('pdf')->nullable();
            $table->text('pdf_url')->nullable();
            $table->text('file_sp')->nullable();
            $table->text('file_dupak')->nullable();
            $table->integer('status_upload')->default(0);
            $table->integer('sent_status')->default(0);
            $table->string('pdf_preview')->nullable();
            $table->text('pdf_preview_url')->nullable();
            $table->float('kredit_lama', 10, 3)->default(0)->nullable();
            $table->float('kredit_baru', 10, 3)->default(0)->nullable();
            $table->float('kredit_total', 10, 3)->default(0)->nullable();
            $table->date('tanggal')->nullable();
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
