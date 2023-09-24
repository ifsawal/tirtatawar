<?php

namespace App\Http\Resources\Api\Pelanggan\DetilPelanggan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Pelanggan\DetilPelanggan\GolonganResource;

class DetilPelangganResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $goldetil = $this->whenLoaded('golongan');
        $golongan = new GolonganResource($goldetil);
        return [
            // "id" => $this->id,
            "nama" => $this->nama,
            // "nik" => $this->nik,
            // "kk" => $this->kk,
            "golongan" => $golongan,
        ];
    }
}
