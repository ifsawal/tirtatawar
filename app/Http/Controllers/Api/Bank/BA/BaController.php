<?php

namespace App\Http\Controllers\Api\Bank\BA;

use Illuminate\Http\Request;

use App\Models\Master\Transfer;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Pelanggan\Tagihan\PelangganResource;
use App\Http\Resources\Api\Pelanggan\Tagihan\PencatatanResource;

class BaController extends Controller
{
    protected $nopel;


    protected $checksum;
    protected $payload;


    public function __construct()
    {
    }

    public function tagihan($nopel)
    {
        $val = "/^\\d+$/";

        $validasi = preg_match($val, $nopel); //cek hny angka
        if ($validasi == 0) {
            return response()->json([
                "message" => "Nopel salah",
            ], 422);
        }

        $user = Auth::user();
        $this->payload = request()->header();
        $this->checksum = hash("sha256", $nopel . $user->client_id);
        $this->nopel = $nopel;

        if ($this->checksum <> $this->payload['tandatangan'][0]) {
            return response()->json([
                "pesan" => "Tanda tangan tidak sah",
            ], 401);
        }


        if ($nopel >= 4) {
            return response()->json([
                "status" => false,
                "pesan" => "Data tidak ditemukan",
            ], 404);
        }


        $pelanggan = Pelanggan::with('golongan:id,denda')
            ->where('id', $nopel)->first();
        if (!$pelanggan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pelanggan tidak ditemukan...",
            ], 404);
        }

        $pencatatan = Pencatatan::with('tagihan', 'pelanggan')
            ->where('pelanggan_id', $nopel)
            ->whereRelation('tagihan', 'status_bayar', '=', 'N')
            ->orderBy('id', 'desc')
            ->get();


        $pencatatan = PencatatanResource::customCollection($pencatatan, $pelanggan->golongan->denda);
        if (count($pencatatan) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Tagihan Tidak ditemukan...",
            ], 404);
        }

        $data = [];
        $total = 0;
        $dasar = 0;
        $adm = 0;
        $denda = 0;
        $pajak = 0;
        $meteran = 0;
        $periode = "";
        $id = array();
        $no = 0;
        foreach ($pencatatan as $catat) {
            $total += $catat->tagihan->total;
            $dasar += $catat->tagihan->jumlah;
            $adm += $catat->tagihan->biaya;
            $pajak += $catat->tagihan->pajak;
            $denda += $catat->tagihan->denda;
            $meteran += $catat->pemakaian;
            $periode .= $catat->bulan . "-" . $catat->tahun . ",";

            $id[] = $catat->tagihan->id;
        }

        $data['pelanggan'] = $pelanggan->nama;
        $data['total_tagihan'] = $total;
        $data['dasar_tagihan'] = $dasar;
        $data['adm'] = $adm;
        $data['pajak_air_permukaan'] = $pajak;
        $data['denda'] = $denda;
        $data['pemakaian_m3'] = $meteran;
        $data['jumlah_bulan'] = count($pencatatan);
        $data['periode_bayar'] = $periode;

        DB::beginTransaction();
        try {
            $i = 0;
            foreach ($pencatatan as $catat) {
                $i++;
                if ($i == 1) {
                    $kode_transfer = $catat->bulan . $catat->tahun . $pelanggan->id;
                }

                $transfer = new Transfer();
                $transfer->vendor = "BA";
                $transfer->vendor_id = 0;
                $transfer->bill_id = 0;
                $transfer->va = "-";
                $transfer->tipe = "action";
                $transfer->bank = "ba";
                $transfer->nama = $pelanggan->nama;
                $transfer->jumlah = $total;
                $transfer->url = "-";
                $transfer->ket = "";
                $transfer->kode_transfer = $kode_transfer;
                $transfer->tagihan_id = $catat->tagihan->id;
                $transfer->save();
            }

            $data['id'] = encrypt($transfer->id);
            $data['decr'] = decrypt($data['id']);

            DB::commit();
            // DB::rollback();
            return response()->json([
                "sukses" => true,
                "pesan" => "Tagihan ditemukan...",
                "data" => $data,

            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal membayar, coba sesaat lagi...",
            ], 404);
        }
    }
}
