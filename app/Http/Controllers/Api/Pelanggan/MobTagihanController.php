<?php

namespace App\Http\Controllers\Api\Pelanggan;

use App\Fungsi\Flip;
use Illuminate\Http\Request;
use App\Models\Master\Transfer;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Pelanggan\Tagihan\PelangganResource;
use App\Http\Resources\Api\Pelanggan\Tagihan\PencatatanResource;
use App\Models\Master\Bank;
use Illuminate\Contracts\Encryption\DecryptException;

use function PHPUnit\Framework\returnSelf;

class MobTagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function cektagihan(Request $r)
    {
        $this->validate($r, [
            'nopel' => 'required',
        ]);
        $id = $r->nopel;
        $potong = substr($r->nopel, 0, 2);
        if ($potong === "id") {
            $id = decrypt(substr($r->nopel, 2));
        }

        $pelanggan = Pelanggan::with('golongan:id,denda')
            ->where('id', $id)->first();
        if (!$pelanggan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pelanggan tidak ditemukan...",
            ], 404);
        }

        $pencatatan = Pencatatan::with('tagihan', 'pelanggan')
            ->where('pelanggan_id', $id)
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
        return response()->json([
            "sukses" => true,
            "pesan" => "Tagihan ditemukan...",
            "pelanggan" => new PelangganResource($pelanggan),
            "data" => $pencatatan,
            // "pajak" => 11,
        ], 202);
    }


    public function buattagihan(Request $r)
    {

        $this->validate($r, [
            'nopel' => 'required',
            'kode_bank' => 'required',
        ]);
        $id = $r->nopel;
        $potong = substr($r->nopel, 0, 2);
        if ($potong === "id") {
            $id = decrypt(substr($r->nopel, 2));
        }

        $bankdata = Bank::where('kode', $r->kode_bank)->first();
        if ($bankdata->aktif == "N") {
            return response()->json([
                "sukses" => false,
                "pesan" => "Bank dipilih belum bisa melakukan pembayaran...",
            ], 404);
        }


        $pelanggan = Pelanggan::with('desa:id,desa')->where('id', $id)->first();
        if (!$pelanggan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pelanggan tidak ditemukan...",
            ], 404);
        }
        if ($pelanggan->email === NULL) $pelanggan->email = "tirtatawar1@gmail.com";
        if ($pelanggan->desa === NULL) $pelanggan->desa = "Desa Test";


        if (isset($r->id)) {  //jika tagihan.a satu2
            $id_tagihan = decrypt($r->id);
            $pencatatan = Pencatatan::with('tagihan', 'pelanggan')
                ->where('pelanggan_id', $id)
                ->whereRelation('tagihan', 'status_bayar', '=', 'N')
                ->whereRelation('tagihan', 'id', '=', $id_tagihan)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $pencatatan = Pencatatan::with('tagihan', 'pelanggan')
                ->where('pelanggan_id', $id)
                ->whereRelation('tagihan', 'status_bayar', '=', 'N')
                ->orderBy('id', 'desc')
                ->get();
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


        $title = "Tagihan PDAM ";
        $total_jumlah = $total + $biaya_bank;
        $bank = $r->kode_bank;
        $nama = $pelanggan->nama;
        $email = $pelanggan->email;
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

    /**
     * Show the form for creating a new resource.
     */


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
