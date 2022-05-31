<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drivers = Driver::all();

        if(count($drivers)>0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $drivers
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
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
        $storeData = $request->all(); // mengambil semua input dari api client
        $regDate = date('ymd');
        $lastId = Driver::select('id')->orderBy('id','desc')->first();
        $lastId = (int)substr($lastId, -3);
        $storeData['id_driver']='DRV'.$regDate.'-'.$lastId+1;
        $storeData['status_driver'] = 'Aktif';
        $storeData['status_ketersediaan'] = 'Available';
        $storeData['rerata_rating'] = 0;
        $storeData['password'] = bcrypt($storeData['tgl_lahir']);
        $validate = Validator::make($storeData,[
            'id_driver' => 'required|unique:driver',
            'nama' => 'required',
            'alamat' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'email' => 'required|email:rfc,dns',
            'no_telp' => 'required|digits_between: 0,13|starts_with:08',
            'bahasa' => 'required',
            'status_ketersediaan' =>'required',
            'tarif_driver' => 'required',
            'rerata_rating'=> 'required',
            'status_driver'=> 'required',
            'sim'=> 'required',
            'surat_bebas_napza'=> 'required',
            'surat_kesehatan_jiwa'=> 'required',
            'skck'=> 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);
        
        $driver = Driver::create($storeData);
        return response([
            'message' => 'Add Driver Success',
            'data' => $driver
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
        $driver = Driver::where('id_driver',$id)->first();

        if(!is_null($driver)){
            return response([
                'message' => 'Retrieve Driver Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Driver Not Found',
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
        $driver = Driver::where('id_driver',$id)->first();

        if(is_null($driver)){
            return response([
                'message' => 'Driver Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'id_driver' => ['required', Rule::unique('driver')->ignore($driver)],
            'nama' => 'required',
            'alamat' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'email' => 'required|email:rfc,dns',
            'no_telp' => 'required|digits_between: 0,13|starts_with:08',
            'bahasa' => 'required',
            'status_ketersediaan' =>'required',
            'tarif_driver' => 'required',
            'rerata_rating'=> 'required',
            'status_driver'=> 'required',
            'sim'=> 'required',
            'surat_bebas_napza'=> 'required',
            'surat_kesehatan_jiwa'=> 'required',
            'skck'=> 'required'
        ]);

        //$driver->id_driver = $updateData['id_driver'];
        $driver->nama = $updateData['nama'];
        $driver->alamat = $updateData['alamat'];
        $driver->tgl_lahir = $updateData['tgl_lahir'];
        $driver->jenis_kelamin = $updateData['jenis_kelamin'];
        $driver->email = $updateData['email'];
        $driver->no_telp = $updateData['no_telp'];
        $driver->bahasa = $updateData['bahasa'];
        $driver->status_ketersediaan = $updateData['status_ketersediaan'];
        $driver->tarif_driver = $updateData['tarif_driver'];
        $driver->rerata_rating = $updateData['rerata_rating'];
        $driver->status_driver = $updateData['status_driver'];
        $driver->sim = $updateData['sim'];
        $driver->surat_bebas_napza = $updateData['surat_bebas_napza'];
        $driver->surat_kesehatan_jiwa = $updateData['surat_kesehatan_jiwa'];
        $driver->skck = $updateData['skck'];

        if($driver->save()){
            return response([
                'message' => 'Update Driver Success',
                'data' => $driver
            ],200);
        };

        return response([
            'message' => 'Update Driver Failed',
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
        $driver = Driver::where('id_driver',$id)->first();

        if(is_null($driver)){
            return response([
                'message' => 'Driver Not Found',
                'data' => null
            ],404);
        }

        $driver->status_driver = 'Tidak Aktif';

        if($driver ->save()){
            return response([
                'message' => 'Delete Driver Success',
                'data' => $driver
            ],200);
        }

        return response([
            'message' => 'Delete Driver Failed',
            'data' => null
        ],400);
    }
    public function driverAvailable(){
        $driver = Driver::where('status_ketersediaan', 'Available')->get();

        if(!is_null($driver)){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Driver Not Found',
            'data' => null
        ], 404);
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
