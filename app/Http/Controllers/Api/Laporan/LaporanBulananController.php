<?php

namespace App\Http\Controllers\Api\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Master\Pencatatan;
use Illuminate\Http\Request;

class LaporanBulananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function laporan_bulanan(Request $r)
    {
        $this->validate($r, [
            'bulan' => 'required',
            'tahun' => 'required',

        ]);

        $catat = Pencatatan::query();
        $catat->select(
            'tagihans.id',
            'pencatatans.bulan',
            'pencatatans.tahun',
            'tagihans.total',
            'tagihans.status_bayar',
            'tagihans.sistem_bayar',
            'tagihans.tgl_bayar',
            'pelanggans.nama'

        );
        $catat->join('tagihans', 'tagihans.pencatatan_id', '=', 'pencatatans.id');
        $catat->join('pelanggans', 'pelanggans.id', '=', 'pencatatans.pelanggan_id');
        $catat->where('pencatatans.bulan', '=', $r->bulan);
        $catat->where('pencatatans.tahun', '=', $r->tahun);

        isset($r->golongan_id) ? $catat->where('pelanggans.golongan_id', '=', $r->golongan_id) : '';
        isset($r->wiljalan_id) ? $catat->where('pelanggans.wiljalan_id', '=', $r->wiljalan_id) : '';
        isset($r->status_bayar) ? $catat->where('tagihans.status_bayar', '=', $r->status_bayar) : '';


        $catat->get();

        $catatan = $catat->paginate(50);
        if (count($catatan) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Data tidak ditemukan...",
            ], 404);
        }


        return response()->json([
            "sukses" => true,
            "pesan" => "Sukses, data ditemukan...",
            "data" => $catatan,
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
