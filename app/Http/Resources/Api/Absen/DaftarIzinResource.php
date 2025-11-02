<?php

namespace App\Http\Resources\Api\Absen;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Api\Absen\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DaftarIzinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $tanggal = $this->created_at ? Carbon::parse($this->created_at) : null;
        $lampiran = $this->lampiran != null ? url('files2/absen/' . $tanggal->format('Y') . '/' . $tanggal->format('m') . '/' . $tanggal->format('d') . '/' . $this->lampiran) : null;
        $ttd = $this->ttd != null ? url('files2/absen/' . $tanggal->format('Y') . '/' . $tanggal->format('m') . '/' . $tanggal->format('d') . '/' . $this->ttd) : null;


        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', function () {
                return UserResource::make($this->user);
            }),
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'jenis' => $this->jenis,
            'alasan' => $this->alasan,
            'status_approval' => $this->status_approval,
            'lampiran' => $lampiran,
            'ttd' => $ttd,
            'user_penyetuju' => $this->whenLoaded('user_penyetuju', function () {
                return UserResource::make($this->user_penyetuju);
            }),


        ];
    }
}
