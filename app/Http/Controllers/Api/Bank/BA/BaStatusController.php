<?php

namespace App\Http\Controllers\Api\Bank\BA;

use Illuminate\Http\Request;
use App\Models\Master\Tagihan;
use App\Models\Master\Transfer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BaStatusController extends Controller
{

    protected $data;
    protected $id_transaksi;
    protected $no_trx;
    protected $status;
    protected $waktu;


    protected $checksum;
    protected $payload;

    public function status(Request $r)
    {
        $user = Auth::user();
        $this->payload = request()->header();


        $data = $r->getContent();
        $data = json_decode($data, true);


        $validator = Validator::make($data, [
            'id_transaksi' => 'required|max:255',
            'no_trx' => 'required',
            'status' => "required|in:Y,N",
            'waktu' => "required|date_format:Y-m-d H:i:s",
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status"    => false,
                "pesan" => $validator->errors(),
                "kode" => "02"
            ], 404);
        }


        $this->id_transaksi     = $data['id_transaksi'];
        $this->no_trx          = $data['no_trx'];
        $this->status          = $data['status'];
        $this->waktu            = $data['waktu'];




        $this->checksum = hash("sha256", $this->id_transaksi . $this->no_trx . $this->status . $this->waktu . $user->client_id);

        if (!isset($this->payload['tandatangan'][0]) or ($this->checksum <> $this->payload['tandatangan'][0])) {
            return response()->json([
                "status"    => false,
                "pesan" => "Tanda tangan tidak sah",
                "kode" => "02"
            ], 401);
        }

        $id_trx_dasar = decrypt($this->id_transaksi);

        $transfer = Transfer::where("bill_id", $id_trx_dasar)->get();
        if (count($transfer) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "ID transaksi tagihan salah...",
                "kode"  => "02"
            ], 404);
        }

        DB::beginTransaction();
        try {
            foreach ($transfer as $tran) {
                if ($tran->status_bayar == "Y") {
                    return response()->json([
                        "sukses" => false,
                        "pesan" => "Status sukses sudah diterima dan dikirim sebelumnya",
                        "kode"  => "90"
                    ], 200);
                    break;
                }


                Transfer::where('id', $tran->id)
                    ->update(['status_bayar' =>  $this->status, 'status_bayar_vendor' => 'SUCCESSFUL', 'vendor_id_string' => $this->no_trx]);

                if ($this->status == 'Y') {

                    //proses transfer pelanggan
                    if ($tran->tagihan_id <> NULL) {
                        Tagihan::where('id', $tran->tagihan_id)
                            ->update(['status_bayar' => 'Y', 'sistem_bayar' => 'Transfer', 'tgl_bayar' => date('Y-m-d H:i:s')]);
                    }
                }
            }



            DB::commit();
            return response()->json([
                "sukses" => true,
                "pesan" => "Sukses...",
                "kode"  => "00",
                "data"  => $data,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal...",
                "kode" => "03",
            ], 404);
        }
    }
}
