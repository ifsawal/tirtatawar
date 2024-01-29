<?php

namespace App\Fungsi\Pencatatan;

use App\Http\Controllers\Api\Master\PencatatanController;

class Prosespencatatan extends PencatatanController
{
    public function proses($r)
    {
        return $this->catat_manual($r);
    }
}
