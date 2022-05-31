<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = "transaksi";

    protected $fillable=[
        'id_transaksi',
        'id_pegawai',
        'id_driver',
        'id_customer',
        'id_mobil',
        'jenis_penyewaan',
        'tgl_transaksi',
        'tgl_mulai_sewa',
        'tgl_selesai',
        'tgl_pengembalian',
        'sub_total',
        'status_penyewaan',
        'tgl_pembayaran',
        'metode_pembayaran',
        'total_diskon',
        'total_denda',
        'total_harga_bayar',
        'bukti_pembayaran',
        'rating_driver',
        'performa_driver',
        'rating_rental',
        'performa_rental'
    ];
}
