<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Models\Master\Pencatatan;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanBayarBulananExport;

class LaporanBulananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public static function filter($r, $cetak = "N")
    {

        $catat = Pencatatan::query();

        if ($cetak == "cetak") {
            $catat->select(
                'pelanggans.nama',
                'golongans.golongan',
                'wiljalans.jalan',
                'pencatatans.bulan',
                'pencatatans.tahun',
                'tagihans.jumlah',
                'tagihans.biaya',
                'tagihans.pajak',
                'tagihans.total',
                'tagihans.status_bayar',
                'tagihans.sistem_bayar',
                'tagihans.tgl_bayar',
            );
        } else {
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
        }

        $catat->join('tagihans', 'tagihans.pencatatan_id', '=', 'pencatatans.id');
        $catat->join('pelanggans', 'pelanggans.id', '=', 'pencatatans.pelanggan_id');
        if ($cetak == "cetak") {
            $catat->join('golongans', 'pelanggans.golongan_id', '=', 'golongans.id');
            $catat->join('wiljalans', 'pelanggans.wiljalan_id', '=', 'wiljalans.id');
        }
        // $cetak == "cetak" ? $catat->join('golongans', 'pelanggans.golongan_id', '=', 'golongans.id') : "";
        $catat->where('pencatatans.tahun', '=', $r->tahun);
        $catat->where('pencatatans.bulan', '=', $r->bulan);

        isset($r->golongan_id) ? $catat->where('pelanggans.golongan_id', '=', $r->golongan_id) : '';
        isset($r->wiljalan_id) ? $catat->where('pelanggans.wiljalan_id', '=', $r->wiljalan_id) : '';
        isset($r->status_bayar) ? $catat->where('tagihans.status_bayar', '=', $r->status_bayar) : '';

        isset($r->waktu_bayar) ? $catat->whereYear('tagihans.tgl_bayar', '=', date('Y', strtotime($r->waktu_bayar))) : '';
        isset($r->waktu_bayar) ? $catat->whereMonth('tagihans.tgl_bayar', '=', date('m', strtotime($r->waktu_bayar))) : '';

        if ($cetak == "cetak") {
            return $catat->get();
        }
        return $catat->paginate(50);
    }


    public function laporan_bulanan(Request $r)
    {
        $this->validate($r, [
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        $catatan = self::filter($r);

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

    public function laporanbayarbulananexport(Request $r)
    {
        return Excel::download(new LaporanBayarBulananExport($r), 'laporan.xlsx');
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
