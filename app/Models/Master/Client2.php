<?php

namespace App\Models\Master;

use Laravel\Sanctum\HasApiTokens;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Model; //Authenticatable;

class Client2 extends Model
{
    use HasFactory, SoftDeletes,Notifiable, HasApiTokens;

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
