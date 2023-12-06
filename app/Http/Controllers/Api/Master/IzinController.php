<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\IzinPerubahan;
use Illuminate\Support\Facades\Auth;

class IzinController extends Controller
{
    public function data_izin()
    {
        $data = IzinPerubahan::where('status', 0)
            ->select([
                "id",
                "ket",
                "created_at"
            ])
            ->orderBy('created_at', "DESC")
            ->get()
            ->map(function ($item) {
                return [
                    "id" => $item->id,
                    "ket" => $item->ket,
                    "tanggal" => date('d-m-Y H:i:s', strtotime($item->created_at)),
                ];
            });

        return response()->json([
            'sukses' => true,
            'pesan' => "Perubahan berhasil...",
            'data' => $data,
        ], 202);
    }


    public function hapus_izin(Request $r)
    {
        $data = IzinPerubahan::findOrFail($r->id);
        $data->delete();
        return response()->json([
            'sukses' => true,
            'pesan' => "Sukses terhapus...",
        ], 204);
    }

    public function izin_di_setujui(Request $r)
    {
        $this->validate($r, [
            'id' => 'required',   // id izin
        ]);

        DB::beginTransaction();
        try {
            $data = IzinPerubahan::findOrFail($r->id);
            DB::table($data->tabel)
                ->where('id', $data->id_dirubah)
                ->update([
                    $data->fild => $data->final
                ]);


            $data->user_id_penyetuju = Auth::user()->id;
            $data->status = 1;
            $data->save();


            DB::commit();

            return response()->json([
                "sukses" => true,
                "pesan" => "Proses persetujuan sukses...",
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal Menyetujui...",
            ], 404);
        }
    }
}
