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
        Schema::create('mobil', function (Blueprint $table) {
            $table->increments('id_mobil');
            $table->timestamps();
            $table->unsignedInteger('id_pemilik_mobil')->nullable();
            $table->string('nama_mobil');
            $table->string('tipe_mobil');
            $table->string('jenis_transmisi');
            $table->string('jenis_bahan_bakar');
            $table->string('warna_mobil');
            $table->double('volume_bagasi');
            $table->string('fasilitas');
            $table->integer('kapasitas');
            $table->string('plat_nomor');
            $table->string('nomor_stnk');
            $table->string('kategori_aset');
            $table->double('harga_sewa');
            $table->string('status_sewa');
            $table->date('tgl_terakhir_servis');
            $table->date('periode_kontrak_mulai')->nullable();
            $table->date('periode_kontrak_akhir')->nullable();
            $table->string('status_mobil');
            $table->string('foto');

            $table->foreign('id_pemilik_mobil')->nullable()->references('id_pemilik_mobil')->on('pemilik_mobil');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobil');
    }
};
