<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Mobil;
use App\Models\Pegawai;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$transaksis = Transaksi::all();
        if(is_null(Transaksi::all())) {
            return response([
                'message' => 'Empty',
                'data' => null
            ], 400);
        }
        $transaksis = DB::table('transaksi')
                        ->leftjoin('pegawai', 'transaksi.id_pegawai', '=', 'pegawai.id_pegawai')
                        ->leftjoin('customer', 'transaksi.id_customer', '=', 'customer.id_customer')
                        ->leftjoin('driver', 'transaksi.id_driver', '=', 'driver.id_driver')
                        ->leftjoin('mobil', 'transaksi.id_mobil', '=', 'mobil.id_mobil')
                        ->leftjoin('promo', 'transaksi.id_promo', '=', 'promo.id_promo') 
                        ->select('transaksi.id as id_tr', 'transaksi.*', 'customer.nama as nama_customer', 'driver.nama as nama_driver', 'mobil.nama_mobil', 'pegawai.nama as nama_pegawai')
                        /* ->select('transaksi.id as id_tr', 'transaksi.*', 'customer.nama as nama_customer', 'customer.*',
                        'pegawai.*', 'promo.*', 'mobil.*', 'driver.*') */
                        ->orderBy('transaksi.id','asc')                     
                        ->get();

        if(count($transaksis)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksis
            ], 200);
        }
         
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function showByIdCustomer($id) {
        //$transaksi = Transaksi::where('id_customer', $id)->get();
        $transaksis = DB::table('transaksi')
        ->leftjoin('pegawai', 'transaksi.id_pegawai', '=', 'pegawai.id_pegawai')
        ->leftjoin('customer', 'transaksi.id_customer', '=', 'customer.id_customer')
        ->leftjoin('driver', 'transaksi.id_driver', '=', 'driver.id_driver')
        ->leftjoin('mobil', 'transaksi.id_mobil', '=', 'mobil.id_mobil')
        ->leftjoin('promo', 'transaksi.id_promo', '=', 'promo.id_promo') 
        /* ->select('transaksi.id as id_tr', 'transaksi.*', 'customer.nama as nama_customer', 'customer.*',
        'pegawai.*', 'promo.*', 'mobil.*', 'driver.*') */
        ->select('transaksi.id as id_tr', 'transaksi.*', 'customer.nama as nama_customer', 'driver.nama as nama_driver', 'mobil.nama_mobil', 'pegawai.nama as nama_pegawai')
        ->where('transaksi.id_customer', $id)
        ->where('transaksi.status_penyewaan', '!=', "Pembayaran Berhasil")
        ->orderBy('transaksi.id','asc')                     
        ->get();
        if(!is_null($transaksis)) {
            return response([
                'message' => 'Retrieve Transaksi Success',
                'data' => $transaksis
            ], 200);
        }

        return response([
            'message' => 'Transaksi Not Found',
            'data' => null
        ], 404);
    }
    public function showByIdCustomer2($id) {
        //$transaksi = Transaksi::where('id_customer', $id)->get();
        $transaksis = DB::table('transaksi')
        ->leftjoin('pegawai', 'transaksi.id_pegawai', '=', 'pegawai.id_pegawai')
        ->leftjoin('customer', 'transaksi.id_customer', '=', 'customer.id_customer')
        ->leftjoin('driver', 'transaksi.id_driver', '=', 'driver.id_driver')
        ->leftjoin('mobil', 'transaksi.id_mobil', '=', 'mobil.id_mobil')
        ->leftjoin('promo', 'transaksi.id_promo', '=', 'promo.id_promo') 
        /* ->select('transaksi.id as id_tr', 'transaksi.*', 'customer.nama as nama_customer', 'customer.*',
        'pegawai.*', 'promo.*', 'mobil.*', 'driver.*') */
        ->select('transaksi.id as id_tr', 'transaksi.*', 'customer.nama as nama_customer', 'driver.nama as nama_driver', 'mobil.nama_mobil', 'pegawai.nama as nama_pegawai')
        ->where('transaksi.id_customer', $id)
        ->where('transaksi.status_penyewaan', '=', "Pembayaran Berhasil")
        ->orderBy('transaksi.id','asc')                     
        ->get();
        if(!is_null($transaksis)) {
            return response([
                'message' => 'Retrieve Transaksi Success',
                'data' => $transaksis
            ], 200);
        }

        return response([
            'message' => 'Transaksi Not Found',
            'data' => null
        ], 404);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $storeData = $request->all();

        $orderDate = date('Y-m-d');
        $jenisPenyewaan = 0;
        if($storeData['jenis_penyewaan'] === 'Penyewaan Mobil + Driver'){
            $jenisPenyewaan = 1;
        }elseif($storeData['jenis_penyewaan']==='Penyewaan Mobil'){
            $jenisPenyewaan = 0;
        }
        //ini salah
        //yg masuk malah string 'tgl_mulai_sewa' sama 'tgl_selesai'
        // $dateStart = Carbon::parse('tgl_mulai_sewa');
        // $dateEnd = Carbon::parse('tgl_selesai');

        //yg masuk tanggal dari storeData
        $dateStart = Carbon::parse($storeData['tgl_mulai_sewa']);
        $dateEnd = Carbon::parse($storeData['tgl_selesai']);

        $dayInterval = $dateStart->diffInDays($dateEnd);
 
           
        $totalHargaDriver = 0.0;
        $idDriver =  $storeData['id_driver'];
        if($idDriver != null){
            $tarifDriver = DB::table('driver')
                            ->where('id_driver', $idDriver)->first()->tarif_driver;
            $totalHargaDriver = $dayInterval * $tarifDriver;
        }else{
            $totalHargaDriver = 0.0;
        }
       
        $idMobil = $storeData['id_mobil'];
        $tarifMobil = DB::table('mobil')
                            ->where('id_mobil', $idMobil)->first()->harga_sewa;
        $totalHargaMobil = $dayInterval * $tarifMobil;
        
        $lastId = Transaksi::select('id')->orderBy('id','desc')->first();
        $lastId = (int)substr($lastId, -3);
        $storeData['id_transaksi']= 'TRN'.date('ymd', strtotime($orderDate)).'0'.$jenisPenyewaan.'-'.$lastId+1;
        $storeData['tgl_transaksi'] = date('Y-m-d h:i:s');
        $storeData['tgl_pembayaran'] = null;
        $storeData['id_pegawai'] = null;
        $storeData['status_penyewaan'] = "Belum Diverifikasi";
        $storeData['metode_pembayaran'] = "-";
        $storeData['total_diskon'] = 0;
        $storeData['total_denda'] = 0;
        $storeData['total_harga_bayar'] = 0;
        $storeData['bukti_pembayaran'] = "-";
        $storeData['sub_total'] = $totalHargaDriver + $totalHargaMobil;
        $storeData['rating_driver'] = 0.0;
        $storeData['performa_driver'] = "-";
        $storeData['rating_rental'] = 0.0;
        $storeData['performa_rental'] = "-";

        $validate = Validator::make($storeData,[
            'id_transaksi' => 'required|unique:transaksi',
           //'id_driver' => 'required',
            'id_customer'=> 'required',
            'id_mobil'=> 'required',
            'jenis_penyewaan' => 'required',
            'tgl_transaksi'=> 'required|date',
            'tgl_mulai_sewa' => 'required|date_format:Y-m-d H:i:s',
            'tgl_selesai'=> 'required|date_format:Y-m-d H:i:s',
            //'tgl_pengembalian' => 'required|date',
            'sub_total' => 'required', 
            'status_penyewaan' => 'required',
            //'tgl_pembayaran'=> 'required',
            'metode_pembayaran'=> 'required',
            'total_diskon' => 'required',
            'total_denda' => 'required',
            'total_harga_bayar'=> 'required',
            //'bukti_pembayaran' => 'required',
            //'rating_driver' => 'required',S
            'performa_driver' => 'required',
            //'rating_rental' => 'required',
            //'performa_rental' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $transaksi = Transaksi::create($storeData);

        return response([
            'message' => 'Add Transaksi Success',
            'data' => $transaksi
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaksi = Transaksi::where('id_transaksi', $id)->get();

        if(!is_null($transaksi)){
            return response([
                'message' => 'Retrieve Transaksi Success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'message' => 'Transaksi Not Found',
            'data' => null
        ], 404);
    }
    
    public function laporanMobil($bulan,$tahun)
    {
        $laporan = DB::table('transaksi')
        ->join('mobil', 'transaksi.id_mobil',"=","mobil.id_mobil")
        ->select('tipe_mobil','nama_mobil', DB::raw('COUNT(transaksi.id_mobil) AS jumlah_peminjaman'),DB::raw('SUM(total_harga_bayar) AS total_pendapatan' ))
        //->selectRaw('tipe_mobil,nama_mobil,COUNT(transaksi.id_mobil),SUM(total_harga_bayar)')
        ->whereMonth('tgl_transaksi','=',$bulan)
        ->whereYear('tgl_transaksi','=',$tahun)
        ->groupBy('nama_mobil')
        ->groupBy('tipe_mobil')
        ->orderBy('total_pendapatan','desc')
        ->get()
        ->toArray();

        if(!is_null($laporan)) {
            return response([
                'message' => 'Retrieve Data Success',
                'data' => $laporan
            ], 200);
        }

        return response([
            'message' => 'Data Not Found',
            'data' => null
        ], 404);
    }

    public function laporanCustomer($bulan,$tahun)
    {
        $laporan = DB::table('transaksi')
        ->join('customer', 'transaksi.id_customer',"=","customer.id_customer")
        ->select('nama', DB::raw('COUNT(transaksi.id_customer) AS jumlah_transaksi'))
        //->selectRaw('tipe_mobil,nama_mobil,COUNT(transaksi.id_mobil),SUM(total_harga_bayar)')
        ->whereMonth('tgl_transaksi','=',$bulan)
        ->whereYear('tgl_transaksi','=',$tahun)
        ->groupBy('nama')
        ->orderBy('jumlah_transaksi','desc')
        ->limit(5)
        ->get()
        ->toArray();

        if(!is_null($laporan)) {
            return response([
                'message' => 'Retrieve Data Success',
                'data' => $laporan
            ], 200);
        }

        return response([
            'message' => 'Data Not Found',
            'data' => null
        ], 404);
    }

    public function laporanDriver($bulan,$tahun)
    {
        $laporan = DB::table('transaksi')
        ->join('driver', 'transaksi.id_driver',"=","driver.id_driver")
        ->select('transaksi.id_driver AS driver_id','nama', DB::raw('COUNT(transaksi.id_driver) AS jumlah_transaksi'))
        //->selectRaw('tipe_mobil,nama_mobil,COUNT(transaksi.id_mobil),SUM(total_harga_bayar)')
        ->whereMonth('tgl_transaksi','=',$bulan)
        ->whereYear('tgl_transaksi','=',$tahun)
        ->groupBy('transaksi.id_driver')
        ->groupBy('nama')
        ->orderBy('jumlah_transaksi','desc')
        ->limit(5)
        ->get()
        ->toArray();

        if(!is_null($laporan)) {
            return response([
                'message' => 'Retrieve Data Success',
                'data' => $laporan
            ], 200);
        }

        return response([
            'message' => 'Data Not Found',
            'data' => null
        ], 404);
    }

    public function laporanPendapatan($bulan,$tahun)
    {
        $laporan = DB::table('transaksi')
        ->join('customer', 'transaksi.id_customer',"=","customer.id_customer")
        ->join('mobil', 'transaksi.id_mobil',"=","mobil.id_mobil")
        ->select('nama','nama_mobil','jenis_penyewaan', DB::raw('COUNT(transaksi.id_customer) AS jumlah_transaksi'),DB::raw('SUM(total_harga_bayar) AS Pendapatan'))
        //->selectRaw('tipe_mobil,nama_mobil,COUNT(transaksi.id_mobil),SUM(total_harga_bayar)')
        ->whereMonth('tgl_transaksi','=',$bulan)
        ->whereYear('tgl_transaksi','=',$tahun)
        ->where('status_penyewaan',"=","Pembayaran Berhasil")
        ->groupBy('nama')
        ->groupBy('nama_mobil')
        ->groupBy('jenis_penyewaan')
        ->orderBy('nama','asc')
        ->get()
        ->toArray();

        if(!is_null($laporan)) {
            return response([
                'message' => 'Retrieve Data Success',
                'data' => $laporan
            ], 200);
        }

        return response([
            'message' => 'Data Not Found',
            'data' => null
        ], 404);
    }

    public function laporanPerforma($bulan,$tahun)
    {
        $laporan = DB::table('transaksi')
        ->join('driver', 'transaksi.id_driver',"=","driver.id_driver")
        ->select('transaksi.id_driver AS driver_id','nama', DB::raw('COUNT(transaksi.id_driver) AS jumlah_transaksi'),'rerata_rating')
        //->selectRaw('tipe_mobil,nama_mobil,COUNT(transaksi.id_mobil),SUM(total_harga_bayar)')
        ->whereMonth('tgl_transaksi','=',$bulan)
        ->whereYear('tgl_transaksi','=',$tahun)
        ->whereNotNull('rating_driver')
        ->where('rating_driver', '!=', 0)
        ->groupBy('nama')
        ->groupBy('driver_id')
        ->groupBy('rerata_rating')
        ->orderBy('jumlah_transaksi','desc')
        ->get()
        ->toArray();

        if(!is_null($laporan)) {
            return response([
                'message' => 'Retrieve Data Success',
                'data' => $laporan
            ], 200);
        }

        return response([
            'message' => 'Data Not Found',
            'data' => null
        ], 404);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::where('id_transaksi',$id)->first(); 

        if(is_null($transaksi)){
            return response([
                'message' => 'Transaksi Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'id_transaksi' => ['required',Rule::unique('transaksi')->ignore($transaksi)],
            //'id_pegawai' => 'required',
            //'id_driver' => 'required',
            'id_customer'=> 'required',
            'id_mobil'=> 'required',
            'jenis_penyewaan' => 'required',
            'tgl_transaksi'=> 'required|date',
            'tgl_mulai_sewa' => 'required|date',
            'tgl_selesai'=> 'required|date',
            //'tgl_pengembalian' => 'required|date',
            'sub_total' => 'required', 
            'status_penyewaan' => 'required',
            //'tgl_pembayaran'=> 'required',
            //'metode_pembayaran'=> 'required',
            'total_diskon' => 'required',
            'total_denda' => 'required',
            'total_harga_bayar'=> 'required',
            //'bukti_pembayaran' => 'required',
            //'rating_driver' => 'required',
            //'performa_driver' => 'required',
            //'rating_rental' => 'required',
            //'performa_rental' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

          
        $customer = Customer::where('id_customer', $updateData['id_customer'])->first();
        if($customer->sim == "-" && $updateData['jenis_penyewaan'] == "Penyewaan Mobil") {
            return response(['message' => 'Jenis Penyewaan tidak valid karena customer belum memiliki SIM'], 400);
        }

        $transaksi->id_transaksi = $updateData['id_transaksi'];
        $transaksi->id_pegawai = $updateData['id_pegawai'];
        $transaksi->id_driver = $updateData['id_driver'];
        $transaksi->id_customer = $updateData['id_customer'];
        $transaksi->id_mobil = $updateData['id_mobil'];
        $transaksi->id_promo = $updateData['id_promo'];
        $transaksi->jenis_penyewaan = $updateData['jenis_penyewaan'];
        $transaksi->tgl_transaksi = $updateData['tgl_transaksi'];
        $transaksi->tgl_mulai_sewa = $updateData['tgl_mulai_sewa'];
        $transaksi->tgl_selesai = $updateData['tgl_selesai'];
        $transaksi->tgl_pengembalian = $updateData['tgl_pengembalian'];
        $transaksi->sub_total = $updateData['sub_total'];
        $transaksi->status_penyewaan = $updateData['status_penyewaan'];
        $transaksi->tgl_pembayaran = $updateData['tgl_pembayaran'];
        $transaksi->metode_pembayaran = $updateData['metode_pembayaran'];
        $transaksi->total_diskon = $updateData['total_diskon'];
        $transaksi->total_denda = $updateData['total_denda'];
        $transaksi->total_harga_bayar = $updateData['total_harga_bayar'];
        $transaksi->bukti_pembayaran = $updateData['bukti_pembayaran'];
        //$transaksi->rating_driver = $updateData['rating_driver'];
        //$transaksi->performa_driver = $updateData['performa_driver'];
        //$transaksi->rating_rental = $updateData['rating_rental'];
        //$transaksi->performa_rental = $updateData['performa_rental'];
        if($transaksi->save()){
            return response([
                'message' => 'Update Transaksi Success',
                'data' => $transaksi
            ],200);
        };
      
        return response([
            'message' => 'Update Transaksi Failed',
            'data' => null
        ],400);
    }
    public function updateDetail(Request $request, $id)
    {
        $transaksi = Transaksi::where('id_transaksi',$id)->first(); 

        if(is_null($transaksi)){
            return response([
                'message' => 'Transaksi Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'id_transaksi' => ['required',Rule::unique('transaksi')->ignore($transaksi)],
            //'id_pegawai' => 'required',
            //'id_driver' => 'required',
            'id_customer'=> 'required',
            'id_mobil'=> 'required',
            'jenis_penyewaan' => 'required',
            'tgl_transaksi'=> 'required|date',
            'tgl_mulai_sewa' => 'required|date',
            'tgl_selesai'=> 'required|date',
            //'tgl_pengembalian' => 'required|date',
            'sub_total' => 'required', 
            'status_penyewaan' => 'required',
            //'tgl_pembayaran'=> 'required',
            //'metode_pembayaran'=> 'required',
            'total_diskon' => 'required',
            'total_denda' => 'required',
            'total_harga_bayar'=> 'required',
            //'bukti_pembayaran' => 'required',
            //'rating_driver' => 'required',
            //'performa_driver' => 'required',
            //'rating_rental' => 'required',
            //'performa_rental' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

          
        $customer = Customer::where('id_customer', $updateData['id_customer'])->first();
        if($customer->sim == "-" && $updateData['jenis_penyewaan'] == "Penyewaan Mobil") {
            return response(['message' => 'Jenis Penyewaan tidak valid karena customer belum memiliki SIM'], 400);
        }

        $transaksi->id_transaksi = $updateData['id_transaksi'];
        $transaksi->id_pegawai = $updateData['id_pegawai'];
        $transaksi->id_driver = $updateData['id_driver'];
        $transaksi->id_customer = $updateData['id_customer'];
        $transaksi->id_mobil = $updateData['id_mobil'];
        $transaksi->id_promo = $updateData['id_promo'];
        $transaksi->jenis_penyewaan = $updateData['jenis_penyewaan'];
        $transaksi->tgl_transaksi = $updateData['tgl_transaksi'];
        $transaksi->tgl_mulai_sewa = $updateData['tgl_mulai_sewa'];
        $transaksi->tgl_selesai = $updateData['tgl_selesai'];
        $transaksi->tgl_pengembalian = $updateData['tgl_pengembalian'];
        $transaksi->sub_total = $updateData['sub_total'];
        $transaksi->status_penyewaan = $updateData['status_penyewaan'];
        $transaksi->tgl_pembayaran = $updateData['tgl_pembayaran'];
        $transaksi->metode_pembayaran = $updateData['metode_pembayaran'];
        $transaksi->total_diskon = $updateData['total_diskon'];
        $transaksi->total_denda = $updateData['total_denda'];
        $transaksi->total_harga_bayar = $updateData['total_harga_bayar'];
        $transaksi->bukti_pembayaran = $updateData['bukti_pembayaran'];
        //$transaksi->rating_driver = $updateData['rating_driver'];
        //$transaksi->performa_driver = $updateData['performa_driver'];
        //$transaksi->rating_rental = $updateData['rating_rental'];
        //$transaksi->performa_rental = $updateData['performa_rental'];
        if($transaksi->save()){
            return response([
                'message' => 'Update Transaksi Success',
                'data' => $transaksi
            ],200);
        };
      
        return response([
            'message' => 'Update Transaksi Failed',
            'data' => null
        ],400);
    }

    public function updateRating(Request $request, $id)
    {
        $transaksi = Transaksi::where('id_transaksi',$id)->first(); 

        if(is_null($transaksi)){
            return response([
                'message' => 'Transaksi Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'id_transaksi' => ['required',Rule::unique('transaksi')->ignore($transaksi)],
            //'id_pegawai' => 'required',
            //'id_driver' => 'required',
            'id_customer'=> 'required',
            'id_mobil'=> 'required',
            'jenis_penyewaan' => 'required',
            'tgl_transaksi'=> 'required|date',
            'tgl_mulai_sewa' => 'required|date',
            'tgl_selesai'=> 'required|date',
            //'tgl_pengembalian' => 'required|date',
            'sub_total' => 'required', 
            'status_penyewaan' => 'required',
            //'tgl_pembayaran'=> 'required',
            //'metode_pembayaran'=> 'required',
            'total_diskon' => 'required',
            'total_denda' => 'required',
            'total_harga_bayar'=> 'required',
            //'bukti_pembayaran' => 'required',
            //'rating_driver' => 'required',
            //'performa_driver' => 'required',
            //'rating_rental' => 'required',
            //'performa_rental' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

          
        $customer = Customer::where('id_customer', $updateData['id_customer'])->first();
        if($customer->sim == "-" && $updateData['jenis_penyewaan'] == "Penyewaan Mobil") {
            return response(['message' => 'Jenis Penyewaan tidak valid karena customer belum memiliki SIM'], 400);
        }

        $transaksi->id_transaksi = $updateData['id_transaksi'];
        $transaksi->id_pegawai = $updateData['id_pegawai'];
        $transaksi->id_driver = $updateData['id_driver'];
        $transaksi->id_customer = $updateData['id_customer'];
        $transaksi->id_mobil = $updateData['id_mobil'];
        $transaksi->jenis_penyewaan = $updateData['jenis_penyewaan'];
        $transaksi->tgl_transaksi = $updateData['tgl_transaksi'];
        $transaksi->tgl_mulai_sewa = $updateData['tgl_mulai_sewa'];
        $transaksi->tgl_selesai = $updateData['tgl_selesai'];
        $transaksi->tgl_pengembalian = $updateData['tgl_pengembalian'];
        $transaksi->sub_total = $updateData['sub_total'];
        $transaksi->status_penyewaan = $updateData['status_penyewaan'];
        $transaksi->tgl_pembayaran = $updateData['tgl_pembayaran'];
        $transaksi->metode_pembayaran = $updateData['metode_pembayaran'];
        $transaksi->total_diskon = $updateData['total_diskon'];
        $transaksi->total_denda = $updateData['total_denda'];
        $transaksi->total_harga_bayar = $updateData['total_harga_bayar'];
        $transaksi->bukti_pembayaran = $updateData['bukti_pembayaran'];
        $transaksi->rating_driver = $updateData['rating_driver'];
        $transaksi->performa_driver = $updateData['performa_driver'];
        $transaksi->rating_rental = $updateData['rating_rental'];
        $transaksi->performa_rental = $updateData['performa_rental'];
        if($transaksi->save()){
            return response([
                'message' => 'Terima Kasih Atas Penilaiannya',
                'data' => $transaksi
            ],200);
        };
      
        return response([
            'message' => 'Gagal Memberi Penilaian',
            'data' => null
        ],400);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::where('id_transaksi',$id)->get();

        if(is_null($transaksi)){
            return response([
                'message' => 'Transaksi Not Found',
                'data' => null
            ],404);
        }

        // $transaksi->status_customer = 'Tidak Aktif';

        if($transaksi ->delete()){
            return response([
                'message' => 'Delete Transaksi Success',
                'data' => $transaksi
            ],200);
        }

        return response([
            'message' => 'Delete Transaksi Failed',
            'data' => null
        ],400); 
    }

    public function storeImage(Request $request)
    {
        $updateData = $request->all();

        if($updateData['imgB64'] != '') {
            $image_64 = $updateData['imgB64']; //your base64 encoded data

            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
          
            $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
          
          // find substring fro replace here eg: data:image/png;base64,
          
            $image = str_replace($replace, '', $image_64); 
          
            $image = str_replace(' ', '+', $image); 
          
            $imageName = Str::random(10).'.'.$extension;

            ///** @var \Illuminate\Support\Facades\Storage $user **/
            //$imgUrl = url('storage/app/public/'.$imageName);;
          
            //Storage::disk('public')->put($imageName, base64_decode($image));
            $imgUrl = url('storage/'.$imageName);;

            Storage::disk('public')->put($imageName, base64_decode($image));

            return response([
                'message' => 'Store Image Success',
                'data' => $imgUrl
            ], 200);
        }

        return response([
            'message' => 'Store Image Failed',
            'data' => null
        ], 400);
    }
    public function hitungTotalHarga($id) {

        $toBeUpdated = Transaksi::where('id_transaksi', $id)->first();
        $totalDiskon = 0.0;
        $idPromo = $toBeUpdated->id_promo;
        
        if($idPromo != null) {
            $besarDiskon = DB::table('promo')->where('id_promo', $idPromo)->first()->besar_diskon;
            $totalDiskon = $toBeUpdated->sub_total * ($besarDiskon/100);
        }

        $totalHarga = $toBeUpdated->sub_total + $toBeUpdated->total_denda - $totalDiskon;
        $toBeUpdated->total_diskon = $totalDiskon;
        $toBeUpdated->total_harga_bayar = $totalHarga;
        if($toBeUpdated->save()) {
            return response([
                'message' => 'Update Harga Success',
                'data' => $toBeUpdated
            ], 200);
        }

        return response([
            'message' => 'Update Harga Failed',
            'data' => null
        ], 400);
    }
    public function returnMobil($id) {

        $toBeUpdated = Transaksi::where('id_transaksi', $id)->first();
        $tgl_pengembalian = date('Y-m-d h:i:s');
        
        $toBeUpdated->tgl_pengembalian = $tgl_pengembalian;

        $dateStart = Carbon::parse($toBeUpdated->tgl_selesai_sewa);
        $dateEnd = Carbon::parse($tgl_pengembalian);
        $hourInterval = $dateStart->diffInHours($dateEnd);
  
        $idDriver =  $toBeUpdated->id_driver;
        $tarifDriver = 0.0;

        if($idDriver != null) {
            $tarifDriver = DB::table('driver')->where('id_driver', $idDriver)->first()->tarif_driver;
        }
        
        $idMobil = $toBeUpdated->id_mobil;
 
        $tarifMobil = DB::table('mobil')->where('id_mobil', $idMobil)->first()->harga_sewa;
        if($hourInterval > 3){
            $toBeUpdated->total_denda = $tarifDriver + $tarifMobil;}
        else{
            $toBeUpdated->total_denda = 0.0;
        
        }
        
        
        
        
        
        //kurang code utk save ke db nya
        if($toBeUpdated->save()) {
            return response([
                'message' => 'Return Mobil Success',
                'data' => $toBeUpdated
            ], 200);
        }

        return response([
            'message' => 'Return Mobil Failed',
            'data' => null
        ], 400);
        }
}
