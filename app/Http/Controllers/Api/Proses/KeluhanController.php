<?php

namespace App\Http\Controllers\Api\Proses;

use App\Models\Keluhan\Tim;
use Illuminate\Http\Request;
use App\Models\Keluhan\Proses;
use App\Models\Keluhan\Keluhan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Api\Proses\Keluhan\ListKeluhanReseource;
use App\Models\Keluhan\Keluhanphoto;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class KeluhanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }
    public function tampilphotopengerjaan($tanggal, $photo)
    {
        $tahun = date('Y', strtotime($tanggal));
        $bulan = date('m', strtotime($tanggal));
        $tanggal = date('d', strtotime($tanggal));
        $path = public_path() . '/files2/keluhan/' . $tahun . '/' . $bulan . '/' . $tanggal . '/' . $photo . '.jpg';
        return Response::download($path);
    }

    public function photokeluhan(Request $r)
    {
        $this->validate($r, [
            'id' => 'required', //keluhan id
        ]);

        $keluhan = Keluhan::with('photokeluhan')
            ->where('id', $r->id)->first();

        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data" => $keluhan,

        ], 202);
    }



    public function simpan_poto_pekerjaan(Request $r)
    {
        $this->validate($r, [
            'id' => 'required', //keluhan id
        ]);

        $keluhan = Keluhan::find($r->id);
        if ($keluhan->status == "selesai") {
            return response()->json([
                "sukses" => false,
                "pesan" => "Proses selesai sudah dilakukan...",
                "kode" => 2,
            ], 404);
        }

        DB::beginTransaction();

        try {
            $nama_gambar = config('external.nama_gambar');

            $photo = new Keluhanphoto();
            $photo->keluhan_id = $r->id;
            $photo->photo = md5($nama_gambar . now());
            $photo->tanggal = now();
            $photo->save();

            $tahun = date('Y');
            $bulan = date('m');
            $tanggal = date('d');
            if (!File::isDirectory(public_path() . '/files2/keluhan/' . $tahun . '/' . $bulan . '/' . $tanggal)) {
                File::makeDirectory(public_path() . '/files2/keluhan/' . $tahun . '/' . $bulan . '/' . $tanggal, 0777, true, true);
            }


            $plainText = base64_decode(str_replace(array('-', '_', ' ', '\n'), array('+', '/', '+', ' '), $r->file));

            $ifp = fopen(public_path() . '/files2/keluhan/' . $tahun . '/' . $bulan . '/' . $tanggal . '/' . $photo->photo . '.jpg', "wb");
            fwrite($ifp,  $plainText);
            fclose($ifp);

            DB::commit();

            return response()->json([
                "sukses" => true,
                "pesan" => "Berhasil upload..."
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal upload..."
            ], 404);
        }
    }

    public function pekerjaanselesai(Request $r)
    {
        $this->validate($r, [
            'id' => 'required', //keluhan id
        ]);

        $user_id = Auth::user()->id;

        $tim = Tim::where('keluhan_id', $r->id)
            ->where('user_id', $user_id)->first();

        if (!$tim) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Anda bukan tim yang diturunkan...",
                "kode" => 1,
            ], 404);
        }

        $keluhan = Keluhan::find($r->id);
        if ($keluhan->status == "selesai") {
            return response()->json([
                "sukses" => false,
                "pesan" => "Proses selesai sudah dilakukan...",
                "kode" => 2,
            ], 404);
        }

        $keluhan->status = "selesai";
        $keluhan->save();

        $proses = new Proses();
        $proses->keluhan_id = $r->id;
        $proses->proses = "Selesai";
        $proses->user_id = $user_id;
        $proses->save();


        return response()->json([
            "sukses" => True,
            "pesan" => "Proses pengerjaan selesai",
            "kode" => 2,
        ], 201);
    }
    public function detilkeluhan(Request $r)
    {
        $this->validate($r, [
            'id' => 'required',
        ]);

        $kel = Keluhan::with(
            'pelanggan:id,nama,lat,long,nolama,wiljalan_id,desa_id,wiljalan_id,rute_id',
            'pelanggan.hp_pelanggan:id,nohp,pelanggan_id',
            'pelanggan.photo_rumah:id,pelanggan_id',
            'pelanggan.desa:id,desa,kecamatan_id',
            'pelanggan.desa.kecamatan:id,kecamatan',
            'pelanggan.rute:id,rute',
            'pelanggan.wiljalan:id,jalan',
        )
            ->Where('id',  $r->id)
            ->first();

        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data" => $kel,
        ], 202);
    }

    public function listkeluhan(Request $r)
    {
        $key = "status";
        $value = "proses";

        $key2 = "status";
        $value2 = NULL;



        if (isset($r->berdasarkan) && $r->berdasarkan == "selesai") {
            $value = $r->berdasarkan;
            $value2 = $r->berdasarkan;
        }

        if (isset($r->berdasarkan) && $r->berdasarkan == "wiljalan_id" or $r->berdasarkan == "desa_id") {
            $keyrelationpelanggan = $r->berdasarkan;
            $valuerelationpelanggan = $r->id;
            $operator = "=";
            $kel = ListKeluhanReseource::collection(Keluhan::with('pelanggan:id,nama,wiljalan_id,desa_id')
                ->whereRelation('pelanggan', $keyrelationpelanggan, $operator, $valuerelationpelanggan)
                // ->Where($key,  $value)
                // ->Where($key2,  $value2)
                ->orderBy('id', 'desc')
                ->limit(50)
                ->get());
        } else {

            $kel = ListKeluhanReseource::collection(Keluhan::with('pelanggan:id,nama,wiljalan_id,desa_id')
                ->Where($key,  $value)
                ->orWhere($key2,  $value2)
                ->orderBy('id', 'desc')
                ->limit(50)
                ->get());
        }
        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data" => $kel,
        ], 202);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function simpan_petugas(Request $r)
    {
        $this->validate($r, [
            'keluhan_id' => 'required',
            'user_id' => 'required',
            'proses' => 'required',
            'jabatan' => 'required',
        ]);
        $user_id = Auth::user()->id;

        $keluhan = Keluhan::find($r->keluhan_id);
        if ($keluhan->status == "selesai") {
            return response()->json([
                "sukses" => false,
                "pesan" => "Proses selesai sudah dilakukan...",
                "kode" => 2,
            ], 404);
        }

        $pro = Proses::where('keluhan_id', $r->keluhan_id)->first();


        DB::beginTransaction();
        try {
            if (!$pro) {
                $proses = new Proses();
                $proses->keluhan_id = $r->keluhan_id;
                $proses->user_id = $user_id;
                $proses->proses = $r->proses;
                $proses->save();
            }

            $tim = new Tim();
            $tim->keluhan_id = $r->keluhan_id;
            $tim->user_id = $r->user_id;
            $tim->status = $r->jabatan;
            $tim->save();

            DB::commit();

            return response()->json([
                "sukses" => true,
                "pesan" => "Berhasil tersimpan..."
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal sistem...",
                "kode" => 1,
            ], 404);
        }
    }

    /**
     * Store a newly 
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
