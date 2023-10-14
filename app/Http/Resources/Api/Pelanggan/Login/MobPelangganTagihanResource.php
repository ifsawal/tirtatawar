<?php

namespace App\Http\Resources\Api\Pelanggan\Login;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Pelanggan\Tagihan\TagihanResource;

class MobPelangganTagihanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        if ($this->tagihan->status_bayar == "N") {  //jika sudah bayar jngan di denda lagi
            $update_denda = TagihanResource::simpan_denda($this->bulan, $this->tahun, $this->tagihan->denda_perbulan, $this->tagihan->denda, $this->tagihan->id, $this->tagihan->total);
            $denda = $update_denda->denda;
            $total = $update_denda->total;
        } else {
            $denda = $this->tagihan->denda;
            $total = $this->tagihan->total;
        }

        return [
            "id" => encrypt($this->id),
            "awal" => $this->awal,
            "akhir" => $this->akhir,
            "pemakaian" => $this->pemakaian,
            "bulan" => $this->bulan,
            "tahun" => $this->tahun,
            "photo" => $this->photo,
            // "tagihan" => {
            "jumlah" => $this->tagihan->jumlah,
            "diskon" => $this->tagihan->diskon,
            "denda" => $denda,
            "pajak" => $this->tagihan->pajak,
            "diskon" => $this->tagihan->diskon,
            "biaya" => $this->tagihan->biaya,
            "total" => $total,
            "status_bayar" => $this->tagihan->status_bayar == "N" ? "Belum bayar" : "Sudah dibayar",
            "sistem_bayar" => $this->tagihan->sistem_bayar,

        ];
    }
}
