<?php

namespace App\Models\Master;

use App\Models\User;
use App\Models\Master\Desa;
use App\Models\Master\Pdam;
use App\Models\Master\Golongan;
use App\Models\Master\Pencatatan;
use App\Models\Master\PhotoRumah;
use Laravel\Sanctum\HasApiTokens;
// use Illuminate\Database\Eloquent\Model;
use App\Models\Master\HpPelanggan;
use App\Models\Master\GolPenetapan;


use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Model; //Authenticatable;

class Pelanggan extends Model //Authenticatable //Model 
{
    use HasFactory, SoftDeletes;
    use HasApiTokens, Notifiable;

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];


    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function user_perubahan()
    {
        return $this->belongsTo(User::class, 'user_id_perubahan', 'id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'user_id_petugas', 'id');
    }

    public function pdam()
    {
        return $this->belongsTo(Pdam::class, 'pdam_id', 'id');
    }

    public function rute()
    {
        return $this->belongsTo(Rute::class, 'rute_id', 'id');
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'golongan_id', 'id');
    }
    public function wiljalan()
    {
        return $this->belongsTo(Wiljalan::class, 'wiljalan_id', 'id');
    }

    public function hp_pelanggan()
    {
        return $this->hasMany(HpPelanggan::class, 'pelanggan_id', 'id');
    }

    public function gol_penetapan()
    {
        return $this->hasMany(GolPenetapan::class, 'pelanggan_id', 'id');
    }

    public function pencatatan()
    {
        return $this->hasMany(Pencatatan::class, 'pelanggan_id', 'id');
    }
    public function photo_rumah()
    {
        return $this->hasMany(PhotoRumah::class, 'pelanggan_id', 'id');
    }
}
