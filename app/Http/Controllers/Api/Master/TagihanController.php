<?php

namespace App\Http\Controllers\Api\Master;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Master\Tagihan;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Api\Pelanggan\Tagihan\TagihanResource;
use App\Models\Master\Transfer;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function cari()
    {
    }

    public function detilinfotransfer(Request $r)
    {
        $this->validate($r, [
            'id' => 'required', //tagihan transfer
        ]);

        $transfer = Transfer::findOrFail($r->id);
        $transfer = Transfer::with('tagihan:id,total,pencatatan_id', 'tagihan.pencatatan:id,bulan,tahun')
            ->select(['id', 'tagihan_id'])
            ->where('kode_transfer', $transfer->kode_transfer)
            ->where('status_bayar', "Y")
            ->get();

        return response()->json([
            "sukses" => true,
            "pesan" => "Ditemukan...",
            'data' => $transfer,
        ], 202);
    }


    public function infotransfer(Request $r)
    {
        $this->validate($r, [
            'id' => 'required', //tagihan id
        ]);

        $tagihan = Tagihan::with('transfer')
            ->where('id', $r->id)
            ->first();


        return response()->json([
            "sukses" => true,
            "pesan" => "Ditemukan...",
            'tagihan' => $tagihan,
        ], 202);
    }




    public function cektagihandanupdate(Request $r)
    {
        $this->validate($r, [
            'id' => 'required', //pencatatan id
        ]);


        $pencatatan = Pencatatan::where('id', $r->id)
            ->first();
        $tagihan = Tagihan::where('pencatatan_id', $pencatatan->id)
            ->first();

        if ($tagihan->status_bayar == "Y") {  //jika sudah bayar jangan di proses lagi
            return response()->json([
                "sukses" => true,
                "pesan" => "Ditemukan...",
                'tagihan' => $tagihan,
            ], 202);
        }



        // $waktucatat = $pencatatan->tahun . '-' . $pencatatan->bulan . '-' . '1';
        // $kurangi1bulan = date('Y-m', strtotime(Carbon::create($pencatatan->tahun, $pencatatan->bulan, 1)->subMonths(1)));
        // $denda = TagihanResource::denda($waktucatat, $kurangi1bulan, $tagihan->denda_perbulan);



        DB::beginTransaction();
        try {
            //proses simpan
            // $total = $tagihan->total;
            // if ($denda > 0 and $denda <> $tagihan->denda) {

            //     if ($tagihan->denda == 0) {
            //         $tagihan->subtotal = $tagihan->subtotal  + $denda;
            //         $tagihan->total = $tagihan->subtotal;
            //     } else {
            //         //jika denda sudah diisi di tabel, maka di kurangi dulu denda kemudian jumlahkan dengan hitungan denda terbaru
            //         $tagihan->subtotal = ($tagihan->subtotal - $tagihan->denda) + $denda;
            //         $tagihan->total = $tagihan->subtotal;
            //     }
            //     $tagihan->denda = $denda;
            //     $tagihan->save();
            $update_denda = TagihanResource::simpan_denda($pencatatan->bulan, $pencatatan->tahun, $tagihan->denda_perbulan, $tagihan->denda, $tagihan->id, $tagihan->total);
            $tagihan = $update_denda;
            DB::commit();
            // DB::rollback();



            return response()->json([
                "sukses" => true,
                "pesan" => "Ditemukan...",
                // 'pencatatan' => $pencatatan,
                'tagihan' => $tagihan,
            ], 202);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal cek dan update..." . $e,
            ], 404);
        }
    }

    public function index(Request $r) //tagihan
    {
        $this->validate($r, [
            'id' => 'required',
        ]);

        $user_id = Auth::user()->id;

        $pelanggan = Pelanggan::with('desa:id,desa')
            ->select('id', 'nama', 'desa_id', 'lat', 'long')
            ->where('id', '=', $r->id)
            ->first();

        $catat = Pencatatan::with('tagihan:id,jumlah,diskon,total,status_bayar,sistem_bayar,created_at,pencatatan_id')
            ->where('pelanggan_id', '=', $r->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
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
