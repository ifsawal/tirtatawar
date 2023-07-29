<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Role\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        // return $request['email'];

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()
                ->json(['sukses' => false, 'pesan' => 'Login gagal...'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        if ($user->email_verified_at === NULL) {
            return response()
                ->json([
                    'sukses' => false,
                    'pesan' => "Email belum disetujui...",
                ], 401);
        }

        $role = $user->getRoleNames();
        $col = collect($user->getAllPermissions());
        $permisi = $col->map(function ($col) {
            return collect($col->toArray())
                ->only(['id', 'name'])
                ->all();
        });

        $jumlah_permisi = count($col);
        $user->j_permisi = $jumlah_permisi;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()
            ->json([
                'sukses' => true,
                'pesan' => "Login Berhasil",
                'token' => $token,
                'email' => $user->email,
                'nama' => $user->nama,
                'role' => $role,
                'j_permisi' => $jumlah_permisi,
                'permisi' => $permisi,
            ], 201);
    }






    public function logout()
    {
        // auth()->user()->tokens()->delete();

        Auth::user()->tokens()->delete();
        return response()
            ->json([
                'sukses' => true,
                'pesan' => "Logout sukses...",
            ], 204);
    }

    public function daftar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),);
        }

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'j_permisi' => 0,
        ]);

        return response()->json([
            'sukses' => true,
            'pesan' => "Pendaftaran berhasil...",
        ], 201);
    }
}
