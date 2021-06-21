<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnForUplodFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_staffs', function (Blueprint $table) {
            $table->string('file_sk_pengangkatan_perancang')->after('user_register_id')->nullable();
            $table->string('file_sk_terakhir_perancang')->after('file_sk_pengangkatan_perancang')->nullable();
            $table->string('file_kartu_pegawai')->after('file_sk_terakhir_perancang')->nullable();
            $table->string('file_seluruh_pak')->after('file_kartu_pegawai')->nullable();
            $table->string('file_ijazah')->after('file_seluruh_pak')->nullable();
            $table->string('file_sttpl')->after('file_ijazah')->nullable();
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
            $table->dropColumn('file_sk_pengangkatan_perancang');
            $table->dropColumn('file_sk_terakhir_perancang');
            $table->dropColumn('file_kartu_pegawai');
            $table->dropColumn('file_seluruh_pak');
            $table->dropColumn('file_ijazah');
            $table->dropColumn('file_sttpl');
        });
    }
}
