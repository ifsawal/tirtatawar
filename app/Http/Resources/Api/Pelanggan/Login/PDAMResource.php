<?php

namespace App\Http\Resources\Api\Pelanggan\Login;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PDAMResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // "id" => bcrypt($this->id),
            "pdam" => $this->pdam,
            "nama" => $this->nama,
        ];
    }
}
