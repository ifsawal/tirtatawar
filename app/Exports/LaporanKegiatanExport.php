<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanKegiatanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return User::with(['absen' => function ($q) {
            $q->whereMonth('tanggal', $this->bulan)
                ->whereYear('tanggal', $this->tahun)
                ->where('jenis_absen', 'lapangan');
        }])
        ->where('email_verified_at', '!=', null)
        ->where('id', '!=', 1)
        ->get();
    }


    public function headings(): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->bulan, $this->tahun);
        $days = range(1, $daysInMonth);

        return array_merge(
            ['Nama'],
            $days,
            ['Hadir', 'Izin', 'Sakit', 'Cuti']
        );
    }

    public function map($user): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->bulan, $this->tahun);
        $row = [$user->nama];

        $hadir = $izin = $sakit = $cuti = 0;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $absen = $user->absen->firstWhere('tanggal', sprintf('%04d-%02d-%02d', $this->tahun, $this->bulan, $day));

            if ($absen) {
                $status = strtoupper(substr($absen->status, 0, 1)); // H, I, S, C
                $row[] = $status;

                // hitung total
                switch (strtolower($absen->status)) {
                    case 'hadir': $hadir++; break;
                    case 'izin': $izin++; break;
                    case 'sakit': $sakit++; break;
                    case 'cuti': $cuti++; break;
                }
            } else {
                $row[] = ''; // tidak ada data
            }
        }

        $row[] = $hadir;
        $row[] = $izin;
        $row[] = $sakit;
        $row[] = $cuti;

        return $row;
    }
}
