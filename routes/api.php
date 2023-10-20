<?php

use App\Models\Master\Bank;
use Illuminate\Http\Request;
use App\Models\Keluhan\Keluhan;
use App\Models\Master\Pelanggan;
use App\Models\Master\GolPenetapan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Master\DesaController;
use App\Http\Controllers\Api\Master\UserController;
use App\Http\Controllers\Api\Proses\BayarController;
use App\Http\Controllers\Api\Master\SetoranController;
use App\Http\Controllers\Api\Master\TagihanController;
use App\Http\Controllers\Api\Proses\KeluhanController;
use App\Http\Controllers\Api\Proses\WebhookController;
use App\Http\Controllers\Api\Auth\AuthMobileController;
use App\Http\Controllers\Api\Master\PelangganController;
use App\Http\Controllers\Api\Master\PembayaranController;
use App\Http\Controllers\Api\Master\PencatatanController;
use App\Http\Controllers\Api\Master\PhotoRumahController;
use App\Http\Controllers\Api\Pelanggan\MobBankController;
use App\Http\Controllers\Api\Pengguna\PenggunaController;
use App\Http\Controllers\Api\Data\DataPelangganController;
use App\Http\Controllers\Api\Master\HpPelangganController;
use App\Http\Controllers\Api\Master\GolPenetapanController;
use App\Http\Controllers\Api\Master\PhotoCatatanController;
use App\Http\Controllers\Api\Laporan\LaporanBayarController;
use App\Http\Controllers\Api\Pelanggan\MobTagihanController;
use App\Http\Controllers\Api\Proses\NotifikasiFcmController;
use App\Http\Controllers\Api\Pelanggan\PelangganMobController;
use App\Http\Controllers\Api\Laporan\LaporanBayarBankController;
use App\Http\Controllers\Api\Pelanggan\MobDetilPelangganController;
use App\Http\Controllers\Api\Pelanggan\Login\Keluhan\KeluhansimController;
use App\Http\Controllers\Api\Pelanggan\Login\MobPelangganTagihanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/daftar', [AuthController::class, 'daftar']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/loginmobile', [AuthMobileController::class, 'loginmobile']);
Route::post('/daftarpelanggan', [AuthController::class, 'daftarpelanggan']);






