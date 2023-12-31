<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use App\Models\Master\HpPelanggan;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Master\PelangganController;

class HpPelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function destroy(string $hp_pelanggan)
    {

        $hp = HpPelanggan::findOrFail($hp_pelanggan);
        $pelanggan_id = $hp->pelanggan_id;
        $hp->delete();

        PelangganController::simpanJumlahNoHp($pelanggan_id);  //update jumlah nomor hp

        return response()->json([
            'sukses' => true,
            'pesan' => "Data terhapus...",
        ], 204);
    }
}
