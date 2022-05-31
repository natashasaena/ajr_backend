<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;
    protected $table = "mobil";
    protected $primaryKey ='id_mobil';
    protected $fillable =[
        'id_pemilik_mobil',
        'nama_mobil',
        'tipe_mobil',
        'jenis_transmisi',
        'jenis_bahan_bakar',
        'warna_mobil',
        'volume_bagasi',
        'fasilitas',
        'kapasitas',
        'plat_nomor',
        'nomor_stnk',
        'kategori_aset',
        'harga_sewa',
        'status_sewa',
        'tgl_terakhir_servis',
        'periode_kontrak_mulai',
        'periode_kontrak_akhir',
        'status_mobil',
        'foto'
    ];
}
