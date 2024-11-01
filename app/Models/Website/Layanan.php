<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;
    protected $fillable = [
        'judul',
        'slug',
        'icon',
        'des_pendek',
        'deskripsi',
        'status',
    ];
}
