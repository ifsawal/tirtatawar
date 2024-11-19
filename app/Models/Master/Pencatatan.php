<?php

namespace App\Models\Master;

use App\Models\User;
use App\Models\Master\Tagihan;
use App\Models\Master\Pelanggan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pencatatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];


    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user_perubahan()
    {
        return $this->belongsTo(User::class, 'user_id_perubahan', 'id');
    }

    public function tagihan()
    {
        return $this->hasOne(Tagihan::class, 'pencatatan_id', 'id');
    }
}
