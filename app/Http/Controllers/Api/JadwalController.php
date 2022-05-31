<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jadwals = Jadwal::all();

        if(count($jadwals)>0){
            return response([
                'message' => 'Retrieve ALl Success',
                'data' => $jadwals
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
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'shift' => 'required',
            'hari' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $jadwal = Jadwal::create($storeData);

        return response([
            'message' => 'Add Jadwal Success',
            'data' => $jadwal
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
        $jadwal = Jadwal::find($id);

        if(!is_null($jadwal)){
            return response([
                'message' => 'Retrieve Jadwal Success',
                'data' => $jadwal
            ],200);
        }

        return response([
            'message' => 'Jadwal Not Found',
            'data' => null
        ],404);
    }
    public function showByHariAndShift($hari, $shift) {
        $jadwal = Jadwal::where('hari', $hari)->where('shift', $shift)->first();

        if(!is_null($jadwal)) {
            return response([
                'message' => 'Retrieve Jadwal Success',
                'data' => $jadwal
            ], 200);
        }

        return response([
            'message' => 'Jadwal Not Found',
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
        $jadwal = Jadwal::find($id);

        if(is_null($jadwal)){
            return response([
                'message' => 'Jadwal Not Found',
                'data' => null
            ], 400);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'shift' => 'required',
            'hari' => 'required'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $jadwal->shift = $updateData['shift'];
        $jadwal->hari = $updateData['hari'];

        if($jadwal->save()){
            return response([
                'message' => 'Update Jadwal Success',
                'data' => $jadwal
            ], 200);
        }

        return response([
            'message' => 'Update Jadwal Failed',
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
        $jadwal = Jadwal::find($id);

        if(is_null($jadwal)){
            return response([
                'message' => 'Jadwal Not Found',
                'data' => null
            ],404);
        }

        if($jadwal->delete()){
            return response([
                'message' => 'Delete Jadwal Success',
                'data' => $jadwal
            ],200);
        }

        return response([
            'message' => 'Delete Jadwal Failed',
            'data' => null
        ],400);
    }
}
