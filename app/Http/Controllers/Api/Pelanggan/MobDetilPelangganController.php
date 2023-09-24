<?php

namespace App\Http\Controllers\Api\Pelanggan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Pelanggan\DetilPelanggan\DetilPelangganResource;
use App\Models\Master\Pelanggan;
use Illuminate\Support\Facades\Auth;

class MobDetilPelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function golongantarifpelanggan()
    {
        $user_id = Auth::user()->id;
        $pelanggan = new DetilPelangganResource(Pelanggan::with('golongan', 'golongan.goldetil')
            ->where('id', $user_id)->first());

        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data" => $pelanggan,
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
