<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('promo')->insert([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'kode_promo' => 'MHS',
            'jenis_promo' => 'Pelajar & Mahasiswa',
            'keterangan' => 'Promo bagi customer yang berusia mulai dari 17-22 tahun
            dan memiliki kartu identitas pelajar/mahasiswa, mendapat 
            diskon sebesar 20%',
            'besar_diskon' => 20.0,
            'status_promo' => 'Aktif',
        ]);

        DB::table('promo')->insert([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'kode_promo' => 'BDAY',
            'jenis_promo' => 'Ulang Tahun',
            'keterangan' => 'Promo bagi customer yang sedang berulang tahun, mendapat 
            diskon sebesar 15%.',
            'besar_diskon' => 15.0,
            'status_promo' => 'Tidak Aktif',
        ]);

        DB::table('promo')->insert([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'kode_promo' => 'MDK',
            'jenis_promo' => 'Mudik',
            'keterangan' => 'Promo berlaku selama masa libur Lebaran dan Nataru, 
            mendapat diskon sebesar 25%.',
            'besar_diskon' => 25.0,
            'status_promo' => 'Tidak Aktif',
        ]);

        DB::table('promo')->insert([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'kode_promo' => 'WKN',
            'jenis_promo' => 'Weekend ',
            'keterangan' => 'Promo berlaku selama hari Sabtu dan Minggu, mendapat 
            diskon sebesar 10%',
            'besar_diskon' => 10.0,
            'status_promo' => 'Aktif',
        ]);
    }
}
