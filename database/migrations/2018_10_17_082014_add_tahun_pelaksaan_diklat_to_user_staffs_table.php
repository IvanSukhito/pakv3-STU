<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTahunPelaksaanDiklatToUserStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_staffs', function (Blueprint $table) {
            $table->date('tahun_pelaksaan_diklat')->nullable();
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
            $table->dropColumn('tahun_pelaksaan_diklat');
        });
    }
}
