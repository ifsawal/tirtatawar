<?php

namespace App\Http\Resources\Api\Pelanggan\Login\Keluhan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimResource extends JsonResource
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
            "user_id" => $this->user_id,
            "status" => $this->status,
            "created_at" => $this->created_at,

        ];
    }
}
