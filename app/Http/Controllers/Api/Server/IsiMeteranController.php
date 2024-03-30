<?php

namespace App\Http\Controllers\Api\Server;

use App\Fungsi\Pencatatan\Prosespencatatan;
use App\Fungsi\Pencatatan\Prosestagihan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Master\Tagihan;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\DB;
use App\Models\Master\GolPenetapan;
use App\Http\Controllers\Controller;

class IsiMeteranController extends Controller
{

    // public function hitung($awal, $akhir)
    // {
    //     $pemakaian = $akhir - $awal;
    //     //jika minus
    //     if ($pemakaian < 0) {
    //         $panjang = strlen($awal);
    //         if ($panjang <= 3) {  //JIKA HASIL DIIBAWAH 3 DIGIT maka batalkan
    //             return -1;
    //         }

    //         $digit = "";
    //         for ($i = 0; $i < $panjang; $i++) {
    //             $digit = $digit . "9";
    //         }
    //         $digit = $digit + 1;

    //         $pemakaian = ($digit - $awal) + $akhir;
    //     }
    //     return $pemakaian;
    // }


    // public function simpanTagihan($pencatatan_id, $pelanggan_id, $pemakaian, $aksi = "tambah")
    // {

    //     if ($aksi == "tambah") {
    //         $tagihan = new Tagihan();
    //     } else {
    //         $tagihan = Tagihan::where('pencatatan_id', '=', $pencatatan_id)->first();
    //     }

    //     $golongan = Pelanggan::with('golongan:id,golongan,biaya,pajak,denda', 'golongan.goldetil:id,nama,awal_meteran,akhir_meteran,harga,golongan_id')
    //         ->select('nama', 'golongan_id', 'penetapan')
    //         ->where('id', '=', $pelanggan_id)
    //         ->get();

    //     $a = "";
    //     $jumlah = 0;
    //     if ($golongan[0]->penetapan == 1) {  //jika penetapan
    //         $harga = GolPenetapan::where('pelanggan_id', '=', $pelanggan_id)
    //             ->where('aktif', '=', 'Y')
    //             ->first();
    //         $biaya = $golongan[0]->golongan->biaya;
    //         $dasar_pajak = $golongan[0]->golongan->pajak;
    //         $denda_perbulan = $golongan[0]->golongan->denda;

    //         $jumlah = $harga->harga;
    //         $jumlah_pajak = $harga->pajak;
    //     } else if (isset($golongan[0]->golongan->goldetil)) {  //jika sesuai tarif
    //         foreach ($golongan[0]->golongan->goldetil as $detil) {
    //             if ($pemakaian == 0) {
    //                 $jumlah = $jumlah + 0;
    //                 break;
    //             } else if ($pemakaian > $detil->awal_meteran && $pemakaian <= $detil->akhir_meteran && $detil->akhir_meteran <> 0) {
    //                 $jumlah = $jumlah + ($detil->harga * ($pemakaian - $detil->awal_meteran));
    //                 break;
    //             } else if ($detil->akhir_meteran == 0) {
    //                 $jumlah = $jumlah + ($detil->harga * ($pemakaian - $detil->awal_meteran));
    //                 break;
    //             } else {
    //                 $jumlah = $jumlah + ($detil->harga * ($detil->akhir_meteran - $detil->awal_meteran));
    //             }
    //         }
    //         $biaya = $golongan[0]->golongan->biaya;
    //         $dasar_pajak = $golongan[0]->golongan->pajak;
    //         $denda_perbulan = $golongan[0]->golongan->denda;

    //         $jumlah = $jumlah;
    //         $jumlah_pajak = $pemakaian * $dasar_pajak;
    //     }


    //     $tagihan->pencatatan_id = $pencatatan_id;
    //     $tagihan->jumlah = $jumlah;
    //     $tagihan->denda_perbulan = $denda_perbulan;
    //     $tagihan->biaya = $biaya;
    //     $tagihan->pajak = $jumlah_pajak;
    //     $tagihan->subtotal = $jumlah + $biaya + $jumlah_pajak;
    //     $tagihan->total = $tagihan->subtotal;
    //     $tagihan->diskon = 0;
    //     $tagihan->denda = 0;
    //     isset($harga->id) ? $tagihan->gol_penetapan_id = $harga->id : ""; //isi id gol_penetapan jika terdaftar sebagai penetapan
    //     $tagihan->status_bayar = 'N';
    //     $tagihan->save();
    // }

