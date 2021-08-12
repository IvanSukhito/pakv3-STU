<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxDupakKegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_dupak_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dupak_id')->default(0);
            $table->unsignedBigInteger('kegiatan_id')->default(0);
            $table->unsignedBigInteger('ms_kegiatan_id')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->index(['id', 'dupak_id', 'kegiatan_id'], 'dupak_kegiatan_index');
            $table->foreign('dupak_id', 'dupak_kegiatan')
                ->references('id')->on('tx_dupak')
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
        Schema::dropIfExists('tx_dupak_kegiatan');
    }
}
