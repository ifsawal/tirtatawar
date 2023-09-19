<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Models\Master\Pelanggan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Pelanggan\Login\PelangganResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class AuthMobileController extends Controller
{


    public function loginmobile(Request $request)
    {

        if (!Auth::guard('pelanggan')->attempt($request->only('email', 'password'))) {
            return response()
                ->json(['sukses' => false, 'pesan' => 'Login gagal...'], 401);
        }

        $pelanggan = new PelangganResource(Pelanggan::with(
            'pdam:id,pdam,nama,kabupaten_id',  //,lat,long
            'desa:id,desa',
            'golongan:id,golongan',
            'rute:id,rute',
            'hp_pelanggan:id,nohp,pelanggan_id'
        )
            ->where('email', $request['email'])->firstOrFail());
        $token = $pelanggan->createToken('auth_token', ['pelanggan'])->plainTextToken;

        $token_fcm = "";
        if (isset($request->token_fcm)) $token_fcm = $request->token_fcm;

        $pecah = explode('|', $token);
        $idtoken = $pecah[0];
        if (isset($request->token_fcm)) {
            DB::table('personal_access_tokens')
                ->where('id', '=', $idtoken)
                ->update(['token_fcm' => $token_fcm]);
        }

        $pel_id = DB::table('personal_access_tokens')
            ->select('tokenable_id')
            ->where('id', '=', $idtoken)->first();

        // $pel = Pelanggan::findOrFail($pel_id->tokenable_id);

        return response()
            ->json([
                'sukses' => true,
                'pesan' => "Login Berhasil",
                'token' => $token,
                'data' => $pelanggan,
            ], 201);
    }

    public function logoutmobile()
    {
        // $tok = Auth::user()->currentAccessToken();


        Auth::user()->tokens()->delete();
        return response()
            ->json([
                'sukses' => true,
                'pesan' => "Logout sukses...",

            ], 204);
    }
}
