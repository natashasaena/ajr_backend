<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;
    protected $table = "promo";
    protected $primaryKey = 'id_promo';
    protected $fillable=[
        'kode_promo',
        'jenis_promo',
        'keterangan',
        'besar_diskon',
        'status_promo'
    ];
}
