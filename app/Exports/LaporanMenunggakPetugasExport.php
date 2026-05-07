<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanMenunggakPetugasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $r;

    public function __construct($r)
    {
        $this->r = $r;
    }

    public function collection()
    {

        $tahun = $this->r->tahun;

        $data = DB::table('pelanggans as p')
            ->join('users as u', 'p.user_id_petugas', '=', 'u.id')
            ->join('pencatatans as c', 'p.id', '=', 'c.pelanggan_id')
            ->join('tagihans as t', 'c.id', '=', 't.pencatatan_id')

            // ->where('t.status_bayar', 'N')
            ->whereNull('p.deleted_at')
            ->where('c.tahun', $tahun)

            ->groupBy('p.user_id_petugas', 'u.nama')

            ->selectRaw('
        u.nama as user,
        COUNT(c.id) as total_pencatatan,
        SUM(
            CASE 
                WHEN t.status_bayar = "N" 
                THEN 1 
                ELSE 0 
            END
        ) as jumlah_pelanggan_belum_bayar,

        COALESCE(
            SUM(
                CASE 
                    WHEN t.status_bayar = "N" 
                    THEN t.total_nodenda 
                    ELSE 0 
                END
            ),0
        ) as jumlah_rupiah_belum_bayar,

            ROUND(
        (
            SUM(CASE WHEN t.status_bayar = "N" THEN 1 ELSE 0 END)
            / NULLIF(COUNT(c.id),0)
        ) * 100,
        2
    ) as persentase_belum_bayar
        
    ')->get();
        //     $data = DB::table('pelanggans as p')
        //         ->join('users as u', 'p.user_id_petugas', '=', 'u.id')
        //         ->join('pencatatans as c', 'p.id', '=', 'c.pelanggan_id')
        //         ->join('tagihans as t', 'c.id', '=', 't.pencatatan_id')

        //         ->where('t.status_bayar', 'N')
        //         ->whereNull('p.deleted_at')
        //         ->where('c.tahun', $tahun)

        //         ->groupBy('p.user_id_petugas', 'u.nama')

        //         ->selectRaw('
        //     u.nama as user,
        //     COUNT(t.id) as jumlah_pelanggan_belum_bayar,
        //     COALESCE(SUM(t.total_nodenda),0) as jumlah_rupiah_belum_bayar
        // ')->get();

        return $data;
    }


    public function headings(): array
    {
        return [
            [
                'Laporan Menunggak Petugas Tahun ' . $this->r->tahun,
            ],
            [],
            [
                'Petugas',
                'Jumlah Lembar Tagihan',
                'Jumlah Lembar Nunggak',
                'Total Rupiah Nunggak',
                'Persentase Nunggak',

            ]
        ];
    }

    public function map($row): array
    {
        return [
            $row->user,
            $row->total_pencatatan,
            $row->jumlah_pelanggan_belum_bayar,
            $row->jumlah_rupiah_belum_bayar,
            $row->persentase_belum_bayar,
        ];
    }
}
