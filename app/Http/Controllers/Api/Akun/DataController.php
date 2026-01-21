<?php

namespace App\Http\Controllers\Api\Akun;

use App\Models\Role;
use App\Models\User;


use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Permission\PermissionRegistrar;

class DataController extends Controller
{
    public function akun(Request $r, $cari = null)
    {
        $user = User::with('roles:id,name,guard_name', 'permissions:id,name,guard_name');
        $user->where('email_verified_at', '!=', null);
        $user->where('id', '!=', 1);
        if ($cari != null) {
            $user->where('nama', 'like', '%' . $cari . '%');
        }
        if ($r->role) {
            $user->whereHas('roles', function ($q) use ($r) {
                $q->where('name', $r->role);
            });
        }
        $user = $user->get();
        return response()->json([
            'sukses' => true,
            'data' => $user,
            'data2' => Role::all(['id', 'name', 'guard_name']),
            'data3' => Permission::all('id', 'name', 'guard_name'),
        ], 200);
    }

    public function akun_daftar_ganti_role(Request $r)
    {
        $this->validate($r, [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($r->user_id);
        $role = Role::findById($r->role_id, 'web');

        // assign role
        $user->syncRoles([$role->name]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return response()->json([
            'sukses' => true,
            'pesan' => "Berhasil di daftarkan...",
        ], 201);
    }


    public function akun_daftar_permisi(Request $r)
    {
        $this->validate($r, [
            'user_id' => 'required|exists:users,id',
            'permisi_id' => 'required|exists:permissions,id',
        ]);

        $user = User::find($r->user_id);

        $user->givePermissionTo($r->permisi_id);

        return response()->json([
            'sukses' => true,
            'pesan' => "Berhasil di daftarkan...",
        ], 201);
    }

    public function akun_hapus_permisi(Request $r)
    {
        $this->validate($r, [
            'user_id' => 'required|exists:users,id',
            'permisi_id' => 'required|exists:permissions,id',
        ]);

        $user = User::find($r->user_id);

        $permission = Permission::where('id', $r->permisi_id)->first();

        $user->revokePermissionTo($permission);

        return response()->json([
            'sukses' => true,
            'pesan' => "Berhasil di dihapus...",
        ], 201);
    }

    public function role(Request $request)
    {
        $roles = Role::with([
            'permissions:id,name,guard_name',
            'user:id,nama,email',
        ])->get(['id', 'name', 'guard_name']);
        return response()->json([
            'sukses' => true,
            'data' => $roles,
            'data2' => Permission::all('id', 'name', 'guard_name'),
        ]);
    }


    public function role_tambah_permisi(Request $r)
    {
        $this->validate($r, [
            'role_id' => 'required|exists:roles,id', // tagihan id
            'permisi_id' => 'required|exists:permissions,id',
        ]);

        $role = Role::findById($r->role_id, 'web');
        $permission = Permission::findById($r->permisi_id, 'web');

        $role->givePermissionTo($permission);

        return response()->json([
            'sukses' => true,
            'pesan' => "Sukses menambahkan permisi...",
        ], 201);
    }

    public function role_hapus_permisi(Request $r)
    {
        $this->validate($r, [
            'role_id' => 'required|exists:roles,id', // tagihan id
            'permisi_id' => 'required|exists:permissions,id',
        ]);

        $role = Role::findById($r->role_id, 'web');
        $permission = Permission::findById($r->permisi_id, 'web');

        $role->revokePermissionTo($permission);

        return response()->json([
            'sukses' => true,
            'pesan' => "Sukses menghapus permisi...",
        ], 201);
    }


    public function permisi(Request $request)
    {
        $permissions = Permission::with([
            'roles:id,name,guard_name',
            'user:id,nama,email',
        ])->orderBy('id', 'desc')->get(['id', 'name', 'guard_name']);
        return response()->json([
            'sukses' => true,
            'data' => $permissions,
        ]);
    }

    public function data_user_aktif(Request $request)
    {
        // Ambil semua token aktif beserta user-nya
        $tokens = PersonalAccessToken::with('tokenable')
            ->where('tokenable_type', '=', 'App\\Models\\User') // hanya untuk model User
            ->whereNotNull('token')
            ->where('tokenable_id', '!=', 1) // kecuali user dengan ID 1
            ->when($request->cari, function ($q) use ($request) {
                $q->whereHas('tokenable', function ($u) use ($request) {
                    $u->where('nama', 'like', '%' . $request->cari . '%');
                });
            })
            ->get(['id', 'tokenable_id', 'tokenable_type', 'name', 'last_used_at', 'created_at']);

        // Ambil hanya data user unik
        $users = $tokens->pluck('tokenable')->unique('id')->values();

        return response()->json([
            'sukses' => true,
            'data' => $users,
        ]);
    }
}
