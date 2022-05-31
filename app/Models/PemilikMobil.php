<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemilikMobil extends Model
{
    use HasFactory;
    protected $table = "pemilik_mobil";
    protected $primaryKey = 'id_pemilik_mobil';
    protected $fillable = [
        'nama',
        'no_ktp',
        'alamat',
        'no_telp',
        'status_pemilik'
    ];
}
