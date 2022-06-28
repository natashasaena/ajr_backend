<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Driver extends Authenticatable
{
    use  HasApiTokens, HasFactory, Notifiable;
    protected $table = "driver";
    protected $fillable = [
        'id_driver',
        'nama',
        'alamat',
        'tgl_lahir',
        'jenis_kelamin',
        'email',
        'no_telp',
        'bahasa',
        'status_ketersediaan',
        'password',
        'tarif_driver',
        'rerata_rating',
        'status_driver',
        'sim',
        'surat_bebas_napza',
        'surat_kesehatan_jiwa',
        'skck'
    ];
}
