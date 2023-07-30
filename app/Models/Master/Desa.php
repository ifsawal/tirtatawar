<?php

namespace App\Models\Master;


use App\Models\Master\Kecamatan;
use App\Models\Master\Pelanggan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Desa extends Model
{
    use HasFactory;


    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'desa_id', 'id');
    }
}
