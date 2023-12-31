<?php

namespace App\Models\Keluhan;

use App\Models\Keluhan\Tim;
use App\Models\Keluhan\Proses;
use App\Models\Master\Pelanggan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keluhan extends Model
{
    use HasFactory;

    public function proses()
    {
        return $this->hasMany(Proses::class, 'keluhan_id', 'id');
    }

    public function tim()
    {
        return $this->hasMany(Tim::class, 'keluhan_id', 'id');
    }
    public function photokeluhan()
    {
        return $this->hasMany(Keluhanphoto::class, 'keluhan_id', 'id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
