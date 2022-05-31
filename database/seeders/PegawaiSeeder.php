<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pegawai;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create('id_ID');
        $gender = $faker->randomElement(['male', 'female']);
        $jenisKelamin = ($gender === 'male' ? 'Laki-laki' : 'Perempuan');
        $firstNamePeg = $faker->firstName($gender);
        $lastNamePeg = $faker->lastName($gender);
        $tglLahirPeg = $faker->date($format = 'Y-m-d', $max = '-20years');
        DB::table('pegawai')->insert([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'id_role' => 1,
            'nama' => $firstNamePeg.' '.$lastNamePeg,
            'alamat' => $faker->address(),
            'tgl_lahir' => $tglLahirPeg,
            'jenis_kelamin' => $jenisKelamin,
            'email' => $firstNamePeg.'.'.$lastNamePeg.'@gmail.com',
            'no_telp' => $faker->phoneNumber(),
            'password' =>bcrypt($tglLahirPeg),
            'status_pegawai' => 'Aktif',
            'foto' => $faker->imageUrl(),
        ]);
        Pegawai::factory(8)->create();
    }
}
