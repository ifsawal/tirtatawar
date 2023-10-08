<?php

namespace App\Models\Master;

use App\Models\Master\Jenisbayar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bayar extends Model
{
    use HasFactory;

    public function jenisbayar()
    {
        return $this->belongsTo(Jenisbayar::class, 'jenisbayar_id', 'id');
    }
}
