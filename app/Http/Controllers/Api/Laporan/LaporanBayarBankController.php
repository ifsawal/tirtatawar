<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Models\Master\Tagihan;
use App\Http\Controllers\Controller;

class LaporanBayarBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function laporanbayarbank(Request $r)
    {
        isset($r->tanggal) ? $tanggal = date('Y-m-d', strtotime($r->tanggal)) : $tanggal = date('Y-m-d');

        $tagihan = Tagihan::with('pencatatan:id,bulan,tahun,pelanggan_id', 'pencatatan.pelanggan:id,nama')
            ->whereDate('tgl_bayar', $tanggal)
            ->where('status_bayar', 'Y')
            ->where('sistem_bayar', 'Transfer')
            ->limit(50)
            ->orderBy('tgl_bayar', 'DESC')
            ->get();

        if (count($tagihan) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Data tidak ditemukan...",
            ], 404);
        }


        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data" => $tagihan
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
