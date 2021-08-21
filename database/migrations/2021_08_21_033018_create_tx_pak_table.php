<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxPakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_pak', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('upline_id')->default(0);
            $table->unsignedBigInteger('top_kegiatan_id')->default(0);
            $table->unsignedBigInteger('tim_penilai_id')->default(0);
            $table->unsignedBigInteger('unit_kerja_id')->default(0);
            $table->unsignedBigInteger('dupak_id')->default(0);
            $table->longText('info_pak')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('nomor')->nullable();
            $table->string('pdf')->nullable();
            $table->text('pdf_url')->nullable();
            $table->text('file_upload_dupak')->nullable();
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
        Schema::dropIfExists('tx_pak');
    }
}
