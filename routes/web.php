<?php

use App\Models\Role\Hakakses;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Role\IzinController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Master\PdamController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\AlamatController;
use App\Http\Controllers\Master\DownloadController;
use App\Http\Controllers\Role\HakaksesController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('singel.perawatan');
});

Route::get('/role', function () {
    return view('role.role');
});

// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {

    Route::get('/role', [RoleController::class, 'index'])->name('role');
    Route::post('/role', [RoleController::class, 'store'])->name('tambah_role');
    Route::get('/role/{id}', [RoleController::class, 'destroy'])->name('hapus_role');
    Route::get('/role_edit/{id}', [RoleController::class, 'edit'])->name('edit_role');
    Route::post('/role_update', [RoleController::class, 'update'])->name('update_role');

    Route::get('/izin', [IzinController::class, 'index'])->name('izin');
    Route::post('/izin', [IzinController::class, 'store'])->name('tambah_izin');
    Route::get('/izin/{id}', [IzinController::class, 'destroy'])->name('hapus_izin');
    Route::get('/izin_edit/{id}', [IzinController::class, 'edit'])->name('edit_izin');
    Route::post('/izin_update', [IzinController::class, 'update'])->name('update_izin');

    Route::get('/alamat', [AlamatController::class, 'index'])->name('alamat');
    Route::get('/daftarizin', [RoleController::class, 'daftarizin'])->name('daftarizin');

    Route::get('/hakakses_role/{id}', [RoleController::class, 'hakakses_role'])->name('hakakses_role');
    Route::post('/tambah_hakakses', [RoleController::class, 'tambah_hakakses'])->name('tambah_hakakses');
    Route::get('/hapus_hakakses/{idpermisi}/{id_role}', [RoleController::class, 'hapus_hakakses_role'])->name('hapus_hakakses');

    Route::get('/hakakses', [HakaksesController::class, 'index'])->name('hakakses');
    Route::get('/detil_hakakses/{id}', [HakaksesController::class, 'detil_hakakses'])->name('detil_hakakses');
    Route::post('/simpan_hakakses', [HakaksesController::class, 'store'])->name('simpan_hakakses');
    Route::get('/hapus_detil_hakakses/{id_user}/{id_role}', [HakaksesController::class, 'destroy'])->name('hapus_detil_hakakses');



    Route::get('/alamat/provinsi', [AlamatController::class, 'index'])->name('alamat');
    Route::post('/simpan_provinsi', [AlamatController::class, 'simpan_provinsi'])->name('simpan_provinsi');
    Route::delete('/hapus_provinsi', [AlamatController::class, 'hapus_provinsi'])->name('hapus_provinsi');

    Route::post('/get_kabupaten', [AlamatController::class, 'get_kabupaten'])->name('get_kabupaten');
    Route::post('/simpan_kabupaten', [AlamatController::class, 'simpan_kabupaten'])->name('simpan_kabupaten');
    Route::delete('/hapus_kabupaten', [AlamatController::class, 'hapus_kabupaten'])->name('hapus_kabupaten');

    Route::post('/get_kecamatan', [AlamatController::class, 'get_kecamatan'])->name('get_kecamatan');
    Route::post('/simpan_kecamatan', [AlamatController::class, 'simpan_kecamatan'])->name('simpan_kecamatan');
    Route::delete('/hapus_kecamatan', [AlamatController::class, 'hapus_kecamatan'])->name('hapus_kecamatan');

    Route::post('/get_desa', [AlamatController::class, 'get_desa'])->name('get_desa');
    Route::post('/simpan_desa', [AlamatController::class, 'simpan_desa'])->name('simpan_desa');
    Route::delete('/hapus_desa', [AlamatController::class, 'hapus_desa'])->name('hapus_desa');
    Route::delete('/hapus_desa', [AlamatController::class, 'hapus_desa'])->name('hapus_desa');

    Route::get('/pdam', [PdamController::class, 'index'])->name('pdam');
    Route::post('/tambah_pdam', [PdamController::class, 'tambah_pdam'])->name('tambah_pdam');
    Route::post('/update_pdam', [PdamController::class, 'update'])->name('update_pdam');
    Route::get('/edit_pdam/{id}', [PdamController::class, 'edit'])->name('edit_pdam');
    Route::get('/hapus_pdam/{id}', [PdamController::class, 'destroy'])->name('hapus_pdam');

    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::post('/tambah_user', [UserController::class, 'store'])->name('tambah_user');
    Route::get('/edit_user/{id}', [UserController::class, 'edit'])->name('edit_user');
    Route::get('/hapus_user/{id}', [UserController::class, 'destroy'])->name('hapus_user');
    Route::post('/update_user', [UserController::class, 'update'])->name('update_user');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//download
Route::get('/download/admintirtatawar', [DownloadController::class, 'downloadadmin']);
Route::get('/download', [DownloadController::class, 'downloadpelanggan']);
Route::get('/download/pelanggan', [DownloadController::class, 'downloadpelangganolehadmin']);
