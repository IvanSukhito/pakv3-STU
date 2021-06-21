<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSkColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_staffs', function (Blueprint $table) {
            $table->text('sk_sekretariat')->after('file_sk_pengangkatan_perancang')->nullable();
            $table->text('sk_tim_penilai')->after('sk_sekretariat')->nullable();
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
            $table->dropColumn('sk_sekretariat');
            $table->dropColumn('sk_tim_penilai');
        });
    }
}
