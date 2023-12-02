<?php

namespace App\Http\Controllers\Api\Laporan;

use App\Exports\LaporanPencatatanExport;
use Illuminate\Http\Request;
use App\Models\Master\Pencatatan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPencatatanController extends Controller
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

    public function laporanpencatatanexport(Request $r)
    {
        isset($r->semuaakun) ? $user_id = '' : $user_id = Auth::user()->id;
        return Excel::download(new LaporanPencatatanExport($r, $user_id), 'laporan_pencatatan.xlsx');
    }


    public static function filter($r, $user_id = "", $cetak = "N")
    {
        $catat = Pencatatan::query();
        if ($cetak == "cetak") {
            $catat->select(
                'pelanggans.id',
                'pelanggans.nama',
                'pencatatans.bulan',
                'pencatatans.tahun',
                'pencatatans.awal',
                'pencatatans.akhir',
                'pencatatans.pemakaian',
                'pencatatans.manual',
                'users.nama as pencatat',
                'golongans.golongan',
                'desas.desa',
                'wiljalans.jalan',
            );
        } else {
            $catat->select(
                'pelanggans.nama',
                'pencatatans.bulan',
                'pencatatans.tahun',
                'pencatatans.awal',
                'pencatatans.akhir',
                'pencatatans.pemakaian',
                'pencatatans.manual',
                'users.nama as pencatat',

            );
        }
        $catat->join('pelanggans', 'pelanggans.id', '=', 'pencatatans.pelanggan_id');
        $catat->join('users', 'users.id', '=', 'pencatatans.user_id');
        if ($cetak == "cetak") {
            $catat->join('golongans', 'pelanggans.golongan_id', '=', 'golongans.id');
            $catat->leftjoin('desas', 'pelanggans.desa_id', '=', 'desas.id');
            $catat->leftjoin('wiljalans', 'pelanggans.wiljalan_id', '=', 'wiljalans.id');
        }

        $catat->where('pencatatans.tahun', '=', $r->tahun);
        $catat->where('pencatatans.bulan', '=', $r->bulan);
        $user_id == "" ? '' : $catat->where('pencatatans.user_id', '=', $user_id);

        isset($r->golongan_id) ? $catat->where('pelanggans.golongan_id', '=', $r->golongan_id) : '';
        isset($r->wiljalan_id) ? $catat->where('pelanggans.wiljalan_id', '=', $r->wiljalan_id) : '';


        if ($cetak == "cetak") {
            return $catat->get();
        }
        return $catat->paginate(50);
    }

    public function laporanpencatatan(Request $r)
    {
        $this->validate($r, [
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        isset($r->semuaakun) ? $user_id = '' : $user_id = Auth::user()->id;

        $catatan = self::filter($r, $user_id);


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
