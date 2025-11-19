<?php

namespace App\Http\Controllers\Api\Absen;

use Carbon\Carbon;
use App\Models\Master\Absen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\CabangKegiatan;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Absen\AbsenAdminResource;

class AbsenAdminController extends Controller
{
    public function absen_admin(Request $r, $tanggal, $lapangan = null)
    {
        $tanggal = date('Y-m-d', strtotime($tanggal));
        $validator = Validator::make(['id' => $tanggal], [
            'id' => ['required', 'date_format:Y-m-d'],
        ]);

        $lapangan != null ? $jenis_absen = "lapangan" : $jenis_absen = "kantor";

        if ($validator->fails()) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Format id tidak valid. Harus YYYY-MM-DD.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $query = Absen::with('user')
            ->where('jenis_absen', '=', $jenis_absen)
            ->where('tanggal', $tanggal);
        $absen = $query->get();

        $jumlahHadir = (clone $query)
            ->where('status', 'hadir')
            ->count();

        $pagi = (clone $query)
            ->where('status', 'hadir')
            ->where('jam_masuk', '!=', NULL)
            ->where('jam_keluar', NULL)
            ->count();

        $sore = (clone $query)
            ->where('status', 'hadir')
            ->where('jam_keluar', '!=', NULL)
            ->where('jam_masuk', NULL)
            ->count();

        $full = (clone $query)
            ->where('status', 'hadir')
            ->where('jam_keluar', '!=', NULL)
            ->where('jam_masuk', '!=', NULL)
            ->count();

        $tanggalterpilih = $tanggal ? Carbon::parse($tanggal) : null;

        return response()->json([
            'sukses' => true,
            'data' => AbsenAdminResource::collection($absen),
            'hari' => $tanggalterpilih ? $tanggalterpilih->locale('id')->isoFormat('dddd') : null,
            'tanggal' => date('d-m-Y', strtotime($tanggal)),
            'detil' => [
                'hadir' => $jumlahHadir,
                'pagi' => $pagi,
                'sore' => $sore,
                'full' => $full
            ]
        ], 202);
    }


    public function absen_detail($id, $bulan, $tahun)
    {
        $absen = Absen::with('user')
        ->bulan($bulan)
        ->bulan($tahun)
        ->find($id);
        if (!$absen) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Data absen tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'sukses' => true,
            'data' => AbsenAdminResource::make($absen),
        ], 202);
    }

    public function set_kegiatan(Request $r)
    {

        $this->validate($r, [
            'nama_kegiatan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'tanggal' => 'required|date',
            'jam_mulai' => ['required', 'regex:/^(?:[0-9]|1[0-9]|2[0-3]):[0-5]\d$/'],
            'jam_selesai' => ['required', 'regex:/^(?:[0-9]|1[0-9]|2[0-3]):[0-5]\d$/'],
        ]);

        $user_id = Auth::user()->id;

        DB::beginTransaction();
        try {
            $kegiatan = new CabangKegiatan();
            $kegiatan->nama_kegiatan = $r->nama_kegiatan;
            $kegiatan->lokasi = $r->lokasi;
            $kegiatan->radius = 30;
            $kegiatan->lat = $r->lat;
            $kegiatan->long = $r->long;
            $kegiatan->tanggal = $r->tanggal;
            $kegiatan->jam_mulai = $r->jam_mulai;
            $kegiatan->jam_selesai = $r->jam_selesai;
            $kegiatan->user_id_dibuat = $user_id;
            $kegiatan->save();

            DB::commit();
            return response()->json([
                'sukses' => true,
                'pesan' => 'Kegiatan berhasil disimpan.',
            ], 202);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'sukses' => false,
                'pesan' => 'Terjadi kesalahan saat menyimpan kegiatan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function daftar_kegiatan(Request $r, $tanggal)
    {
        $tanggal = date('Y-m-d', strtotime($tanggal));
        $validator = Validator::make(['id' => $tanggal], [
            'id' => ['required', 'date_format:Y-m-d'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Format id tidak valid. Harus YYYY-MM-DD.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $kegiatan = CabangKegiatan::where('tanggal', $tanggal)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $tanggalterpilih = $tanggal ? Carbon::parse($tanggal) : null;

        return response()->json([
            'sukses' => true,
            'data' => $kegiatan,
            'hari' => $tanggalterpilih ? $tanggalterpilih->locale('id')->isoFormat('dddd') : null,
            'tanggal' => date('d-m-Y', strtotime($tanggal))
        ], 202);
    }


}
