<?php

namespace App\Http\Controllers\Api\Bank\BSI;

use Illuminate\Http\Request;
use App\Models\Master\Client;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Pelanggan\Tagihan\PencatatanResource;

class BsiController extends Controller
{

    protected $kodeBank;
    protected $kodeChannel;
    protected $kodeBiller;
    protected $kodeTerminal;
    protected $nomorPembayaran;
    protected $tanggalTransaksi;
    protected $idTransaksi;
    protected $totalNominalInquiry;


    protected $idTagihan;
    protected $totalNominal;
    protected $nomorJurnalPembukuan;

    protected $ceksum;


    public function validasi()
    {

        // PERIKSA APAKAH SELURUH PARAMETER SUDAH LENGKAP
        if (
            empty($this->kodeBank) || empty($this->kodeChannel) || empty($this->kodeTerminal) ||
            empty($this->nomorPembayaran) || empty($this->tanggalTransaksi) || empty($this->idTransaksi)
        ) {
            return [
                'rc' => 'ERR-PARSING-MESSAGE',
                'msg' => 'Invalid Message Format'
            ];
        }

        $bank = Client::where('kode', $this->kodeBank)->first();
        if (!$bank) {
            return [
                'rc' => 'ERR-BANK-UNKNOWN',
                'msg' => 'Collecting agent is not allowed by ' . $this->kodeBank
            ];
        }

        $allowed_collecting_agents = explode(',', $bank->kode);
        // PERIKSA APAKAH KODE BANK DIIZINKAN MENGAKSES WEBSERVICE INI
        if (!in_array($this->kodeBank, $allowed_collecting_agents)) {
            return [
                'rc' => 'ERR-BANK-UNKNOWN',
                'msg' => 'Collecting agent is not allowed by ' . $bank->kode
            ];
        }

        // PERIKSA APAKAH KODE CHANNEL DIIZINKAN MENGAKSES WEBSERVICE INI
        $allowed_channels = explode(',', $bank->channel);
        if (!in_array($this->kodeChannel, $allowed_channels)) {
            return [
                'rc' => 'ERR-CHANNEL-UNKNOWN',
                'msg' => 'Channel is not allowed by ' . $bank->nama
            ];
        }

        if (sha1($this->nomorPembayaran . $bank->client_id . $this->tanggalTransaksi) != $this->ceksum) {
            return [
                'rc' => 'ERR-SECURE-HASH',
                'msg' => 'H2H Checksum is invalid',
            ];
        }

        return [
            'rc' => 'sukses',
            'msg' => 'Format Benar'
        ];
    }

    public function validasi_payment()
    {
        $validasi = $this->validasi();
        $validasi['rc'];
        if ($validasi['rc'] <> "sukses") {
            return response()->json($validasi, 200);
        }

        if (empty($this->idTransaksi || empty($totalNominal))) {
            return [
                'rc' => 'ERR-PARSING-MESSAGE',
                'msg' => 'Invalid Message Format'
            ];
        }
        return [
            'rc' => 'sukses',
            'msg' => 'Format Benar'
        ];
    }


    public function payment(Request $r)
    {
        $data = $r->getContent();
        $data = json_decode($data, true);



        $this->kodeBank                 = $data['kodeBank'];
        $this->kodeChannel             = $data['kodeChannel'];
        $this->kodeBiller             = $data['kodeBiller'];
        $this->kodeTerminal             = $data['kodeTerminal'];
        $this->nomorPembayaran         = $data['nomorPembayaran'];
        $this->idTagihan                 = $data['idTagihan'];
        $this->tanggalTransaksi         = $data['tanggalTransaksi'];
        $this->idTransaksi             = $data['idTransaksi'];
        $this->totalNominal             = $data['totalNominal'];
        $this->nomorJurnalPembukuan    = $data['nomorJurnalPembukuan'];
    }



    public function inquiry(Request $r)
    {
        $data = $r->getContent();
        $data = json_decode($data, true);

        // PARAMATER DI BAWAH INI ADALAH VARIABEL YANG DITERIMA DARI BSI
        $this->kodeBank                 = $data['kodeBank'];
        $this->kodeChannel              = $data['kodeChannel'];
        $this->kodeBiller               = $data['kodeBiller'];
        $this->kodeTerminal             = $data['kodeTerminal'];
        $this->nomorPembayaran          = (int)$nopel = $data['nomorPembayaran'];
        $this->tanggalTransaksi         = $data['tanggalTransaksi'];
        $this->idTransaksi              = $data['idTransaksi'];
        $this->totalNominalInquiry      = $data['totalNominalInquiry'];
        $this->ceksum                   = sha1($this->nomorPembayaran . '$2y$10$rKe1KAG05MmSLwF4kr.tKeW7zixJKnUi6Fv5pMozNh6JmX7N4md5O' . $this->tanggalTransaksi);


        $validasi = $this->validasi();
        $validasi['rc'];
        if ($validasi['rc'] <> "sukses") {
            return response()->json($validasi, 200);
        }

        $pelanggan = Pelanggan::with('golongan:id,denda')
            ->where('id', $this->nomorPembayaran)->first();
        if (!$pelanggan) {
            return response()->json([
                'rc' => 'ERR-NOT-FOUND',
                'msg' => 'Nomor Tidak Ditemukan',
            ], 200);
        }

        $pencatatan = Pencatatan::with('tagihan', 'pelanggan')
            ->where('pelanggan_id', $this->nomorPembayaran)
            ->whereRelation('tagihan', 'status_bayar', '=', 'N')
            ->orderBy('id', 'desc')
            ->get();

        $pencatatan = PencatatanResource::customCollection($pencatatan, $pelanggan->golongan->denda);
        if (count($pencatatan) == 0) {
            return response()->json([
                'rc' => 'ERR-ALREADY-PAID',
                'msg' => 'Sudah Terbayar',
            ], 404);
        }

        $arr_rincian = array();
        $arr_informasi = array();
        $total = 0;
        foreach ($pencatatan as $c) {
            $total = $total + $c->tagihan->total;
            $arr_rincian[] = [
                'kode_rincian' => $c->id,
                'deskripsi' => 'Bulan ' . $c->bulan,
                'nominal' => $c->tagihan->total,
            ];
            $arr_informasi[] = [
                'Info' => '',
            ];
        }

        $data_inquiry = [
            'rc'            => 'OK',
            'msg'             => 'Inquiry Succeeded',
            'nomorPembayaran'     => $nopel,
            'idPelanggan'         => $nopel,
            'nama'             => $pelanggan->nama,
            'totalNominal'         => $total,
            'informasi'         => $arr_informasi,
            'rincian'         => $arr_rincian,
            'idTagihan'        => $this->idTransaksi
        ];

        return response()->json($data_inquiry, 200);
    }
}
