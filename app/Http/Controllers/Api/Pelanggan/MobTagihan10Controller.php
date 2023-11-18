<?php

namespace App\Http\Controllers\Api\Pelanggan;

use App\Fungsi\Flip;
use App\Models\Master\Bank;
use Illuminate\Http\Request;
use App\Models\Master\Transfer;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Pelanggan\Tagihan\PelangganResource;
use App\Http\Resources\Api\Pelanggan\Tagihan\PencatatanResource;

class MobTagihan10Controller extends Controller
{
    public function cektagihan10(Request $r)
    {
        $this->validate($r, [
            'nopel' => 'required',
        ]);


        $tampung_nopel = [];
        $pel = [];

        $urut = 0;
        $total_tagih = 0;

        foreach (explode('-', $r->nopel) as $no) {
            if ($urut > 9) continue; //batas proses

            if ($no == '' or $no == 0) continue;
            if (!is_numeric($no)) continue;

            //jika doble maka hapus salah satu
            $cari = in_array($no, $tampung_nopel);
            if ($cari) continue;
            $tampung_nopel[] = $no;

            $pelanggan = Pelanggan::with('golongan:id,denda')
                ->where('id', $no)->first();
            if (!$pelanggan) continue; //jika tidak ditemukan lanjut


            $pencatatan = Pencatatan::with('tagihan', 'pelanggan')
                ->where('pelanggan_id', $no)
                ->whereRelation('tagihan', 'status_bayar', '=', 'N')
                ->orderBy('id', 'desc')
                ->get();

            //hitung total
            $total_bayar_perpelanggan = 0;
            foreach ($pencatatan as $hit) {
                $total_bayar_perpelanggan = $total_bayar_perpelanggan + $hit->tagihan->total;
            }
            $total_tagih = $total_tagih + $total_bayar_perpelanggan;

            $pencatatan = PencatatanResource::customCollection($pencatatan, $pelanggan->golongan->denda);
            array_push($pel, (object)[
                'pel' => new PelangganResource($pelanggan),
                'catat' => $pencatatan,
                'total' => $total_bayar_perpelanggan,
            ]);


            $urut++; //urut terakhir for
        }

        if ($urut == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pelanggan tidak ditemukan...",
            ], 404);
        }



        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data" => $pel,
            "total_tagih" => $total_tagih,
        ], 202);
    }



    public function buattagihan10(Request $r)
    {
        $this->validate($r, [
            'nopel' => 'required',
            'kode_bank' => 'required',
        ]);


        $bankdata = Bank::where('kode', $r->kode_bank)->first();
        if ($bankdata->aktif == "N") {
            return response()->json([
                "sukses" => false,
                "pesan" => "Bank dipilih belum bisa melakukan pembayaran...",
            ], 404);
        }

        $tampung_nopel = [];


        $urut = 0;
        foreach (explode('-', $r->nopel) as $no) {
            if ($urut > 9) continue; //batas proses

            if ($no == '' or $no == 0) continue;
            if (!is_numeric($no)) continue;

            //jika doble maka hapus salah satu
            $cari = in_array($no, $tampung_nopel);
            if ($cari) continue;
            $tampung_nopel[] = $no;


            $pelanggan = Pelanggan::with('golongan:id,denda')
                ->where('id', $no)->first();
            if (!$pelanggan) {
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Pelanggan Nopel $no tidak ditemukan...",
                ], 404);
                break;
            }

            $urut++;  //urut terakhir
        }


        $pencatatan = Pencatatan::with('tagihan')
            ->whereIn('pelanggan_id', $tampung_nopel)
            ->whereRelation('tagihan', 'status_bayar', '=', 'N')
            ->orderBy('id', 'desc')
            ->get();

        $pel_ada_tagihan = [];  //cek apakah pelanggan ada tagihan belum di bayar. 
        foreach ($pencatatan->setVisible(['pelanggan_id']) as $p_id) {
            $pel_ada_tagihan[] = $p_id->pelanggan_id;
        }


        // menghpus nopel yang ga ada tagihannya
        $tampung_nopel_2 = [];
        foreach ($tampung_nopel as $nop_hap) {
            if (in_array($nop_hap, $pel_ada_tagihan)) {
                $tampung_nopel_2[] = $nop_hap;
            }
        }

        if (count($tampung_nopel_2) == 0) {  //jika tidak ada tagihan semunya
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal, Tidak tidak ditemukan...",
            ], 404);
        }

        $total = 0;
        $id = array();
        $no = 0;
        foreach ($pencatatan as $catat) {
            $total = $total + $catat->tagihan->total;
            $id[] = $catat->tagihan->id;
        }

        if ($bankdata->jenis == "wallet_account") {
            $biaya_bank = ($bankdata->biaya * $total) / 100;
            $biaya_bank = ceil($biaya_bank + (($biaya_bank * $bankdata->ppn) / 100)); //pajak 11%
        } else {
            $biaya_bank = $bankdata->biaya;
        }


        $title = "Tagihan PDAM Kolektif ";
        $total_jumlah = $total + $biaya_bank;
        $bank = $r->kode_bank;
        $nama = implode(" ", $tampung_nopel_2);
        $email = "tirtatawar1@gmail.com";
        $alamat = "";

        $hasil = Flip::create($title, $total_jumlah, $bank, $nama, $email, $alamat, $bankdata->jenis);
        $hasil = json_decode($hasil);

        DB::beginTransaction();
        try {
            $i = 0;
            foreach ($pencatatan as $catat) {
                $i++;
                if ($i == 1) {
                    $kode_transfer = $catat->bulan . $catat->tahun . $pelanggan->id;
                }

                $transfer = new Transfer();
                $transfer->vendor = "flip";
                $transfer->vendor_id = $hasil->link_id;
                $transfer->bill_id = $hasil->bill_payment->id;
                $transfer->va = $hasil->bill_payment->receiver_bank_account->account_number;
                $transfer->tipe = $hasil->bill_payment->receiver_bank_account->account_type;
                $transfer->bank = $hasil->bill_payment->receiver_bank_account->bank_code;
                $transfer->nama = $hasil->customer->name;
                $transfer->jumlah = $total_jumlah;
                $transfer->url = $hasil->payment_url;
                $transfer->ket = "";
                $transfer->kode_transfer = $kode_transfer;
                $transfer->tagihan_id = $catat->tagihan->id;
                $transfer->save();
            }


            DB::commit();
            // DB::rollback();
            return response()->json([
                "sukses" => true,
                "pesan" => "Sukses membuat tagihan...",
                "data" => $hasil,
                "data_server" => $bankdata->setVisible(['nama', 'jenis', 'ket']),

            ], 202);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal membayar, coba sesaat lagi...",
            ], 404);
        }
    }
}
