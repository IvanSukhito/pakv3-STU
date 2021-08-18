<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxSuratPernyataanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_surat_pernyataan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('upline_id')->default(0);
            $table->unsignedBigInteger('top_kegiatan_id')->default(0);
            $table->date('tanggal')->nullable();
            $table->string('nomor')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->string('pdf')->nullable();
            $table->text('pdf_url')->nullable();
            $table->longText('info_surat_pernyataan')->nullable();
            $table->float('total_kredit', 10, 3)->default(0)->nullable();
            $table->integer('status')->default(0);
            $table->text('alasan_menolak')->nullable();
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
        Schema::dropIfExists('tx_surat_pernyataan');
    }
}
