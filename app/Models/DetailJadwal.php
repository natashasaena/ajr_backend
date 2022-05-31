<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailJadwal extends Model
{
    use HasFactory;
    protected $table = "detail_jadwal";

    protected $fillable=[
        'id_pegawai',
        'id_jadwal'
    ];
}
