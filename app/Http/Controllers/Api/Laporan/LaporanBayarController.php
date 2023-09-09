<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Penagih;
use App\Models\Master\Setoran;
use Illuminate\Support\Facades\Auth;

class LaporanBayarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $r)
    {
        $user_id = Auth::user()->id;
        isset($r->user) ? $user_id = $r->user : "";

        $tanggal = now();
        isset($r->tanggal) ? $tanggal = date('Y-m-d', strtotime($r->tanggal)) : "";

        $penagih = Penagih::with(
            'tagihan:id,jumlah,diskon,denda,total,status_bayar,sistem_bayar,pencatatan_id',
            'tagihan.pencatatan:id,awal,akhir,pemakaian,bulan,tahun,pelanggan_id',
            'tagihan.pencatatan.pelanggan:id,nama',
        )
            ->where('user_id', $user_id)
            ->whereDate('waktu', $tanggal)
            ->orderBy('id', "DESC")
            ->get();

        $setoran = Setoran::where('user_id', $user_id)
            ->whereDate('tanggal', $tanggal)
            ->first();


        return response()->json([
            "sukses" => true,
            "pesan" => "Ditemukan...",
            'penagih' => $penagih,
            'setoran' => $setoran,
        ], 202);
    }

    public function laporanpenerimaan()
    {
        $user_id = Auth::user()->id;
        $setoran = Setoran::with('user:id,nama', 'user_diserahkan:id,nama')
            ->where('user_id', $user_id)
            ->limit(31)->orderBy('id', "DESC")->get();

        return response()->json([
            "sukses" => true,
            "pesan" => "Ditemukan...",
            'setoran' => $setoran,
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
