<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBapakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bapak', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('owner_id')->nullable();
            $table->integer('dupak_id')->nullable();
            $table->integer('ketua_id')->nullable();
            $table->integer('wakil_ketua_id')->nullable();
            $table->integer('sekretariat_id')->nullable();
            $table->text('berita_acara')->nullable();
            $table->string('pdf')->nullable();
            $table->text('pdf_url')->nullable();
            $table->string('pak_pdf')->nullable();
            $table->text('pak_pdf_url')->nullable();
            $table->text('unsur_utama')->nullable();
            $table->text('unsur_penunjang')->nullable();
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
        Schema::dropIfExists('bapak');
    }
}
