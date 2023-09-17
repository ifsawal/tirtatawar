<?php

namespace App\Http\Resources\Api\Pelanggan\Login;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobPelangganTagihanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // "id" => 6,
            "awal" => $this->awal,
            "akhir" => $this->akhir,
            "pemakaian" => $this->pemakaian,
            "bulan" => $this->bulan,
            "tahun" => $this->tahun,
            "photo" => $this->photo,
            // "tagihan" => {
            "jumlah" => $this->tagihan->jumlah,
            "diskon" => $this->tagihan->diskon,
            "denda" => $this->tagihan->denda,
            "total" => $this->tagihan->total,
            "status_bayar" => $this->tagihan->status_bayar == "N" ? "Belum bayar" : "Sudah dibayar",
            "sistem_bayar" => $this->tagihan->sistem_bayar,

        ];
    }
}
