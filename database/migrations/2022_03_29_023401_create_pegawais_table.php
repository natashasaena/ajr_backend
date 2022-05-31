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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->increments('id_pegawai');
            $table->timestamps();
            $table->unsignedInteger('id_role');
            $table->string('nama');
            $table->string('alamat');
            $table->date('tgl_lahir');
            $table->string('jenis_kelamin');
            $table->string('email');
            $table->string('no_telp');
            $table->string('password');
            $table->string('status_pegawai');
            $table->string('foto');

            $table->foreign('id_role')->references('id_role')->on('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pegawai');
    }
};
