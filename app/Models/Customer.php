<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Customer extends Authenticatable
{
    use  HasApiTokens, HasFactory, Notifiable;

    protected $table = "customer";

    protected $fillable = [
        'id_customer',
        'nama',
        'alamat',
        'tgl_lahir',
        'jenis_kelamin',
        'email',
        'no_telp',
        'password',
        'status_customer',
        'sim',
        'identitas'
    ];
}
