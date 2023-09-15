<?php

namespace App\Http\Resources\Api\Pelanggan\Tagihan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PencatatanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * 
     */

    public $denda;
    public function with(Request $request): array
    {
        // $this->denda = $request;
        return [
            'meta' => [
                'key' => 231321,
            ],
        ];
    }

    public function toArray(Request $request): array
    {
        $tagihan = $this->whenLoaded('tagihan');
        $tagihan->bulan = $this->bulan;
        $tagihan->tahun = $this->tahun;
        return [
            // 'id' => $this->id,
            'awal' => $this->awal,
            'akhir' => $this->akhir,
            'pemakaian' => $this->pemakaian,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'denda_perbulan' => $this->denda,
            // 'dsd' => $this->meta->denda,

            'tagihan' => new TagihanResource($tagihan),
        ];
    }
}
