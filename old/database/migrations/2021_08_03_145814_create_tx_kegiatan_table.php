<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxKegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->default(0);
            $table->bigInteger('upline_id')->default(0);
            $table->bigInteger('ms_kegiatan_id')->default(0);
            $table->bigInteger('permen_id')->default(0);
            $table->bigInteger('pelaksana_id')->default(0);
            $table->date('tanggal')->nullable();
            $table->string('judul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->longtext('dokument_pendukung')->nullable();
            $table->longtext('dokument_fisik')->nullable();
            $table->float('kredit_lama', 10, 3)->default(0)->nullable();
            $table->float('kredit', 10, 3)->default(0)->nullable();
            $table->float('kredit_total', 10, 3)->default(0)->nullable();
            $table->string('satuan')->nullable();
            $table->string('pelaksana')->nullable();
            $table->bigInteger('surat_pernyataan_id')->default(0);
            $table->bigInteger('dupak_id')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->text('message')->nullable();
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
        Schema::dropIfExists('tx_kegiatan');
    }
}
