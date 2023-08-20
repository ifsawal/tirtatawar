<?php

namespace App\Models\Master;

use App\Models\Master\Goldetil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Golongan extends Model
{
    use HasFactory;


    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'golongan_id', 'id');
    }

    public function goldetil()
    {
        return $this->hasMany(Goldetil::class, 'golongan_id', 'id');
    }
}
