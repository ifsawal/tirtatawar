<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function cari()
    {
    }

    public function index(Request $r)
    {
        $this->validate($r, [
            'id' => 'required',
        ]);

        $user_id = Auth::user()->id;

        $pelanggan = Pelanggan::with('desa:id,desa')
            ->select('id', 'nama', 'desa_id', 'lat', 'long')
            ->where('id', '=', $r->id)
            ->first();

        $catat = Pencatatan::with('tagihan:id,jumlah,diskon,total,status_bayar,created_at,pencatatan_id')
            ->where('pelanggan_id', '=', $r->id)
            ->orderBy('id', 'desc')
            ->get();

        if (!$pelanggan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pelanggan tidak ditemukan...",
            ], 404);
        }
        return response()->json([
            "sukses" => true,
            "pesan" => "Ditemukan...",
            'data' => $catat,
            'pelanggan' => $pelanggan,
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
