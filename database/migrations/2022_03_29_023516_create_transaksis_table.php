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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('id_transaksi')->unique();
            $table->timestamps();
            $table->unsignedInteger('id_pegawai')->nullable();
            $table->string('id_driver')->nullable();
            $table->string('id_customer');
            $table->unsignedInteger('id_mobil');
            $table->unsignedInteger('id_promo')->nullable();
            $table->string('jenis_penyewaan');
            $table->dateTime('tgl_transaksi');
            $table->dateTime('tgl_mulai_sewa');
            $table->dateTime('tgl_selesai');
            $table->dateTime('tgl_pengembalian')->nullable();
            $table->double('sub_total');
            $table->string('status_penyewaan');
            $table->dateTime('tgl_pembayaran')->nullable();
            $table->string('metode_pembayaran');
            $table->double('total_diskon');
            $table->double('total_denda');
            $table->double('total_harga_bayar');
            $table->string('bukti_pembayaran');
            $table->double('rating_driver');
            $table->string('performa_driver');
            $table->double('rating_rental');
            $table->string('performa_rental');

            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai');
            $table->foreign('id_customer')->references('id_customer')->on('customer');
            $table->foreign('id_driver')->references('id_driver')->on('driver');
            $table->foreign('id_mobil')->references('id_mobil')->on('mobil');
            $table->foreign('id_promo')->references('id_promo')->on('promo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
};
