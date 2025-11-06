<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanAbsensiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
                ->where('jenis_absen', 'kantor');
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
            ['Hadir', 'Setengah Hari', 'Izin', 'Sakit', 'Cuti']
        );
    }


    public function map($user): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->bulan, $this->tahun);
        $row = [$user->nama];

        $hadir = $izin = $sakit = $cuti = $setengah = 0;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $tanggal = sprintf('%04d-%02d-%02d', $this->tahun, $this->bulan, $day);
            $absen = $user->absen->firstWhere('tanggal', $tanggal);

            if ($absen) {
                $status = '';

                // logika status berdasarkan jam masuk / keluar
                if ($absen->jam_masuk && $absen->jam_keluar) {
                    $status = 'H';
                    $hadir++;
                } elseif ($absen->jam_masuk || $absen->jam_keluar) {
                    $status = 'SH';
                    $setengah++;
                } else {
                    // jika ada status manual (izin/sakit/cuti)
                    switch (strtolower($absen->status)) {
                        case 'izin':
                            $status = 'I';
                            $izin++;
                            break;
                        case 'sakit':
                            $status = 'S';
                            $sakit++;
                            break;
                        case 'cuti':
                            $status = 'C';
                            $cuti++;
                            break;
                        default:
                            $status = '';
                            break;
                    }
                }

                $row[] = $status;
            } else {
                $row[] = ''; // tidak ada data absen
            }
        }

        $row[] = $hadir;
        $row[] = $setengah;
        $row[] = $izin;
        $row[] = $sakit;
        $row[] = $cuti;

        return $row;
    }
}
