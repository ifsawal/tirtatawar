<?php

namespace App\Http\Resources\Api\Absen;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $hari = Carbon::parse($this->tanggal)->locale('id')->isoFormat('dddd'); // "Kamis"
        $this->jam_keluar==NULL ? $lengkap="x" : $lengkap="ok";

        $tanggal = $this->tanggal ? Carbon::parse($this->tanggal) : null;
        $baseDatePath = $tanggal ? 'files2/absen/' . $tanggal->format('Y') . '/' . $tanggal->format('m') . '/' . $tanggal->format('d') . '/' : null;


        return [
            'id' => $this->id,
            'tanggal' => date('d-m-Y', strtotime($this->tanggal)),
            'hari' => $hari,
            'jam_masuk' => $this->jam_masuk,
            'jam_keluar' => $this->jam_keluar,
            'status' => strtoupper($this->status),
            'keterangan' => $this->keterangan,
            'lokasi_masuk' => $this->lokasi_masuk,
            'foto_masuk_url' => $this->foto_masuk && $baseDatePath ? url($baseDatePath . $this->foto_masuk) : null,
            'foto_keluar_url' => $this->foto_keluar && $baseDatePath ? url($baseDatePath . $this->foto_keluar) : null,
            'komplet' => $lengkap

        ];
    }
}
