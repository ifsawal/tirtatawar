<?php

namespace App\Models\Master;

use App\Models\User;
use App\Models\Master\Tagihan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penagih extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id', 'id');
    }
}
