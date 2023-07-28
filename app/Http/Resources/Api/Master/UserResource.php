<?php

namespace App\Http\Resources\Api\Master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'email' => $this->email,
            'pdam_id' => $this->pdam_id,
            'pdam' => $this->whenLoaded('pdam'),
        ];
    }
}
