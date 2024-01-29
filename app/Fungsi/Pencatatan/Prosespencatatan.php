<?php

namespace App\Fungsi\Pencatatan;

use App\Http\Controllers\Api\Master\PencatatanController;

class Prosespencatatan extends PencatatanController
{
    public function proses_catatan($r) // catat
    {
        return $this->catat_manual($r);
    }

    public function proses_tagihan($pencatatan_id,$pelanggan_id,$pemakaian){
        return $this->simpanTagihan($pencatatan_id,$pelanggan_id,$pemakaian); 
        
    }
}
