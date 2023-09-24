<?php

namespace App\Http\Resources\Api\Pelanggan\DetilPelanggan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GolDetilResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // "id" => $this->id,
            "nama" => $this->nama,
            "awal_meteran" => $this->awal_meteran,
            "akhir_meteran" => $this->akhir_meteran,
            "harga" => $this->harga,
        ];
    }
}
