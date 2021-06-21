<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsKegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_kegiatan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id');
            $table->text('name');
            $table->float('ak', 10, 3)->nullable();
            $table->string('satuan')->nullable();
            $table->string('jenjang_perancang_id')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('ms_kegiatan');
    }
}
