<?php

namespace App\Models\Master;

use App\Models\User;
use App\Models\Master\Pelanggan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PelangganHapus extends Model
{
    use HasFactory;

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user_aktifkan()
    {
        return $this->belongsTo(User::class, 'user_id_aktifkan', 'id');
    }
}
