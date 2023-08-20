<?php

namespace App\Models\Master;

use App\Models\Master\Golongan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Goldetil extends Model
{
    use HasFactory;

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'golongan_id', 'id');
    }
}
