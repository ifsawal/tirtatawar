<?php

namespace App\Http\Controllers\Api\Pelanggan\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Pelanggan\Login\MobPelangganTagihanDaftarMeteranResource;
use App\Http\Resources\Api\Pelanggan\Login\MobPelangganTagihanResource;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\Auth;

class MobPelangganTagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function daftartagihan()
    {
        $user_id = Auth::user()->id;
        $pencatatan = MobPelangganTagihanResource::collection(Pencatatan::with('tagihan')
            ->where('pelanggan_id', $user_id)
            ->limit(12)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get());

        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data" => $pencatatan,
            "nomor_pelanggan" => $user_id,
        ], 202);
    }

    public function daftarmeteran()
    {
        $user_id = Auth::user()->id;
        $pencatatan = MobPelangganTagihanDaftarMeteranResource::collection(Pencatatan::where('pelanggan_id', $user_id)
            ->select('awal', 'akhir', 'pemakaian', 'bulan', 'tahun', 'photo', 'created_at')
            ->limit(12)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get());

        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data" => $pencatatan
        ], 202);
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
