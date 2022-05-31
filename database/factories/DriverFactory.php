<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        static $drivId = 1;
        static $countDays = 0;
        $faker = $this->faker;
        $gender = $faker->randomElement(['male', 'female']);
        $jenisKelamin = ($gender === 'male' ? 'Laki-laki' : 'Perempuan');
        $firstNameDriv = $faker->firstName($gender);
        $lastNameDriv = $faker->lastName($gender);
        $bahasaDriv = $faker->randomElement(['Indonesia', 'Inggris', 'Indonesia & Inggris']);
        $statusDriv = $faker->randomElement(['Available', 'Not Available']);
        $tarifDriv = $faker->randomElement([20000.0, 25000.0, 30000.0]);
        $rerataRating = $faker->randomElement([4.1, 4.0, 5.0, 4.4, 4.5]);
        $tglLahirDriv = $faker->date($format = 'Y-m-d', $max = '-30years');
        $drivRegDate = date('ymd', strtotime('March 1 2022 +'.$countDays.' days'));
        return [
            //
            'id_driver' => 'DRV'.$drivRegDate.'-'.$drivId++,
            'nama' => $firstNameDriv.' '.$lastNameDriv,
            'alamat' => $faker->address(),
            'tgl_lahir' => $tglLahirDriv,
            'jenis_kelamin' => $jenisKelamin,
            'email' => $firstNameDriv.'.'.$lastNameDriv.'@gmail.com',
            'no_telp' => $faker->phoneNumber(),
            'bahasa' => $bahasaDriv,
            'status_ketersediaan' => $statusDriv,
            'password' => bcrypt($tglLahirDriv),
            'tarif_driver' => $tarifDriv,
            'rerata_rating' => $rerataRating,
            'status_driver'=>'Aktif',
            'sim' => $faker->imageUrl(),
            'surat_bebas_napza' => $faker->imageUrl(),
            'surat_kesehatan_jiwa' => $faker->imageUrl(),
            'skck' => $faker->imageUrl(),
        ];
    }
}
