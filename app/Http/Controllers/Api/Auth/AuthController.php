<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Role\Role;
use App\Models\Master\Rute;
use Illuminate\Http\Request;
use App\Models\Master\Golongan;
use App\Models\Master\Kabupaten;
use App\Models\Master\Kecamatan;
use App\Http\Controllers\Controller;
use App\Models\Master\Pelanggan;
use App\Models\Master\UserWiljalan;
use App\Models\Master\Wiljalan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (isset($request->versi) && $request->versi >= 2) {
        } else {
            return response()
                ->json([
                    'sukses' => false,
                    'pesan' => "Aplikasi kadaluarsa, silahkan update...",
                ], 401);
        }
        // return $request['email'];
        // return Auth::guard('web')->attempt($request->only('email', 'password'));

        if (!Auth::guard('web')->attempt($request->only('email', 'password'))) {
            return response()
                ->json(['sukses' => false, 'pesan' => 'Login gagal...'], 401);
        }


        $user = User::with('pdam:id,pdam,nama,kabupaten_id')->where('email', $request['email'])->firstOrFail();
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

        $kec = Kecamatan::where('kabupaten_id', $user->pdam->kabupaten_id)->get(['id', 'kecamatan']);
        $golongan = Golongan::where('pdam_id', $user->pdam->id)->get(['id', 'golongan']);
        $wiljalan = Wiljalan::where('pdam_id', $user->pdam->id)->orderBy('jalan')->get(['id', 'jalan']);
        $rute = Rute::where('pdam_id', $user->pdam->id)->get(['id', 'rute']);
        $userwiljalan = UserWiljalan::query();
        $userwiljalan->select('wiljalans.id', 'wiljalans.jalan',);
        $userwiljalan->join('wiljalans', 'wiljalans.id', '=', 'user_wiljalans.wiljalan_id');
        $userwiljalan->where('user_wiljalans.user_id', $user->id);

        $bagi_user = $userwiljalan->get();

        $jumlah_permisi = count($col);
        $user->j_permisi = $jumlah_permisi;
        $user->save();

        $token = $user->createToken('auth_token', ['admin'])->plainTextToken;
        return response()
            ->json([
                'sukses' => true,
                'pesan' => "Login Berhasil",
                'token' => $token,
                'email' => $user->email,
                'nama' => $user->nama,
                'user_id' => $user->id,
                'role' => $role[0],
                'pdam' => $user->pdam->pdam,
                'pdam_id' => $user->pdam->id,
                'nama_pdam' => $user->pdam->nama,
                'j_permisi' => $jumlah_permisi,
                'permisi' => $permisi,
                'kec' => $kec,
                'golongan' => $golongan,
                'rute' => $rute,
                'wiljalan' => $wiljalan,
                'wiljalan_user' => $bagi_user,
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
            'password' => 'required|string|min:4',
            'pdam' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),);
        }

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'j_permisi' => 0,
            'pdam_id' => $request->pdam,
        ]);

        return response()->json([
            'sukses' => true,
            'pesan' => "Pendaftaran berhasil...",
            'data' => $user,
        ], 201);
    }


    public function daftarpelanggan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:pelanggans',
            'password' => 'required|string|min:4',
            'kode' => 'required',
            'nopelanggan' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 205);
        }

        $user = Pelanggan::where('id', $request->nopelanggan)
            ->where('kode', $request->kode)
            ->first();
        if (!$user) {
            return response()->json([
                'sukses' => false,
                'pesan' => "Tidak ditemukan...",
                // 'data' => $user,
            ], 204);
        }

        if ($user->email === NULL) {
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
        }



        return response()->json([
            'sukses' => true,
            'pesan' => "Pendaftaran berhasil...",
            // 'data' => $user,
        ], 201);
    }
}
