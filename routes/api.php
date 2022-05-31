<?php
use App\Http\Controllers\Api\PegawaiAuthController;
use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\PemilikMobilController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\DetailJadwalController;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\MobilController;
use App\Http\Controllers\Api\TransaksiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(CustomerAuthController::class)->group(function(){
    Route::post('/register/customer', 'register');
    Route::post('/login/customer', 'login');
    Route::post('/logout/customer', 'logout')->middleware('auth:customer_api');
});

Route::controller(CustomerController::class)->group(function(){
    Route::get('/customer', 'index');
    Route::get('/customer/show/{id}','show');
    Route::post('/customer/store', 'store');
    Route::put('/customer/update/{id}','update');
    Route::delete('/customer/delete/{id}','destroy');
    Route::post('/customer/storeimage', 'storeImage');
});

Route::controller(DriverAuthController::class)->group(function(){
    Route::post('/register/driver', 'register');
    Route::post('/login/driver', 'login');
    Route::post('/logout/driver', 'logout')->middleware('auth:driver_api');
});

Route::controller(PegawaiAuthController::class)->group(function(){
    Route::post('/register/pegawai', 'register');
    Route::post('/login/pegawai', 'login');
    Route::post('/logout/pegawai', 'logout');
});

Route::controller(DetailJadwalController::class)->group(function(){
    Route::get('/detailjadwal', 'index');
    Route::get('/detailjadwal/show/{id}','show');
    Route::post('/detailjadwal/store', 'store');
    Route::put('/detailjadwal/update/{id_pegawai}/{id_jadwal}', 'update');
    Route::delete('/detailjadwal/delete/{id_pegawai}/{id_jadwal}', 'destroy');
    Route::get('detailjadwal/get/idpegawai/{id}', 'showByIdPegawai');
    Route::get('detailjadwal/get/idjadwal/{id}', 'showByIdJadwal');
});

Route::controller(DriverController::class)->group(function(){
    Route::get('/driver', 'index');
    Route::get('/driver/show/{id}','show');
    Route::post('/driver/store', 'store');
    Route::put('/driver/update/{id}','update');
    Route::delete('/driver/delete/{id}','destroy');
    Route::post('/driver/storeimage', 'storeImage');
    Route::get('/driver/showAvailable','driverAvailable');
});
Route::controller(PegawaiController::class)->group(function(){
    Route::get('/pegawai','index');
    Route::get('/pegawai/show/{id}','show');
    Route::post('/pegawai/store', 'store');
    Route::put('/pegawai/update/{id}','update');
    Route::delete('/pegawai/delete/{id}','destroy');
    Route::post('/pegawai/storeimage', 'storeImage');
});
Route::controller(OrderController::class)->group(function () {
    Route::get('/orders/{id}', 'show');
    Route::post('/orders', 'store');
});

Route::controller(PemilikMobilController::class)->group(function(){
    Route::get('/pemilikmobil','index');
    Route::get('/pemilikmobil/show/{id}','show');
    Route::post('/pemilikmobil/store', 'store');
    Route::put('/pemilikmobil/update/{id}','update');
    Route::delete('/pemilikmobil/delete/{id}','destroy');
});

Route::controller(MobilController::class)->group(function(){
    Route::get('/mobil','index');
    Route::get('/mobil/show/{id}','show');
    Route::post('/mobil/store', 'store');
    Route::put('/mobil/update/{id}','update');
    Route::delete('/mobil/delete/{id}','destroy');
    Route::get('/mobil/cekKetersediaan', 'cekKetersediaan');
    Route::get('/mobil/tampilkontrakakanhabis', 'tampilKontrakAkanHabis');
    Route::put('/mobil/updatePeriodeKontrak', 'updatePeriodeKontrak');
    Route::post('/mobil/storeimage', 'storeImage');

});

Route::controller(JadwalController::class)->group(function(){
    Route::get('/jadwal','index');
    Route::get('/jadwal/show/{id}','show');
    Route::post('/jadwal/store', 'store');
    Route::put('/jadwal/update/{id}','update');
    Route::delete('/jadwal/delete/{id}','destroy');
    Route::get('/jadwal/get/{hari}/{shift}', 'showByHariAndShift');
});

Route::controller(PromoController::class)->group(function(){
    Route::get('/promo','index');
    Route::get('/promo/show/{id}','show');
    Route::post('/promo/store', 'store');
    Route::put('/promo/update/{id}','update');
    Route::delete('/promo/delete/{id}','destroy');
});
Route::controller(TransaksiController::class)->group(function(){
    Route::get('/transaksi','index');
    Route::get('/transaksi/showbyidcustomer/{id}', 'showByIdCustomer');
    Route::get('/transaksi/show/{id}','show');
    Route::get('/transaksi/laporan/mobil/{bulan}/{tahun}','laporanMobil');
    Route::get('/transaksi/laporan/customer/{bulan}/{tahun}','laporanCustomer');
    Route::get('/transaksi/laporan/driver/{bulan}/{tahun}','laporanDriver');
    Route::get('/transaksi/laporan/pendapatan/{bulan}/{tahun}','laporanPendapatan');
    Route::get('/transaksi/laporan/performa/{bulan}/{tahun}','laporanPerforma');
    Route::post('/transaksi/store', 'store');
    Route::put('/transaksi/update/{id}','update');
    Route::put('/transaksi/rating/{id}','updateRating');
    Route::delete('/transaksi/delete/{id}','destroy');
    Route::post('/transaksi/storeimage', 'storeImage');
    Route::put('/transaksi/returnmobil/{id}','returnMobil');
    Route::put('/transaksi/hitung/{id}','hitungTotalHarga');
});