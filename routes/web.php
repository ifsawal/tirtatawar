<?php

use App\Http\Controllers\Master\AlamatController;
use App\Http\Controllers\Role\HakaksesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Role\IzinController;
use App\Http\Controllers\Role\RoleController;
use App\Models\Role\Hakakses;

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
    return view('welcome');
});

Route::get('/role', function () {
    return view('role.role');
});

Auth::routes();

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
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
