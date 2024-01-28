<?php

namespace App\Http\Controllers\Api\Master;

use App\Fungsi\KePelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Master\Pelanggan;
use App\Models\Master\PhotoRumah;
use App\Models\Master\HpPelanggan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\Golongan;
use App\Models\Master\IzinPerubahan;
use App\Models\Master\PelangganHapus;
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
            'user_perubahan:id,nama',
            'petugas:id,nama',
            'wiljalan:id,jalan'
        )->where('id', $id)->where('pdam_id', $user[0]->pdam->id)->withTrashed()->get();


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
            $pelanggan = Pelanggan::with('hp_pelanggan')
                ->whereHas('hp_pelanggan', function ($q) use ($kata) {
                    $q->where('nohp', '=', $kata);
                })
                ->where('pdam_id', $user[0]->pdam->id)
                ->get();
        } else {
            if (isset($request->terhapus)) {  //mencari data yang terhapus
                $pelanggan = Pelanggan::with('hp_pelanggan')
                    ->where($tipe, $operator, $kata)->where('pdam_id', $user[0]->pdam->id)->offset(0)->limit(10)->withTrashed()->get();
            } else if (isset($request->terakhirdaftar)) { //terakhir terdaftar
                $pelanggan = Pelanggan::with('hp_pelanggan')
                    ->where('pdam_id', $user[0]->pdam->id)->offset(0)->limit(20)->orderBy('id', 'desc')->get();
            } else {  //mencari data normal
                $pelanggan = Pelanggan::with('hp_pelanggan')
                    ->where($tipe, $operator, $kata)->where('pdam_id', $user[0]->pdam->id)->offset(0)->limit(10)->get();
            }
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

            'wiljalan_id' => 'required',
            'rute_id' => 'required',
            'petugas_id' => 'required',



        ]);

        $kode = rand(10000, 99999);
        $pelanggan = new Pelanggan;
        $pelanggan->nama = $request->nama;
        $pelanggan->nik = $request->nik;
        $pelanggan->kk = $request->kk;
        $pelanggan->golongan_id = $request->golongan_id;
        $pelanggan->pdam_id = $request->pdam_id;
        $pelanggan->desa_id = $request->desa_id;
        $pelanggan->user_id = $request->user_id;
        $pelanggan->kode = $kode;

        $pelanggan->rute_id = $request->rute_id;
        $pelanggan->wiljalan_id = $request->wiljalan_id;
        $pelanggan->user_id_petugas = $request->petugas_id;

        $pelanggan->deleted_at = Carbon::now();
        $pelanggan->save();

        if (isset($request->nohp)) {
            $nohp = new HpPelanggan;
            $nohp->nohp = $request->nohp;
            $nohp->pelanggan_id = $pelanggan->id;
            $pelanggan_id = $pelanggan->id;
            $nohp->aktif = "Y";
            $nohp->save();

            $this->simpanJumlahNoHp($pelanggan_id); //simpan jumlah hp
        }

        return response()->json([
            'sukses' => true,
            'pesan' => "Pendaftaran berhasil...",
            'id' => $pelanggan->id,
            'kode' => $pelanggan->kode,
        ], 201);
    }


    public static function simpanJumlahNoHp($pelanggan_id)
    {
        $jumlah_hp = HpPelanggan::where('pelanggan_id', '=', $pelanggan_id)->count();
        $pel = Pelanggan::withTrashed()->findOrFail($pelanggan_id);
        $pel->hp = $jumlah_hp;
        $pel->save();
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cl = "App\Models\Master\Pelanggan";
        $pel = $cl::find($id);
        if (!$pel) {
            return response()->json([
                'sukses' => false,
                'pesan' => "Data tidak ditemukan...",
            ], 404);
        }
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

    public function update(Request $request, $id)
    {
    }

    public function update_pel(Request $request)
    {

        $this->validate($request, [
            'id' => 'required',

            'nama' => 'required|min:4',
            'nik' => 'required|min:16',
            'golongan_id' => 'required',
            'rute_id' => 'required',
            'user_id_perubahan' => 'required',
            'wiljalan_id' => 'required',
            'petugas_id' => 'required',

            'golongan_nama' => 'required',
            'wiljalan_nama' => 'required',
            'petugas_nama' => 'required',
            'rute_nama' => 'required',
        ]);



        $sebelumnya = IzinPerubahan::where('id_dirubah', $request->id)
            ->where('status', 0)
            ->first();
        if ($sebelumnya) {
            return response()->json([
                'sukses' => false,
                'pesan' => "Perubahan sebelumnya belum disetujui...",
            ], 404);
        }


        $pelanggan = Pelanggan::with('golongan:id,golongan', 'wiljalan:id,jalan', 'rute:id,rute', 'petugas:id,nama')
            ->where('id', $request->id)
            ->first();
        $value = [
            'nama' => $request->nama,
            'nik' => $request->nik,
            'kk' => $request->kk,
            'golongan_id' => $request->golongan_id,
            'wiljalan_id' => $request->wiljalan_id,
            'rute_id' => $request->rute_id,
            'user_id_petugas' => $request->petugas_id,
        ];

        // $goldasar = Golongan::find($pelanggan->golongan_id);
        // $golfinal = Golongan::find($request->golongan_id);

        $user = Auth::user();
        $izin = new IzinPerubahan();
        $izin->tabel = "pelanggans";
        $izin->fild = json_encode($value);

        $izin->id_dirubah = $request->id;
        $izin->dasar = "kolektif";
        $izin->final = 0;
        $izin->user_id = $user->id;
        $izin->ket = "Perubahan Pelanggan Nopel : {$pelanggan->id}<br>
        <b>Data Lama</b><br>
        {$pelanggan->nama} - {$pelanggan->nik} - {$pelanggan->kk} -  {$pelanggan->golongan->golongan}- {$pelanggan->wiljalan->jalan} - {$pelanggan->rute->rute} - {$pelanggan->petugas->nama}  
        <b>Data Baru<b><br>
        {$request->nama} - {$request->nik} - {$request->kk} -  {$request->golongan_nama}- {$request->wiljalan_nama} - {$request->rute_nama} - {$request->petugas_nama}  
        ";
        $izin->pdam_id = $user->pdam_id;
        $izin->save();

        // $pelanggan->golongan_id = $request->golongan_id;


        // if (isset($request->nohp) && !empty($request->nohp)) {
        //     $nohp = new HpPelanggan;
        //     $nohp->nohp = $request->nohp;
        //     $nohp->pelanggan_id = $pelanggan->id;
        //     $nohp->aktif = "Y";
        //     $nohp->save();

        //     $this->simpanJumlahNoHp($pelanggan->id); //simpan jumlah hp
        // }

        return response()->json([
            'sukses' => true,
            'pesan' => "Perubahan berhasil...",
            'id' => $izin,
        ], 202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $r)
    {
        $this->validate($r, [
            'id' => 'required',
            'ket' => 'required',
        ]);


        $pelanggan = Pelanggan::findOrFail($r->id);


        DB::beginTransaction();

        try {
            $pelanggan_id = $pelanggan->id;
            $pelanggan->delete();

            $hap = new PelangganHapus();
            $hap->pelanggan_id = $pelanggan_id;
            $hap->user_id = Auth::user()->id;
            $hap->tgl_nonaktif = now();
            $hap->status_berlaku = 1;
            $hap->ket = $r->ket;
            $hap->save();

            DB::commit();

            return response()->json([
                "sukses" => true,
                "pesan" => "Berhasil memutuskan..."
            ], 204);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Error dan gagal memutuskan..."
            ], 404);
        }
    }


    public function aktif(Request $r)
    {

        $this->validate($r, [
            'id' => 'required',
        ]);


        $aktif = PelangganHapus::where('id', '=', $r->id)
            ->where('status_berlaku', '=', 1)->first();


        DB::beginTransaction();

        try {
            $pelanggan = Pelanggan::withTrashed()->findOrFail($aktif->pelanggan_id);
            $pelanggan->restore();

            $aktif->user_id_aktifkan = Auth::user()->id;
            $aktif->tgl_aktif = now();
            $aktif->status_berlaku = 0;
            $aktif->save();

            DB::commit();

            return response()->json([
                "sukses" => true,
                "pesan" => "Berhasil Mengaktifkan..."
            ], 202);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Error tidak dapat mengaktifkan..." . $e
            ], 404);
        }
    }

    public function pelangganhistoriaktif($id)
    {
        $data = PelangganHapus::with('user:id,nama', 'user_aktifkan:id,nama')
            ->where('pelanggan_id', '=', $id)
            ->orderBy('id', 'DESC')->get();
        return response()->json([
            "sukses" => true,
            "pesan" => "Ditemukan...",
            'data' => $data
        ], 202);
    }
}
