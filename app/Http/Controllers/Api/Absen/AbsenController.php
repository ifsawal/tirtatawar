<?php

namespace App\Http\Controllers\Api\Absen;

use Carbon\Carbon;
use App\Helper\Helpers;
use App\Models\Master\Absen;
use Illuminate\Http\Request;
use App\Models\Master\Cabang;
use App\Models\Master\Usercab;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Resources\Api\Absen\AbsenResource;
use App\Models\Master\Izin;

class AbsenController extends Controller
{
    //
    public function absen(Request $r)
    {
        $this->validate($r, [
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'photo' => 'required',
        ]);

        $user_id = Auth::user()->id;
        $cabang = Usercab::where('user_id', $user_id)->first();
        $cabang ? $cabang_id = $cabang->cabang_id : $cabang_id = 1;

        $cabangData = Cabang::find($cabang_id);

        $jarak = Helpers::jarak($r->lat, $r->long, $cabangData->lat, $cabangData->long);
        $dalamRadius = Helpers::dalamRadius($r->lat, $r->long, $cabangData->lat, $cabangData->long, $cabangData->radius);
        if (!$dalamRadius) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Anda berada di luar radius absen. Jarak Anda dari Kantor: ' . number_format($jarak, 2) . ' meter.'
            ], 400);
        }

        $tanggal = date('Y-m-d');
        $jam_masuk = date('H:i:s');
        $jam_kebelakang = date('H:i:s', strtotime($cabangData->pagi . '-2 hours'));
        if (strtotime($jam_masuk) < strtotime($jam_kebelakang)) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Waktu absen masuk belum dimulai...',
            ], 400);
        }

        if (strtotime($jam_masuk) > strtotime($cabangData->pagi)) { //==========================================
            return response()->json([ //========================================================================
                'sukses' => false,
                'pesan' => 'Waktu absen masuk sudah lewat pukul ' . date('H:i', strtotime($cabangData->pagi)) . '.',
            ], 400);
        }

        $cekAbsen = Absen::where('user_id', '=', $user_id)
            ->where('tanggal', '=', $tanggal)
            ->exists();
        if ($cekAbsen) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Anda sudah melakukan Absen...'
            ], 400);
        }


        $nama_gambar = config('external.nama_gambar');
        $file = md5($nama_gambar . $tanggal . $user_id) . ".jpg";
        if (!File::isDirectory(public_path() . '/files2/absen/' . date('Y') . '/' . date('m') . '/' . date('d'))) {
            File::makeDirectory(public_path() . '/files2/absen/' . date('Y') . '/' . date('m') . '/' . date('d'), 0777, true, true);
        }

        DB::beginTransaction();
        try {

            $absen = new Absen();
            $absen->user_id = $user_id;
            $absen->cabang_id = $cabang_id;
            $absen->tanggal = $tanggal;
            $absen->jam_masuk = $jam_masuk;
            $absen->status = 'hadir';
            $absen->lokasi_masuk = $r->lat . ',' . $r->long;
            $absen->foto_masuk = $file;
            $absen->save();

            $plainText = base64_decode(str_replace(array('-', '_', ' ', '\n'), array('+', '/', '+', ' '), $r->photo));
            $ifp = fopen(public_path() . '/files2/absen/' . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $file, "wb");
            fwrite($ifp,  $plainText);
            fclose($ifp);

            DB::commit();
            return response()->json([
                'sukses' => true,
                'pesan' => 'Absen berhasil dicatat.',
                'pukul' => 'Sukses tercatat pukul : ' . $jam_masuk,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'sukses' => false,
                'pesan' => 'Gagal melakukan absen ',
            ], 500);
        }
    }

    public function absen_pulang(Request $r)
    {
        $this->validate($r, [
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'photo' => 'required',
        ]);


        $user_id = Auth::user()->id;
        $cabang = Usercab::where('user_id', $user_id)->first();
        $cabang ? $cabang_id = $cabang->cabang_id : $cabang_id = 1;

        $cabangData = Cabang::find($cabang_id);

        $jarak = Helpers::jarak($r->lat, $r->long, $cabangData->lat, $cabangData->long);
        $dalamRadius = Helpers::dalamRadius($r->lat, $r->long, $cabangData->lat, $cabangData->long, $cabangData->radius);
        if (!$dalamRadius) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Anda berada di luar radius absen. Jarak Anda dari Kantor: ' . number_format($jarak, 2) . ' meter.'
            ], 400);
        }

        $tanggal = date('Y-m-d');
        $jam_masuk = date('H:i:s');
        $jam_kedepan = date('H:i:s', strtotime($cabangData->sore . '+2 hours'));
        if (strtotime($jam_masuk) < strtotime($cabangData->sore)) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Waktu absen keluar belum dimulai...',
            ], 400);
        }

        if (strtotime($jam_masuk) > strtotime($jam_kedepan)) {  //============================================
            return response()->json([ //========================================================================
                'sukses' => false,
                'pesan' => 'Waktu absen keluar sudah lewat pukul ' . date('H:i', strtotime($cabangData->sore)) . '.',
            ], 400);
        }


        $cekAbsen = Absen::where('user_id', '=', $user_id)
            ->where('tanggal', '=', $tanggal)
            ->first();
        if (!$cekAbsen) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Anda tidak melakukan absen pagi...'
            ], 400);
        }



        if ($cekAbsen->jam_keluar != null) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Anda sudah melakukan Absen pulang...'
            ], 400);
        }


        $nama_gambar = config('external.nama_gambar');
        $file = md5($nama_gambar . $tanggal . $user_id) . "keluar.jpg";
        if (!File::isDirectory(public_path() . '/files2/absen/' . date('Y') . '/' . date('m') . '/' . date('d'))) {
            File::makeDirectory(public_path() . '/files2/absen/' . date('Y') . '/' . date('m') . '/' . date('d'), 0777, true, true);
        }




        DB::beginTransaction();
        try {

            $cekAbsen->jam_keluar = $jam_masuk;
            $cekAbsen->lokasi_keluar = $r->lat . ',' . $r->long;
            $cekAbsen->foto_keluar = $file;
            $cekAbsen->save();

            $plainText = base64_decode(str_replace(array('-', '_', ' ', '\n'), array('+', '/', '+', ' '), $r->photo));
            $ifp = fopen(public_path() . '/files2/absen/' . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $file, "wb");
            fwrite($ifp,  $plainText);
            fclose($ifp);

            DB::commit();
            return response()->json([
                'sukses' => true,
                'pesan' => 'Absen berhasil dicatat.',
                'pukul' => 'Sukses tercatat pukul : ' . $jam_masuk,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'sukses' => false,
                'pesan' => 'Gagal melakukan absen ',

            ], 500);
        }
    }




    public function data_absen_per_user(Request $r)
    {
        $user_id = Auth::user()->id;

        $absen = Absen::where('user_id', $user_id)
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->orderBy('tanggal', 'desc')
            ->get();

        $jumlah = Absen::where('user_id', $user_id)
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->where('jam_keluar', '!=', null)
            ->where('status', 'hadir')
            ->count();

        $a = AbsenResource::collection($absen);


        $cekIzin = Izin::where('user_id', '=', $user_id)
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->get()
            ->makeHidden(['updated_at', 'user_id', 'cabang_id']);

        return response()->json([
            'sukses' => true,
            'data' => $a,
            'jumlah_hadir' => $jumlah,
            'izin' => $cekIzin,
        ], 202);
    }

    public function photo_absen(Request $r, $id)
    {

        $absen = Absen::select('foto_masuk', 'foto_keluar', 'tanggal')
            ->findOrFail($id);
        $bulan = date('m', strtotime($absen->tanggal));
        $tahun = date('Y', strtotime($absen->tanggal));
        $tanggal = date('d', strtotime($absen->tanggal));
        $basePath = 'files2/absen/' . $tahun . '/' . $bulan . '/' . $tanggal . '/';

        $data = [
            'foto_masuk' => $absen->foto_masuk ? url($basePath . $absen->foto_masuk) : null,
            'foto_keluar' => $absen->foto_keluar ? url($basePath . $absen->foto_keluar) : null,
        ];

        return response()->json([
            'sukses' => true,
            'data' => $data,
        ], 202);
    }




    public function izin(Request $r)
    {
        $this->validate($r, [
            'jenis' => 'required|string',
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date',
            'alasan' => 'required|string',
            'ttd' => 'required|string',
        ]);


        $user_id = Auth::user()->id;
        $cabang = Usercab::where('user_id', $user_id)->first();
        $cabang ? $cabang_id = $cabang->cabang_id : $cabang_id = 1;
        $jenis = strtolower($r->jenis);

        $dt = Carbon::createFromFormat('Y-m-d', $r->tgl_awal);
        if ($dt->lt(Carbon::today())) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Tanggal awal tidak boleh lebih kecil dari hari ini.',
            ], 422);
        }

        $dt = Carbon::createFromFormat('Y-m-d', $r->tgl_akhir);
        if ($dt->lt(Carbon::today())) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Tanggal akhir tidak boleh lebih kecil dari hari ini.',
            ], 422);
        }

        $start = Carbon::createFromFormat('Y-m-d', $r->tgl_awal)->startOfDay();
        $end = Carbon::createFromFormat('Y-m-d', $r->tgl_akhir)->startOfDay();
        $diffInclusive = $start->diffInDays($end) + 1; // +1 untuk inklusif
        if ($diffInclusive > 30) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Periode izin tidak boleh lebih dari 30 hari.',
            ], 422);
        }

        $cekAbsen = Absen::where('user_id', '=', $user_id)
            ->where('tanggal', '=', date('Y-m-d'))
            ->exists();
        if ($cekAbsen) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Gagal... Anda sudah melakukan Absen...'
            ], 422);
        }

        $cekIzin = Izin::where('user_id', '=', $user_id)
            ->where('status_approval', '=', "menunggu")
            ->exists();
        if ($cekIzin) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Gagal... Anda sudah mengajukan izin...'
            ], 422);
        }


        $nama_gambar = config('external.nama_gambar');
        $file_lampiran = md5($nama_gambar . $r->tgl_awal . $user_id) . "surat.jpg";
        $file_ttd = md5($nama_gambar . $r->tgl_awal . $user_id) . "ttd.jpg";
        if (!File::isDirectory(public_path() . '/files2/absen/' . date('Y') . '/' . date('m') . '/' . date('d'))) {
            File::makeDirectory(public_path() . '/files2/absen/' . date('Y') . '/' . date('m') . '/' . date('d'), 0777, true, true);
        }

        isset($r->lampiran) ? $lampiran = $file_lampiran : $lampiran = null;


        DB::beginTransaction();
        try {

            $izin = new Izin();
            $izin->user_id = $user_id;
            $izin->cabang_id =  $cabang_id;
            $izin->tanggal_mulai = $r->tgl_awal;
            $izin->tanggal_selesai = $r->tgl_akhir;
            $izin->jenis = $jenis;
            $izin->alasan = $r->alasan;
            $izin->lampiran = $lampiran;
            $izin->ttd = $file_ttd;
            $izin->save();

            $plainText = base64_decode(str_replace(array('-', '_', ' ', '\n'), array('+', '/', '+', ' '), $r->ttd));
            $ifp = fopen(public_path() . '/files2/absen/' . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $file_ttd, "wb");
            fwrite($ifp,  $plainText);
            fclose($ifp);

            if (isset($r->lampiran)) {
                $plainText = base64_decode(str_replace(array('-', '_', ' ', '\n'), array('+', '/', '+', ' '), $r->lampiran));
                $ifp = fopen(public_path() . '/files2/absen/' . date('Y') . '/' . date('m') . '/' . date('d') . '/' . $file_lampiran, "wb");
                fwrite($ifp,  $plainText);
                fclose($ifp);
            }

            DB::commit();
            return response()->json([
                'sukses' => true,
                'pesan' => 'Permohonan berhasil di ajukan...',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'sukses' => false,
                'pesan' => 'Gagal melakukan pengajuan izin  ' . $e->getMessage(),
            ], 500);
        }
    }
}
