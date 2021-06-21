<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMasaPenilaianTerkahirEndToUserStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_staffs', function (Blueprint $table) {
            $table->date('masa_penilaian_terkahir_start')->nullable();
            $table->date('masa_penilaian_terkahir_end')->nullable();
            $table->decimal('angka_kredit')->length(10,3)->default(0)->change();
            $table->string('tahun_pelaksaan_diklat')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_staffs', function (Blueprint $table) {
            $table->dropColumn('masa_penilaian_terkahir_start');
            $table->dropColumn('masa_penilaian_terkahir_end');
            $table->string('angka_kredit')->change();
            $table->date('tahun_pelaksaan_diklat')->change();
        });
    }
}
