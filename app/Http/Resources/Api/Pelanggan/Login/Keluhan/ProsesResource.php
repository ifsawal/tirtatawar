<?php

namespace App\Http\Resources\Api\Pelanggan\Login\Keluhan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProsesResource extends JsonResource
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
            // "keluhan_id" => $this->keluhan_id,
            "proses" => $this->proses,
            "ket" => $this->ket,
            "user_id" => $this->user_ud,
            "created_at" => $this->created_at,
        ];
    }
}
