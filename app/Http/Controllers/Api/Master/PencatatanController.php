<?php

namespace App\Http\Controllers\Api\Master;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Master\Tagihan;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\DB;
use App\Models\Master\GolPenetapan;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;

class PencatatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)  //datameteran
    {
        $this->validate($request, [
            'pelanggan_id' => 'required',
        ]);

        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        $user->hasDirectPermission('pencatatan manual') ? $akses = 1 : $akses = 0;;

        $pelanggan = Pelanggan::where('id', '=', $request->pelanggan_id)->first('nama');
        if (!$pelanggan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pelanggan tidak ditemukan...",
            ], 404);
        }

        $pencatatan = Pencatatan::with('user:id,nama', 'user_perubahan:id,nama')
            ->where('pelanggan_id', '=', $request->pelanggan_id)
            ->select('id', 'awal', 'akhir', 'pemakaian', 'bulan', 'tahun', 'user_id', 'user_id_perubahan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->limit(24)
            ->get();

        if (count($pencatatan) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Data tidak ditemukan...",
            ], 404);
        }


        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "pelanggan" => $pelanggan->nama,
            "data" => $pencatatan,
            "manual" => $akses,
        ], 200);
    }

    public function datameteranmanual(Request $request)
    {
        $this->validate($request, [
            'pelanggan_id' => 'required',
        ]);

        $pelanggan = Pelanggan::where('id', '=', $request->pelanggan_id)->first('nama');
        if (!$pelanggan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pelanggan tidak ditemukan...",
                "kode" => 1,
            ], 404);
        }


        $pencatatan = Pencatatan::with('user:id,nama', 'user_perubahan:id,nama')
            ->where('pelanggan_id', '=', $request->pelanggan_id)
            ->where('manual', 1)
            ->select('id', 'awal', 'akhir', 'pemakaian', 'bulan', 'tahun', 'user_id', 'user_id_perubahan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->limit(50)
            ->get();

        if (count($pencatatan) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pencatatan tidak ditemukan...",
                "kode" => 2,
            ], 404);
        }

        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "pelanggan" => $pelanggan->nama,
            "data" => $pencatatan,
        ], 202);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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


    public function simpanTagihan($pencatatan_id, $pelanggan_id, $pemakaian, $aksi = "tambah")
    {

        if ($aksi == "tambah") {
            $tagihan = new Tagihan();
        } else {
            $tagihan = Tagihan::where('pencatatan_id', '=', $pencatatan_id)->first();
        }

        $golongan = Pelanggan::with('golongan:id,golongan,biaya,pajak,denda', 'golongan.goldetil:id,nama,awal_meteran,akhir_meteran,harga,golongan_id')
            ->select('nama', 'golongan_id', 'penetapan')
            ->where('id', '=', $pelanggan_id)
            ->get();

        $a = "";
        $jumlah = 0;
        if ($golongan[0]->penetapan == 1) {  //jika penetapan
            $harga = GolPenetapan::where('pelanggan_id', '=', $pelanggan_id)
                ->where('aktif', '=', 'Y')
                ->first();
            $biaya = $golongan[0]->golongan->biaya;
            $dasar_pajak = $golongan[0]->golongan->pajak;
            $denda_perbulan = $golongan[0]->golongan->denda;

            $jumlah = $harga->harga;
            $jumlah_pajak = $harga->pajak;
        } else if (isset($golongan[0]->golongan->goldetil)) {  //jika sesuai tarif
            foreach ($golongan[0]->golongan->goldetil as $detil) {
                if ($pemakaian > $detil->awal_meteran && $pemakaian <= $detil->akhir_meteran && $detil->akhir_meteran <> 0) {
                    $jumlah = $jumlah + ($detil->harga * ($pemakaian - $detil->awal_meteran));
                    break;
                } else if ($detil->akhir_meteran == 0) {
                    $jumlah = $jumlah + ($detil->harga * ($pemakaian - $detil->awal_meteran));
                    break;
                } else {
                    $jumlah = $jumlah + ($detil->harga * ($detil->akhir_meteran - $detil->awal_meteran));
                }
            }
            $biaya = $golongan[0]->golongan->biaya;
            $dasar_pajak = $golongan[0]->golongan->pajak;
            $denda_perbulan = $golongan[0]->golongan->denda;

            $jumlah = $jumlah;
            $jumlah_pajak = $pemakaian * $dasar_pajak;
        }


        $tagihan->pencatatan_id = $pencatatan_id;
        $tagihan->jumlah = $jumlah;
        $tagihan->denda_perbulan = $denda_perbulan;
        $tagihan->biaya = $biaya;
        $tagihan->pajak = $jumlah_pajak;
        $tagihan->subtotal = $jumlah + $biaya + $jumlah_pajak;
        $tagihan->total = $tagihan->subtotal;
        $tagihan->diskon = 0;
        $tagihan->denda = 0;
        isset($harga->id) ? $tagihan->gol_penetapan_id = $harga->id : ""; //isi id gol_penetapan jika terdaftar sebagai penetapan
        $tagihan->status_bayar = 'N';
        $tagihan->save();
    }



    //simpan catatan manual
    public function catat_manual(Request $r)
    {
        $this->validate($r, [
            'awal' => 'required|integer',
            'akhir' => 'required|integer',
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
            'pelanggan_id' => 'required',
        ]);
        $user_id = Auth::user()->id;

        $pemakaian = $this->hitung($r->awal, $r->akhir);

        $input = Carbon::parse($r->tahun . "-" . $r->bulan . "-1")->format("Y-m");
        $sekarang = Carbon::now();
        $tambahbulan = $sekarang->addMonth()->format('Y-m');

        if ($input >= $tambahbulan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Meteran bulan depan tidak dapat di isi...",
                "kode" => 2,
            ], 404);
        }




        $cek = Pencatatan::with('tagihan:id,status_bayar,pencatatan_id')
            ->where('pelanggan_id', '=', $r->pelanggan_id)
            ->where('bulan', '=', $r->bulan)
            ->where('tahun', '=', $r->tahun)
            ->first();

        if ($cek) {

            if (isset($cek->tagihan->status_bayar) && $cek->tagihan->status_bayar == "Y") {
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Meteran ini tidak dapat di rubah, karena sudah di bayar...",
                    "kode" => 0,
                ], 404);
            }

            if ($cek->manual == NULL) {
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Input otomatis, tidak dapat dirubah...",
                    "kode" => 1,
                ], 404);
            }
            DB::beginTransaction();
            try {
                $cek->awal = $r->awal; //
                $cek->akhir = $r->akhir; //
                $cek->pemakaian = $pemakaian; //
                $cek->bulan = $r->bulan; //
                $cek->tahun = $r->tahun; //
                $cek->pelanggan_id = $r->pelanggan_id;
                $cek->user_id_perubahan = $user_id; //
                $cek->save();

                $this->simpanTagihan($cek->id, $r->pelanggan_id, $cek->pemakaian, 'ubah');
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
        }

        //baru 
        DB::beginTransaction();
        try {
            $pencatatan =  new Pencatatan();
            $pencatatan->awal = $r->awal; //
            $pencatatan->akhir = $r->akhir; //
            $pencatatan->pemakaian = $pemakaian; //
            $pencatatan->bulan = $r->bulan; //
            $pencatatan->tahun = $r->tahun; //
            $pencatatan->pelanggan_id = $r->pelanggan_id;
            $pencatatan->user_id = $user_id; //
            $pencatatan->manual = 1; //
            $pencatatan->save();

            $this->simpanTagihan($pencatatan->id, $pencatatan->pelanggan_id, $pemakaian);
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
                "pesan" => "Gagal mencatat..." . $e,
            ], 404);
        }
    }





    //simpan catatan rutin
    public function store(Request $request)
    {


        $this->validate($request, [
            'akhir' => 'required|integer',
            'photo' => 'required',
            'pelanggan_id' => 'required',
        ]);

        $pelanggan = Pelanggan::where("id", $request->pelanggan_id)->first();
        if (!$pelanggan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pelanggan tidak ditemukan",
            ], 202);
        }

        $user_id = Auth::user()->id;
        $sebelumnya = Pencatatan::where('pelanggan_id', '=', $request->pelanggan_id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->first('akhir', 'created_at');

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
        $nama_gambar = config('external.nama_gambar');
        $file = md5($nama_gambar . $bulan . $tahun . $request->pelanggan_id) . ".jpg";

        if (!File::isDirectory(public_path() . '/files2/pencatatan/' . $tahun . '/' . $bulan)) {
            File::makeDirectory(public_path() . '/files2/pencatatan/' . $tahun . '/' . $bulan, 0777, true, true);
        }

        $cek = Pencatatan::with('tagihan:id,status_bayar,pencatatan_id')
            ->where('pelanggan_id', '=', $request->pelanggan_id)
            ->where('bulan', '=', $bulan)
            ->where('tahun', '=', $tahun)
            ->first();



        //update
        if ($cek) {

            if (isset($cek->tagihan->status_bayar) && $cek->tagihan->status_bayar == "Y") {
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Meteran ini tidak dapat di rubah, karena sudah di bayar...",
                    "kode" => 0,
                ], 204);
            }


            DB::beginTransaction();
            try {
                $cek->akhir = $request->akhir; //
                $cek->pemakaian = $this->hitung($cek->awal, $request->akhir); //
                $cek->photo = $file;
                $cek->user_id_perubahan = $user_id; //
                $cek->save();

                $this->simpanTagihan($cek->id, $request->pelanggan_id, $cek->pemakaian, 'ubah');

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

            $this->simpanTagihan($pencatatan->id, $pencatatan->pelanggan_id, $pemakaian);


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
                "pesan" => "Gagal mencatat..." . $e,
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
