<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Master\Pelanggan;
use App\Models\Master\PhotoRumah;
use App\Models\Master\HpPelanggan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function belumsetujui()
    {
        $user_id = Auth::user()->id;
        $user = User::with('pdam:id,pdam')->where('id', $user_id)->get();

        $belum = Pelanggan::with(
            'desa',
            'desa.kecamatan:id,kecamatan'
        )
            ->withTrashed()->where('user_id_penyetuju', '=', NULL)->where('pdam_id', $user[0]->pdam->id)->get();


        if (count($belum) == 0) {
            return response()->json([
                'sukses' => false,
                'pesan' => "Data tidak ditemukan...",
            ], 404);
        }

        return response()->json([
            'sukses' => true,
            'pesan' => "Pendaftaran disetujui...",
            'jumlah' => count($belum),
            'data' => $belum,
        ], 200);
    }




    public function setujui(Request $request)
    {
        $user_id = Auth::user()->id;
        $pelanggan = Pelanggan::withTrashed()->findOrFail($request->id);
        $pelanggan->restore();
        $pelanggan->user_id_penyetuju = $user_id;
        $pelanggan->save();

        return response()->json([
            'sukses' => true,
            'pesan' => "Pendaftaran disetujui...",
            'id' => $pelanggan->id,
        ], 202);
    }

    public function carisatu(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::with('pdam:id,pdam')->where('id', $user_id)->get();

        $id = $request->id;
        $pel = Pelanggan::with(
            'user:id,nama',
            'hp_pelanggan',
            'pdam:id,pdam',
            'desa.kecamatan:id,kecamatan',
            'golongan:id,golongan',
            'rute:id,rute',
            'user_perubahan:id,nama'
        )->where('id', $id)->where('pdam_id', $user[0]->pdam->id)->get();

        return response()->json([
            'sukses' => true,
            'pesan' => "Data ditemukan...",
            'data' => $pel
        ], 200);
    }

    public function cari(Request $request)
    {
        $user_id = Auth::user()->id;
        $operator = "=";
        $tipe = $request->tipe;
        $kata = $request->kata;
        ($tipe == "Nomor Pelanggan") ? $tipe = 'id' : '';
        ($tipe == "nama") ? $operator = 'like' : '';
        ($tipe == "nama") ? $kata =  '%' . $kata . '%' : '';

        $user = User::with('pdam:id,pdam')->where('id', $user_id)->get();

        if ($tipe == "nohp") {
            $pelanggan = Pelanggan::with('hp_pelanggan')->whereHas('hp_pelanggan', function ($q) use ($kata) {
                $q->where('nohp', '=', $kata);
            })->where('pdam_id', $user[0]->pdam->id)->get();
        } else {
            $pelanggan = Pelanggan::with('hp_pelanggan')
                ->where($tipe, $operator, $kata)->where('pdam_id', $user[0]->pdam->id)->offset(0)->limit(10)->get();
        }

        $status = "";
        if (count($pelanggan) <> 1 or $pelanggan[0]->lat == "" or $pelanggan[0]->long = "" or !isset($pelanggan[0]->hp_pelanggan[0]->nohp)) {
            $status = "Belum update";
        }
        if (count($pelanggan) > 1) {
            $status = "data 2";
        }

        if (count($pelanggan) <> 0) {
            return response()->json([
                'sukses' => true,
                'pesan' => "Data ditemukan...",
                'status' => $status,
                'jumlah' => count($pelanggan),
                'data' => $pelanggan,
            ], 200);
        } else {
            return response()->json([
                'sukses' => false,
                'pesan' => "Pelanggan tidak ditemukan...",
            ], 404);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**  
     * Store a newly created resource in storage. fdasfdas
     */
    public function deletegambarrumah(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        $user_id = Auth::user()->id;

        $photo = PhotoRumah::findOrFail($request->id);
        if ($photo->delete()) {
            File::delete(public_path() . '/files2/rumah/' . $photo->pelanggan_id . '/' . $photo->id . '.jpg');

            return response()->json([
                "sukses" => true,
                "pesan" => "Sukses menghapus..."
            ], 204);
        }
    }

    public function uploadgambarrumah(Request $request, $id)
    {


        DB::beginTransaction();

        try {
            $photo = new PhotoRumah;
            $photo->pelanggan_id = $request->id;
            $photo->save();

            if (!File::isDirectory(public_path() . '/files2')) {
                File::makeDirectory(public_path() . '/files2', 0777, true, true);
            }
            if (!File::isDirectory(public_path() . '/files2/rumah')) {
                File::makeDirectory(public_path() . '/files2/rumah', 0777, true, true);
            }
            if (!File::isDirectory(public_path() . '/files2/rumah/' . $id)) {
                File::makeDirectory(public_path() . '/files2/rumah/' . $id, 0777, true, true);
            }


            $plainText = base64_decode(str_replace(array('-', '_', ' ', '\n'), array('+', '/', '+', ' '), $request->file));

            $ifp = fopen(public_path() . '/files2/rumah/' . $id . '/' . $photo->id . '.jpg', "wb");
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|min:4',
            'nik' => 'required|min:16',
            'golongan_id' => 'required',
            'pdam_id' => 'required',
            'desa_id' => 'required',
            'user_id' => 'required',
        ]);

        $pelanggan = new Pelanggan;
        $pelanggan->nama = $request->nama;
        $pelanggan->nik = $request->nik;
        $pelanggan->kk = $request->kk;
        $pelanggan->golongan_id = $request->golongan_id;
        $pelanggan->pdam_id = $request->pdam_id;
        $pelanggan->desa_id = $request->desa_id;
        $pelanggan->user_id = $request->user_id;
        if ($pelanggan->rute_id <> "") {
            $pelanggan->rute_id = $request->rute_id;
        }
        $pelanggan->deleted_at = Carbon::now();
        $pelanggan->save();

        if (isset($request->nohp)) {
            $nohp = new HpPelanggan;
            $nohp->nohp = $request->nohp;
            $nohp->pelanggan_id = $pelanggan->id;
            $nohp->aktif = "Y";
            $nohp->save();
        }

        return response()->json([
            'sukses' => true,
            'pesan' => "Pendaftaran berhasil...",
            'id' => $pelanggan->id,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pel = Pelanggan::findOrFail($id);
        return response()->json([
            'sukses' => true,
            'pesan' => "Detemukan...",
            'data' => $pel,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user_id = Auth::user()->id;
        $user = User::with('pdam:id,pdam')->where('id', $user_id)->get();
        return  $pelanggan = Pelanggan::where('id', $id)->where('pdam_id', $user[0]->pdam->id)->offset(0)->limit(10)->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatelokasi(Request $request)
    {

        $this->validate($request, [
            'id' => 'required',
            'lat' => 'required',
            'long' => 'required',
        ]);

        $pelanggan = Pelanggan::findOrFail($request->id);
        $pelanggan->lat = $request->lat;
        $pelanggan->long = $request->long;
        $pelanggan->save();

        return response()->json([
            'sukses' => true,
            'pesan' => "Perubahan berhasil...",
        ], 202);
    }


    public function update(Request $request, string $id)
    {


        $this->validate($request, [
            'nama' => 'required|min:4',
            'nik' => 'required|min:16',
            'golongan_id' => 'required',
            'desa_id' => 'required',
            'rute_id' => 'required',
            'user_id_perubahan' => 'required',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->nama = $request->nama;
        $pelanggan->nik = $request->nik;
        $pelanggan->kk = $request->kk;
        $pelanggan->golongan_id = $request->golongan_id;
        $pelanggan->lat = $request->lat;
        $pelanggan->long = $request->long;
        $pelanggan->desa_id = $request->desa_id;
        $pelanggan->rute_id = $request->rute_id;
        $pelanggan->user_id_perubahan = Auth::user()->id;
        $pelanggan->save();

        if (isset($request->nohp) && !empty($request->nohp)) {
            $nohp = new HpPelanggan;
            $nohp->nohp = $request->nohp;
            $nohp->pelanggan_id = $pelanggan->id;
            $nohp->aktif = "Y";
            $nohp->save();
        }

        return response()->json([
            'sukses' => true,
            'pesan' => "Perubahan berhasil...",
            'id' => $pelanggan->id,
        ], 202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
