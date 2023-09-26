<?php

namespace App\Http\Controllers\Api\Master;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Master\Tagihan;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Api\Pelanggan\Tagihan\TagihanResource;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function cari()
    {
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

        $pelanggan = Pelanggan::with('golongan:id,denda')
            ->where('id', $pencatatan->pelanggan_id)->first();

        $waktucatat = $pencatatan->tahun . '-' . $pencatatan->bulan . '-' . '1';
        $kurangi1bulan = date('Y-m', strtotime(Carbon::create($pencatatan->tahun, $pencatatan->bulan, 1)->subMonths(1)));
        $denda = TagihanResource::denda($waktucatat, $kurangi1bulan, $pelanggan->golongan->denda);

        //proses simpan
        $total = $tagihan->total;
        if ($denda > 0 and $denda <> $tagihan->denda) {
            //     $tagihan = Tagihan::findOrFail($this->id);
            $tagihan->denda = $denda;
            $tagihan->subtotal = $tagihan->total + $denda;
            $tagihan->total = $tagihan->total + $denda;
            // $total = $tagihan->total;
            $tagihan->save();
        }


        return response()->json([
            "sukses" => true,
            "pesan" => "Ditemukan...",
            // 'pencatatan' => $pencatatan,
            'tagihan' => $tagihan,
        ], 202);
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
