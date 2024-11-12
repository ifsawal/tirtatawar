<?php

namespace App\Http\Controllers\Email;

use App\Mail\KirimEmail;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class KirimEmailController extends Controller
{
    public $data="hai";
    public function kirim()
    {
        // $this->data = "hai";
        Mail::to('ifsawal@gmail.com')->send(new KirimEmail($this->data));
    }
}
