<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Penagih;
use App\Models\Master\Setoran;
use App\Models\Viewdatabase\RincianRekapView;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class LaporanBayarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $r)  //DATA LAPORAN BAYAR YANG TAMPIL
    {
        $user_id = Auth::user()->id;
        isset($r->user) ? $user_id = $r->user : "";

        $tanggal = now();
        isset($r->tanggal) ? $tanggal = date('Y-m-d', strtotime($r->tanggal)) : "";


        $queri = [
            'tagihan:id,jumlah,diskon,denda,total,status_bayar,sistem_bayar,pencatatan_id',
            'tagihan.pencatatan:id,awal,akhir,pemakaian,bulan,tahun,pelanggan_id',
            'tagihan.pencatatan.pelanggan:id,nama'
        ];

        if (isset($r->bulan) && isset($r->tahun)) {
            $penagih = Penagih::with($queri)
                ->whereRelation('tagihan.pencatatan', 'bulan', '=', $r->bulan)
                ->whereRelation('tagihan.pencatatan', 'tahun', '=', $r->tahun)
                ->where('user_id', $user_id)
                ->whereDate('waktu', $tanggal)
                ->orderBy('id', "DESC")
                ->get();
        } else {
            $penagih = Penagih::with($queri)
                ->where('user_id', $user_id)
                ->whereDate('waktu', $tanggal)
                ->orderBy('id', "DESC")
                ->get();
        }

        $perbulan_tagih = 0;
        if (isset($r->bulan)) {
            foreach ($penagih as $p) {
                $perbulan_tagih += $p->tagihan->total;
            }
        }
        $setoran = Setoran::where('user_id', $user_id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        // isset($r->bulan) ? $setoran = "-" : "";

        return response()->json([
            "sukses" => true,
            "pesan" => "Ditemukan...",
            'penagih' => $penagih,
            'setoran' => $setoran,
            'perbulan_tagih' => $perbulan_tagih,
        ], 202);
    }


    public function download_laporan_bayar(Request $r)
    {
        $tanggal = now();
        isset($r->tanggal) ? $tanggal = date('Y-m-d', strtotime($r->tanggal)) : "";

        $user = Auth::user();
        $data['user'] = $user->nama;
        $data['tanggal'] = date('d-m-Y', strtotime($tanggal));

        // return view("api/pdf_laporan_bayar", compact('data'));
        $mpdf = new Mpdf();
        $mpdf->WriteHTML(view("api/pdf_laporan_bayar", compact('data')));
        $mpdf->Output('a.pdf', 'D');
    }



    public function laporan_bayar_where(Request $r)
    {
        $user_id = Auth::user()->id;
        isset($r->user) ? $user_id = $r->user : "";

        $tanggal = now();
        isset($r->tanggal) ? $tanggal = date('Y-m-d', strtotime($r->tanggal)) : "";


        $rekap = Penagih::query();
        $rekap->select(
            'penagihs.id',
            'penagihs.jumlah',
            'penagihs.waktu',
            'pencatatans.bulan',
            'pencatatans.tahun',
            'pelanggans.id as no_pel',
            'pelanggans.nama',
            'pelanggans.wiljalan_id',
        );
        $rekap->join('tagihans', 'tagihans.id', '=', 'penagihs.tagihan_id');
        $rekap->join('pencatatans', 'pencatatans.id', '=', 'tagihans.pencatatan_id');
        $rekap->join('pelanggans', 'pelanggans.id', '=', 'pencatatans.pelanggan_id');
        $rekap->where('penagihs.user_id', '=', $user_id);
        isset($r->wiljalan_id) ? $rekap->where('pelanggans.wiljalan_id', '=', $r->wiljalan_id) : "";
        $rekap->whereDate('penagihs.waktu', '=', $r->tanggal);

        $hasil = $rekap->get();

        if (count($hasil) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Data tidak ditemukan...",
            ], 404);
        }

        $total = 0;
        foreach ($hasil as $h) {
            $total = $total + $h['jumlah'];
        }

        return response()->json([
            "sukses" => true,
            "pesan" => "Ditemukan...",
            'penagih' =>  $hasil,
            'setoran' => $total, //total setoran 
        ], 202);
    }

    public function laporanpenerimaan()
    {
        $user_id = Auth::user()->id;
        $setoran = Setoran::with('user:id,nama', 'user_diserahkan:id,nama')
            ->where('user_id', $user_id)
            ->limit(50)->orderBy('id', "DESC")->get();

        return response()->json([
            "sukses" => true,
            "pesan" => "Ditemukan...",
            'setoran' => $setoran,
        ], 202);
    }

    public function laporanditerima(Request $r)
    {
        $tanggal = now();
        isset($r->tanggal) ? $tanggal = date('Y-m-d', strtotime($r->tanggal)) : "";

        $user_id = Auth::user()->id;
        $setoran = Setoran::with('user:id,nama', 'user_diserahkan:id,nama')
            ->where('user_id_diserahkan', $user_id)
            ->whereDate('tanggal', $tanggal)
            ->limit(100)->orderBy('id', "DESC")->get();

        if (count($setoran) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Setoran tidak ditemukan...",
            ], 404);
        }

        return response()->json([
            "sukses" => true,
            "pesan" => "Ditemukan...",
            'setoran' => $setoran,
        ], 202);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function rekap_setoran(Request $r)
    {
        $tanggal = now();
        isset($r->tanggal) ? $tanggal = date('Y-m-d', strtotime($r->tanggal)) : "";
        $setoran = Setoran::with('user:id,nama', 'user_diserahkan:id,nama')
            ->whereDate('tanggal', $tanggal)
            ->limit(100)->orderBy('id', "DESC")->get();

        if (count($setoran) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Setoran tidak ditemukan...",
            ], 404);
        }

        $total = 0;
        $dasar = 0;
        $denda = 0;
        $adm = 0;
        $pajak = 0;
        $diskon = 0;
        foreach ($setoran as $d) {
            $total = $total + $d->jumlah;
            $dasar = $dasar + $d->dasar;
            $denda = $denda + $d->denda;
            $adm = $adm + $d->adm;
            $pajak = $pajak + $d->pajak;
            $diskon = $diskon + $d->diskon;
        }

        return response()->json([
            "sukses" => true,
            "pesan" => "Ditemukan...",
            'setoran' => $setoran,
            'total' => $total,
            'dasar' => $dasar,
            'denda' => $denda,
            'adm' => $adm,
            'pajak' => $pajak,
            'diskon' => $diskon,
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
