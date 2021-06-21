<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileInDupak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dupak', function (Blueprint $table) {
            $table->text('file_sp')->after('pdf_url')->nullable();
            $table->text('file_dupak')->after('file_sp')->nullable();
            $table->integer('status_upload')->after('file_dupak')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dupak', function (Blueprint $table) {
            $table->dropColumn('file_sp');
            $table->dropColumn('file_dupak');
            $table->dropColumn('status_upload');
        });
    }
}
