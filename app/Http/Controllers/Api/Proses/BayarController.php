<?php

namespace App\Http\Controllers\Api\Proses;

use Illuminate\Http\Request;
use App\Models\Master\Penagih;
use App\Models\Master\Setoran;
use App\Models\Master\Tagihan;
use App\Models\Master\Pelanggan;
use Illuminate\Support\Facades\DB;
use App\Models\Master\PenagihHapus;
use App\Http\Controllers\Controller;
use App\Models\Master\IzinPerubahan;
use Illuminate\Support\Facades\Auth;

class BayarController extends Controller
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
    public function simpan_diskon(Request $r)
    {
        $this->validate($r, [
            'id' => 'required', // tagihan id
            'jumlah' => 'required',
        ]);
        $user_id = Auth::user()->id;

        $tagihan = Tagihan::where('id', '=', $r->id)
            ->first();
        if ($tagihan->status_bayar === "Y") {
            return response()->json([
                "sukses" => false,
                "pesan" => "Sudah dibayar...",
            ], 202);
        }
        if ($tagihan->diskon == 0) {
            $tagihan->subtotal = $tagihan->subtotal - $r->jumlah;
            $tagihan->total = $tagihan->subtotal;
        } else {
            //jika diskon sudah diisi di tabel, maka di kurangi dulu denda kemudian jumlahkan dengan hitungan denda terbaru
            $tagihan->subtotal = ($tagihan->subtotal + $tagihan->diskon) - $r->jumlah;
            $tagihan->total = $tagihan->subtotal;
        }

        $tagihan->diskon = $r->jumlah;
        $tagihan->save();

        return response()->json([
            "sukses" => true,
            "pesan" => "Sukses menambah diskon...",
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $r)
    {
        $this->validate($r, [
            'id' => 'required',
            'pelanggan_id' => 'required',
        ]);
        $user_id = Auth::user()->id;


        DB::beginTransaction();
        try {
            $tagihan = Tagihan::where('id', '=', $r->id)
                ->where('status_bayar', '=', 'Y')
                // ->where('sistem_bayar', '=', 'Cash')
                ->first();
            if ($tagihan) {
                DB::rollback();
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Tagihan sudah dibayar...",
                ], 202);
            }

            $tagihan = Tagihan::findOrFail($r->id);
            $tagihan->status_bayar = "Y";
            $tagihan->sistem_bayar = "Cash";
            $tagihan->tgl_bayar = date('Y-m-d H:i:s');
            $tagihan->save();

            $penagih = new Penagih();
            $penagih->tagihan_id = $r->id;
            $penagih->user_id = $user_id;
            $penagih->jumlah = $tagihan->total;
            $penagih->waktu = now();
            $penagih->save();


            $setoran = Setoran::where('tanggal', '=', date('Y-m-d'))
                ->where('user_id', '=', $user_id)
                ->first();

            if ($setoran) {
                $tambahsetoran = Setoran::findOrFail($setoran->id);
                $tambahsetoran->jumlah = $tambahsetoran->jumlah  + $penagih->jumlah;
                $tambahsetoran->save();
            } else {
                $tambahsetoran = new Setoran();
                $setoransebelumnya = 0;
                $tambahsetoran->user_id = $user_id;
                $tambahsetoran->tanggal = date('Y-m-d');
                $tambahsetoran->jumlah = $penagih->jumlah;
                $tambahsetoran->save();
            }

            $userpenagih = Penagih::with('user:id,nama')
                ->where('id', '=', $penagih->id)->first();

            $pelangan = Pelanggan::with(
                'user:id,nama',
                'pdam:id,pdam,ttd',
                'desa.kecamatan:id,kecamatan',
                'golongan:id,golongan,biaya',
                'rute:id,rute',
            )->where('id', $r->pelanggan_id)->first();

            DB::commit();
            // DB::rollback();

            return response()->json([
                "sukses" => true,
                "pesan" => "Pembayaran sukses...",
                "pelanggan" => $pelangan,
                "datatagihan" => $tagihan,
                "penagih" =>  $userpenagih,
                "setoran" =>  $tambahsetoran,
                "tanggal" =>  date('d-m-Y'),
                "nohp" =>  "082223550421",
                "link" =>  "https://www.tirtatawar.com/playstore",

            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal membayar...",
            ], 404);
        }
    }

    public function cetak_ulang(Request $r)
    {
        $this->validate($r, [
            'id' => 'required',
            'pelanggan_id' => 'required',
        ]);
        $user_id = Auth::user()->id;


        DB::beginTransaction();
        try {
            $tagihan = Tagihan::where('id', '=', $r->id)
                ->where('status_bayar', '=', 'Y')
                ->where('sistem_bayar', '=', 'Cash')
                ->first();

            if (!$tagihan) {
                DB::rollback();
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Tagihan belum dibayar atau tidak melalui cash...",
                ], 202);
            }

            $tagihan = Tagihan::findOrFail($r->id);





            $userpenagih = Penagih::with('user:id,nama')
                ->where('tagihan_id', '=', $tagihan->id)->first();

            $pelangan = Pelanggan::with(
                'user:id,nama',
                'pdam:id,pdam,ttd',
                'desa.kecamatan:id,kecamatan',
                'golongan:id,golongan,biaya',
                'rute:id,rute',
            )->where('id', $r->pelanggan_id)->first();

            DB::commit();
            // DB::rollback();

            return response()->json([
                "sukses" => true,
                "pesan" => "Pembayaran sukses...",
                "pelanggan" => $pelangan,
                "datatagihan" => $tagihan,
                "penagih" =>  $userpenagih,
                "tanggal" =>  date('d-m-Y'),
                "nohp" =>  "082223550421",
                "link" =>  "https://www.tirtatawar.com/playstore",
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal menampilkan data...",
            ], 404);
        }
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
    public function destroy(Request $r)  //hapus berdasarkan no tagihna
    {
        $user_id = Auth::user()->id;


        DB::beginTransaction();
        try {
            $tagihan = Tagihan::where('id', '=', $r->id)  //id tagihan
                ->where('status_bayar', '=', 'Y')
                // ->where('sistem_bayar', '=', 'Cash')
                ->first();
            if (!$tagihan) {
                DB::rollback();
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Tagihan ini belum dibayar...",
                ], 202);
            }

            $tagihan = Tagihan::findOrFail($r->id);

            $penagih = Penagih::where('tagihan_id', $tagihan->id)->first();
            $jumlah = $penagih->jumlah;
            $tanggal = $penagih->waktu;



            if ($penagih->user_id <> $user_id) {
                DB::rollback();
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Anda tidak berhak membatalkan pembayaran ini...",
                ], 202);
            }

            // if (date('Y-m-d', strtotime($tanggal)) <> date('Y-m-d')) {
            //     DB::rollback();
            //     return response()->json([
            //         "sukses" => false,
            //         "pesan" => "Gagal membatalkan karena lewat hari...",
            //     ], 404);
            // }


            if ($penagih->user_id_izinhapus == NULL) {

                $data = Tagihan::with('pencatatan', 'pencatatan.pelanggan')
                    ->where('id', $tagihan->id)
                    ->first();


                // $user = Auth::user();
                $cek_izin = IzinPerubahan::where('id_dirubah', $penagih->id)
                    ->where('status', 0)
                    ->first();
                if ($cek_izin) {
                    return response()->json([
                        "sukses" => false,
                        "pesan" => "Izin pembatalan sebelumnya belum disetujui...",
                    ], 404);
                }

                $izin = new IzinPerubahan();
                $izin->tabel = "penagihs";
                $izin->fild = "user_id_izinhapus";
                $izin->id_dirubah = $penagih->id;
                $izin->dasar = "";
                $izin->final = 1;
                $izin->user_id = $user_id;
                $izin->ket = "<font color=red>Pembatalan Pembayaran </font><br>Nopel " . $data->pencatatan->pelanggan->id .
                    " - " . $data->pencatatan->pelanggan->nama . "<br>" .
                    " Sejumlah Rp.  " . $data->total . "- periode " . $data->pencatatan->bulan . "-" . $data->pencatatan->tahun . "<br>"
                    . "<br>Oleh " . Auth::user()->nama;
                $izin->pdam_id = Auth::user()->pdam_id;
                $izin->save();

                DB::commit();
                // DB::rollback();
                return response()->json([
                    "sukses" => true,
                    "pesan" => "Pembatalan menunggu izin...",
                ], 202);
            }



            $tagihan->status_bayar = "N";
            $tagihan->sistem_bayar = NULL;
            $tagihan->tgl_bayar = NULL;
            $tagihan->save();




            $pindah = new PenagihHapus();
            $pindah->user_id = $penagih->user_id;
            $pindah->jumlah = $penagih->jumlah;
            $pindah->waktu = $penagih->waktu;
            $pindah->tagihan_id = $penagih->tagihan_id;
            $pindah->user_id_izinhapus = $penagih->user_id_izinhapus;
            $pindah->user_id_penghapus = $user_id;
            $pindah->save();

            $penagih->delete();  //hapus

            $setoran = Setoran::whereDate('tanggal', '=', date('Y-m-d', strtotime($tanggal)))
                ->where('user_id', '=', $user_id)
                ->first();

            if ($setoran->diterima == 1) {
                DB::rollback();
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Gagal membatalkan karena setoran ini sudah diserahkan dan disetujui...",
                ], 404);
            }

            $setoran->jumlah = $setoran->jumlah - $jumlah;
            $setoran->save();



            DB::commit();
            // DB::rollback();

            return response()->json([
                "sukses" => true,
                "pesan" => "Pembatan sukses...",

            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal membatalkan...",
                "pesan_e" => $e,
            ], 404);
        }
    }
}
