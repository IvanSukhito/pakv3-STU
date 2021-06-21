<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPermenIdAtMsKegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ms_kegiatan', function (Blueprint $table) {
            $table->integer('permen_id')->after('jenjang_perancang_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ms_kegiatan', function (Blueprint $table) {
            $table->dropColumn('permen_id');
        });
    }
}
