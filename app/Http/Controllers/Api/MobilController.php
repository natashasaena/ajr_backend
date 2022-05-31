<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MobilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mobils = Mobil::all();

        if(count($mobils)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mobils
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
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
    {   $storeData = $request->all();
        $storeData['status_mobil'] = 'Aktif';
        $validate = null;
        if($storeData['id_pemilik_mobil'] != null){
            $validate = Validator::make($storeData,[
                'id_pemilik_mobil' => 'required',
                'nama_mobil' => 'required',
                'tipe_mobil' => 'required',
                'jenis_transmisi'=> 'required',
                'jenis_bahan_bakar'=> 'required',
                'warna_mobil' => 'required',
                'volume_bagasi'=> 'required',
                'fasilitas'=> 'required',
                'kapasitas'=> 'required|numeric',
                'plat_nomor'=> 'required',
                'nomor_stnk'=> 'required',
                'kategori_aset'=> 'required',
                'harga_sewa'=> 'required|numeric',
                'status_sewa'=> 'required',
                'tgl_terakhir_servis'=> 'required|date',
                'periode_kontrak_mulai'=> 'required|date',
                'periode_kontrak_akhir'=> 'required|date',
                'foto'=> 'required'
            ]);
        }else{
            $validate = Validator::make($storeData,[
                'nama_mobil' => 'required',
                'tipe_mobil' => 'required',
                'jenis_transmisi'=> 'required',
                'jenis_bahan_bakar'=> 'required',
                'warna_mobil' => 'required',
                'volume_bagasi'=> 'required',
                'fasilitas'=> 'required',
                'kapasitas'=> 'required|numeric',
                'plat_nomor'=> 'required',
                'nomor_stnk'=> 'required',
                'kategori_aset'=> 'required',
                'harga_sewa'=> 'required|numeric',
                'status_sewa'=> 'required',
                'tgl_terakhir_servis'=> 'required|date',
                'foto'=> 'required'
            ]);
        }


        if($validate->fails())
            return response(['message'=>$validate->errors()],400);
    
        $mobil = Mobil::create($storeData);

        return response([
            'message' => 'Add Mobil Success',
            'data' => $mobil
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mobil = Mobil::find($id);

        if(!is_null($mobil)){
            return response([
                'message' => 'Retrieve Mobil Success',
                'data' => $mobil
            ],200);
        }

        return response([
            'message' => 'Mobil Not Found',
            'data' => null
        ],404);

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
        //
        $mobil = Mobil::find($id);

        if(is_null($mobil)){
            return response([
                'message' => 'Mobil Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = null;
        if($updateData['id_pemilik_mobil'] != null){
            $validate = Validator::make($updateData,[
                'id_pemilik_mobil' => 'required',
                'nama_mobil' => 'required',
                'tipe_mobil' => 'required',
                'jenis_transmisi'=> 'required',
                'jenis_bahan_bakar'=> 'required',
                'warna_mobil' => 'required',
                'volume_bagasi'=> 'required',
                'fasilitas'=> 'required',
                'kapasitas'=> 'required|numeric',
                'plat_nomor'=> 'required',
                'nomor_stnk'=> 'required',
                'kategori_aset'=> 'required',
                'harga_sewa'=> 'required|numeric',
                'status_sewa'=> 'required',
                'tgl_terakhir_servis'=> 'required|date',
                'periode_kontrak_mulai'=> 'required|date',
                'periode_kontrak_akhir'=> 'required|date',
                'foto'=> 'required'
            ]);
        }else{
            $validate = Validator::make($updateData,[
                'nama_mobil' => 'required',
                'tipe_mobil' => 'required',
                'jenis_transmisi'=> 'required',
                'jenis_bahan_bakar'=> 'required',
                'warna_mobil' => 'required',
                'volume_bagasi'=> 'required',
                'fasilitas'=> 'required',
                'kapasitas'=> 'required|numeric',
                'plat_nomor'=> 'required',
                'nomor_stnk'=> 'required',
                'kategori_aset'=> 'required',
                'harga_sewa'=> 'required|numeric',
                'status_sewa'=> 'required',
                'tgl_terakhir_servis'=> 'required|date',
                'foto'=> 'required'
            ]);
        }

        if($validate->fails())
            return response(['message'=>$validate->errors()],400);

        $mobil->id_pemilik_mobil = $updateData['id_pemilik_mobil'];
        $mobil->nama_mobil = $updateData['nama_mobil'];
        $mobil->tipe_mobil = $updateData['tipe_mobil'];
        $mobil->jenis_transmisi = $updateData['jenis_transmisi'];
        $mobil->jenis_bahan_bakar = $updateData['jenis_bahan_bakar'];
        $mobil->warna_mobil = $updateData['warna_mobil'];
        $mobil->volume_bagasi = $updateData['volume_bagasi'];
        $mobil->fasilitas = $updateData['fasilitas'];
        $mobil->kapasitas = $updateData['kapasitas'];
        $mobil->plat_nomor = $updateData['plat_nomor'];
        $mobil->nomor_stnk = $updateData['nomor_stnk'];
        $mobil->kategori_aset = $updateData['kategori_aset'];
        $mobil->harga_sewa = $updateData['harga_sewa'];
        $mobil->status_sewa = $updateData['status_sewa'];
        $mobil->tgl_terakhir_servis = $updateData['tgl_terakhir_servis'];
        $mobil->periode_kontrak_mulai = $updateData['periode_kontrak_mulai'];
        $mobil->periode_kontrak_akhir = $updateData['periode_kontrak_akhir'];
        $mobil->foto = $updateData['foto'];

        if($mobil->save()){
            return response([
                'message' => 'Update Mobil Success',
                'data' => $mobil
            ], 200);
        }

        return response([
            'message' => 'Update Mobil Failed',
            'data' => null
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $mobil = Mobil::find($id);

        if(is_null($mobil)){
            return response([
                'message' => 'Mobil Not Found',
                'data' => null
            ], 404);
        }
        $mobil->status_mobil = 'Tidak Aktif';

        if($mobil->save()){
            return response([
                'message' => 'Delete Mobil Success',
                'data' => $mobil
            ], 200);
        }

        return response([
            'message' => 'Delete Mobil Failed',
            'data' => null
        ], 400);
    }

    public function cekKetersediaan(){
        $mobil = Mobil::where('status_sewa', 'Available')->get();

        if(!is_null($mobil)){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $mobil
            ], 200);
        }

        return response([
            'message' => 'Mobil Not Found',
            'data' => null
        ], 404);
    }

    public function tampilKontrakAkanHabis() {
        $mobil = Mobil::where('kategori_aset', 'Aset Mitra')->whereRaw('DATEDIFF(periode_kontrak_akhir, now()) < ?', [45])->get();

        if($mobil->isNotEmpty()) {
            return response([
                'message' => 'Retrieve Mobil Success',
                'data' => $mobil
            ], 200);
        }

        return response([
            'message' => 'Mobil Not Found',
            'data' => null
        ], 404);
    }

    public function updatePeriodeKontrak(Request $request, $id) {
        $mobil = Mobil::find($id);

        if(is_null($mobil)) {
            return response([
                'message' => 'Mobil Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'periode_kontrak_mulai' => 'required',
            'periode_kontrak_selesai' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $mobil->periode_kontrak_mulai = $updateData['periode_kontrak_mulai'];
        $mobil->periode_kontrak_akhir = $updateData['periode_kontrak_akhir'];

        if($mobil->save()) {
            return response([
                'message' => 'Update Mobil Success',
                'data' => $mobil
            ], 200);
        }

        return response([
            'message' => 'Update Mobil Failed',
            'data' => null
        ], 400);
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
}
