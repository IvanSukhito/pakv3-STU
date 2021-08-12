<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->bigInteger('permen_id')->default(0);
            $table->bigInteger('parent_id')->default(0);
            $table->bigInteger('top_id')->default(0);
            $table->text('name');
            $table->float('ak', 10, 3)->nullable();
            $table->string('satuan')->nullable();
            $table->bigInteger('jenjang_perancang_id')->default(0);
            $table->integer('orders')->default(1);
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
