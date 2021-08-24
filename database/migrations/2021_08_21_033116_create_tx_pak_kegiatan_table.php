<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTxPakKegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tx_pak_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pak_id')->default(0);
            $table->unsignedBigInteger('kegiatan_id')->default(0);
            $table->unsignedBigInteger('top_kegiatan_id')->default(0);
            $table->unsignedBigInteger('ms_kegiatan_id')->default(0);
            $table->float('kredit_ori', 10, 3)->default(0)->nullable();
            $table->float('kredit_old', 10, 3)->default(0)->nullable();
            $table->float('kredit_new', 10, 3)->default(0)->nullable();
            $table->string('message')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->index(['id', 'pak_id', 'kegiatan_id'], 'pak_kegiatan_index');
            $table->foreign('pak_id', 'pak_kegiatan')
                ->references('id')->on('tx_pak')
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
        Schema::dropIfExists('tx_pak_kegiatan');
    }
}
