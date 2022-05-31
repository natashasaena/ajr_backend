<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_jadwal', function (Blueprint $table) {
            $table->timestamps();
            $table->unsignedInteger('id_jadwal');
            $table->unsignedInteger('id_pegawai');

            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal')->cascadeOnDelete();
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('detail_jadwals', function (Blueprint $table) {
        //     $table->dropForeign('id_jadwal');
        // });
        // Schema::table('detail_jadwals', function (Blueprint $table) {
        //     $table->dropForeign('id_pegawai');
        // });
        Schema::dropIfExists('detail_jadwal');
    }
};
