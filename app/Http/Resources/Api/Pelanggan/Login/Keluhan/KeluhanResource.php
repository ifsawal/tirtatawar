<?php

namespace App\Http\Resources\Api\Pelanggan\Login\Keluhan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KeluhanResource extends JsonResource
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
            // "pelanggan_id" => $this->pelanggan_id,
            "keluhan" => $this->keluhan,
            "status" => $this->status,
            "ket" => $this->ket,
            "created_at" => date('d-m-Y', strtotime($this->created_at)),
            "proses" => ProsesResource::collection($this->whenLoaded('proses')),
            "tim" => TimResource::collection($this->whenLoaded('tim')),
        ];
    }
}
