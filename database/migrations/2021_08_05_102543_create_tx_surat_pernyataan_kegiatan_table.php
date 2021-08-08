<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxSuratPernyataanKegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_surat_pernyataan_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surat_pernyataan_id')->default(0);
            $table->unsignedBigInteger('kegiatan_id')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->index(['id', 'surat_pernyataan_id', 'kegiatan_id']);
            $table->foreign('surat_pernyataan_id')
                ->references('id')->on('tx_surat_pernyataan')
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
        Schema::dropIfExists('tx_surat_pernyataan_kegiatan');
    }
}
