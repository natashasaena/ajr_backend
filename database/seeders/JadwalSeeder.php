<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $arrayHari = array('Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
        for($i = 0; $i < count($arrayHari); $i++) {
            for($j = 1; $j <=2; $j++) {
                DB::table('jadwal')->insert([
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'shift' => $j,
                    'hari' => $arrayHari[$i],
                ]);
            }
        }
    }
}
