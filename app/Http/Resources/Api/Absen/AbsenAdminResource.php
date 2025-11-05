<?php

namespace App\Http\Resources\Api\Absen;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Api\Absen\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsenAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $tanggal = $this->tanggal ? Carbon::parse($this->tanggal) : null;
        $baseDatePath = $tanggal ? 'files2/absen/' . $tanggal->format('Y') . '/' . $tanggal->format('m') . '/' . $tanggal->format('d') . '/' : null;

        $komplit = ($this->jam_keluar != NULL) ? "ok" : "x";
        $this->jam_masuk == NULL || $this->jam_keluar == null ? $status = "SETENGAH HARI" : $status = strtoupper($this->status);
        $this->jam_masuk == NULL && $this->jam_keluar == NULL ? $status = $status = strtoupper($this->status) : null;
        $this->jenis_absen == "lapangan" ? $status = "<font color='#1b5a04'>HADIR (KEGIATAN)</font>" : null;

        return [
            'id' => $this->id,

            'tanggal' => $tanggal ? $tanggal->toDateString() : null,
            'hari' => $tanggal ? $tanggal->locale('id')->isoFormat('dddd') : null,
            'jam_masuk' => $this->jam_masuk ? Carbon::parse($this->jam_masuk)->format('H:i') : null,
            'jam_keluar' => $this->jam_keluar ? Carbon::parse($this->jam_keluar)->format('H:i') : null,
            'status' => $status,
            'keterangan' => $this->keterangan,
            'lokasi_masuk' => $this->lokasi_masuk,
            'lokasi_keluar' => $this->lokasi_keluar,
            'komplit' => $komplit,
            'foto_masuk_url' => $this->foto_masuk && $baseDatePath ? url($baseDatePath . $this->foto_masuk) : null,
            'foto_keluar_url' => $this->foto_keluar && $baseDatePath ? url($baseDatePath . $this->foto_keluar) : null,
            'user' => $this->whenLoaded('user', function () {
                return UserResource::make($this->user);
            }),
        ];
    }
}
