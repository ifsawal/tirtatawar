<?php

namespace App\Models\Master;

use Laravel\Sanctum\HasApiTokens;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Model; //Authenticatable;

class Client extends Model
{
    use HasFactory, SoftDeletes;
    use HasApiTokens, Notifiable;


    protected $hidden = [
        'password',
        'remember_token',
    ];
}
