<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $customers = Customer::all(); // mengambil semua data customer

        if(count($customers) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $customers
            ], 200);
        }// return data semua customer dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ],400);// return message data customer kosong
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

    // method untuk menambah 1 data customer baru(create)
    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $regDate = date('ymd');
        $lastId = Customer::select('id')->orderBy('id','desc')->first();
        $lastId = (int)substr($lastId, -3);
        $storeData['id_customer']='CUS'.$regDate.'-'.$lastId+1;
        $storeData['status_customer'] = 'Aktif';
        $storeData['password'] = bcrypt($storeData['tgl_lahir']);
        $validate = Validator::make($storeData,[
            'id_customer' => 'required|unique:customer',
            'nama' => 'required',
            'alamat' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'email' => 'required|email:rfc,dns',
            'no_telp' => 'required|digits_between: 0,13|starts_with:08',
            'identitas' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);
        
        $customer = Customer::create($storeData);
        return response([
            'message' => 'Add Customer Success',
            'data' => $customer
        ],200);
    }

    // method untuk menampilkan 1 data customer(search)
    public function show($id)
    {
        $customer = Customer::where('id_customer',$id)->first(); // untuk mendapatkan id customer

        if(!is_null($customer)){
            return response([
                'message' => 'Retrieve Customer Success',
                'data' => $customer
            ],200);
        }// return data customer yang ditemukan dalam bentuk json

        return response([
            'message' => 'Customer Not Found',
            'data' => null
        ],404); // return message saat data customer tidak ditemukan
    
    }


    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        $customer = Customer::where('id_customer',$id)->first(); 

        if(is_null($customer)){
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'nama' => 'required',
            'alamat' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'email' => 'required|email:rfc,dns',
            'no_telp' => 'required|digits_between:0,13|starts_with:08',
            'password' => 'required',
            'status_customer' =>'required',
            'identitas' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $customer->nama = $updateData['nama'];
        $customer->alamat = $updateData['alamat'];
        $customer->tgl_lahir = $updateData['tgl_lahir'];
        $customer->jenis_kelamin = $updateData['jenis_kelamin'];
        $customer->email = $updateData['email'];
        $customer->no_telp = $updateData['no_telp'];
        $customer->password = $updateData['password'];
        $customer->status_customer = $updateData['status_customer'];
        $customer->sim = $updateData['sim'];
        $customer->identitas = $updateData['identitas'];

        if($customer->save()){
            return response([
                'message' => 'Update Customer Success',
                'data' => $customer
            ],200);
        };

        return response([
            'message' => 'Update Customer Failed',
            'data' => null
        ],400);
    }   
    
    // 
    public function destroy($id)
    {
        $customer = Customer::where('id_customer',$id)->first(); 

        if(is_null($customer)){
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ],404);
        }

        $customer->status_customer = 'Tidak Aktif';

        if($customer ->save()){
            return response([
                'message' => 'Delete Customer Success',
                'data' => $customer
            ],200);
        }

        return response([
            'message' => 'Delete Customer Failed',
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

}
