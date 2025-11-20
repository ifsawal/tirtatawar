<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Models\Master\Tagihan;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanBayarBankExport;
use App\Models\Master\Transfer;

class LaporanBayarBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function laporanbayarbank(Request $r)
    {
        isset($r->tanggal) ? $tanggal = date('Y-m-d', strtotime($r->tanggal)) : $tanggal = date('Y-m-d');

        $tagihan = Tagihan::with([
            'pencatatan:id,bulan,tahun,pelanggan_id',
            'pencatatan.pelanggan:id,nama',
            'transfer' => function ($query) {
                $query->where('status_bayar', 'Y')
                    ->select('tagihan_id', 'vendor', 'nama', 'jumlah', 'bill_id', 'status_bayar');
            },
        ])
            ->whereDate('tgl_bayar', $tanggal)
            ->where('status_bayar', 'Y')
            ->where('sistem_bayar', 'Transfer')
            ->limit(50)
            ->orderBy('tgl_bayar', 'DESC')
            ->get();

        if (count($tagihan) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Data tidak ditemukan...",
            ], 404);
        }

        // $flip=Transfer::where('status_bayar', 'Y')
        // ->whereDate('tgl_bayar', $tanggal)
        // ->where('vendor','flip')
        // ->get();


  

        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data" => $tagihan,

        ], 202);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function laporanbayarbankdownload(Request $r)
    {

        return Excel::download(new LaporanBayarBankExport($r), 'laporan_bayar_bank.xlsx');
    }

    public static function laporanbayarbankdata($r)
    {
        isset($r->tanggal) ? $tanggal = date('Y-m-d', strtotime($r->tanggal)) : $tanggal = date('Y-m-d');

        $rekap = Tagihan::query();
        $rekap->select(
            'tagihans.tgl_bayar',
            'pelanggans.id',
            'pelanggans.nama',
            'golongans.golongan',
            'wiljalans.jalan',
            'pencatatans.bulan',
            'pencatatans.tahun',
            'pencatatans.pemakaian',
            'tagihans.jumlah',
            'tagihans.biaya',
            'tagihans.diskon',
            'tagihans.denda',
            'tagihans.pajak',
            'tagihans.total',
            'tagihans.status_bayar',
            'transfers.vendor',
            'transfers.va',
            'transfers.bank',
            'transfers.tipe',
            'transfers.jumlah as jumlah_bayar',
            'pencatatans.id as no_ref',
            'transfers.status_bayar as status_trasfer',
        );
        $rekap->join('pencatatans', 'pencatatans.id', '=', 'tagihans.pencatatan_id');
        $rekap->join('pelanggans', 'pelanggans.id', '=', 'pencatatans.pelanggan_id');
        $rekap->join('golongans', 'pelanggans.golongan_id', '=', 'golongans.id');
        $rekap->join('wiljalans', 'pelanggans.wiljalan_id', '=', 'wiljalans.id');
        $rekap->join('transfers', 'transfers.tagihan_id', '=', 'tagihans.id');
        $rekap->whereYear('tagihans.tgl_bayar', '=', date('Y', strtotime($tanggal)));
        $rekap->whereMonth('tagihans.tgl_bayar', '=', date('m', strtotime($tanggal)));
        $rekap->where('tagihans.status_bayar', '=', "Y");
        $rekap->where('transfers.status_bayar', '=', "Y");
        $rekap->where('tagihans.sistem_bayar', '=', "Transfer");
        $rekap->orderBy('tagihans.tgl_bayar', "desc");


        return $rekap->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

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
