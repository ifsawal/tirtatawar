<?php

use Illuminate\Http\Request;
use App\Models\Master\Pelanggan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Master\DesaController;
use App\Http\Controllers\Api\Master\UserController;
use App\Http\Controllers\Api\Master\PelangganController;
use App\Http\Controllers\Api\Master\PencatatanController;
use App\Http\Controllers\Api\Master\PhotoRumahController;
use App\Http\Controllers\Api\Data\DataPelangganController;
use App\Http\Controllers\Api\Master\HpPelangganController;

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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/user/ganti_p', [UserController::class, 'ganti_p']);
    Route::resource('/user', UserController::class);

    Route::get('/potorumahpelanggan/{id}', [PhotoRumahController::class, 'potorumahpelanggan']);


    Route::post('/datameteran', [PencatatanController::class, 'index']);
    Route::post('/catat', [PencatatanController::class, 'store']);

    Route::post('/delete/gambar/rumah', [PelangganController::class, 'deletegambarrumah']);
    Route::post('/upload/gambar/rumah/{id}', [PelangganController::class, 'uploadgambarrumah']);
    Route::get('/pelanggan/belumsetujui', [PelangganController::class, 'belumsetujui']);
    Route::post('/pelanggan/updatelokasi', [PelangganController::class, 'updatelokasi']);
    Route::post('/pelanggan/cari', [PelangganController::class, 'cari']);
    Route::post('/pelanggan/carisatu', [PelangganController::class, 'carisatu']);
    Route::post('/laporan_survei', [DataPelangganController::class, 'index']);
    Route::post('/setujui', [PelangganController::class, 'setujui']);
    Route::resource('/pelanggan', PelangganController::class);

    Route::resource('/hp_pelanggan', HpPelangganController::class);

    Route::get('/desa_di_kec/{id}', [DesaController::class, 'desa_di_kecamatan']);
});


Route::get('/tampilphoto/{folder}/{nama}', [PhotoRumahController::class, 'tampilphoto']);
