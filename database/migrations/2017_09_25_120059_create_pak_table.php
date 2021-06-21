<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pak', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('owner_id');
            $table->integer('dupak_id');
            $table->integer('bapak_id');
            $table->float('point1a1_lama', 10, 3)->default(0)->nullable();
            $table->float('point1a1_baru', 10, 3)->default(0)->nullable();
            $table->float('point1a1_total', 10, 3)->default(0)->nullable();
            $table->float('point1a2_lama', 10, 3)->default(0)->nullable();
            $table->float('point1a2_baru', 10, 3)->default(0)->nullable();
            $table->float('point1a2_total', 10, 3)->default(0)->nullable();
            $table->float('point1b_lama', 10, 3)->default(0)->nullable();
            $table->float('point1b_baru', 10, 3)->default(0)->nullable();
            $table->float('point1b_total', 10, 3)->default(0)->nullable();
            $table->float('point1c_lama', 10, 3)->default(0)->nullable();
            $table->float('point1c_baru', 10, 3)->default(0)->nullable();
            $table->float('point1c_total', 10, 3)->default(0)->nullable();
            $table->float('total_point_1_lama', 10, 3)->default(0)->nullable();
            $table->float('total_point_1_baru', 10, 3)->default(0)->nullable();
            $table->float('total_point_1', 10, 3)->default(0)->nullable();
            $table->float('point2_lama', 10, 3)->default(0)->nullable();
            $table->float('point2_baru', 10, 3)->default(0)->nullable();
            $table->float('point2_total', 10, 3)->default(0)->nullable();
            $table->float('total_point_2_lama', 10, 3)->default(0)->nullable();
            $table->float('total_point_2_baru', 10, 3)->default(0)->nullable();
            $table->float('total_point_2', 10, 3)->default(0)->nullable();
            $table->float('total_point_lama', 10, 3)->default(0)->nullable();
            $table->float('total_point_baru', 10, 3)->default(0)->nullable();
            $table->float('total_point', 10, 3)->default(0)->nullable();
            $table->string('pdf')->nullable();
            $table->text('pdf_url')->nullable();
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
        Schema::dropIfExists('pak');
    }
}
