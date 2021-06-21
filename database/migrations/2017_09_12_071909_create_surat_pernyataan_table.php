<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuratPernyataanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_pernyataan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('supervisor_id')->nullable();
            $table->integer('dupak_id')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('nomor')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->string('pdf')->nullable();
            $table->text('pdf_url')->nullable();
            $table->tinyInteger('approved')->default(0);
            $table->tinyInteger('connect')->default(0);
            $table->float('total_kredit_lama', 10, 3)->default(0)->nullable();
            $table->float('total_kredit', 10, 3)->default(0)->nullable();
            $table->float('total_kredit_total', 10, 3)->default(0)->nullable();
            $table->string('lokasi')->nullable();
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
        Schema::dropIfExists('surat_pernyataan');
    }
}
