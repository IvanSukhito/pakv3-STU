<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTmtAndTahunToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_staffs', function (Blueprint $table) {
            $table->date('tmt_golongan')->nullable();
            $table->string('status_diangkat')->nullable();
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
            $table->dropColumn('tmt_golongan');
            $table->dropColumn('status_diangkat');
        });
    }
}
