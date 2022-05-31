<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pegawai;
use App\Models\Jadwal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DetailJadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countPegawai = Pegawai::count('id_pegawai');
        $countJadwal = Jadwal::count('id_jadwal');

        // Setengah pegawai awal ambil shift 1
        // Seperempat pegawai ambil shift 1 Hari Senin - Rabu
        for($i = 1; $i < floor($countPegawai/4); $i++) {
            for($j = 1; $j < $countJadwal+1; $j++) {
                if($j % 2 == 1 && $j <= $countJadwal/2) {
                    DB::table('detail_jadwal')->insert([
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'id_jadwal' => $j,
                        'id_pegawai' => ($i + 1),
                    ]);
                }
            }
        }

        // Seperempat pegawai ambil shift 1 Hari Kamis - Minggu
        for($i = floor($countPegawai/4); $i < floor($countPegawai/2); $i++) {
            for($j = 1; $j < $countJadwal+1; $j++) {
                if($j % 2 == 1 && $j > $countJadwal/2) {
                    DB::table('detail_jadwal')->insert([
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'id_jadwal' => $j,
                        'id_pegawai' => ($i + 1),
                    ]);
                }
            }
        }

        // Setengah pegawai akhir ambil shift 2
        // Seperempat pegawai ambil shift 2 Hari Senin - Rabu
        for($i = floor($countPegawai/2); $i < floor($countPegawai*3/4); $i++) {
            for($j = 1; $j < $countJadwal+1; $j++) {
                if($j % 2 == 0 && $j <= $countJadwal/2) {
                    DB::table('detail_jadwal')->insert([
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'id_jadwal' => $j,
                        'id_pegawai' => ($i + 1),
                    ]);
                }
            }
        }

        // Seperempat pegawai ambil shift 2 Hari Kamis - Minggu
        for($i = floor($countPegawai*3/4); $i < $countPegawai; $i++) {
            for($j = 1; $j < $countJadwal+1; $j++) {
                if($j % 2 == 0 && $j > $countJadwal/2) {
                    DB::table('detail_jadwal')->insert([
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'id_jadwal' => $j,
                        'id_pegawai' => ($i + 1),
                    ]);
                }
            }
        }
    }
}
