<?php

namespace App\Models\Master;

use App\Models\Master\Transfer;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tagihan extends Model
{
    use HasFactory;

    public function pencatatan()
    {
        return $this->belongsTo(Pencatatan::class, 'pencatatan_id', 'id');
    }
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
    }

    public function transfer()
    {
        return $this->hasMany(Transfer::class, 'tagihan_id', 'id');
    }
}
