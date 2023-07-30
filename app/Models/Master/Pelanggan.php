<?php

namespace App\Models\Master;

use App\Models\User;
use App\Models\Master\Desa;
use App\Models\Master\HpPelanggan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelanggan extends Model
{
    use HasFactory, SoftDeletes;


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

    public function hp_pelanggan()
    {
        return $this->hasMany(HpPelanggan::class, 'pelanggan_id', 'id');
    }
}
