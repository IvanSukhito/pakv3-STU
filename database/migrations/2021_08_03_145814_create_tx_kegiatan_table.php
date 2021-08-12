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
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('upline_id')->default(0);
            $table->unsignedBigInteger('ms_kegiatan_id')->default(0);
            $table->unsignedBigInteger('top_id')->default(0);
            $table->unsignedBigInteger('permen_id')->default(0);
            $table->unsignedBigInteger('user_jenjang_id')->default(0);
            $table->unsignedBigInteger('kegiatan_jenjang_id')->default(0);
            $table->string('user_name')->nullable();
            $table->string('upline_name')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('judul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->longtext('dokument_pendukung')->nullable();
            $table->longtext('dokument_fisik')->nullable();
            $table->float('kredit_ori', 10, 3)->default(0)->nullable();
            $table->float('kredit', 10, 3)->default(0)->nullable();
            $table->string('satuan')->nullable();
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
