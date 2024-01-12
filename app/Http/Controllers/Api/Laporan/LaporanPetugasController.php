<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Pelanggan;
use App\Models\Master\UserWiljalan;
use App\Models\Master\Wiljalan;
use Illuminate\Support\Facades\Auth;

class LaporanPetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function data_pencatatan(Request $r)
    {

        $this->validate($r, [
            'bulan' => 'required', // bayar id
            'tahun' => 'required', // bayar id
        ]);

        $user = Auth::user();
        $user_id = $user->id;
        isset($r->byuser) ? $user_id = $r->byuser : "";  //ISI ID USER


        $data = Pelanggan::query();
        $data->select(
            'pelanggans.id',
            'pelanggans.nama',
            'tagihans.total',
            'tagihans.status_bayar',
            'tagihans.sistem_bayar',
        );
        $data->join('pencatatans', 'pencatatans.pelanggan_id', '=', 'pelanggans.id');
        $data->join('tagihans', 'tagihans.pencatatan_id', '=', 'pencatatans.id');
        $data->where('pelanggans.user_id_petugas', '=', $user_id);

        isset($r->golongan_id) ? $data->where('pelanggans.golongan_id', '=', $r->golongan_id) : '';
        isset($r->wiljalan_id) ? $data->where('pelanggans.wiljalan_id', '=', $r->wiljalan_id) : '';
        isset($r->status_bayar) ? $data->where('tagihans.status_bayar', '=', $r->status_bayar) : '';

        $data->where('pencatatans.tahun', '=', $r->tahun);
        $data->where('pencatatans.bulan', '=', $r->bulan);
        $data->where('pelanggans.pdam_id', '=', $user->pdam_id);
        $jumlah_data = $data->get()->count();

        if ($jumlah_data == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Data tidak ditemukan...",
            ], 404);
        }

        $total = 0;
        $bayar = 0;
        $jumlah_terbayar = 0;
        foreach ($data->get() as $d) {
            $total += $d['total'];
            if ($d['status_bayar'] == "Y") {
                $bayar += 1;
                $jumlah_terbayar += $d['total'];
            }
        }

        return response()->json([
            "sukses" => true,
            "pesan" => "Sukses, data ditemukan...",
            "jumlah_data" => $jumlah_data,
            "jumlah_rupiah" => $total,
            "jumlah_terbayar" => $jumlah_terbayar,
            "terbayar" => $bayar,
            "data" => $data->paginate(50),
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
