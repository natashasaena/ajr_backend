<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use App\Models\Driver;
use Illuminate\Support\Facades\Validator;

class DriverAuthController extends Controller
{
    public function register(Request $request){
        $registrationData = $request->all();
        $validate = Validator::make($registrationData,[
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
            return response(['message' => $validate->errors()], 400);

        $regDate = date('ymd');
        $lastId = Driver::select('id')->orderBy('id','desc')->first();
        $lastId = (int)substr($lastId, -3);
        $registrationData['id_driver']='DRV'.$regDate.'-'.$lastId+1;
        $registrationData['status_driver'] = 'Aktif';
        $registrationData['password'] = bcrypt($registrationData['tgl_lahir']);
        $user = Driver::create($registrationData);
        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200);
    }

    public function login(Request $request){
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 401);
        
        $email = $loginData['email'];
        $password = $loginData['password'];
        if (!Auth::guard('driver')->attempt(['email' => $email, 'password' => $password]))
            return response(['message' => 'Invalid Credentials'], 401);
        /** @var \App\Models\Driver $user **/
        $user = Auth::guard('driver')->user();
        $token = $user->createToken('Authentication Token')->accessToken;
            
        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}
