<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use App\Models\Driver;
use App\Models\Mobil;
use App\Models\Promo;
use Illuminate\Support\Carbon;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi>
 */
class TransaksiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = $this->faker;

        static $transId = 1;
        static $countDays = 0;
        static $state = 0;
        if($transId % 5 == 0) {
            $countDays++;
        }
        if($countDays > 7) {
            $state = 2;
        } else if($countDays > 5) {
            $state = 1;
        }
        
        // $idPegawai = $faker->randomElement(DB::table('pegawai')->select('id_pegawai')->where('id_role', 3)->get());
        $idPegawai = $faker->randomElement(array_column(DB::table('pegawai')->select('id_pegawai')->where('id_role', 3)->get()->toArray(), 'id_pegawai'));
        $idCustomer = $faker->randomElement(array_column(DB::table('customer')->select('id_customer')->get()->toArray(), 'id_customer'));

        $minIdDriver = Driver::min('id_driver');
        $maxIdDriver = Driver::max('id_driver');
        $idDriverArray = array_column(DB::table('driver')->select('id_driver')->get()->toArray(), 'id_driver');
        $idDriver = null;
        $cekTidakPunyaSim = DB::table('customer')->select('*')->where('id_customer', $idCustomer)->get();
        if($cekTidakPunyaSim[0]->sim === '-') {
            $idDriver = $faker->randomElement($idDriverArray); // Jika tidak punya SIM
        } else {
            $idDriver = $faker->randomElement([$faker->randomElement($idDriverArray), null]); // Jika punya SIM
        }

        $minIdMobil = Mobil::min('id_mobil');
        $maxIdMobil = Mobil::max('id_mobil');
        $idMobil = $faker->numberBetween($minIdMobil, $maxIdMobil);

        $minIdPromo = Promo::min('id_promo');
        $maxIdPromo = Promo::max('id_promo');
        $idPromo = $faker->randomElement([$faker->numberBetween($minIdPromo, $maxIdPromo), null]);

        $jenisPenyewaan = null;
        $idJenisPenyewaan = null;
        if($idDriver == null) {
            $jenisPenyewaan = 'Penyewaan Mobil';
            $idJenisPenyewaan = 0;
        } else {
            $jenisPenyewaan = 'Penyewaan Mobil + Driver';
            $idJenisPenyewaan = 1;
        }

        //Init Var
        $orderDate = null;
        $mulaiSewaDate = null;
        $selesaiSewaDate = null;
        $kembaliDate = null;
        $totalHargaSewa = 0.0;
        $statusPenyewaan = '-';
        $transDate = null;
        $metodePembayaran = '-';
        $totalDiskon = 0.0;
        $totalDenda = 0.0;
        $totalHargaBayar = 0.0;
        $urlBuktiPembayaran = '-';
        $ratingDriver = 0.0;
        $performaDriver = '-';
        $ratingPerusahaan = 0.0;
        $performaPerusahaan = '-';

        // - Belum Diverifikasi - 1
        // - Sedang Berjalan - 2
        // - Pembayaran Berhasil - 3
        // - Pembayaran Gagal - 4

        $chooseStatus = 0;
        if($state == 0) {
            $chooseStatus = $faker->numberBetween(3, 4);
        } else if($state == 1){
            $chooseStatus = 2;
        } else if($state == 2) {
            $chooseStatus = 1;
        }

        if($chooseStatus == 1) {
            $orderDate = date('Y-m-d');
            $mulaiSewaDate = date('Y-m-d', strtotime($orderDate.' +'.$faker->numberBetween(2,5).' days'));
            $selesaiSewaDate = date('Y-m-d', strtotime($mulaiSewaDate.' +'.$faker->numberBetween(1,5).' days'));
            $kembaliDate = null;

            $dateStart = Carbon::parse($mulaiSewaDate);
            $dateEnd = Carbon::parse($selesaiSewaDate);
            $dayInterval = $dateStart->diffInDays($dateEnd);

            $tarifDriver = 0.0;
            $subtotalDriver = 0.0;
            if($idDriver != null) {
                $tarifDriver = DB::table('driver')->select('tarif_driver')->where('id_driver', $idDriver)->get();
                $subtotalDriver = $tarifDriver[0]->tarif_driver * $dayInterval;
            } else {
                $subtotalDriver = 0.0;
            }

            $tarifMobil = DB::table('mobil')->select('harga_sewa')->where('id_mobil', $idMobil)->get();
            $subtotalMobil = $tarifMobil[0]->harga_sewa * $dayInterval;

            $totalHargaSewa = $subtotalDriver + $subtotalMobil;
            $statusPenyewaan = 'Belum Diverifikasi';
            $transDate = null;
            $metodePembayaran = '-';
            $totalDiskon = 0.0;
            $totalDenda = 0.0;
            $totalHargaBayar = 0.0;
            $urlBuktiPembayaran = '-';
            $ratingDriver = 0.0;
            $performaDriver = '-';
            $ratingPerusahaan = 0.0;
            $performaPerusahaan = '-';
        } else if($chooseStatus == 2) {
            $orderDate = date('Y-m-d', strtotime('-2 day'));
            $mulaiSewaDate = date('Y-m-d', strtotime($orderDate.' +'.$faker->numberBetween(2,5).' days'));
            $selesaiSewaDate = date('Y-m-d', strtotime($mulaiSewaDate.' +'.$faker->numberBetween(1,5).' days'));
            $kembaliDate = null;

            $dateStart = Carbon::parse($mulaiSewaDate);
            $dateEnd = Carbon::parse($selesaiSewaDate);
            $dayInterval = $dateStart->diffInDays($dateEnd);

            $tarifDriver = 0.0;
            $subtotalDriver = 0.0;
            if($idDriver != null) {
                $tarifDriver = DB::table('driver')->select('tarif_driver')->where('id_driver', $idDriver)->get();
                $subtotalDriver = $tarifDriver[0]->tarif_driver * $dayInterval;
            } else {
                $subtotalDriver = 0.0;
            }

            $tarifMobil = DB::table('mobil')->select('harga_sewa')->where('id_mobil', $idMobil)->get();
            $subtotalMobil = $tarifMobil[0]->harga_sewa * $dayInterval;

            $totalHargaSewa = $subtotalDriver + $subtotalMobil;
            $statusPenyewaan = 'Sedang Berjalan';
            $transDate = null;
            $metodePembayaran = '-';
            $totalDiskon = 0.0;
            $totalDenda = 0.0;
            $totalHargaBayar = 0.0;
            $urlBuktiPembayaran = '-';
            $ratingDriver = 0.0;
            $performaDriver = '-';
            $ratingPerusahaan = 0.0;
            $performaPerusahaan = '-';
        } else if($chooseStatus == 3) {
            $orderDate = date('Y-m-d', strtotime('March 1 2022 +'.$countDays.' days'));
            $mulaiSewaDate = date('Y-m-d', strtotime($orderDate.' +'.$faker->numberBetween(2,5).' days'));
            $selesaiSewaDate = date('Y-m-d', strtotime($mulaiSewaDate.' +'.$faker->numberBetween(1,5).' days'));
            $kembaliDate = date('Y-m-d', strtotime($selesaiSewaDate.' +'.$faker->numberBetween(0,1).' days'));;

            $dateStart = Carbon::parse($mulaiSewaDate);
            $dateEnd = Carbon::parse($selesaiSewaDate);
            $dayInterval = $dateStart->diffInDays($dateEnd);

            $tarifDriver = 0.0;
            $subtotalDriver = 0.0;
            if($idDriver != null) {
                $tarifDriver = DB::table('driver')->select('tarif_driver')->where('id_driver', $idDriver)->get();
                $subtotalDriver = $tarifDriver[0]->tarif_driver * $dayInterval;
            } else {
                $subtotalDriver = 0.0;
            }

            $tarifMobil = DB::table('mobil')->select('harga_sewa')->where('id_mobil', $idMobil)->get();
            $subtotalMobil = $tarifMobil[0]->harga_sewa * $dayInterval;

            $totalHargaSewa = $subtotalDriver + $subtotalMobil;
            $statusPenyewaan = 'Pembayaran Berhasil';

            $transDate = date('Y-m-d', strtotime($kembaliDate.' +'.$faker->numberBetween(0,1).' days'));
            $metodePembayaran = '-';
            
            if($idPromo != null) {
                $tarifDiskon = DB::table('promo')->select('besar_diskon')->where('id_promo', $idPromo)->get();
                $totalDiskon = $totalHargaSewa * ($tarifDiskon[0]->besar_diskon / 100);
            } else {
                $totalDiskon = 0.0;
            }

            if($kembaliDate > $selesaiSewaDate) {
                $dateStart2 = Carbon::parse($kembaliDate);
                $dateEnd2 = Carbon::parse($selesaiSewaDate);
                $dayInterval2 = $dateStart2->diffInDays($dateEnd2);
                $keterlambatan = $dayInterval2;

                $dendaDriver = 0.0;
                if($idDriver != null) {
                    $dendaDriver = $tarifDriver[0]->tarif_driver * $keterlambatan;
                } else {
                    $dendaDriver = 0.0;
                }

                $dendaMobil = $tarifMobil[0]->harga_sewa * $keterlambatan;

                $totalDenda = $dendaMobil + $dendaDriver;
            } else {
                $totalDenda = 0.0;
            }

            $totalHargaBayar = $totalHargaSewa - $totalDiskon + $totalDenda;

            $urlBuktiPembayaran = $faker->imageUrl();
            $ratingDriver = $faker->randomElement([1.0, 2.0, 3.0, 4.0, 5.0]);
            $performaDriver = $faker->sentence();
            $ratingPerusahaan = $faker->randomElement([1.0, 2.0, 3.0, 4.0, 5.0]);
            $performaPerusahaan = $faker->sentence();
        } else if($chooseStatus == 4) {
            $orderDate = date('Y-m-d', strtotime('March 1 2022 +'.$countDays.' days'));
            $mulaiSewaDate = date('Y-m-d', strtotime($orderDate.' +'.$faker->numberBetween(2,5).' days'));
            $selesaiSewaDate = date('Y-m-d', strtotime($mulaiSewaDate.' +'.$faker->numberBetween(1,5).' days'));
            $kembaliDate = date('Y-m-d', strtotime($selesaiSewaDate.' +'.$faker->numberBetween(0,1).' days'));;

            $dateStart = Carbon::parse($mulaiSewaDate);
            $dateEnd = Carbon::parse($selesaiSewaDate);
            $dayInterval = $dateStart->diffInDays($dateEnd);

            $tarifDriver = 0.0;
            $subtotalDriver = 0.0;
            if($idDriver != null) {
                $tarifDriver = DB::table('driver')->select('tarif_driver')->where('id_driver', $idDriver)->get();
                $subtotalDriver = $tarifDriver[0]->tarif_driver * $dayInterval;
            } else {
                $subtotalDriver = 0.0;
            }

            $tarifMobil = DB::table('mobil')->select('harga_sewa')->where('id_mobil', $idMobil)->get();
            $subtotalMobil = $tarifMobil[0]->harga_sewa * $dayInterval;

            $totalHargaSewa = $subtotalDriver + $subtotalMobil;
            $statusPenyewaan = 'Pembayaran Gagal';

            $transDate = date('Y-m-d', strtotime($kembaliDate.' +'.$faker->numberBetween(0,1).' days'));
            $metodePembayaran = '-';

            if($idPromo != null) {
                $tarifDiskon = DB::table('promo')->select('besar_diskon')->where('id_promo', $idPromo)->get();
                $totalDiskon = $totalHargaSewa * ($tarifDiskon[0]->besar_diskon / 100);
            } else {
                $totalDiskon = 0.0;
            }

            if($kembaliDate > $selesaiSewaDate) {
                $dateStart2 = Carbon::parse($kembaliDate);
                $dateEnd2 = Carbon::parse($selesaiSewaDate);
                $dayInterval2 = $dateStart2->diffInDays($dateEnd2);
                $keterlambatan = $dayInterval2;

                $dendaDriver = 0.0;
                if($idDriver != null) {
                    $dendaDriver = $tarifDriver[0]->tarif_driver * $keterlambatan;
                } else {
                    $dendaDriver = 0.0;
                }

                $dendaMobil = $tarifMobil[0]->harga_sewa * $keterlambatan;

                $totalDenda = $dendaMobil + $dendaDriver;
            } else {
                $totalDenda = 0.0;
            }

            $totalHargaBayar = $totalHargaSewa - $totalDiskon + $totalDenda;

            $urlBuktiPembayaran = '-';
            $ratingDriver = 0.0;
            $performaDriver = '-';
            $ratingPerusahaan = 0.0;
            $performaPerusahaan = '-';
        } 
        
        //Cadangan
        // else if($chooseStatus == 5) {
        //     $orderDate = date('Y-m-d', strtotime('March 1 2022 +'.$countDays.' days'));
        //     $mulaiSewaDate = date('Y-m-d', strtotime($orderDate.' +'.$faker->numberBetween(2,5).' days'));
        //     $selesaiSewaDate = date('Y-m-d', strtotime($mulaiSewaDate.' +'.$faker->numberBetween(1,5).' days'));
        //     $kembaliDate = date('Y-m-d', strtotime($selesaiSewaDate.' +'.$faker->numberBetween(0,1).' days'));;

        //     $dateStart = Carbon::parse($mulaiSewaDate);
        //     $dateEnd = Carbon::parse($selesaiSewaDate);
        //     $dayInterval = $dateStart->diffInDays($dateEnd);

        //     $tarifDriver = 0.0;
        //     $subtotalDriver = 0.0;
        //     if($idDriver != null) {
        //         $tarifDriver = DB::table('driver')->select('tarif_driver')->where('id_driver', $idDriver)->get();
        //         $subtotalDriver = $tarifDriver[0]->tarif_driver * $dayInterval;
        //     } else {
        //         $subtotalDriver = 0.0;
        //     }

        //     $tarifMobil = DB::table('mobil')->select('harga_sewa')->where('id_mobil', $idMobil)->get();
        //     $subtotalMobil = $tarifMobil[0]->harga_sewa * $dayInterval;

        //     $totalHargaSewa = $subtotalDriver + $subtotalMobil;
        //     $statusPenyewaan = 'Sudah Lunas Belum Verifikasi';

        //     $transDate = date('Y-m-d', strtotime($kembaliDate.' +'.$faker->numberBetween(0,1).' days'));
        //     $metodePembayaran = '-';

        //     if($idPromo != null) {
        //         $tarifDiskon = DB::table('promo')->select('besar_diskon')->where('id_promo', $idPromo)->get();
        //         $totalDiskon = $totalHargaSewa * ($tarifDiskon[0]->besar_diskon / 100);
        //     } else {
        //         $totalDiskon = 0.0;
        //     }

        //     if($kembaliDate > $selesaiSewaDate) {
        //         $dateStart2 = Carbon::parse($kembaliDate);
        //         $dateEnd2 = Carbon::parse($selesaiSewaDate);
        //         $dayInterval2 = $dateStart2->diffInDays($dateEnd2);
        //         $keterlambatan = $dayInterval2;

        //         $dendaDriver = 0.0;
        //         if($idDriver != null) {
        //             $dendaDriver = $tarifDriver[0]->tarif_driver * $keterlambatan;
        //         } else {
        //             $dendaDriver = 0.0;
        //         }

        //         $dendaMobil = $tarifMobil[0]->harga_sewa * $keterlambatan;

        //         $totalDenda = $dendaMobil + $dendaDriver;
        //     } else {
        //         $totalDenda = 0.0;
        //     }

        //     $totalHargaBayar = $totalHargaSewa - $totalDiskon + $totalDenda;

        //     $urlBuktiPembayaran = $faker->imageUrl();
        //     $ratingDriver = 0.0;
        //     $performaDriver = '-';
        //     $ratingPerusahaan = 0.0;
        //     $performaPerusahaan = '-';
        // }


        // Catatan Kaki
        // $orderDate = date('Y-m-d', strtotime('March 1 2022 +'.$randomDay.' days'));
        // $mulaiSewaDate = date('Y-m-d', strtotime($orderDate.' +'.$faker->numberBetween(2,5).' days'));
        // $selesaiSewaDate = date('Y-m-d', strtotime($mulaiSewaDate.' +'.$faker->numberBetween(1,5).' days'));
        // $kembaliDate = $faker->randomElement([$selesaiSewaDate, date('Y-m-d', strtotime($selesaiSewaDate.' +'.$faker->numberBetween(1,5).' day'))]);
        // $transDate = date('ymd', strtotime('March 1 2022 +'.$randomDay.' days'));
        return [
            //
            'id_transaksi' => 'TRN'.date('ymd', strtotime($orderDate)).'0'.$idJenisPenyewaan.'-'.$transId++,
            'id_pegawai' => $idPegawai,
            'id_driver' => $idDriver,
            'id_customer' => $idCustomer,
            'id_mobil' => $idMobil,
            'id_promo' => $idPromo,
            'jenis_penyewaan' => $jenisPenyewaan,
            'tgl_transaksi' => $orderDate,
            'tgl_mulai_sewa' => $mulaiSewaDate,
            'tgl_selesai' => $selesaiSewaDate,
            'tgl_pengembalian' => $kembaliDate,
            'sub_total' => $totalHargaSewa,
            'status_penyewaan' => $statusPenyewaan,
            'tgl_pembayaran' => $transDate,
            'metode_pembayaran' => $metodePembayaran,
            'total_diskon' => $totalDiskon,
            'total_denda' => $totalDenda,
            'total_harga_bayar' => $totalHargaBayar,
            'bukti_pembayaran' => $urlBuktiPembayaran,
            'rating_driver' => $ratingDriver,
            'performa_driver' => $performaDriver,
            'rating_rental' => $ratingPerusahaan,
            'performa_rental' => $performaPerusahaan,
        ];
    }
}
