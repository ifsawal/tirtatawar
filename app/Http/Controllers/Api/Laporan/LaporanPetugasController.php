<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\UserWiljalan;
use Illuminate\Support\Facades\Auth;

class LaporanPetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function data_pencatatan(Request $r)
    {
        $user = Auth::user();
        $user_id = $user->id;
        isset($r->byuser) ? $user_id = $r->byuser : "";  //ISI ID USER

        $data = UserWiljalan::query();
        $data->select(
            'pelanggans.nama',
        );
        $data->join('wiljalans', 'wiljalans.id', '=', 'user_wiljalans.wiljalan_id');
        $data->join('wiljalans', 'wiljalans.id', '=', 'pelanggans.wiljalan_id');
        $data->where('user_wiljalans.id', '=', $r->id);
        // $data->where('rekaps.tahun', '=', $r->tahun);
        // $data->where('rekaps.bulan', '=', $r->bulan);
        // $data->where('rekaps.pdam_id', '=', $pdam_id);
        return $data->get();
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
