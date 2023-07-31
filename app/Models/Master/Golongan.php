<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    use HasFactory;


    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'golongan_id', 'id');
    }
}
