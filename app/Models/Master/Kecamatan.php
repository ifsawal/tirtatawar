<?php

namespace App\Models\Master;

use App\Models\Master\Desa;
use App\Models\Master\Kabupaten;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kecamatan extends Model
{
    use HasFactory;

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id', 'id');
    }

    public function desa()
    {
        return $this->hasMany(Desa::class, 'kecamatan_id', 'id');
    }
}
