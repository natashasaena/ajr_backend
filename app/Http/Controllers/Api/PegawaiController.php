<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pegawais = Pegawai::all();

        if(count($pegawais)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pegawais
            ], 200);
        }
         
        return response([
            'message' => 'Empty',
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storeData = $request->all();
        $storeData['status_pegawai'] = 'Aktif';
        $storeData['password'] = bcrypt($storeData['tgl_lahir']);
        $validate = Validator::make($storeData,[
            'id_role' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'email' => 'required|email:rfc,dns',
            'no_telp' => 'required|digits_between: 0,13|starts_with:08',
            'password' => 'required',
           // 'status_pegawai' => 'required',
            'foto' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $pegawai = Pegawai::create($storeData);

        return response([
            'message' => 'Add Pegawai Success',
            'data' => $pegawai
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
        $pegawai = Pegawai::find($id);

        if(!is_null($pegawai)){
            return response([
                'message' => 'Retrieve Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Pegawai Not Found',
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
        $pegawai = Pegawai::find($id);

        if(is_null($pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'id_pegawai' => ['required',Rule::unique('pegawai')->ignore($pegawai)],
            'id_role' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'email' => 'required|email:rfc,dns',
            'no_telp' => 'required|digits_between: 0,13|starts_with:08',
            'password' => 'required',
            'status_pegawai' => 'required',
            'foto' => 'required'
        ]);

        $pegawai->id_role = $updateData['id_role'];
        $pegawai->nama = $updateData['nama'];
        $pegawai->alamat = $updateData['alamat'];
        $pegawai->tgl_lahir = $updateData['tgl_lahir'];
        $pegawai->jenis_kelamin = $updateData['jenis_kelamin'];
        $pegawai->email = $updateData['email'];
        $pegawai->no_telp = $updateData['no_telp'];
        $pegawai->password = bcrypt($updateData['password']);
        //$pegawai->status_pegawai = $updateData['status_pegawai'];
        $pegawai->foto = $updateData['foto'];
     
        
        if($pegawai->save()){
            return response([
                'message' => 'Update Pegawai Success',
                'data' => $pegawai
            ],200);
        };

        return response([
            'message' => 'Update Pegawai Failed',
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
        $pegawai = Pegawai::find($id);

        if(is_null($pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ],404);
        }

        $pegawai->status_pegawai = 'Tidak Aktif';

        if($pegawai ->save()){
            return response([
                'message' => 'Delete Pegawai Success',
                'data' => $pegawai
            ],200);
        }

        return response([
            'message' => 'Delete Pegawai Failed',
            'data' => null
        ],400);
    }
}
