<?php

namespace App\Http\Controllers\Api\Bank\BA;



use App\Models\Master\Tagihan;
use App\Models\Master\Transfer;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
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

        // $val = "/^\\d+$/";
        // $validasi = preg_match($val, $nopel); //cek hny angka
        // if ($validasi == 0) {
        //     return response()->json([
        //         "status" => false,
        //         "message" => "Nopel salah",
        //         "kode" => "02"
        //     ], 422);
        // }

        // Log::info(request()->header());

        $validator = Validator::make(["nopel" => $nopel], [
            'nopel' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status"    => false,
                "pesan" => $validator->errors(),
                "kode" => "02"
            ], 404);
        }

        $user = Auth::user();
        $this->payload = request()->header();
        $this->checksum = hash("sha256", $nopel . $user->client_id);
        $this->nopel = $nopel;


        if (!isset($this->payload['tandatangan'][0]) or ($this->checksum <> $this->payload['tandatangan'][0])) {
            return response()->json([
                "status"    => false,
                "pesan" => "Tanda tangan tidak sah",
                "kode" => "02"
            ], 401);
        }

        $urldasar = URL::to('/');
        if ($urldasar == "https://www.sandbox.tirtatawar.com" or $urldasar == "http://localhost:85/tirtatawar/public") {
            if ($nopel >= 5) {
                return response()->json([
                    "status" => false,
                    "pesan" => "Data tidak ditemukan.",
                    "kode" => "03"
                ], 404);
            }
        }




        $pelanggan = Pelanggan::with('golongan:id,denda')
            ->where('id', $nopel)->first();
        if (!$pelanggan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pelanggan tidak ditemukan...",
                "kode" => "03"
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
                "no reff"   =>  $catat->id,
            ];

            $id[] = $catat->tagihan->id;
        }

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

                if ($i == 1) {
                    $bill_id = $transfer->id;
                }

                Transfer::where('id', $transfer->id)
                    ->update(['bill_id' => $bill_id]);

                Tagihan::where('id', $catat->tagihan->id)
                    ->update(['bayar_bank' => date('Y-m-d H:i:s')]);
            }

            $data['id_transaksi'] = encrypt($bill_id);
            $data['detil'] = $detil;
            // $data['decr'] = decrypt($data['id']);

            DB::commit();
            // DB::rollback();
            return response()->json([
                "sukses" => true,
                "pesan" => "Tagihan ditemukan...",
                "kode"  => "00",
                "data" => $data,

            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal membayar, coba sesaat lagi...",
                'kode'  => "02"
            ], 500);
        }
    }
}
