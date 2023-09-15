<?php

namespace App\Http\Resources\Api\Pelanggan\Tagihan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Pelanggan\Tagihan\PencatatanResource;

class PelangganResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'no_pel' => $this->id,
            'nama' => substr($this->nama, 0, 3) . '******',
            // 'pencatatan' => PencatatanResource::collection($this->whenLoaded('pencatatan')),
            // 'users' => UserResource::collection($this->whenLoaded('users')),
        ];
    }
}
