<?php

namespace App\Http\Controllers\Api\Laporan;

use App\Exports\LaporanBayarExport;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Master\LapBayar;
use App\Models\Master\Wiljalan;
use App\Models\Master\Pelanggan;
use App\Models\Master\UserWiljalan;
use App\Http\Controllers\Controller;
use App\Models\Master\Penagih;
use App\Models\Master\Tagihan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function ambil_data($r, $user_id, $pdam_id, $tampil_data = NULL)
    {

        $data = Pelanggan::query();
        $data->select(
            'pelanggans.id',
            'pelanggans.nama',
            'tagihans.total_nodenda',
            'tagihans.status_bayar',
            'tagihans.sistem_bayar',
            'tagihans.total',
        );
        $data->join('pencatatans', 'pencatatans.pelanggan_id', '=', 'pelanggans.id');
        $data->join('tagihans', 'tagihans.pencatatan_id', '=', 'pencatatans.id');
        $data->where('pelanggans.user_id_petugas', '=', $user_id);

        isset($r->golongan_id) ? $data->where('pelanggans.golongan_id', '=', $r->golongan_id) : '';
        isset($r->wiljalan_id) ? $data->where('pelanggans.wiljalan_id', '=', $r->wiljalan_id) : '';
        isset($r->status_bayar) ? $data->where('tagihans.status_bayar', '=', $r->status_bayar) : '';
        isset($r->sistem_bayar) ? $data->where('tagihans.sistem_bayar', '=', $r->sistem_bayar) : '';


        $data->where('pencatatans.tahun', '=', $r->tahun);
        $data->where('pencatatans.bulan', '=', $r->bulan);
        $data->where('pelanggans.pdam_id', '=', $pdam_id);
        $jumlah_data = $data->get()->count();

        if ($jumlah_data == 0) {
            return [
                "sukses" => false,
                "pesan" => "Data tidak ditemukan...",
            ];
        }

        $total = 0;
        $bayar = 0;
        $total_drd = 0;
        $jumlah_terbayar = 0;
        foreach ($data->get() as $d) {
            $total_drd += $d['total_nodenda'];
            if ($d['status_bayar'] == "Y") {
                $bayar += 1;
                $jumlah_terbayar += $d['total'];
                $total += $d['total'];
            } elseif ($d['status_bayar'] == "N") {
                $total += $d['total_nodenda'];
            }
        }

        if ($tampil_data === NULL) {
            return [
                "sukses" => true,
                "pesan" => "Sukses, data ditemukan...",
                "drd" => $total_drd,
                "jumlah_data" => $jumlah_data,
                "jumlah_rupiah" => $total,
                "jumlah_terbayar" => $jumlah_terbayar,
                "terbayar" => $bayar,
                "data" => $data->paginate(50),
            ];
        } else {
            return [
                "sukses" => true,
                "pesan" => "Sukses, data ditemukan...",
                "drd" => $total_drd,
                "jumlah_data" => $jumlah_data,
                "jumlah_rupiah" => $total,
                "jumlah_terbayar" => $jumlah_terbayar,
                "terbayar" => $bayar,
            ];
        }
    }


    public function data_pencatatan(Request $r) //perorang
    {

        $this->validate($r, [
            'bulan' => 'required',
            'tahun' => 'required',
        ]);


        $user = Auth::user();
        $user_id = $user->id;
        $pdam_id = $user->pdam_id;
        isset($r->byuser) ? $user_id = $r->byuser : "";  //ISI ID USER

        $dataperorang = $this->ambil_data($r, $user_id, $pdam_id);
        if ($dataperorang['sukses'] == false) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Data tidak ditemukan...",
            ], 404);
        }
        $dataperorang['tagih_sendiri'] = $this->tagih_lapangan($user_id, $r);
        // $dataperorang['tagih_selain_akun'] = $this->nama_penagih_selain_akun($user_id, $r);



        // $akunlain = [];
        // $jumlah = [];
        // foreach ($dataperorang['tagih_selain_akun'] as $u) {

        //     $akunlain[] = [
        //         $u['nama'],
        //         $this->jumlah_tagih_lapangan_selain_akun_kita($u['id'], $r, $user_id)
        //     ];
        // }
        // $dataperorang['penginput_lain'] = $akunlain;
        return response()->json($dataperorang, 202);
    }


    public function data_pencatatan_selain_akun(Request $r) //DATA PENAGIH  selain akun KITA
    {
        $this->validate($r, [
            'bulan' => 'required',
            'tahun' => 'required',
        ]);
        $user = Auth::user();
        !isset($r->byuser) ? $byuser = $user->id : $byuser = $r->byuser;


        $data = $this->nama_penagih_selain_akun($byuser, $r);
        $akunlain = [];
        $jumlah = [];
        foreach ($data as $u) {
            $akunlain[] = [
                'nama'  => $u['nama'],
                'total' => $this->jumlah_tagih_lapangan_selain_akun_kita($u['id'], $r, $byuser),
            ];
        }

        if ($data->count() == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Data tidak ditemukan...",
            ], 404);
        }

        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data"  =>  $akunlain,
            "bank"  => $this->ditagih_bank($byuser, $r),
        ], 202);
    }

    public function ditagih_bank($id_petugas, $r)
    {
        $data = Tagihan::query();
        $data->join('pencatatans', 'pencatatans.id', '=', 'tagihans.pencatatan_id');
        $data->join('pelanggans', 'pelanggans.id', '=', 'pencatatans.pelanggan_id');
        $data->where('pencatatans.bulan', '=', $r->bulan);
        $data->where('pencatatans.tahun', '=', $r->tahun);
        $data->where('tagihans.status_bayar', '=', 'Y');
        $data->where('tagihans.sistem_bayar', '=', 'Transfer');
        $data->where('pelanggans.user_id_petugas', '=', $id_petugas);
        return $data->sum('tagihans.total');
    }
    public function jumlah_tagih_lapangan_selain_akun_kita($user_id, $r, $id_yang_login)
    {
        $data = Penagih::query();
        $data->join('tagihans', 'tagihans.id', '=', 'penagihs.tagihan_id');
        $data->join('pencatatans', 'pencatatans.id', '=', 'tagihans.pencatatan_id');
        $data->join('pelanggans', 'pelanggans.id', '=', 'pencatatans.pelanggan_id');
        $data->where('pencatatans.bulan', '=', $r->bulan);
        $data->where('pencatatans.tahun', '=', $r->tahun);
        $data->where('penagihs.user_id', '<>', $id_yang_login);
        $data->where('penagihs.user_id', '=', $user_id);
        $data->where('pelanggans.user_id_petugas', '=', $id_yang_login);
        return $data->sum('tagihans.total');
    }

    public function tagih_lapangan($user_id, $r)
    {
        $data = Penagih::query();
        // $data->select('tagihans.total');

        $data->join('tagihans', 'tagihans.id', '=', 'penagihs.tagihan_id');
        $data->join('pencatatans', 'pencatatans.id', '=', 'tagihans.pencatatan_id');
        $data->join('pelanggans', 'pelanggans.id', '=', 'pencatatans.pelanggan_id');
        $data->where('pencatatans.bulan', '=', $r->bulan);
        $data->where('pencatatans.tahun', '=', $r->tahun);
        $data->where('penagihs.user_id', '=', $user_id);
        $data->where('pelanggans.user_id_petugas', '=', $user_id);
        return $data->sum('tagihans.total');
    }

    public function nama_penagih_selain_akun($user_id, $r)
    {
        $data = Penagih::query();
        $data->distinct();

        $data->join('tagihans', 'tagihans.id', '=', 'penagihs.tagihan_id');
        $data->join('pencatatans', 'pencatatans.id', '=', 'tagihans.pencatatan_id');
        $data->join('pelanggans', 'pelanggans.id', '=', 'pencatatans.pelanggan_id');
        $data->join('users', 'users.id', '=', 'penagihs.user_id');
        $data->where('pencatatans.bulan', '=', $r->bulan);
        $data->where('pencatatans.tahun', '=', $r->tahun);
        $data->where('penagihs.user_id', '<>', $user_id);
        $data->where('pelanggans.user_id_petugas', '=', $user_id);
        return $data->get(['users.id', 'users.nama']);
    }

    public function data_pencatatan_banyak(Request $r) //proses dan simpan
    {
        $this->validate($r, [
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        $user = Auth::user();
        $pdam_id = $user->pdam_id;

        $ka = 2; //petugas
        $user = User::with('roles:id,name')
            ->where('pdam_id', $pdam_id)
            ->whereHas('roles', function ($q) use ($ka) {
                $q->where('id', '=', $ka);
            })
            ->get();

        $da = [];

        foreach ($user as $u) {
            $data = $this->ambil_data($r, $u['id'], $pdam_id, true);
            if ($data['sukses'] == false) continue;

            $tagih_lapangan = (int)$this->tagih_lapangan($u['id'], $r);


            $lap = LapBayar::where('bulan', $r->bulan)
                ->where('tahun', $r->tahun)
                ->where('user_id', $u['id'])
                ->first();
            if (!$lap) {
                $lap = new LapBayar();
            }

            $lap->user_id =  $u['id'];
            $lap->bulan =  $r->bulan;
            $lap->tahun =  $r->tahun;
            $lap->jumlah_p =  $data['jumlah_data'];
            $lap->p_terbayar =  $data['terbayar'];
            $lap->p_no_bayar =  $data['jumlah_data'] - $data['terbayar'];
            $lap->total_rp =  $data['jumlah_rupiah'];
            $lap->rp_terbayar =  $data['jumlah_terbayar'];
            $lap->rp_no_bayar =  $data['jumlah_rupiah'] - $data['jumlah_terbayar'];
            $lap->tagih_sendiri =  $tagih_lapangan;
            $lap->save();

            $da[] = $lap;
        }

        return response()->json([
            "sukses" => true,
            "pesan" => "Proses selesai...",
            "data" => $da,
        ], 201);
    }


    public static function rekap($r)
    {

        $data = LapBayar::query();
        $data->select(
            'users.nama',
            'lap_bayars.bulan',
            'lap_bayars.tahun',
            'lap_bayars.jumlah_p',
            'lap_bayars.p_terbayar',
            'lap_bayars.p_no_bayar',
            'lap_bayars.total_rp',
            'lap_bayars.rp_terbayar',
            'lap_bayars.rp_no_bayar',
            'lap_bayars.tagih_sendiri',
        );
        $data->join('users', 'users.id', '=', 'lap_bayars.user_id');
        $data->where('lap_bayars.bulan', '=', $r->bulan);
        $data->where('lap_bayars.tahun', '=', $r->tahun);
        $hasil_data = $data->get();


        return $hasil_data;
    }


    public function laporanbayarexport(Request $r)
    {
        $this->validate($r, [
            'bulan' => 'required',
            'tahun' => 'required',
        ]);
        return Excel::download(new LaporanBayarExport($r), 'laporan_bayar.xlsx');
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
