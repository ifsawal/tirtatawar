<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\IzinPerubahan;
use Illuminate\Support\Facades\Auth;

class IzinController extends Controller
{
    public function histori_izin()
    {
        $data = IzinPerubahan::with('user_penyetuju:id,nama')
            ->where('status', 1)
            ->select([
                "id",
                "ket",
                "created_at",
                "user_id_penyetuju",
            ])
            ->orderBy('created_at', "DESC")
            ->limit(70)
            ->get()
            ->map(function ($item) {
                return [
                    "id" => $item->id,
                    "ket" => $item->ket,
                    "tanggal" => date('d-m-Y H:i:s', strtotime($item->created_at)),
                    "disetujui" => $item->user_penyetuju->nama,
                ];
            });

        return response()->json([
            'sukses' => true,
            'pesan' => "Perubahan berhasil...",
            'data' => $data,
        ], 202);
    }


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
        $data = IzinPerubahan::find($r->id);
        if ($data && $data->status == 1) {
            return response()->json([
                'sukses' => false,
                'pesan' => "Gagal...Data sudah disetujui terlebih dahulu..",
            ], 404);
        }
        $data->delete();
        return response()->json([
            'sukses' => true,
            'pesan' => "Sukses terhapus...",
        ], 204);
    }

    public function izin_di_setujui(Request $r) //satu satu
    {
        $this->validate($r, [
            'id' => 'required',   // id izin
        ]);

        DB::beginTransaction();
        try {
            $data = IzinPerubahan::findOrFail($r->id);
            if ($data->status == 1) {
                DB::rollback();
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Izin sudah disetujui...",
                ], 404);
            }

            if ($data->dasar == "kolektif") {  //jika kolektif
                DB::commit();
                return $this->izin_di_setujui_colektif($r);
            }

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

    public function izin_di_setujui_colektif(Request $r)  //bnyak fild
    {
        $this->validate($r, [
            'id' => 'required',   // id izin
        ]);

        DB::beginTransaction();
        try {
            $data = IzinPerubahan::findOrFail($r->id);

            DB::table($data->tabel)
                ->where('id', $data->id_dirubah)
                ->update(json_decode($data->fild, TRUE));


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
