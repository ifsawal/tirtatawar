<?php

use Illuminate\Http\Request;
use App\Models\Master\Pelanggan;
use App\Models\Master\GolPenetapan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\AuthMobileController;
use App\Http\Controllers\Api\Master\DesaController;
use App\Http\Controllers\Api\Master\UserController;
use App\Http\Controllers\Api\Master\PelangganController;
use App\Http\Controllers\Api\Master\PencatatanController;
use App\Http\Controllers\Api\Master\PhotoRumahController;
use App\Http\Controllers\Api\Data\DataPelangganController;
use App\Http\Controllers\Api\Laporan\LaporanBayarController;
use App\Http\Controllers\Api\Master\HpPelangganController;
use App\Http\Controllers\Api\Master\GolPenetapanController;
use App\Http\Controllers\Api\Master\PhotoCatatanController;
use App\Http\Controllers\Api\Master\SetoranController;
use App\Http\Controllers\Api\Master\TagihanController;
use App\Http\Controllers\Api\Proses\BayarController;
use App\Http\Controllers\Api\Proses\NotifikasiFcmController;

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



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logoutmobile', [AuthMobileController::class, 'logoutmobile']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::post('/user/ganti_p', [UserController::class, 'ganti_p']);
    Route::resource('/user', UserController::class);

    Route::get('/potorumahpelanggan/{id}', [PhotoRumahController::class, 'potorumahpelanggan']);
    Route::get('/potoc/{id}', [PhotoCatatanController::class, 'potocatatan']);


    Route::post('/datameteran', [PencatatanController::class, 'index']);
    Route::post('/catat', [PencatatanController::class, 'store']);

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
    Route::post('/bayar', [BayarController::class, 'store']);
    Route::post('/batalbayar', [BayarController::class, 'destroy']);

    Route::post('/laporanbayar', [LaporanBayarController::class, 'index']);
    Route::post('/laporanpenerimaan', [LaporanBayarController::class, 'laporanpenerimaan']);
    Route::post('/laporanditerima', [LaporanBayarController::class, 'laporanditerima']);
    Route::post('/simpanpenyerahan', [SetoranController::class, 'rubah']);




    Route::post('/kirimnotifikasi', [NotifikasiFcmController::class, 'notif']);
});


Route::get('/tampilphoto/{folder}/{nama}', [PhotoRumahController::class, 'tampilphoto']);
Route::get('/tampilphotoc/{tahun}/{bulan}/{photo}', [PhotoCatatanController::class, 'tampilphotoc']);
