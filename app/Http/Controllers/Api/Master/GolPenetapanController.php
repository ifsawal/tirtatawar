<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use App\Models\Master\Pelanggan;
use Illuminate\Support\Facades\DB;
use App\Models\Master\GolPenetapan;
use App\Http\Controllers\Controller;
use App\Models\Master\IzinPenetapan;
use Illuminate\Support\Facades\Auth;

class GolPenetapanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',

        ]);

        $cek = GolPenetapan::where('pelanggan_id', '=', $request->id)
            ->select('id', 'harga', 'pajak')
            ->where('aktif', '=', 'Y')
            ->first();

        $histori = GolPenetapan::with('user:id,nama', 'user_perubahan:id,nama')
            ->where('pelanggan_id', '=', $request->id)

            ->limit(10)->orderBy('id', 'DESC')
            ->get();


        return response()->json([
            'sukses' => true,
            'pesan' => "Data ditemukan...",
            'data_aktif' => $cek,
            'data_histori' => $histori,
        ], 202);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function simpan_inti($id, $harga, $pajak, $alasan, $ket)
    {
        $user_id = Auth::user()->id;

        $pelanggan = Pelanggan::where('id', $id)->first();
        if (!$pelanggan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pelanggan tidak ditemukan..."
            ], 404);
        }

        $sebelumnya = GolPenetapan::where('pelanggan_id', '=', $id)
            ->where('aktif', '=', 'Y')
            ->first();

        if ($sebelumnya) {

            DB::beginTransaction();
            try {
                $sebelumnya->aktif = "N";
                $sebelumnya->tgl_akhir = now();
                $sebelumnya->user_id_perubahan = $user_id;
                $sebelumnya->save();

                $penetapan = new GolPenetapan();
                $penetapan->pelanggan_id = $id;
                $penetapan->harga = $harga;
                $penetapan->pajak = $pajak;
                $penetapan->aktif = "Y";
                $penetapan->tgl_awal = now();
                $penetapan->alasan = $alasan;
                $penetapan->ket = $ket;
                $penetapan->user_id = $user_id;
                $penetapan->save();

                $pel = Pelanggan::findOrFail($id);
                $pel->penetapan = 1;
                $pel->save();

                DB::commit();
                // DB::rollback();
                return response()->json([
                    'sukses' => true,
                    'pesan' => "Perubahan harga berhasil...",

                ], 202);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Erorr..."
                ], 404);
            }
        }

        DB::beginTransaction();
        try {
            $penetapan = new GolPenetapan();
            $penetapan->pelanggan_id = $id;
            $penetapan->harga = $harga;
            $penetapan->pajak = $pajak;
            $penetapan->aktif = "Y";
            $penetapan->tgl_awal = now();
            $penetapan->alasan = $alasan;
            $penetapan->ket = $ket;
            $penetapan->user_id = $user_id;
            $penetapan->save();

            $pel = Pelanggan::findOrFail($id);
            $pel->penetapan = 1;
            $pel->save();

            DB::commit();
            return response()->json([
                'sukses' => true,
                'pesan' => "Penetapan harga tetap berhasil...",
            ], 202);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Erorr..."
            ], 404);
        }
    }

    public function store(Request $r) //penetapan dari menu pelanggan
    {
        $this->validate($r, [
            'id' => 'required',
            'harga' => 'required',
            'pajak' => 'required',
            'alasan' => 'required',

        ]);

        return $this->simpan_inti($r->id, $r->harga, $r->pajak, $r->alasan, $r->ket);
    }



    public function simpan_penetapan_dari_catatan(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'harga' => 'required',
            'pajak' => 'required',
            'alasan' => 'required',

        ]);
        $user = Auth::user();

        $pelanggan = Pelanggan::where('id', $request->id)->first();
        if (!$pelanggan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pelanggan tidak ditemukan..."
            ], 404);
        }

        $sebelumnya = IzinPenetapan::where('pelanggan_id', '=', $request->id)
            ->where('status', '=', 0)
            ->first();
        if ($sebelumnya) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Izin sebelumnya belum disetujui..."
            ], 404);
        }


        DB::beginTransaction();
        try {
            $penetapan = new IzinPenetapan();
            $penetapan->pelanggan_id = $request->id;
            $penetapan->harga = $request->harga;
            $penetapan->pajak = $request->pajak;
            $penetapan->aktif = "Y";
            $penetapan->harga = $request->harga;
            $penetapan->tgl_awal = now();
            $penetapan->alasan = $request->alasan;
            $penetapan->ket = $request->ket;
            $penetapan->user_id = $user->id;
            $penetapan->pdam_id = $user->pdam_id;
            $penetapan->save();

            DB::commit();
            return response()->json([
                'sukses' => true,
                'pesan' => "Permintaan izin penetapan harga tetap berhasil...",
            ], 202);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Erorr..."
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function data_izin_penetapan()
    {
        $rekap = IzinPenetapan::query();
        $rekap->select(
            'izin_penetapans.id',
            'izin_penetapans.harga',
            'izin_penetapans.pajak',
            'izin_penetapans.tgl_awal',
            'izin_penetapans.alasan',
            'izin_penetapans.ket',
            'pelanggans.id as pel_id',
            'pelanggans.nama',
            'users.id as user_id',
            'users.nama as user',

        );
        $rekap->join('pelanggans', 'pelanggans.id', '=', 'izin_penetapans.pelanggan_id');
        $rekap->join('users', 'users.id', '=', 'izin_penetapans.user_id');
        $rekap->where('izin_penetapans.status', '=', '0');


        return response()->json([
            'sukses' => true,
            'pesan' => "Perubahan berhasil...",
            'data' => $rekap->get(),
        ], 202);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function setujuipenetapan(Request $r)
    {
        $this->validate($r, [
            'id' => 'required',  //ID IZIN PENETAPAN
        ]);

        $data = IzinPenetapan::where('id', $r->id)
            ->where('status', '0')
            ->first();

        if (!$data) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Data ini sudah diproses..."
            ], 404);
        }

        $data->status = 1;
        $data->user_id_penyetuju = Auth::user()->id;
        $data->save();
        return $this->simpan_inti($data->pelanggan_id, $data->harga, $data->pajak, $data->alasan, $data->ket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function hapus_izin_penetapan(Request $r)
    {
        $this->validate($r, [
            'id' => 'required',  //ID IZIN PENETAPAN
        ]);

        $data = IzinPenetapan::where('id', $r->id)
            ->where('status', '0')
            ->first();
        if (!$data) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Data tidak ditemukan"
            ], 404);
        }


        $data->delete();
        return response()->json([
            "sukses" => true,
            "pesan" => "Sukses ditolak..."
        ], 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $r)
    {
        $this->validate($r, [
            'id' => 'required',
        ]);
        $sebelumnya = GolPenetapan::where('pelanggan_id', '=', $r->id)
            ->where('aktif', '=', 'Y')
            ->first();
        $user_id = Auth::user()->id;

        if ($sebelumnya) {
            DB::beginTransaction();
            try {

                $sebelumnya->aktif = 'N';
                $sebelumnya->tgl_akhir = now();
                $sebelumnya->user_id_perubahan = $user_id;

                $sebelumnya->save();

                $pel = Pelanggan::findOrFail($r->id);
                $pel->penetapan = 0;
                $pel->save();

                DB::commit();
                return response()->json([
                    'sukses' => true,
                    'pesan' => "Sukses terhapus...",
                ], 204);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Erorr..."
                ], 404);
            }
        }
    }
}
