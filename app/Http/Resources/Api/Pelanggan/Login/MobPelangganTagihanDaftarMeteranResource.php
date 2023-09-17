<?php

namespace App\Http\Resources\Api\Pelanggan\Login;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobPelangganTagihanDaftarMeteranResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "awal" => $this->awal,
            "akhir" => $this->akhir,
            "pemakaian" => $this->pemakaian,
            "bulan" => $this->bulan,
            "tahun" => $this->tahun,
            "photo" => $this->photo,
            "created_at" => ($this->created_at <> NULL) ? date('d-m-Y', strtotime($this->created_at)) : NULL,
        ];
    }
}
