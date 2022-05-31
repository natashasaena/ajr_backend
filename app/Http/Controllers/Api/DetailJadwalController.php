<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailJadwal;
use App\Models\Jadwal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function PHPUnit\Framework\isEmpty;

class DetailJadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(is_null(DetailJadwal::all())) {
            return response([
                'message' => 'Empty',
                'data' => null
            ], 400);
        }
        // $detailJadwals = DetailJadwal::all();
        $detailJadwals = DB::table('detail_jadwal')
                            ->join('pegawai', 'detail_jadwal.id_pegawai', '=', 'pegawai.id_pegawai')
                            ->join('jadwal', 'detail_jadwal.id_jadwal', '=', 'jadwal.id_jadwal')
                            ->get();


        if(count($detailJadwals) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $detailJadwals
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
        //
        $storeData = $request->all();
        $cekJumlahShift = DetailJadwal::where('id_pegawai', $storeData['id_pegawai'])->count();
        if($cekJumlahShift == 6) {
            return response([
                'message' => 'Maksimal 6 shift',
            ], 400);
        }

        $validate = Validator::make($storeData, [
            'id_jadwal' => ['required', Rule::unique('detail_jadwal', 'id_jadwal')->where(
                function ($query) use ($storeData) {
                    return $query->where([
                        ["id_jadwal", "=", $storeData['id_jadwal']],
                        ["id_pegawai", "=", $storeData['id_pegawai']]
                    ]
                );
            })],
            'id_pegawai' => ['required', Rule::unique('detail_jadwal', 'id_pegawai')->where(
                function ($query) use ($storeData) {
                    return $query->where([
                        ["id_jadwal", "=", $storeData['id_jadwal']],
                        ["id_pegawai", "=", $storeData['id_pegawai']]
                    ]
                );
            })]
        ]);


        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $detailJadwal = DetailJadwal::create($storeData);
        
        return response([
            'message' => 'Add Detail Jadwal Success',
            'data' => $detailJadwal
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
        $detailJadwal = DetailJadwal::where('id_pegawai',$id)->get();
        
        if(!is_null($detailJadwal)){
            return response([
                'message' => 'Retrieve Detail Jadwal Success',
                'data' => $detailJadwal
            ], 200);
        }

        return response([
            'message' => 'Detail Jadwal Not Found',
            'data' => null
        ], 404);
    }
    public function showByIdPegawai($id) {
        if(DetailJadwal::where('id_pegawai', $id)->get()->isEmpty()) {
            return response([
                'message' => 'Empty',
                'data' => null
            ], 400);
        }
        // $detailJadwal = DetailJadwal::where('id_pegawai', $id)->get();
        $detailJadwal = DB::table('detail_jadwal')
                            ->join('pegawai', 'detail_jadwal.id_pegawai', '=', 'pegawai.id_pegawai')
                            ->join('jadwal', 'detail_jadwal.id_jadwal', '=', 'jadwal.id_jadwal')
                            ->where('pegawai.id_pegawai', $id)
                            ->get();

        if($detailJadwal->isNotEmpty()) {
            return response([
                'message' => 'Retrieve Detail Jadwal Success',
                'data' => $detailJadwal
            ], 200);
        }

        return response([
            'message' => 'Detail Jadwal Not Found',
            'data' => null
        ], 404);
    }

    public function showByIdJadwal($id) {
        if(DetailJadwal::where('id_jadwal', $id)->get().isEmpty()) {
            return response([
                'message' => 'Empty',
                'data' => null
            ], 400);
        }
        // $detailJadwal = DetailJadwal::where('id_jadwal', $id)->get();
        $detailJadwal = DB::table('detail_jadwal')
                            ->join('pegawai', 'detail_jadwal.id_pegawai', '=', 'pegawai.id_pegawai')
                            ->join('jadwal', 'detail_jadwal.id_jadwal', '=', 'jadwal.id_jadwal')
                            ->where('jadwal.id_jadwal', $id)
                            ->get();

        if($detailJadwal->isNotEmpty()) {
            return response([
                'message' => 'Retrieve Detail Jadwal Success',
                'data' => $detailJadwal
            ], 200);
        }

        return response([
            'message' => 'Detail Jadwal Not Found',
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
    public function update(Request $request, $id_pegawai, $id_jadwal) {
        $updateData = $request->all();
        $detailJadwal = DetailJadwal::where('id_pegawai', $id_pegawai)->where('id_jadwal', $id_jadwal)->first();
        if(is_null($detailJadwal)) {
            return response([
                'message' => 'Detail Jadwal Not Found',
                'data' => null
            ], 404);
        }

        $validate = Validator::make($updateData, [
            'id_jadwal' => ['required', Rule::unique('detail_jadwal', 'id_jadwal')->where(
                function ($query) use ($updateData) {
                    return $query->where([
                        ["id_jadwal", "=", $updateData['id_jadwal']],
                        ["id_pegawai", "=", $updateData['id_pegawai']]
                    ]
                );
            })->ignore($detailJadwal)],
            'id_pegawai' => ['required', Rule::unique('detail_jadwal', 'id_pegawai')->where(
                function ($query) use ($updateData) {
                    return $query->where([
                        ["id_jadwal", "=", $updateData['id_jadwal']],
                        ["id_pegawai", "=", $updateData['id_pegawai']]
                    ]
                );
            })->ignore($detailJadwal)]
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        if(DetailJadwal::where('id_pegawai', $id_pegawai)
                        ->where('id_jadwal', $id_jadwal)
                        ->update([
                            'id_pegawai' => $updateData['id_pegawai'],
                            'id_jadwal' => $updateData['id_jadwal']
                        ])) {
            return response([
                'message' => 'Update Detail Jadwal Success',
                'data' => $detailJadwal
            ], 200);
        }

        if($detailJadwal->save()) {
            return response([
                'message' => 'Update Detail Jadwal Success',
                'data' => $detailJadwal
            ], 200);
        }

        return response([
            'message' => 'Update Detail Jadwal Failed',
            'data' => null
        ], 400);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_pegawai, $id_jadwal) {
        $detailJadwal = DetailJadwal::where('id_pegawai', $id_pegawai)->where('id_jadwal', $id_jadwal)->first();

        if(is_null($detailJadwal)) {
            return response([
                'message' => 'Detail Jadwal Not Found',
                'data' => null
            ], 404);
        }

        if(DetailJadwal::where('id_pegawai', $id_pegawai)->where('id_jadwal', $id_jadwal)->delete()) {
            return response([
                'message' => 'Delete Detail Jadwal Success',
                'data' => $detailJadwal
            ], 200);
        }

        return response([
            'message' => 'Delete Detail Jadwal Failed',
            'data' => null
        ], 400);
    }
}