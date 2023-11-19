<?php

namespace App\Http\Controllers\Api\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Master\Pelanggan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PelangganMobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function cek($nopel)
    {
        $pelanggan = Pelanggan::where('id', $nopel)
            ->select('nama')->first();
        if ($pelanggan) {
            $pelanggan->nama = substr($pelanggan->nama, 0, 3) . '******' . substr($pelanggan->nama, -2);
            return response()->json([
                "sukses" => true,
                "pesan" => "Data ditemukan...",
                "data" => $pelanggan,
            ], 202);
        } else {
            return response()->json([
                "sukses" => true,
                "pesan" => "Tidak ditemukan...",
            ], 404);
        }
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
