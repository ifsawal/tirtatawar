<?php

namespace App\Models\Master;


use App\Models\Master\Pelanggan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rute extends Model
{
    use HasFactory;

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'rute_id', 'id');
    }
}
