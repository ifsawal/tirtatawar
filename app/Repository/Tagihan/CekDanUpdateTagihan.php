<?php


namespace App\Repository\Tagihan;

use App\Http\Resources\Api\Pelanggan\Tagihan\TagihanResource;

class CekDanUpdateTagihan
{

    public static function ambilTagihan($pencatatan, $denda_perbulan)
    {

        $data = [];
        foreach ($pencatatan as $p) {

            $tagihanResouce=TagihanResource::simpan_denda($p->bulan, $p->tahun, $denda_perbulan, $p->tagihan->denda, $p->tagihan->id, $p->tagihan->total);
                 // $perubahan_denda = self::simpan_denda($this->bulan, $this->tahun, $this->denda_perbulan, $this->denda, $this->id, $this->total);
            $data[] = [
                'id'        => encrypt($p->id),
                'awal'      => $p->awal,
                'akhir'     => $p->akhir,
                'pemakaian' => $p->pemakaian,
                'bulan'     => $p->bulan,
                'tahun'     => $p->tahun,
                'denda_perbulan'     => $denda_perbulan,
                'tagihan'   => [
                    'id' => encrypt($p->tagihan->id),
                    'jumlah' => $p->tagihan->jumlah,
                    'diskon' => $p->tagihan->diskon,
                    'pajak' => $p->tagihan->pajak,
                    'denda' => $tagihanResouce->denda,
                    'biaya' => $p->tagihan->biaya,
                    'subtotal' => $tagihanResouce->total,
                    'total' => $tagihanResouce->total,

                ]
            ];
        }
        return $data;
    }
}
