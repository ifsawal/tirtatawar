<?php

namespace App\Http\Resources\Api\Pelanggan\Tagihan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagihanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public static function denda($waktucatat, $kurangi1bulan, $dendaperbulan)
    {
        $sekarang = date('Y-m', strtotime(now()));
        $waktucatat = date('Y-m', strtotime($waktucatat));

        $catatkurangi1bulan = $kurangi1bulan;
        $waktukurang1bulan = date('Y-m', strtotime(Carbon::now()->subMonths(1)));

        $denda = 0;
        if ($waktucatat == $sekarang) {
            return $denda = 0;
        } else if ($catatkurangi1bulan == $waktukurang1bulan) {
            return $denda = 0;
        } else {
            $date1 = date_create($sekarang . '-1');
            $date2 = date_create($waktucatat . '-1');
            $interval = date_diff($date1, $date2);

            return $denda = ($interval->m - 1) * $dendaperbulan;
        }
    }


    public function toArray(Request $request): array
    {
        $waktucatat = $this->tahun . '-' . $this->bulan . '-' . '1';
        $kurangi1bulan = date('Y-m', strtotime(Carbon::create($this->tahun, $this->bulan, 1)->subMonths(1)));

        $denda = $this->denda($waktucatat, $kurangi1bulan, $this->denda_perbulan);

        return [
            // 'id' => $this->id,
            'jumlah' => $this->jumlah,
            'diskon' => $this->diskon,
            'denda' => $denda,
            'total' => $this->total,

            // 'bulan' => $this->bulan, //untuk ngambil bulan
            // 'tahun' => $this->tahun, //untuk ngambil tahun

            'status_bayar' => $this->status_bayar,
        ];
    }
}