Route::group(['middleware' => ['auth:sanctum', 'abilities:admin']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::post('/user/ganti_p', [UserController::class, 'ganti_p']);
    Route::resource('/user', UserController::class);

    Route::get('/potorumahpelanggan/{id}', [PhotoRumahController::class, 'potorumahpelanggan']);
    Route::get('/potoc/{id}', [PhotoCatatanController::class, 'potocatatan']);



    Route::post('/datameteran', [PencatatanController::class, 'index']);
    Route::post('/datameteranmanual', [PencatatanController::class, 'datameteranmanual']);
    Route::post('/catat', [PencatatanController::class, 'store']);
    Route::post('/catatmanual', [PencatatanController::class, 'catat_manual']);

    Route::post('/delete/gambar/rumah', [PelangganController::class, 'deletegambarrumah']);
    Route::post('/upload/gambar/rumah/{id}', [PelangganController::class, 'uploadgambarrumah']);
    Route::get('/pelanggan/belumsetujui', [PelangganController::class, 'belumsetujui']);
    Route::post('/pelanggan/updatelokasi', [PelangganController::class, 'updatelokasi']);
    Route::post('/pelanggan/cari', [PelangganController::class, 'cari']);
    Route::post('/pelanggan/carisatu', [PelangganController::class, 'carisatu']);
    Route::get('/pelangganhistoriaktif/{id}', [PelangganController::class, 'pelangganhistoriaktif']);
    Route::post('/pelangganhapus', [PelangganController::class, 'destroy']);
    Route::post('/pelangganaktif', [PelangganController::class, 'aktif']);

    Route::post('/laporan_survei', [DataPelangganController::class, 'index']);

    Route::post('/penetapan', [GolPenetapanController::class, 'store']);
    Route::post('/cekpenetapan', [GolPenetapanController::class, 'index']);
    Route::post('/nonaktifpenetapan', [GolPenetapanController::class, 'destroy']);

    Route::post('/setujui', [PelangganController::class, 'setujui']);
    Route::resource('/pelanggan', PelangganController::class);
    Route::resource('/hp_pelanggan', HpPelangganController::class);

    Route::get('/desa_di_kec/{id}', [DesaController::class, 'desa_di_kecamatan']);


    Route::post('/tagihan', [TagihanController::class, 'index']);
    Route::post('/cektagihandanupdate', [TagihanController::class, 'cektagihandanupdate']);
    Route::post('/infotransfer', [TagihanController::class, 'infotransfer']);

    Route::post('/bayar', [BayarController::class, 'store']);
    Route::post('/batalbayar', [BayarController::class, 'destroy']);
    Route::post('/cetak_penagihan', [BayarController::class, 'cetak_ulang']);
    Route::post('/diskon', [BayarController::class, 'simpan_diskon']);

    Route::post('/laporanbayarbank', [LaporanBayarBankController::class, 'laporanbayarbank']);

    Route::post('/laporanbayar', [LaporanBayarController::class, 'index']);
    Route::post('/laporanpenerimaan', [LaporanBayarController::class, 'laporanpenerimaan']);
    Route::post('/laporanditerima', [LaporanBayarController::class, 'laporanditerima']);
    Route::post('/simpanpenyerahan', [SetoranController::class, 'rubah']);

    Route::post('/listkeluhan', [KeluhanController::class, 'listkeluhan']);
    Route::post('/detilkeluhan', [KeluhanController::class, 'detilkeluhan']);
    Route::post('/simpanpetugas', [KeluhanController::class, 'simpan_petugas']);
    Route::post('/pekerjaanselesai', [KeluhanController::class, 'pekerjaanselesai']);
    Route::post('/simpan_poto_pekerjaan', [KeluhanController::class, 'simpan_poto_pekerjaan']);
    Route::post('/photokeluhan', [KeluhanController::class, 'photokeluhan']);
    Route::post('/simpankeluhan', [KeluhanController::class, 'simpan_keluhan']);

    Route::post('/datauser', [PenggunaController::class, 'datauser']);
    Route::post('/detiluser', [PenggunaController::class, 'detiluser']);
    Route::post('/tambahstatus', [PenggunaController::class, 'tambahstatus']);
    Route::post('/terimakaryawan', [PenggunaController::class, 'terimakaryawan']);
    Route::post('/nonaktifkaryawan', [PenggunaController::class, 'nonaktifkaryawan']);
    Route::post('/aksesinputmanual', [PenggunaController::class, 'aksesinputmanual']);
    Route::post('/hapusaksesinputmanual', [PenggunaController::class, 'hapusaksesinputmanual']);

    Route::post('/jenisbayar', [PembayaranController::class, 'jenisbayar']);
    Route::post('/simpanbayar', [PembayaranController::class, 'simpanbayar']);
    Route::post('/hapusbayar', [PembayaranController::class, 'hapusbayar']);



    Route::post('/kirimnotifikasi', [NotifikasiFcmController::class, 'notif']);
});


Route::get('/tampilphoto/{folder}/{nama}', [PhotoRumahController::class, 'tampilphoto']);
Route::get('/tampilphotoc/{tahun}/{bulan}/{photo}', [PhotoCatatanController::class, 'tampilphotoc']);
Route::get('/tampilphotopengerjaan/{tanggal}/{photo}', [KeluhanController::class, 'tampilphotopengerjaan']);



//untuk pelanggan
Route::group(['middleware' => ['auth:sanctum', 'abilities:pelanggan']], function () {
    Route::post('/logoutmobile', [AuthMobileController::class, 'logoutmobile']);

    Route::post('/golongantarifpelanggan', [MobDetilPelangganController::class, 'golongantarifpelanggan']);
    Route::post('/mobtagihan', [MobPelangganTagihanController::class, 'daftartagihan']);
    Route::post('/daftarmeteran', [MobPelangganTagihanController::class, 'daftarmeteran']);

    Route::post('/keluhansim', [KeluhansimController::class, 'simpan_keluhan']);
    Route::post('/keluhan', [KeluhansimController::class, 'tampil_keluhan']);
});


Route::get('/cek/{nopel}', [PelangganMobController::class, 'cek']);
Route::post('/cektagihan', [MobTagihanController::class, 'cektagihan']);
Route::get('/bank', [MobBankController::class, 'pilihbank']);
Route::get('/cekbank/{bank}', [MobBankController::class, 'cekbank']);

Route::post('/buattagihan', [MobTagihanController::class, 'buattagihan']);

Route::post('/callbacktirtatawar', [WebhookController::class, 'callback']);
