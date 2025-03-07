<?php

namespace App\Http\Controllers\Api\Pelanggan;

use App\Fungsi\Flip;
use App\Models\Master\Bank;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request as Request2;


class MobBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function cekbank($bank)
    {
        $f=Flip::gangguan($bank);
        Log::channel('custom')->info("log flip cek status bank" .$f .Request2::ip() .'-'.Request2::server("SERVER_ADDR"));
        return $f;
    }

    public function pilihbank()
    {
        $perawatan = Flip::perawatan();
        $a = json_decode($perawatan);
        Log::channel('custom')->info("log flip" .   $perawatan .Request2::ip() .'-'.Request2::server("SERVER_ADDR"));
        if ($a->maintenance == 1) {
            return response()->json([
                "sukses" => true,
                "pesan" => "Virtual Accont sedang Perawatan...",
            ], 503);
        }


        $bank = Bank::select('kode', 'nama', 'biaya', 'jenis', 'aktif')
            ->get();
        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data" => $bank,
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
