<?php

namespace App\Http\Resources\Api\Pelanggan\Tagihan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Master\Tagihan;
use Illuminate\Support\Facades\DB;
use DragonCode\Support\Facades\Helpers\Arr;
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

    public static function simpan_denda($bulan, $tahun, $dendaperbulan, $denda_saatini, $tagihan_id, $total)
    {
        DB::beginTransaction();
        try {
            $waktucatat = $tahun . '-' . $bulan . '-' . '1';
            $kurangi1bulan = date('Y-m', strtotime(Carbon::create($tahun, $bulan, 1)->subMonths(1)));

            $tagihan = Tagihan::findOrFail($tagihan_id);

            $denda = self::denda($waktucatat, $kurangi1bulan, $dendaperbulan);
            if ($denda > 0 and $denda <> $denda_saatini) { //jika hasil penghitungan > 0; dan $denda tidak sama dengan denda di tabel tagihan
                if ($denda_saatini == 0) {
                    $tagihan->subtotal = $tagihan->total + $denda;
                    $tagihan->total = $tagihan->subtotal;
                } else {
                    $tagihan->subtotal = ($tagihan->total - $denda_saatini) + $denda;
                    $tagihan->total = $tagihan->subtotal;
                }
                $total = $tagihan->total;

                $tagihan->denda = $denda;
                $tagihan->save();
            }
            return $tagihan;
            // DB::commit();
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function toArray(Request $request): array
    {
        $perubahan_denda = self::simpan_denda($this->bulan, $this->tahun, $this->denda_perbulan, $this->denda, $this->id, $this->total);

        return [
            'id' => encrypt($this->id),
            'jumlah' => $this->jumlah,
            'diskon' => $this->diskon,
            'pajak' => $this->pajak,
            'denda' => $perubahan_denda->denda,
            'biaya' => $this->biaya,
            'subtotal' => $perubahan_denda->total,
            'total' => $perubahan_denda->total,

            // 'bulan' => $this->bulan, //untuk ngambil bulan
            // 'tahun' => $this->tahun, //untuk ngambil tahun

            'status_bayar' => $this->status_bayar,
        ];
    }
}
