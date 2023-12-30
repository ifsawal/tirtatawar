<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Pelanggan;
use App\Models\Master\UserWiljalan;
use App\Models\Master\Wiljalan;
use Illuminate\Support\Facades\Auth;

class LaporanPetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function data_pencatatan(Request $r)
    {

        $this->validate($r, [
            'bulan' => 'required', // bayar id
            'tahun' => 'required', // bayar id
        ]);

        $user = Auth::user();
        $user_id = $user->id;
        isset($r->byuser) ? $user_id = $r->byuser : "";  //ISI ID USER
        

        $data = Pelanggan::query();
        $data->select(
            'pelanggans.id',
            'pelanggans.nama',
        );
        $data->join('pencatatans', 'pencatatans.pelanggan_id', '=', 'pelanggans.id');
        $data->join('tagihans', 'tagihans.pencatatan_id', '=', 'pencatatans.id');
        $data->where('pelanggans.user_id_petugas', '=', $user_id);

        $data->where('pelanggans.tahun', '=', $r->tahun);
        $data->where('pelanggans.bulan', '=', $r->bulan);
        $data->where('pelanggans.pdam_id', '=', $user->pdam_id);
        return  count($data->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
