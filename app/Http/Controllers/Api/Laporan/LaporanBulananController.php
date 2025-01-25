<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\DB;
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
                'pelanggans.id',
                'pelanggans.nama',
                'golongans.golongan',
                'wiljalans.jalan',
                'pencatatans.bulan',
                'pencatatans.tahun',
                'pencatatans.pemakaian',
                'tagihans.jumlah',
                'tagihans.biaya',
                'tagihans.pajak',

                // \DB::raw('(CASE WHEN tagihans.status_bayar = "Y" THEN tagihans.total ELSE tagihans.total_nodenda END) AS status_lable'), 
                // DB::raw('if(tagihans.status_bayar = "Y",tagihans.total,tagihans.total_nodenda) AS status_lable'), 
                // 'if(tagihans.status_bayar = "Y", tagihans.total, tagihans.total_nodenda)', 

                'tagihans.status_bayar',
                'tagihans.sistem_bayar',
                'tagihans.tgl_bayar',
                'users.nama as nama_user',
                'tagihans.total_nodenda',
                'tagihans.denda',

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
                'pelanggans.nama',
                'pelanggans.id as id_pel',
            );
        }



        $catat->join('tagihans', 'tagihans.pencatatan_id', '=', 'pencatatans.id');
        $catat->join('pelanggans', 'pelanggans.id', '=', 'pencatatans.pelanggan_id');


        if ($cetak == "cetak") {
            $catat->join('golongans', 'pelanggans.golongan_id', '=', 'golongans.id');
            $catat->join('wiljalans', 'pelanggans.wiljalan_id', '=', 'wiljalans.id');
            $catat->leftjoin('penagihs', 'penagihs.tagihan_id', '=', 'tagihans.id');
            $catat->leftjoin('users', 'penagihs.user_id', '=', 'users.id');
        }

        $catat->where('pencatatans.tahun', '=', $r->tahun);
        $catat->where('pencatatans.bulan', '=', $r->bulan);

        isset($r->golongan_id) ? $catat->where('pelanggans.golongan_id', '=', $r->golongan_id) : '';
        isset($r->wiljalan_id) ? $catat->where('pelanggans.wiljalan_id', '=', $r->wiljalan_id) : '';
        isset($r->status_bayar) ? $catat->where('tagihans.status_bayar', '=', $r->status_bayar) : '';

        isset($r->waktu_bayar) ? $catat->whereYear('tagihans.tgl_bayar', '=', date('Y', strtotime($r->waktu_bayar))) : '';
        isset($r->waktu_bayar) ? $catat->whereMonth('tagihans.tgl_bayar', '=', date('m', strtotime($r->waktu_bayar))) : '';

        if ($cetak == "cetak") {
            // return $catat->get();
            
            $tampung=[];
            foreach($catat->get() as $d){
                
                $denda=0;
                $denda_pembayaran=0;
                if($d->status_bayar=="Y"){
                    $denda=$d['denda'];
                    $denda_pembayaran=$denda+$d->total_nodenda;
                }

                $tampung[]=[
                    "pelanggan_id"      => $d->id,
                    "nama"              => $d->nama,
                    "golongan"              => $d->golongan,
                    "jalan"              => $d->jalan,
                    "bulan"              => $d->bulan,
                    "tahun"              => $d->tahun,
                    "pemakaian"              => $d->pemakaian,
                    "jumlah"              => $d->jumlah,
                    "biaya"              => $d->biaya,
                    "pajak"              => $d->pajak,
                    "total_nodenda"              => $d->total_nodenda,
                    "status_bayar"              => $d->status_bayar,
                    "sistem_bayar"              => $d->sistem_bayar,
                    "tgl_bayar"              => $d->tgl_bayar,
                    "denda"              => $denda,
                    "denda_pembayaran"              => $denda_pembayaran,
                    "nama_user"              => $d->nama_user,

                ];
            }


            return collect($tampung);

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
