<?php

namespace App\Http\Controllers\Api\Pelanggan;

use App\Fungsi\Flip;
use Illuminate\Http\Request;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Pelanggan\Tagihan\PelangganResource;
use App\Http\Resources\Api\Pelanggan\Tagihan\PencatatanResource;

class MobTagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function cektagihan(Request $r)
    {
        $this->validate($r, [
            'nopel' => 'required',
        ]);


        $pelanggan = Pelanggan::with('golongan:id,denda')
            ->where('id', $r->nopel)->first();

        $pencatatan = Pencatatan::with('tagihan', 'pelanggan')
            ->whereRelation('pelanggan', 'id', '=', $r->nopel)
            ->whereRelation('tagihan', 'status_bayar', '=', 'N')
            ->orderBy('id', 'desc')
            ->get();


        return $pencatatan = PencatatanResource::collection($pencatatan)
            ->additional(['meta' => [
                'denda' => $pelanggan->golongan->denda,
            ]]);
        if (count($pencatatan) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Tidak ditemukan...",
            ], 404);
        }
        return response()->json([
            "sukses" => true,
            "pesan" => "Sukses menghapus...",
            "pelanggan" => new PelangganResource($pelanggan),
            "data" => $pencatatan,
        ], 202);
    }
    public function buattagihan(Request $r)
    {

        $this->validate($r, [
            'nopel' => 'required',
        ]);

        $title = "PDAM Tirta Tawar";
        $jumlah = 30500;
        $bank = "mandiri";
        $nama = "Mr. Rm titanic";
        $email = "awal@bandung.com";
        $alamat = "Jln. Takengon";
        return Flip::create($title, $jumlah, $bank, $nama, $email, $alamat);
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
