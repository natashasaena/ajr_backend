<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PemilikMobil>
 */
class PemilikMobilFactory extends Factory
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
        $firstNamePem = $faker->firstName($gender);
        $lastNamePem = $faker->lastName($gender);
        return [
            //
            'nama' => $firstNamePem.' '.$lastNamePem,
            'no_ktp' => $faker->nik(),
            'alamat' => $faker->address(),
            'no_telp' => $faker->phoneNumber(),
            'status_pemilik' =>'Aktif',
        ];
    }
}
