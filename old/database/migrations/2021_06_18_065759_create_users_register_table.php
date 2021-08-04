<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersRegisterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_register', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('unit_kerja_id')->default(0);
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('telp')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('create_staff_status')->default(0);
            $table->text('file')->nullable();
            $table->text('dokumen_lampiran')->nullable();
            $table->timestamps();
            $table->foreign('unit_kerja_id')
            ->references('id')->on('unit_kerja')
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
        Schema::dropIfExists('user_register');
    }
}
