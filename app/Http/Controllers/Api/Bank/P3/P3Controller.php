<?php

namespace App\Http\Controllers\Api\Bank\P3;

use App\Models\Master\Bank;
// use Illuminate\Http\Request;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Repository\Tagihan\CekDanUpdateTagihan;

class P3Controller extends Controller
{

    protected $nopel;
    protected $checksum;
    protected $payload;

    public function cek_tagihan($nopel, $bank = NULL)
    {

        $validator = Validator::make(["nopel" => $nopel], [
            'nopel' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"    => false,
                "pesan" => $validator->errors(),
            ], 404);
        }


        Log::channel('custom')->info("Cek Tagihan " .   Request::ip() . "-" . $nopel);

        $user = Auth::user();
        $this->payload = request()->header();
        $this->checksum = hash("sha256", $nopel . $user->client_id);
        $this->nopel = $nopel;
        $ip = Request::ip();

        $jenis_prod = env("APP_ENV", "");
        $jenis_akses = env("APP_JENIS", "");
        if ($user->ip <> $ip and $jenis_prod == "production") {
            if ($jenis_akses === "sandbox") {
            } else {
                return response()->json([
                    "status"    => false,
                    "pesan" => "Akses server tidak di izinkan",
                ], 401);
            }
        }


        if (!isset($this->payload['tanda-tangan'][0]) or ($this->checksum <> $this->payload['tanda-tangan'][0])) {
            return response()->json([
                "status"    => false,
                "pesan" => "Tanda tangan tidak sah.",
            ], 401);
        }


        if ($user->contoh === NULL) {
        } else {
            $pecah = explode(",", $user->contoh);
            if (!in_array($nopel, $pecah)) {
                return response()->json([
                    "status" => false,
                    "pesan" => "Pelanggan terdaftar tidak ditemukan.",
                ], 404);
            }
        }


        $pelanggan = Pelanggan::with('golongan:id,denda')
            ->where('id', $nopel)->first();
        if (!$pelanggan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pelanggan tidak ditemukan.",
            ], 404);
        }

        $pencatatan = Pencatatan::with('tagihan', 'pelanggan')
            ->where('pelanggan_id', $nopel)
            ->whereRelation('tagihan', 'status_bayar', '=', 'N')
            ->orderBy('id', 'desc')
            ->get();

        $pencatatan = CekDanUpdateTagihan::ambilTagihan($pencatatan, $pelanggan->golongan->denda);
        $pencatatan = json_decode(collect($pencatatan));

        if (count($pencatatan) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Tagihan Tidak ditemukan...",
                "kode" => "14"
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
        $detil = array();
        $no = 0;
        foreach ($pencatatan as $catat) {
            $total += $catat->tagihan->total;
            $dasar += $catat->tagihan->jumlah;
            $adm += $catat->tagihan->biaya;
            $pajak += $catat->tagihan->pajak;
            $denda += $catat->tagihan->denda;
            $meteran += $catat->pemakaian;
            $periode .= $catat->bulan . "-" . $catat->tahun . ",";

            $detil[] = [
                "periode"   =>  $catat->bulan . "/" . $catat->tahun,
                "pemakaian"   =>  $catat->pemakaian,
                "denda"   =>  $catat->tagihan->denda,
                "tagihan"   =>  $catat->tagihan->jumlah,
                "no reff"   =>  decrypt($catat->id),
            ];

            $id[] = $catat->tagihan->id;
        }
        // return $detil;

        $data['pelanggan'] = $pelanggan->nama;
        $data['total_tagihan'] = $total;
        $data['harga_air'] = $dasar;
        $data['adm'] = $adm;
        $data['pajak_air_permukaan'] = $pajak;
        $data['denda'] = $denda;
        $data['pemakaian_m3'] = $meteran;
        $data['jumlah_bulan'] = count($pencatatan);
        $data['periode_bayar'] = $periode;
        $data['detil'] = $detil;
        $data['biaya_layanan_bank'] = "muncul pada enpoint buat-tagihan";

        if ($bank === NULL) {
        } else {
            $b = Bank::where('kode', $bank)->first();
            if ($b) {
                $data['biaya_layanan_bank'] = $b['biaya'];
            } else {
                return response()->json([
                    "status" => false,
                    "pesan" => "Pilihan Bank tidak tersedia.",
                ], 404);
            }
        }

        return response()->json([
            "sukses" => true,
            "pesan" => "Tagihan ditemukan...",
            "data" => $data,

        ], 200);
    }
}
