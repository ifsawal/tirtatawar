<?php

namespace App\Http\Controllers\Api\Proses;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\Pelanggan;
use App\Models\Master\Penagih;
use App\Models\Master\Setoran;
use App\Models\Master\Tagihan;
use Illuminate\Support\Facades\Auth;

class BayarController extends Controller
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
    public function store(Request $r)
    {
        $this->validate($r, [
            'id' => 'required',
            'pelanggan_id' => 'required',
        ]);
        $user_id = Auth::user()->id;


        DB::beginTransaction();
        try {
            $tagihan = Tagihan::where('id', '=', $r->id)
                ->where('status_bayar', '=', 'Y')
                ->where('sistem_bayar', '=', 'Cash')
                ->first();
            if ($tagihan) {
                DB::rollback();
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Tagihan sudah dibayar...",
                ], 202);
            }

            $tagihan = Tagihan::findOrFail($r->id);
            $tagihan->status_bayar = "Y";
            $tagihan->sistem_bayar = "Cash";
            $tagihan->save();

            $penagih = new Penagih();
            $penagih->tagihan_id = $r->id;
            $penagih->user_id = $user_id;
            $penagih->jumlah = $tagihan->total;
            $penagih->waktu = now();
            $penagih->save();


            $setoran = Setoran::where('tanggal', '=', date('Y-m-d'))
                ->where('user_id', '=', $user_id)
                ->first();

            if ($setoran) {
                $tambahsetoran = Setoran::findOrFail($setoran->id);
                $tambahsetoran->jumlah = $tambahsetoran->jumlah  + $penagih->jumlah;
                $tambahsetoran->save();
            } else {
                $tambahsetoran = new Setoran();
                $setoransebelumnya = 0;
                $tambahsetoran->user_id = $user_id;
                $tambahsetoran->tanggal = date('Y-m-d');
                $tambahsetoran->jumlah = $penagih->jumlah;
                $tambahsetoran->save();
            }

            $userpenagih = Penagih::with('user:id,nama')
                ->where('id', '=', $penagih->id)->first();

            $pelangan = Pelanggan::with(
                'user:id,nama',
                'pdam:id,pdam',
                'desa.kecamatan:id,kecamatan',
                'golongan:id,golongan,biaya',
                'rute:id,rute',
            )->where('id', $r->pelanggan_id)->first();

            // DB::commit();
            DB::rollback();

            return response()->json([
                "sukses" => true,
                "pesan" => "Pembayaran sukses...",
                "pelanggan" => $pelangan,
                "datatagihan" => $tagihan,
                "penagih" =>  $userpenagih,
                "setoran" =>  $tambahsetoran,
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal membayar...",
            ], 404);
        }
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
