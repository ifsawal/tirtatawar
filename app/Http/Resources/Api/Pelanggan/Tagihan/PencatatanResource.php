<?php

namespace App\Http\Resources\Api\Pelanggan\Tagihan;

use Illuminate\Http\Request;
use App\Models\Master\Tagihan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;

class PencatatanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * 
     */

    private static $data;
    public static function customCollection($resource, $data)
    {
        self::$data = $data;
        return parent::collection($resource);
    }

    public function toArray(Request $request): array
    {
        $tagihan = $this->whenLoaded('tagihan');
        $tagihan->bulan = $this->bulan;
        $tagihan->tahun = $this->tahun;
        $tagihan->denda_perbulan = self::$data;

        $detiltagihan = new TagihanResource($tagihan);

        return [
            'id'        => encrypt($this->id),
            'awal'      => $this->awal,
            'akhir'     => $this->akhir,
            'pemakaian' => $this->pemakaian,
            'bulan'     => $this->bulan,
            'tahun'     => $this->tahun,
            'denda_perbulan' => self::$data,
            // 'dsd' => $this->meta->denda,
            'tagihan'   => $detiltagihan,
            // 'total'     => $tagihan->total,


        ];
    }
}
