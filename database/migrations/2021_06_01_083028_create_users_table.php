<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('upline_id')->default(0);
            $table->unsignedBigInteger('pangkat_id')->default(0);
            $table->unsignedBigInteger('golongan_id')->default(0);
            $table->unsignedBigInteger('jenjang_perancang_id')->default(0);
            $table->unsignedBigInteger('pendidikan_id')->default(0);
            $table->unsignedBigInteger('unit_kerja_id')->default(0);
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('email')->nullable();
            $table->string('kartu_pegawai')->nullable();
            $table->string('alamat_kantor')->nullable();
            $table->integer('jenis_kelamin')->default(1);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->unsignedBigInteger('role_id')->default(0);
            $table->dateTime('last_login')->nullable();
            $table->tinyInteger('calon_perancang')->default(0);
            $table->tinyInteger('perancang')->default(0);
            $table->tinyInteger('atasan')->default(0);
            $table->tinyInteger('sekretariat')->default(0);
            $table->tinyInteger('tim_penilai')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->index(['id', 'username']);
            $table->foreign('role_id')
                ->references('id')->on('role')
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
        Schema::dropIfExists('users');
    }
}
