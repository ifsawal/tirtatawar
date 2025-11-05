<?php

namespace App\Http\Controllers\Api\Absen;

use App\Helper\Helpers;
use App\Models\Master\Absen;
use Illuminate\Http\Request;
use App\Models\Master\Usercab;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Master\CabangKegiatan;

class KegiatanController extends Controller
{
    public function tampil_kegiatan(Request $r)
    {
        $tanggal_hari_ini = date('Y-m-d');
        $kegiatan = CabangKegiatan::where('tanggal', $tanggal_hari_ini)->get();

        return response()->json([
            'sukses' => true,
            'data' => $kegiatan,
        ], 202);
    }



    public function absen_kegiatan(Request $r)
    {
        $this->validate($r, [
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'photo' => 'required',
            'id' => 'required',
        ]);

        $user_id = Auth::user()->id;

        $cabang = Usercab::where('user_id', $user_id)->first();
        $cabang ? $cabang_id = $cabang->cabang_id : $cabang_id = 1;
        $cabangData = CabangKegiatan::findOrFail($r->id);
        $jarak = Helpers::jarak($r->lat, $r->long, $cabangData->lat, $cabangData->long);
        $dalamRadius = Helpers::dalamRadius($r->lat, $r->long, $cabangData->lat, $cabangData->long, $cabangData->radius);
        if (!$dalamRadius) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Anda berada di luar radius absen. Jarak Anda dari Lokasi kegiatan: ' . number_format($jarak, 2) . ' meter.'
            ], 400);
        }



        // $cabangData = null;
        // $cabangTerdekat = CabangKegiatan::findOrFail($r->id);
        // foreach ($cabangTerdekat as $c) {
        //     $dalamRadius = Helpers::dalamRadius($r->lat, $r->long, $c->lat, $c->long, $c->radius);
        //     if ($dalamRadius) {
        //         $cabangData = $c;
        //         break;
        //     }
        // }
        // if ($cabangData == null) {
        //     return response()->json([
        //         'sukses' => false,
        //         'pesan' => 'Anda berada di luar radius absen.'
        //     ], 400);
        // }
        // $cabang_id = $cabangData->id;




        $tanggal = date('Y-m-d');
        $jam_masuk = date('H:i:s');

        if (strtotime($jam_masuk) < strtotime($cabangData->jam_mulai)) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Waktu absen masuk belum dimulai...',
            ], 400);
        }

        if (strtotime($jam_masuk) > strtotime($cabangData->jam_selesai)) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Waktu absen masuk sudah berakhir...',
            ], 400);
        }


        $cekAbsen = Absen::where('user_id', '=', $user_id)
            ->where('jenis_absen', '=', 'lapangan')
            ->where('tanggal', '=', $tanggal)
            ->where('kegiatan_id', '=', $cabangData->id)
            ->exists();
        if ($cekAbsen) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Anda sudah melakukan Absen...'
            ], 400);
        }


        $nama_gambar = config('external.nama_gambar');
        $file = md5($nama_gambar . $tanggal . $user_id.$cabangData->id."kegiatan") . ".jpg";
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
            $absen->jenis_absen = 'lapangan';
            $absen->kegiatan_id = $cabangData->id;
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
}
