<?php

namespace App\Http\Controllers\Api\Pelanggan\Login\Keluhan;

use Illuminate\Http\Request;
use App\Models\Keluhan\Keluhan;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Pelanggan\Login\Keluhan\KeluhanResource;
use Illuminate\Support\Facades\Auth;

class KeluhansimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function tampil_keluhan()
    {
        $user_id = Auth::user()->id;
        $keluhan = KeluhanResource::collection(Keluhan::with('proses', 'tim')
            ->where('pelanggan_id', $user_id)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get());
        if (count($keluhan) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Tidak detemukan...",
                "kode" => 1,
            ], 404);
        }

        return response()->json([
            "sukses" => true,
            "pesan" => "ditemukan...",
            "data" => $keluhan,
        ], 202);
    }
    public function simpan_keluhan(Request $r)
    {
        $this->validate($r, [
            'keluhan' => 'required',
        ]);
        $user_id = Auth::user()->id;

        $cari = Keluhan::where('pelanggan_id', $user_id)
            ->whereDate('created_at', now())
            ->first();
        if ($cari) {
            return response()->json([
                "sukses" => true,
                "pesan" => "Kamu telah menyampaikan keluhan hari ini...",
                "kode" => 1,
            ], 404);
        }

        $simpan = new Keluhan();
        $simpan->pelanggan_id = $user_id;
        $simpan->keluhan = $r->keluhan;
        $simpan->save();

        return response()->json([
            "sukses" => true,
            "pesan" => "Tersimpan..."
        ], 201);
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
