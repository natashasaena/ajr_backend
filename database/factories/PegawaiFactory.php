<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pegawai>
 */
class PegawaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = $this->faker;
        $gender = $faker->randomElement(['male', 'female']);
        $jenisKelamin = ($gender === 'male' ? 'Laki-laki' : 'Perempuan');
        $firstNamePeg = $faker->firstName($gender);
        $lastNamePeg = $faker->lastName($gender);
        $tglLahirPeg = $faker->date($format = 'Y-m-d', $max = '-20years');
        return [
            //
            'id_role' => $faker->numberBetween(2, 3),
            'nama' => $firstNamePeg.' '.$lastNamePeg,
            'alamat' => $faker->address(),
            'tgl_lahir' => $tglLahirPeg,
            'jenis_kelamin' => $jenisKelamin,
            'email' => $firstNamePeg.'.'.$lastNamePeg.'@gmail.com',
            'no_telp' => $faker->phoneNumber(),
            'password' => bcrypt($tglLahirPeg),
            'status_pegawai' =>'Aktif',
            'foto' => $faker->imageUrl(),
        ];
    }
}
