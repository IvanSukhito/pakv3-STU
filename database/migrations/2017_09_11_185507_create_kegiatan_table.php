<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('ms_kegiatan_id')->nullable();
            $table->integer('pelaksana_id')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('judul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('dokument')->nullable();
            $table->float('kredit_lama', 10, 3)->default(0)->nullable();
            $table->float('kredit', 10, 3)->default(0)->nullable();
            $table->float('kredit_total', 10, 3)->default(0)->nullable();
            $table->string('satuan')->nullable();
            $table->string('pelaksana')->nullable();
            $table->integer('sp')->default(0);
            $table->integer('dupak')->default(0);
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
        Schema::dropIfExists('kegiatan');
    }
}
