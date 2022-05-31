<?php

namespace Database\Factories;

use App\Models\PemilikMobil;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mobil>
 */
class MobilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = $this->faker;

        // $minIdPemilikMobil = DB::table('pemilik_mobil')->min('id_pemilik_mobil');
        // $maxIdPemilikMobil = DB::table('pemilik_mobil')->max('id_pemilik_mobil');

        $minIdPemilikMobil = PemilikMobil::min('id_pemilik_mobil');
        $maxIdPemilikMobil = PemilikMobil::max('id_pemilik_mobil');
        // $idPemilikMobil = $faker->randomElement([$faker->numberBetween($minIdPemilikMobil, $maxIdPemilikMobil), null]);

        $assetPerusahaanAtauMitra = $faker->numberBetween(1, 2);
        $kategoriAset = null;
        $idPemilikMobil = null;
        $periodeKontrakMulai = null;
        $periodeKontrakAkhir = null;

        if($assetPerusahaanAtauMitra == 1) {
            $kategoriAset = 'Aset Mitra';
            $idPemilikMobil = $faker->numberBetween($minIdPemilikMobil, $maxIdPemilikMobil);
            $periodeKontrakMulai = $faker->dateTimeBetween($startDate = '-1 years', $endDate = '-9 months');
            $periodeKontrakAkhir = $faker->dateTimeBetween($startDate = '+3 months', $endDate = '+1 years');
        } else if($assetPerusahaanAtauMitra == 2) {
            $kategoriAset = 'Aset Perusahaan';
            $idPemilikMobil = null;
            $periodeKontrakMulai = null;
            $periodeKontrakAkhir = null;
        }

        $chooseMobil = $faker->numberBetween(1, 8);
        
        $namaMobil = null;
        $tipeMobil = null;
        $jenisTransmisi = null;
        $jenisBahanBakar = null;
        $fasilitasMobil = null;

        if($chooseMobil == 1) {
            $namaMobil = 'Toyota New Vios';
            $tipeMobil = 'Sedan';
            $jenisTransmisi = 'AT';
            $jenisBahanBakar = 'Pertamax';
            $fasilitasMobil = 'AC, Safety Bag';
        } else if($chooseMobil == 2) {
            $namaMobil = 'Honda Civic';
            $tipeMobil = 'Sedan';
            $jenisTransmisi = 'AT';
            $jenisBahanBakar = 'Pertamax';
            $fasilitasMobil = 'AC, Multimedia, Honda Sensing Safety';
        } else if($chooseMobil == 3) {
            $namaMobil = 'Toyota New Agya';
            $tipeMobil = 'City Car';
            $jenisTransmisi = 'AT';
            $jenisBahanBakar = 'Pertalite';
            $fasilitasMobil = 'AC, Air Bag';
        } else if($chooseMobil == 4) {
            $namaMobil = 'Honda Brio';
            $tipeMobil = 'City Car';
            $jenisTransmisi = 'MT';
            $jenisBahanBakar = 'Pertalite';
            $fasilitasMobil = 'AC, Air Bag';
        } else if($chooseMobil == 5) {
            $namaMobil = 'Toyota Rush';
            $tipeMobil = 'SUV';
            $jenisTransmisi = 'AT';
            $jenisBahanBakar = 'Pertamax';
            $fasilitasMobil = 'AC, Air Bag';
        } else if($chooseMobil == 6) {
            $namaMobil = 'Toyota Fortuner';
            $tipeMobil = 'SUV';
            $jenisTransmisi = 'AT';
            $jenisBahanBakar = 'Pertamax';
            $fasilitasMobil = 'AC, Air Bag, Safety System';
        } else if($chooseMobil == 7) {
            $namaMobil = 'Toyota New Avanza';
            $tipeMobil = 'MPV';
            $jenisTransmisi = 'CVT';
            $jenisBahanBakar = 'Pertalite';
            $fasilitasMobil = 'AC, Air Bag, Safety System';
        } else if($chooseMobil == 8) {
            $namaMobil = 'Toyota Alphard';
            $tipeMobil = 'MPV';
            $jenisTransmisi = 'AT';
            $jenisBahanBakar = 'Pertamax';
            $fasilitasMobil = 'AC, Air Bag';
        }

        // $namaMobil = $faker->randomElement([
        //     'Toyota New Vios',
        //     'Honda Civic',
        //     'Toyota New Agya',
        //     'Honda Brio',
        //     'Toyota Rush',
        //     'Toyota Fortuner',
        //     'Toyota New Avanza',
        //     'Toyota Alphard',
        // ]);
    
        // $tipeMobil = $faker->randomElement([
        //     'Sedan',
        //     'City Car',
        //     'SUV',
        //     'MPV',
        // ]);

        // $jenisTransmisi = $faker->randomElement([
        //     'AT',
        //     'MT',
        // ]);

        // $jenisBahanBakar = $faker->randomElement([
        //     'Pertamax',
        //     'Pertalite',
        // ]);

        // $fasilitasMobil = $faker->randomElement([
        //     'AC, Safety Bag',
        //     'AC, Multimedia, Honda Sensing Safety',
        //     'AC, Air Bag, Safety System',
        // ]);

        //

        $kodePlatAwal = $faker->randomElement([
            'AB', 'AD', 'AA', 'AG', 'AE', 'DK'
        ]);

        $digitPlat = $faker->randomNumber(4, true);

        $kodePlatAkhir = strtoupper($faker->lexify('??'));

        // $kategoriAset = $faker->randomElement([
        //     'Aset Perusahaan', 'Aset Mitra',
        // ]);

        $hargaSewa = $faker->randomElement([
            400000, 500000, 350000, 300000,
            800000, 450000,
        ]);

        $statusSewa = $faker->randomElement([
            'Available', 'Not Available',
        ]);

        return [
            //
            'id_pemilik_mobil' => $idPemilikMobil,
            'nama_mobil' => $namaMobil,
            'tipe_mobil' => $tipeMobil,
            'jenis_transmisi' => $jenisTransmisi,
            'jenis_bahan_bakar' => $jenisBahanBakar,
            'warna_mobil' => $faker->randomElement(['Merah','Abu-abu','Hitam','Putih']),
            'volume_bagasi' => $faker->numberBetween(1, 9),
            'fasilitas' => $fasilitasMobil,
            'kapasitas' => $faker->numberBetween(4, 8),
            'plat_nomor' => $kodePlatAwal.$digitPlat.$kodePlatAkhir,
            'nomor_stnk' => $faker->randomNumber(7, true),
            'kategori_aset' => $kategoriAset,
            'harga_sewa' => $hargaSewa,
            'status_sewa' => $statusSewa,
            'tgl_terakhir_servis' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
            'periode_kontrak_mulai' => $periodeKontrakMulai,
            'periode_kontrak_akhir' => $periodeKontrakAkhir,
            'status_mobil' => 'Aktif',
            'foto' => $faker->imageUrl(),
        ];
    }
}
