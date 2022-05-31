<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PemilikMobil;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PemilikMobilController extends Controller
{
 
    public function index()
    {
        $pemilikMobils = PemilikMobil::all();

        if(count($pemilikMobils) >0 ){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pemilikMobils
            ],200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $storeData = $request->all();
        $storeData['status_pemilik'] = 'Aktif';
        $validate = Validator::make($storeData,[
            'nama' => 'required',
            'no_ktp' => 'required',
            'alamat' => 'required',
            'no_telp'=> 'required|digits_between: 0,13|starts_with:08',
            'status_pemilik' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $pemilikMobil = PemilikMobil::create($storeData);
        return response([
            'message' => 'Add Pemilik Mobil Success',
            'data' => $pemilikMobil
        ],200);
    }

    // method untuk menampilkan data pemilik mobil
    public function show($id)
    {
        $pemilikMobil = PemilikMobil::find($id);

        if(!is_null($pemilikMobil)){
            return response([
                'message' => 'Retrive Pemilik Mobil Success',
                'data' => $pemilikMobil
            ],200);
        }
        return response([
            'message' => 'Pemilik Mobil Not Found',
            'data' => $pemilikMobil
        ],404);
    }


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
        $pemilikMobil = PemilikMobil::find($id);
        if(is_null($pemilikMobil)){
            return response([
                'message' => 'Pemilik Mobil Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'nama' => 'required',
            'no_ktp' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required|digits_between: 0,13|starts_with:08',
            //'status_pemilik' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $pemilikMobil->nama = $updateData['nama'];
        $pemilikMobil->no_ktp = $updateData['no_ktp'];
        $pemilikMobil->alamat = $updateData['alamat'];
        $pemilikMobil->no_telp = $updateData['no_telp'];
        //$pemilikMobil->status_pemilik = $updateData['status_pemilik'];

        if($pemilikMobil->save()){
            return response([
                'message' => 'Update Pemilik Mobil Succes',
                'data' => $pemilikMobil
            ],200);
        }

        return response([
            'message' => 'Update Pemilik Mobil Failed',
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
        $pemilikMobil = PemilikMobil::find($id);

        if(is_null($pemilikMobil)){
            return response([
                'message' => 'Pemilik Mobil Not Found',
                'data' => null 
            ],404);
        }

        $pemilikMobil->status_pemilik ='Tidak Aktif';
        if($pemilikMobil->save()){
            return response([
            'message' => 'Delete Pemilik Mobil Success',
            'data' => $pemilikMobil
            ],200);
        }

        return response([
            'message' => 'Delete Pemilik Mobil Failed',
            'data' => null
        ],400);
        
    }
}
