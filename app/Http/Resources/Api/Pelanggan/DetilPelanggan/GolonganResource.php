<?php

namespace App\Http\Resources\Api\Pelanggan\DetilPelanggan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Pelanggan\DetilPelanggan\GolDetilResource;

class GolonganResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "golongan" => $this->golongan,
            "jenis" => $this->jenis,
            "harga" => $this->harga,
            "biaya" => $this->biaya,
            "denda" => $this->denda,
            "goldetil" => GolDetilResource::collection($this->whenLoaded('goldetil')),
            // 'pencatatan' => PencatatanResource::collection($this->whenLoaded('pencatatan')),
        ];
    }
}
