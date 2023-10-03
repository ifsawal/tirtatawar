<?php

namespace App\Http\Controllers\Api\Pengguna;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function tambahstatus(Request $r)
    {
        $this->validate($r, [
            'user_id' => 'required',
            'role' => 'required',
        ]);

        $user = User::findOrFail($r->user_id);
        $role = $user->getRoleNames();
        if (isset($role[0])) {
            $user->removeRole($role[0]); //hapus role awal
            $user->assignRole($r->role); //rubah role baru

            return response()->json([
                'sukses' => true,
                'pesan' => "Telah dirubah...",
                'kode' => 2,

            ], 202);
        } else {

            $user->assignRole('petugas'); //tambah role baru
            return response()->json([
                'sukses' => true,
                'pesan' => "Terdaftar Baru...",
                'kode' => 1,
            ], 202);
        }
    }

    public function detiluser(Request $r)
    {
        $user = User::where('id', $r->id)->withTrashed()
            ->first();
        $role = $user->getRoleNames();
        if (isset($role[0])) {

            return response()->json([
                'sukses' => true,
                'pesan' => "Data ditemukan...",
                'role' => $role[0],
            ], 202);
        } else {
            return response()->json([
                'sukses' => true,
                'pesan' => "Tidak terdaftar sebagai karyawan...",
            ], 404);
        }
    }

    public function datauser()
    {
        $user_id = Auth::user()->id;
        $user = User::where('id', $user_id)->first();


        $pengguna = User::with('roles:id,name')
            ->where('pdam_id', $user->pdam_id)->withTrashed()
            ->get();
        $role = Role::all();

        return response()->json([
            'sukses' => true,
            'pesan' => "Data ditemukan...",
            'data' => $pengguna,
            'role' => $role,

        ], 202);


        // $user->getPermissionNames();
        // $role = $user->getRoleNames();
        // $col = collect($user->getAllPermissions());
        // return   $permisi = $col->map(function ($col) {
        //     return collect($col->toArray())
        //         ->only(['id', 'name'])
        //         ->all();
        // });
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
    public function store(Request $request)
    {
        //
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
