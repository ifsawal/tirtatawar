<?php

namespace App\Http\Controllers\Api\Bank\P3;

use App\Fungsi\Flip;
use App\Models\Master\Bank;
use Illuminate\Http\Request;
use App\Models\Master\Transfer;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Repository\Tagihan\CekDanUpdateTagihan;
use Illuminate\Support\Facades\Request as Request2;

class P3TagihanController extends Controller
{

    protected $data;
    protected $nopel;
    protected $kode_bank;
    protected $waktu;


    protected $checksum;
    protected $payload;


    public function buat_tagihan(Request $r)
    {


        $data = $r->getContent();
        $data = json_decode($data, true);

        Log::channel('custom')->info("Create Tagihan " .  $r->getClientIp()."-".$data['nopel']." ".$data['kode_bank']." ".$data['waktu']." ");

        $validator = Validator::make($data, [
            'nopel' => 'required|max:255',
            'kode_bank' => "required",
            'waktu' => "required|date_format:Y-m-d H:i:s",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"    => false,
                "pesan" => $validator->errors(),
            ], 404);
        }

        $user = Auth::user();
        $this->payload = request()->header();
        $this->checksum = hash("sha256", $data['nopel'] . $data['kode_bank'] . $data['waktu'] . $user->client_id);
        $this->nopel = $data['nopel'];
        $this->kode_bank = $data['kode_bank'];
        $ip = Request2::ip();

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
                "pesan" => "Tanda tangan tidak sah",
            ], 401);
        }

        if ($user->contoh === NULL or $user->contoh == "") {
        } else {
            $pecah = explode(",", $user->contoh);
            if (!in_array($this->nopel, $pecah)) {
                return response()->json([
                    "status" => false,
                    "pesan" => "Pelanggan terdaftar tidak ditemukan.",
                ], 404);
            }
        }


        $b=Bank::where('kode',$data['kode_bank'])->first();
        if(!$b){
            return response()->json([
                "status" => false,
                "pesan" => "Pilihan Bank tidak tersedia.",
            ], 404);
        }


        $pelanggan = Pelanggan::with('golongan:id,denda')
            ->where('id', $this->nopel)->first();
        if (!$pelanggan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pelanggan tidak ditemukan.",
            ], 404);
        }


        $pencatatan = Pencatatan::with('tagihan', 'pelanggan')
            ->where('pelanggan_id', $this->nopel)
            ->whereRelation('tagihan', 'status_bayar', '=', 'N')
            ->orderBy('id', 'desc')
            ->get();

            $pencatatan = CekDanUpdateTagihan::ambilTagihan($pencatatan,$pelanggan->golongan->denda);
            $pencatatan=json_decode(collect($pencatatan));

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



        $title = "Tagihan ".$user->nama;
        $total_jumlah = $total + $b['biaya'];
        $bank = $this->kode_bank;
        $nama = $pelanggan->nama;
        
        $email = $user->email;
        $alamat = "";

        $hasil = Flip::create($title, $total_jumlah, $bank, $nama, $email, $alamat, $b['jenis']);
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
                $transfer->tagihan_id = decrypt($catat->tagihan->id);
                $transfer->save();
            }



            $hasil_flip['customer']=$hasil->customer;
            $hasil_flip['bill_payment']=$hasil->bill_payment;
            if ($jenis_akses === "sandbox") {
                $hasil_flip['simulasi_pembayaran']=$hasil->payment_url;
            } 
            DB::commit();
            // DB::rollback();
            return response()->json([
                "sukses" => true,
                "pesan" => "Sukses membuat tagihan...",
                "data" => $hasil_flip,
                // "data_server" => $b->setVisible(['nama', 'jenis', 'ket']),

            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal membayar, coba sesaat lagi... $e",
            ], 404);
        }
    }
}
