<?php

namespace App\Http\Controllers\Api\Bank\P3;

use Illuminate\Http\Request;
use App\Models\Master\Tagihan;
use App\Models\Master\Transfer;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class P3ResetController extends Controller
{
    public function reset(Request $r)
    {
        Log::info("Reset oleh " . $r->getClientIp());

        $jenis_akses = env("APP_JENIS", "");
        if ($jenis_akses == "sandbox") {
        } else {
            return response()->json([
                "sukses" => false,
                "pesan" => "Enpoint ini hanya bisa di akses melakui Sandbox atau pengujian...",
                "kode"  => "03"
            ], 404);
        }

        $user = Auth::user();
        if ($user->contoh === NULL or $user->contoh == "") {
            return response()->json([
                "status" => false,
                "pesan" => "Contoh Pelanggan tidak ditemukan, silahkan Ajukan contoh ke admin.",
            ], 404);
        }
        
        
        $pecah = explode(",", $user->contoh);
        

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::beginTransaction();
        try {

            for ($i = 0; $i< count($pecah); $i++) {

                $pelanggan = Pelanggan::whereId($pecah[$i])
                    ->first();

                $pencatatan = Pencatatan::where('pelanggan_id', '=', $pelanggan->id)->get();
                foreach ($pencatatan as $p) {

                    $c = Pencatatan::where('id', '=', $p->id)->first();

                    if (($c->bulan == 1 or $c->bulan == 2 or $c->bulan == 3 or $c->bulan == 4) && $c->tahun = 2024) { 
                    } else {
                        $c->forceDelete();
                    }


                    $tagihan = Tagihan::where('pencatatan_id', '=', $p->id)->first();
                    if ($tagihan && $tagihan->status_bayar == "Y" && $tagihan->sistem_bayar = "Transfer") {
                        $transfer = Transfer::where('tagihan_id', '=', $tagihan->id)
                            ->get();
                        foreach ($transfer as $t) {
                            $TF = Transfer::whereId($t->id)->first();
                            $TF->delete();
                        }

                        $tagihan->status_bayar = "N";
                        $tagihan->sistem_bayar = NULL;
                        $tagihan->tgl_bayar = NULL;
                        $tagihan->save();
                    }
                }
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::commit();
            return response()->json([
                "sukses" => true,
                "pesan" => "Reset sukses...",
                "kode"  => "00"
            ], 200);
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Reset Gagal...",
                "kode" => "03",
            ], 404);
        }
    }
}
