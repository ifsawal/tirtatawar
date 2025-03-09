<?php

namespace App\Http\Controllers\Api\Master;

use Carbon\Carbon;
use App\Models\User;
use App\Fungsi\Respon;
use Illuminate\Http\Request;
use App\Models\Master\Tagihan;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\Master\GolPenetapan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use DragonCode\Support\Facades\Helpers\Arr;

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
        $user->hasDirectPermission('pencatatan manual') ? $akses = 1 : $akses = 0;

        $pelanggan = Pelanggan::where('id', '=', $request->pelanggan_id)->first(['nama', 'penetapan']);
        if (!$pelanggan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pelanggan tidak ditemukan...",
            ], 404);
        }

        $penetapan = "";
        if ($pelanggan->penetapan == "1") {
            $penetapan = GolPenetapan::where('pelanggan_id', $request->pelanggan_id)->where('aktif', 'Y')->first();
        }

        $pencatatan = Pencatatan::with('user:id,nama', 'user_perubahan:id,nama')
            ->where('pelanggan_id', '=', $request->pelanggan_id)
            ->select(['id', 'awal', 'akhir', 'pemakaian', 'bulan', 'tahun', 'user_id', 'user_id_perubahan', "created_at as waktu"])
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
            "penetapan" => $penetapan,
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
            // ->where('manual', 1)
            ->select(['id', 'awal', 'akhir', 'pemakaian', 'bulan', 'tahun', 'user_id', 'user_id_perubahan', 'manual', 'kunci_edit', 'ket'])
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
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function hitung($awal, $akhir)
    {
        $pemakaian = $akhir - $awal;
        //jika minus
        if ($pemakaian < 0) {
            $panjang = strlen($awal);
            if ($panjang <= 3) {  //JIKA HASIL DIIBAWAH 3 DIGIT maka batalkan
                return -1;
            }

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
                if ($pemakaian == 0) {
                    $jumlah = $jumlah + 0;
                    break;
                } else if ($pemakaian > $detil->awal_meteran && $pemakaian <= $detil->akhir_meteran && $detil->akhir_meteran <> 0) {
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
        $tagihan->total_nodenda = $tagihan->total;
        $tagihan->save();
    }


    //BELUM DI PAKE
    public static function simpanTagihanStatic($pencatatan_id2, $pelanggan_id2, $pemakaian2)
    {
        self::simpanTagihan($pencatatan_id2, $pelanggan_id2,  $pemakaian2);
    }






    public function cek_tanggal_input($bulan, $tahun, $user_id, $edit = false)
    {
        $input = Carbon::parse($tahun . "-" . $bulan . "-1")->format("Y-m");
        $sekarang = Carbon::now();
        $tambahbulan = $sekarang->addMonth()->format('Y-m'); //tambah 1 bulan ke depan dari sekarang


        if (date('Y-m') == $input && date('d') <= 20) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Pencatatan meteraan bulan " . $input . " belum dibuka...",
                "kode" => 2,
            ], 404);
        }

        if ($input >= $tambahbulan) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Meteran bulan depan tidak dapat di isi....",
                "kode" => 2,
            ], 404);
        }


        //seting yang harus di buat
        // 1. bulan dan tahun untuk super user //1 kolom, isi array tahun-bulan,  dan array akun
        // 2. buka input sampai tanggal tertentu // tahun-bulan, tanggal di buka
        // 3. input 1 bulan untuk 1 akun  //tahun-bulan, id

        if (($input == "2024-03" or
            $input == "2024-01" or
            $input == "2024-02" or
            $input == "2024-04" or
            $input == "2024-05" or
            $input == "2024-06" or
            $input == "2024-07" or
            $input == "2024-08" or
            $input == "2024-09" or
            $input == "2024-10" or
            $input == "2024-11" or
            $input == "2024-12" or
            $input == "2025-01" or
            $input == "2025-02" or
            $input == "2023-12" or
            $input == "2023-11") and ($user_id == 1 or $user_id == 26)) {
        } else //HAPUS NANTIK 2 baris ini

            // if ($input == "2025-02" && $edit==true) {  //buka input semua orang
            // } else //HAPUS NANTIK 2 baris ini

            // if ($input == "2025-02" && $edit==true && $user_id==11) {   //buka input 1 orang 20
            // } else //HAPUS NANTIK 2 baris ini

            if ($input < Carbon::now()->format('Y-m')) {  //FITUR METERAN SEBELUMNYA TIDAK BOLEH DIISI
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Meteran bulan lalu tidak dapat diisi lagi...",
                    "kode" => 2,
                ], 404);
            }
        return "Ok";
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
        if ($pemakaian < 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Sepertinya input meteran terbalik...",
                "kode" => 2,
            ], 404);
        } else if ($pemakaian > 50)  //JIKA  TERLALU BESAR PEMAKAIAN
        {
            if (!isset($r->pemakaian_besar)) {
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Total pemakaian : " . $pemakaian . " m3<br> Pemakaian diatas 50 m3, Contreng persetujuan...",
                    "kode" => 4, //harus conteng pemakaian besar
                ], 404);
            }
        }


        $input_dan_edit = false;
        if ($this->cek_tanggal_input($r->bulan, $r->tahun, $user_id, true) == "Ok") {
            $input_dan_edit = true;
        } else {
            $input_dan_edit = false;
            return $this->cek_tanggal_input($r->bulan, $r->tahun, $user_id, true);
        }


        $kurangbulan = Carbon::parse($r->tahun . "-" . $r->bulan . "-1")->subMonthsNoOverflow()->format('m');  //kurangi 1 bulan
        $kurangtahun = Carbon::parse($r->tahun . "-" . $r->bulan . "-1")->subMonthsNoOverflow()->format('Y');  //kurangi tahun berdasarkan bulan

        $cek_bln_lalu = Pencatatan::where('pelanggan_id', '=', $r->pelanggan_id)
            ->where('bulan', $kurangbulan)
            ->where('tahun', $kurangtahun)
            ->first();

        $cek = Pencatatan::with('tagihan:id,status_bayar,pencatatan_id')
            ->where('pelanggan_id', '=', $r->pelanggan_id)
            ->where('bulan', '=', $r->bulan)
            ->where('tahun', '=', $r->tahun)
            ->first();

        $ket = NULL;
        if ($cek_bln_lalu) {
            if ($cek_bln_lalu->akhir == $r->awal) {
                $ket = NULL;
            } else $ket = "Awal salah"; //artinya tidak sama
        } else $ket = NULL;


        if ($cek) {

            //di aktifkan fungsian ini. bila hanya untuk EDIT
            // if ($input_dan_edit && (date("m") !== $r->bulan)) {
            //     return response()->json([
            //         "sukses" => false,
            //         "pesan" => "Meteran sudah di input, dan update data tidak di izinkan karena lewat batas waktu update...",
            //         "kode" => 0,
            //     ], 404);
            // }


            if (isset($cek->tagihan->status_bayar) && $cek->tagihan->status_bayar == "Y") {
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Meteran ini tidak dapat di rubah, karena sudah di bayar...",
                    "kode" => 0,
                ], 404);
            }

            if ($cek->kunci_edit === 1) {
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Meteran ini tidak dapat di rubah, karena sudah pernah melakukan pembayaran...",
                    "kode" => 0,
                ], 404);
            }

            if ($cek->manual === NULL) {
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Input otomatis, tidak dapat dirubah manual...",
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
                $cek->ket = $ket; //
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
            $pencatatan->ket = $ket; //
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

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //catat manual banyak
    public function simpan_data_banyak(Request $r)
    {
        $this->validate($r, [
            'bulan' => 'required',
            'tahun' => 'required',
            'meteran' => 'required',
        ]);
        $user_id = Auth::user()->id;



        if ($this->cek_tanggal_input($r->bulan, $r->tahun, $user_id) == "Ok") {
        } else {
            return $this->cek_tanggal_input($r->bulan, $r->tahun, $user_id);
        }



        $bukakurung = str_replace(array('('), "[", $r->meteran);
        $tutupkurung = str_replace(array(')'), "]", $bukakurung);
        $koma = str_replace(array(' '), ",", $tutupkurung);
        if (!json_decode($koma)) {
            return Respon::respon2("Format data salah...");
        }
        $j = json_decode($koma);


        Log::info($user_id . " Input Manual Kolektif " .  $koma);  ///catat log input kolektif banyak

        $urut = collect($j)->sortBy('nopel');
        $nopel = array();
        foreach ($urut as $u) {
            $nopel[] = $u->nopel;
        }
        // return $nopel;


        $pelanggan = Pelanggan::whereIn('id', $nopel)
            ->orderBy('id')
            ->get();
        if (count($pelanggan) !== count($nopel)) {
            return Respon::respon2("Mohon di cek, ada nomor pelanggan yang salah");
        }

        $bulan_sebelumnya = Carbon::parse($r->tahun . "-" . $r->bulan . "-1")->subMonthsNoOverflow()->format('n');
        $kurangtahun = Carbon::parse($r->tahun . "-" . $r->bulan . "-1")->subMonthsNoOverflow()->format('Y');  //kurangi tahun berdasarkan bulan


        $sebelumnya = Pencatatan::whereIn('pelanggan_id', $nopel)
            ->where('bulan', $bulan_sebelumnya)
            ->where('tahun', $kurangtahun)
            ->orderBy('pelanggan_id')
            ->get();

        if (count($sebelumnya) !== count($nopel)) {
            return Respon::respon2("Gagal, ada data catatan lalu yang tidak ditemukan...");
        }

        $pel = array();
        $status = "";
        $i = 0;
        foreach ($urut as $p) {
            $pakai = $p->meteran - $sebelumnya[$i]['akhir'];

            if ($pakai < 0) {
                $status = "Gagal, karena minus";
            } else if ($pakai > 60) {
                $status = "Gagal... di atas 60m3";
            } else {

                DB::beginTransaction();
                try {
                    $pencatatan =  new Pencatatan();
                    $pencatatan->awal = $sebelumnya[$i]['akhir']; //
                    $pencatatan->akhir = $p->meteran; //
                    $pencatatan->pemakaian = $pakai; //
                    $pencatatan->bulan = $r->bulan; //
                    $pencatatan->tahun = $r->tahun; //
                    $pencatatan->pelanggan_id = $p->nopel;
                    $pencatatan->user_id = $user_id; //
                    $pencatatan->manual = 1; //
                    $pencatatan->ket = NULL; //
                    $pencatatan->save();

                    $this->simpanTagihan($pencatatan->id, $pencatatan->pelanggan_id, $pakai);
                    DB::commit();

                    $status = "Sukses";
                } catch (\Exception $e) {
                    $status = "Gagal, error data";
                }
            }

            $pel[] = [
                "nopel" =>  $p->nopel,
                "meteran" => $p->meteran,
                "nopel_database" => $pelanggan[$i]['id'],
                "meteran_lalu" => $sebelumnya[$i]['akhir'],
                "nama" =>  $pelanggan[$i]['nama'],
                "nopel_di_pencatatan" =>  $sebelumnya[$i]['pelanggan_id'],
                "pemakaian" => $pakai,
                "status" => $status,
            ];
            $i++;
        }

        return Respon::respon($pel);
    }






    public function ambil_data_belum_tercatat(Request $r)
    {

        $user = Auth::user();
        $catat = Pelanggan::query(); //cari pelanggan yang sudah tercatat
        $catat->select(
            'pelanggans.id',
        );
        $catat->join('pencatatans', 'pencatatans.pelanggan_id', '=', 'pelanggans.id');
        $catat->where('pelanggans.user_id_petugas', '=', $user->id);
        $catat->Where('pencatatans.tahun', '=', $r->tahun);
        $catat->Where('pencatatans.bulan', '=', $r->bulan);




        $pel = Pelanggan::query();
        $pel->select(
            'pelanggans.id',
            'pelanggans.nama',
            'wiljalans.jalan',
        );

        $pel->join('wiljalans', 'wiljalans.id', '=', 'pelanggans.wiljalan_id');
        $pel->whereNotIn('pelanggans.id', $catat->get());
        $pel->where('pelanggans.user_id_petugas', '=', $user->id);
        isset($r->wiljalan_id) ? $pel->where('pelanggans.wiljalan_id', '=', $r->wiljalan_id) : '';
        $pel->limit(20);
        // return$pel->get();








        $bulan_sebelumnya = Carbon::parse($r->tahun . "-" . $r->bulan . "-1")->subMonthsNoOverflow()->format('n');
        $kurangtahun = Carbon::parse($r->tahun . "-" . $r->bulan . "-1")->subMonthsNoOverflow()->format('Y');  //kurangi tahun berdasarkan bulan

        if (count($pel->get()) === 0) {

            $pel = Pelanggan::query();
            $pel->select(
                'pelanggans.id',
                'pelanggans.nama',
                'wiljalans.jalan',
            );
            $pel->join('user_wiljalans', 'user_wiljalans.wiljalan_id', '=', 'pelanggans.wiljalan_id');
            $pel->join('wiljalans', 'wiljalans.id', '=', 'user_wiljalans.wiljalan_id');
            $pel->whereNotIn('pelanggans.id', $catat->get());
            $pel->where('user_wiljalans.user_id', '=', $user->id);
            isset($r->wiljalan_id) ? $pel->where('pelanggans.wiljalan_id', '=', $r->wiljalan_id) : '';
            $pel->limit(20);

            if (count($pel->get()) === 0) {
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Data tidak ditemukan...",
                ], 404);
            }
        }





        $hasil = $pel->get()->map(function ($da) use ($bulan_sebelumnya, $kurangtahun) {
            $catatansebelumnya = Pencatatan::where('pelanggan_id', $da->id)
                ->select('id', 'awal', 'akhir', 'pemakaian')
                ->where('bulan', $bulan_sebelumnya)
                ->where('tahun', $kurangtahun)
                ->first();
            return [
                "id" => $da->id,
                "nama" => $da->nama,
                "wiljalan" => $da->jalan,
                "catatan" => $catatansebelumnya,
            ];
        });

        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data" => $hasil
        ], 202);
    }



    //simpan catatan rutin otomatis
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function store(Request $request)
    {


        $this->validate($request, [
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
            ->first(['akhir', 'created_at', 'awal']);


        //jika input pertama kali
        if (!$sebelumnya) {
            if (isset($request->sama_dengam_bulan_lalu)) {  //JIKA AKHIR METERAN TIDAK DI ISI
                return response()->json([
                    "sukses" => false,
                    "pesan" => "sebelumnya tidak ada meteran...",
                    "kode" => 0,
                ], 404);
            }

            if (isset($request->awal)) {
                $meteranSebelumnya = $request->awal;
            } else {
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Meteran awal belum diisi",
                    "kode" => 0,
                ], 404);
            }
        } else {
            $meteranSebelumnya = $sebelumnya->akhir;
        }

        if (isset($request->tanggal)) $waktu = Carbon::create($request->tanggal);
        else $waktu = Carbon::now();
        $bulan = $waktu->month;  //BERUBAH JIKA MUNDUR
        $tahun = $waktu->year;
        // $bulan = 2;  //manual  ex. 2 (tanpa 0)
        // $tahun = 2024;

        $cek = Pencatatan::with('tagihan:id,status_bayar,pencatatan_id') //PASTIKAN APAKAH UPDATE ATAU DAFTAR BARU
            ->where('pelanggan_id', '=', $request->pelanggan_id)
            ->where('bulan', '=', $bulan)
            ->where('tahun', '=', $tahun)
            ->first();

        //TULISAN DENGAM (BUKAN DENGAN)
        if (isset($request->sama_dengam_bulan_lalu)) {  //JIKA SAMA DENGAN BULAN LALU ANGKA METERAN
            if ($cek) {
                $request->akhir = $sebelumnya->awal;
            } else {
                $request->akhir = $sebelumnya->akhir;
            }
        } else {
            $this->validate($request, [
                'akhir' => 'required|integer',
            ]);
        }


        $nama_gambar = config('external.nama_gambar');
        $file = md5($nama_gambar . $bulan . $tahun . $request->pelanggan_id) . ".jpg";

        if (!File::isDirectory(public_path() . '/files2/pencatatan/' . $tahun . '/' . $bulan)) {
            File::makeDirectory(public_path() . '/files2/pencatatan/' . $tahun . '/' . $bulan, 0777, true, true);
        }


        //update
        if ($cek) {

            if (isset($cek->tagihan->status_bayar) && $cek->tagihan->status_bayar == "Y") {
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Meteran ini tidak dapat di rubah, karena sudah di bayar...",
                    "kode" => 0,
                ], 204);
            }

            if ($this->hitung($cek->awal, $request->akhir) < 0) {   //UNTUK NGECEK INPUTAN DI BAWAH INI
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Sepertinya input meteran terbalik...",
                    "kode" => 1,
                ], 404);
            } else if ($this->hitung($cek->awal, $request->akhir) > 50)  //JIKA  TERLALU BESAR PEMAKAIAN
            {
                if (!isset($request->pemakaian_besar)) {
                    return response()->json([
                        "sukses" => false,
                        "pesan" => "Total pemakaian : " . $this->hitung($cek->awal, $request->akhir) . " m3<br> Pemakaian diatas 50 m3, Contreng persetujuan...",
                        "kode" => 4,  //harus conteng pemakaian besar
                    ], 404);
                }
            }


            DB::beginTransaction();
            try {
                $cek->akhir = $request->akhir; //
                $cek->pemakaian = $this->hitung($cek->awal, $request->akhir); //
                $cek->photo = $file;
                $cek->manual = NULL;
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
                    "pesan" => "Gagal merubah data... $e",
                ], 404);
            }
            exit;
        }


        $pemakaian = $this->hitung($meteranSebelumnya, $request->akhir);
        if ($pemakaian < 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Sepertinya input meteran terbalik...",
                "kode" => 2,
            ], 404);
        } else if ($pemakaian > 50)  //JIKA  TERLALU BESAR PEMAKAIAN
        {
            if (!isset($request->pemakaian_besar)) {
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Total pemakaian : " . $pemakaian . " m3<br> Pemakaian diatas 50 m3, Contreng persetujuan...",
                    "kode" => 4, //harus conteng pemakaian besar
                ], 404);
            }
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
                "pesan" => "Sukses tercatat...",
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
    public function izinkan_update(Request $r)
    {
        $pencatatan = Pencatatan::findOrFail($r->id);
        $pencatatan->kunci_edit = NULL;
        $pencatatan->save();
        return response()->json([

            "sukses" => true,
            "pesan" => "Sukses... Pencatatan sudah dapat diedit...",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function catat_keterangan(Request $r)
    {
        $this->validate($r, [
            'id' => 'required',  //id pencatatan
            'ket' => 'required',
        ]);

        $pencatatan = Pencatatan::findOrFail($r->id);
        $pencatatan->ket = $r->ket;
        $pencatatan->save();
        return response()->json([
            "sukses" => true,
            "pesan" => "Sukses... Pencatatan berhasil dibuat...",
        ], 201);
    }
}
