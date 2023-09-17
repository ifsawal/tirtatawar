<?php

namespace App\Http\Resources\Api\Pelanggan\Login;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PelangganResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        // $pdam = $this->whenLoaded('pdam');
        // $desa = $this->whenLoaded('desa');
        return [
            "id" => bcrypt($this->id),
            "nama" => $this->nama,
            "nik" => $this->nik,
            "kk" => $this->kk,
            "lat" => $this->lat,
            "long" => $this->long,
            "email" => $this->email,
            "nolama" => $this->nolama,
            "pdam" => $this->pdam->pdam,
            "desa" => $this->desa->desa,
            "golongan" => $this->golongan->golongan,
            "rute" => isset($this->rute->rute) ? $this->rute->rute : "",
            "hp" => HpResource::collection($this->whenLoaded('hp_pelanggan')),
        ];
    }
}