    public function isi_meteran_belum_isi(Request $r)
    {
        // $p = new Prosespencatatan();
        // return $p->proses_catatan($r);
        $waktu = Carbon::createFromDate($r->tahun, $r->bulan, 1);
        $waktu = $waktu->subMonthsNoOverflow(1);
        $tahun = $waktu->format("Y");
        $bulanlalu = $waktu->format("m");

        $pemakaian = $r->pemakaian;


        $rekap = Pelanggan::query();
        $rekap->select(
            'pelanggans.id',
        );
        $rekap->join('pencatatans', 'pencatatans.pelanggan_id', '=', 'pelanggans.id');
        $rekap->where('pencatatans.tahun', '=', $r->tahun);
        $rekap->where('pencatatans.bulan', '=', $r->bulan);


        $pel = Pelanggan::query();
        $pel->select(
            'id',
            'nama',
        );
        $pel->whereNotIn('id', $rekap->get());

        $sukses = 0;
        $gagal = 0;
        $bulan_lalu_tidak_ditemukan = 0;

        foreach ($pel->get() as $p) {
            DB::beginTransaction();
            try {

                $cekcatat = Pencatatan::where('bulan', $bulanlalu)
                    ->where('tahun', $tahun)
                    ->where('pelanggan_id', $p->id)
                    ->first();
                if (!$cekcatat) {
                    DB::rollback();
                    $bulan_lalu_tidak_ditemukan += 1;
                    continue;
                }

                $akhir = $cekcatat->akhir + $r->pemakaian;
                // if ($cekcatat->pemakaian == 0) {
                //     $akhir = $cekcatat->akhir;
                //     $pemakaian = 0;
                // } else {
                //     $pemakaian = $r->pemakaian;
                // }

                $pencatatan =  new Pencatatan();
                $pencatatan->awal = $cekcatat->akhir; //
                $pencatatan->akhir = $akhir; //
                $pencatatan->pemakaian = $pemakaian; //
                $pencatatan->bulan = $r->bulan; //
                $pencatatan->tahun = $r->tahun; //
                $pencatatan->pelanggan_id = $p->id;
                $pencatatan->user_id = 1; //
                $pencatatan->manual = 1; //
                $pencatatan->save();

                $tag = new Prosespencatatan();
                $tag->simpanTagihan($pencatatan->id, $p->id, $r->pemakaian);
                // $this->simpanTagihan($pencatatan->id, $p->id, $r->pemakaian);
                DB::commit();
                $sukses += 1;
            } catch (\Exception $e) {
                DB::rollback();
                $gagal += 1;
            }
        }
        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "sukses" => $sukses,
            "gagal" => $gagal,
            "bulan_lalu_tidak_ditemukan" => $bulan_lalu_tidak_ditemukan,
        ], 202);
    }



    public function isi_meteran(Request $r) //otomatis
    {

        $waktu = Carbon::createFromDate($r->tahun, $r->bulan, 1);
        $waktu = $waktu->subMonthsNoOverflow(1);
        $tahun = $waktu->format("Y");
        $bulanlalu = $waktu->format("m");

        $pemakaian = $r->pemakaian;

        $pel = Pelanggan::where('user_id_petugas', $r->petugas_id)
            ->where('wiljalan_id', $r->wiljalan_id)
            ->where('golongan_id', $r->golongan_id)
            ->get();


        $sukses = 0;
        $gagal = 0;
        $bulan_lalu_tidak_ditemukan = 0;
        foreach ($pel as $p) {

            DB::beginTransaction();
            try {

                $cekcatat = Pencatatan::where('bulan', $bulanlalu)
                    ->where('tahun', $tahun)
                    ->where('pelanggan_id', $p->id)
                    ->first();
                if (!$cekcatat) {
                    DB::rollback();
                    $bulan_lalu_tidak_ditemukan += 1;
                    continue;
                }

                $akhir = $cekcatat->akhir + $r->pemakaian;
                // if ($cekcatat->pemakaian == 0) {
                //     $akhir = $cekcatat->akhir;
                //     $pemakaian = 0;
                // } else {
                //     $pemakaian = $r->pemakaian;
                // }

                $pencatatan =  new Pencatatan();
                $pencatatan->awal = $cekcatat->akhir; //
                $pencatatan->akhir = $akhir; //
                $pencatatan->pemakaian = $pemakaian; //
                $pencatatan->bulan = $r->bulan; //
                $pencatatan->tahun = $r->tahun; //
                $pencatatan->pelanggan_id = $p->id;
                $pencatatan->user_id = 1; //
                $pencatatan->manual = 1; //
                $pencatatan->save();

                $tag = new Prosespencatatan();
                $tag->simpanTagihan($pencatatan->id, $p->id, $r->pemakaian);
                // $this->simpanTagihan($pencatatan->id, $p->id, $r->pemakaian);
                DB::commit();
                $sukses += 1;
            } catch (\Exception $e) {
                DB::rollback();
                $gagal += 1;
            }
        }

        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "sukses" => $sukses,
            "gagal" => $gagal,
            "bulan_lalu_tidak_ditemukan" => $bulan_lalu_tidak_ditemukan,
        ], 202);
    }
}
