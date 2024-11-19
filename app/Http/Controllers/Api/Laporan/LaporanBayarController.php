<?php

namespace App\Http\Controllers\Api\Laporan;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use App\Models\Master\Penagih;
use App\Models\Master\Setoran;
use App\Models\Master\Golongan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanBayarPerhariExport;
use App\Models\Viewdatabase\RincianRekapView;

class LaporanBayarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function query($r, $user_id, $tanggal) //mengbil data pembayaran hari ini
    {
        $queri = [
            'tagihan:id,jumlah,diskon,denda,total,status_bayar,sistem_bayar,pencatatan_id',
            'tagihan.pencatatan:id,awal,akhir,pemakaian,bulan,tahun,pelanggan_id',
            'tagihan.pencatatan.pelanggan:id,nama,golongan_id,wiljalan_id',
            'tagihan.pencatatan.pelanggan.golongan:id,golongan',
            'tagihan.pencatatan.pelanggan.wiljalan:id,jalan',
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

        $gol = Golongan::all('id', 'golongan');
        $hitgol = [];
        foreach ($gol as $go) {
            $hitgol[$go->golongan] = 0;
        }


        $perbulan_tagih = 0;
        foreach ($penagih as $p) {
            if (isset($r->bulan)) {
                $perbulan_tagih += $p->tagihan->total;
            }
            foreach ($gol as $g) {
                if ($g->id == $p->tagihan->pencatatan->pelanggan->golongan->id) {
                    $hitgol[$p->tagihan->pencatatan->pelanggan->golongan->golongan] += $p->jumlah;
                }
            }
        }

        $setoran = Setoran::where('user_id', $user_id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        $hasil['penagih'] = $penagih;
        $hasil['setoran'] = $setoran;
        $hasil['perbulan_tagih'] = $perbulan_tagih;
        $hasil['pergolongan'] = $hitgol;
        return $hasil;
    }

    public function index(Request $r)  //DATA LAPORAN BAYAR YANG TAMPIL
    {
        $user_id = Auth::user()->id;
        isset($r->user) ? $user_id = $r->user : "";

        $tanggal = now();
        isset($r->tanggal) ? $tanggal = date('Y-m-d', strtotime($r->tanggal)) : "";


        $hasil = $this->query($r, $user_id, $tanggal);

        // isset($r->bulan) ? $setoran = "-" : "";

        return response()->json([
            "sukses" => true,
            "pesan" => "Ditemukan...",
            'penagih' => $hasil['penagih'],
            'setoran' => $hasil['setoran'],
            'perbulan_tagih' => $hasil['perbulan_tagih'],
        ], 202);
    }



    public function download_laporan_bayar(Request $r)
    {
        $tanggal = now();
        isset($r->tanggal) ? $tanggal = date('Y-m-d', strtotime($r->tanggal)) : "";

        $user = Auth::user();
        isset($r->user) ? $user_id = $r->user : $user_id = $user->id;


        $data['user'] = $user->nama;
        $data['tanggal'] = date('d-m-Y', strtotime($tanggal));

        $queri = $this->query($r, $user_id, $tanggal);
        $data['trx'] = count($queri['penagih']);
        $data['data'] = $queri['penagih'];
        $data['pergolongan'] = $queri['pergolongan'];
        $data['setoran'] = $queri['setoran'];

        // return view("api/pdf_laporan_bayar", compact('data'));
        $mpdf = new Mpdf();
        $mpdf->WriteHTML(view("api/pdf_laporan_bayar", compact('data')));
        $mpdf->Output('Laporan_bayar_' . $tanggal . '.pdf', 'D');
    }

    public function proses_download_laporan_bayar_excel(Request $r){
        $tanggal = now();
        isset($r->tanggal) ? $tanggal = date('Y-m-d', strtotime($r->tanggal)) : "";

        $user = Auth::user();
        isset($r->user) ? $user_id = $r->user : $user_id = $user->id;

        $data['user'] = $user->nama;
        $data['tanggal'] = date('d-m-Y', strtotime($tanggal));

        $queri = $this->query($r, $user_id, $tanggal);
        // return $queri['penagih'];
        $has=[];
        $a=0;
        foreach ($queri['penagih'] as $p){
            $a++;
            $has[]=[
                "no"=>$a,
                "nopel"=>$p['tagihan']['pencatatan']['pelanggan']['id'],
                "nama"=>$p['tagihan']['pencatatan']['pelanggan']['nama'],
                "bulan"=>$p['tagihan']['pencatatan']['bulan'],
                "bulan"=>$p['tagihan']['pencatatan']['tahun'],
                "jumlah"=>$p['jumlah'],
                "golongan"=>$p['tagihan']['pencatatan']['pelanggan']['golongan']['golongan'],
                "jalan"=>$p['tagihan']['pencatatan']['pelanggan']['wiljalan']['jalan'],

            ];
        }
        $has['user']=$queri['user'];
        $has['tanggal']=$queri['tanggal'];

        return $has;
    }

    public function download_laporan_bayar_excel(Request $r){


        // $this->validate($r, [
        //     'tanggal' => 'required',
        //     'user' => 'required|integer',
        // ]);
        return Excel::download(new LaporanBayarPerhariExport($r), 'laporan_bayar_perhari.xlsx');

    }


    public static function query2(Request $r, $user_id, $tanggal)
    {
        $query = Penagih::with(
            "tagihan:id,jumlah,diskon,biaya,pajak,denda,total,status_bayar,total_nodenda,pencatatan_id",
            "tagihan.pencatatan:id,bulan,tahun,pelanggan_id",
            "tagihan.pencatatan.pelanggan:id,nama,golongan_id,wiljalan_id",
            "tagihan.pencatatan.pelanggan.golongan:id,golongan",
            "tagihan.pencatatan.pelanggan.wiljalan:id,jalan"
        )
            ->wheredate("waktu", $tanggal)
            ->where('user_id', $user_id)
            ->get()
            ->sortByDesc('tagihan.pencatatan.bulan');

            
        return $data[] = [
            "semua"     => $query,

        ];
    }

    public function laporan_bayar_pecah_perbulan(Request $r) //download
    {
        $tanggal = now();
        isset($r->tanggal) ? $tanggal = date('Y-m-d', strtotime($r->tanggal)) : "";

        $user = Auth::user();
        isset($r->user) ? $user_id = $r->user : $user_id = $user->id;

        $data['user'] = $user->nama;
        $data['tanggal'] = date('d-m-Y', strtotime($tanggal));

        $semua_data = self::query2($r, $user_id, $tanggal);

        return $data['semua_data'] = $semua_data['semua'];


        $mpdf = new Mpdf();
        $mpdf->WriteHTML(view("api/pdf_laporan_bayar_pecah", compact('data')));
        $mpdf->Output('Laporan_bayar_' . $tanggal . '.pdf', 'D');
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
