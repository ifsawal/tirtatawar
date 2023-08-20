<?php

namespace App\Http\Controllers\Api\Master;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PencatatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function hitung($awal, $akhir)
    {
        $pemakaian = $akhir - $awal;
        //jika minus
        if ($pemakaian < 0) {
            $panjang = strlen($awal);
            $digit = "";
            for ($i = 0; $i < $panjang; $i++) {
                $digit = $digit . "9";
            }
            $digit = $digit + 1;

            $pemakaian = ($digit - $awal) + $akhir;
        }
        return $pemakaian;
    }

    public function store(Request $request)
    {


        $this->validate($request, [
            'akhir' => 'required|integer',
            'photo' => 'required',
            'pelanggan_id' => 'required',
        ]);

        $user_id = Auth::user()->id;
        $sebelumnya = Pencatatan::where('pelanggan_id', '=', $request->pelanggan_id)->latest('id')->first('akhir', 'created_at');
        //jika input pertama kali
        if (!$sebelumnya) {
            if (isset($request->awal)) {
                $meteranSebelumnya = $request->awal;
            } else {
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Meteran awal tidak ditemukan",
                    "kode" => 0,
                ], 404);
            }
        } else {
            $meteranSebelumnya = $sebelumnya->akhir;
        }

        $pemakaian = $this->hitung($meteranSebelumnya, $request->akhir);


        if (isset($request->tanggal)) $waktu = Carbon::create($request->tanggal);
        else $waktu = Carbon::now();

        $bulan = $waktu->month;
        $tahun = $waktu->year;
        $file = md5("$1Ap*9%" . $bulan . $tahun) . ".jpg";

        if (!File::isDirectory(public_path() . '/files2/pencatatan/' . $tahun . '/' . $bulan)) {
            File::makeDirectory(public_path() . '/files2/pencatatan/' . $tahun . '/' . $bulan, 0777, true, true);
        }

        $cek = Pencatatan::where('pelanggan_id', '=', $request->pelanggan_id)
            ->where('bulan', '=', $bulan)
            ->where('tahun', '=', $tahun)
            ->first();

        //update
        if ($cek) {
            DB::beginTransaction();
            try {
                $cek->akhir = $request->akhir; //
                $cek->pemakaian = $this->hitung($cek->awal, $request->akhir); //
                $cek->photo = $file;
                $cek->user_id_perubahan = $user_id; //
                $cek->save();

                $plainText = base64_decode(str_replace(array('-', '_', ' ', '\n'), array('+', '/', '+', ' '), $request->photo));
                $ifp = fopen(public_path() . '/files2/pencatatan/' . $tahun . '/' . $bulan . '/' . $file, "wb");
                fwrite($ifp,  $plainText);
                fclose($ifp);

                DB::commit();

                return response()->json([
                    "sukses" => true,
                    "pesan" => "Berhasil merubah angka meteran...",
                    "data" => $cek->setVisible(['awal', 'akhir', 'pemakaian', 'bulan', 'tahun']),
                ], 201);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    "sukses" => false,
                    "pesan" => "Gagal merubah data...",
                ], 404);
            }
            exit;
        }
        //baru 
        DB::beginTransaction();
        try {
            $pencatatan =  new Pencatatan();
            $pencatatan->awal = $meteranSebelumnya; //
            $pencatatan->akhir = $request->akhir; //
            $pencatatan->pemakaian = $pemakaian; //
            $pencatatan->bulan = $bulan; //
            $pencatatan->tahun = $tahun; //
            $pencatatan->photo = $file;
            $pencatatan->pelanggan_id = $request->pelanggan_id;
            $pencatatan->user_id = $user_id; //
            $pencatatan->save();

            $plainText = base64_decode(str_replace(array('-', '_', ' ', '\n'), array('+', '/', '+', ' '), $request->photo));
            $ifp = fopen(public_path() . '/files2/pencatatan/' . $tahun . '/' . $bulan . '/' . $file, "wb");
            fwrite($ifp,  $plainText);
            fclose($ifp);

            DB::commit();

            return response()->json([
                "sukses" => true,
                "pesan" => "Berhasil tercatat...",
                "data" => $pencatatan->setVisible(['awal', 'akhir', 'pemakaian', 'bulan', 'tahun']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();



            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal mencatat...",
            ], 404);
        }
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
