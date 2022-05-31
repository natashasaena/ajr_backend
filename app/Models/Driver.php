<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Driver extends Model
{
    use HasFactory;
    protected $table = "driver";
    protected $fillable = [
        'id_driver',
        'nama',
        'alamat',
        'tgl_lahir',
        'jenis_kelamin',
        'email',
        'no_telp',
        'bahasa',
        'status_ketersediaan',
        'password',
        'tarif_driver',
        'rerata_rating',
        'status_driver',
        'sim',
        'surat_bebas_napza',
        'surat_kesehatan_jiwa',
        'skck'

    ];

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
