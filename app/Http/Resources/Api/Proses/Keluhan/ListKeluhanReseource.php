<?php

namespace App\Http\Resources\Api\Proses\Keluhan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListKeluhanReseource extends JsonResource
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
            // "pelanggan_id"=> 1,
            "keluhan" => $this->keluhan,
            "status" => $this->status,
            "ket" => $this->ket,
            "created_at" => date('d-m-Y', strtotime($this->created_at)),
            "pelanggan" => $this->pelanggan->nama,
        ];
    }
}
