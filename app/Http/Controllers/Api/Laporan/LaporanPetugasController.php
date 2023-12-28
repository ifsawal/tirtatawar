<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $user = Auth::user();
        $user_id = $user->id;
        isset($r->byuser) ? $user_id = $r->byuser : "";  //ISI ID USER
        // $user_id = 13;

        $data = Wiljalan::query();
        $data->select(
            'pelanggans.id',
            'pelanggans.nama',
        );
        $data->join('user_wiljalans', 'user_wiljalans.wiljalan_id', '=', 'wiljalans.id');
        $data->join('pelanggans', 'pelanggans.wiljalan_id', '=', 'wiljalans.id');
        $data->where('user_wiljalans.user_id', '=', $user_id);
        // $data->where('if()');

        // $data->where('rekaps.tahun', '=', $r->tahun);
        // $data->where('rekaps.bulan', '=', $r->bulan);
        // $data->where('rekaps.pdam_id', '=', $pdam_id);
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
