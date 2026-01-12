<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;





class LaporanStokOpNameExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */


    protected int $tahun;
    protected $akhirTahun;

    public function __construct(int $tahun,$batas_data_diambil)
    {
        $this->tahun = $tahun;

        $this->akhirTahun =  date('Y-m-t', strtotime($batas_data_diambil));
    }


    public function collection()
    {
        $akhir = $this->akhirTahun;
        return DB::table('wiljalans as w')
            ->leftJoin('pelanggans as p', 'p.wiljalan_id', '=', 'w.id')
            ->leftJoin('pencatatans as c', function ($join) {
                $join->on('c.pelanggan_id', '=', 'p.id')
                    ->where('c.tahun', $this->tahun);
            })
            ->leftJoin('tagihans as t', function ($join) use ($akhir) {
                $join->on('t.pencatatan_id', '=', 'c.id')
                    ->where(function ($q) use ($akhir) {
                        $q->where('t.status_bayar', 'N')
                            ->orWhere(function ($q2) use ($akhir) {
                                $q2->where('t.status_bayar', 'Y')
                                    ->whereDate('t.tgl_bayar', '>', $akhir);
                            });
                    });
            })
            ->select(
                'w.id',
                'w.jalan',
                DB::raw('COUNT(DISTINCT CASE WHEN t.id IS NOT NULL THEN p.id END) as jumlah_pelanggan_belum_bayar'),
                DB::raw('COUNT(DISTINCT t.id) as jumlah_bulan_belum_bayar'),
                DB::raw('COALESCE(SUM(t.total_nodenda), 0) as total_belum_dibayar')
            )
            ->groupBy('w.id', 'w.jalan')
            ->orderBy('w.jalan')
            ->get();

        // return DB::table('wiljalans as w')
        //     ->leftJoin('pelanggans as p', 'p.wiljalan_id', '=', 'w.id')
        //     ->leftJoin('pencatatans as c', 'c.pelanggan_id', '=', 'p.id')
        //     ->where('c.tahun', $this->tahun)
        //     ->leftJoin('tagihans as t', function ($join) use ($akhir) {
        //         $join->on('t.pencatatan_id', '=', 'c.id')
        //             ->where(function ($q) use ($akhir) {
        //                 $q->where('t.status_bayar', 'N')
        //                     ->orWhere(function ($q2) use ($akhir) {
        //                         $q2->where('t.status_bayar', 'Y')
        //                             ->whereDate('t.tgl_bayar', '>', $akhir);
        //                     });
        //             });
        //     })
        //     ->select(
        //         'w.id',
        //         'w.jalan',
        //         DB::raw('COUNT(DISTINCT p.id) as jumlah_pelanggan_belum_bayar'),
        //         DB::raw('COUNT(t.id) as jumlah_bulan_belum_bayar'),
        //         DB::raw('COALESCE(SUM(t.total_nodenda), 0) as total_belum_dibayar')
        //     )
        //     ->groupBy('w.id', 'w.jalan')
        //     ->orderBy('w.jalan')
        //     ->get();
    }

    public function headings(): array
    {
        return [
            'Wilayah',
            'Jumlah Pelanggan Belum Bayar',
            'Jumlah Bulan Belum Bayar',
            'Total Belum Dibayar',
        ];
    }

    public function map($row): array
    {
        return [
            $row->jalan,
            $row->jumlah_pelanggan_belum_bayar,
            $row->jumlah_bulan_belum_bayar,
            $row->total_belum_dibayar,
        ];
    }
}
