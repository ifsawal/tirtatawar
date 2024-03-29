<?php

namespace App\Http\Resources\Api\Data;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataPelangganTanpaHalamanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        if ($this->lat === NULL or $this->long === NULL or $this->desa_id === NULL or $this->rute_id === NULL  or $this->hp === NULL) {
            $lengkap = "";
        } else {
            $lengkap = "lengkap";
        }

        return [
            'id' => $this->id,
            'nama' => $this->nama,
            "nik" => $this->nik,
            "kk" => $this->kk,
            "lat" => $this->lat,
            "long" => $this->long,
            "email" => $this->email,
            "nolama" => $this->nolama,
            "status" => $lengkap,
        ];
    }
}
