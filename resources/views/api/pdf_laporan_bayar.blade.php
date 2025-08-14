<pre>
<b>LAPORAN BAYAR</b>
An.             : {{ $data['user'] }}
Tanggal bayar   : {{ $data['tanggal'] }}

Total Penerimaan hari ini   : @rp($data['setoran']['jumlah'])

Jumlah Transaksi            : {{ $data['trx'] }} Trx
Jumlah Pelanggan            : {{ $data['jumlah_pelanggan_ditagih'] }} Pelanggan
Rincian :
1. Total Harga Pemakaian    : @rp($data['setoran']['dasar'])

2. Total ADM                : @rp($data['setoran']['adm'])

3. Total Denda              : @rp($data['setoran']['denda'])

4. Total Pajak              : @rp($data['setoran']['pajak'])


Rincian Tagihan Pergolongan
@foreach ($data['pergolongan'] as $key => $value)
@if ($value != 0)
{{ $key }} = @rp($value) -
@endif
@endforeach

<b>Data Tagihan</b> 
_____________________________________________________________
</pre>
<table>
    <tr>
        <td>
            <center>Nomor
        </td>
        <td>
            <center>No Pel
        </td>
        <td>
            <center>Nama Pel
        </td>
        <td>
            <center>Periode
        </td>
        <td>
            <center>Jumlah
        </td>
        <td>
            <center>Golongan
        </td>
        <td>
            <center>Wilayah/jalan
        </td>
    </tr>
    {{ $no = 1 }}
    <tbody>
        @foreach ($data['data'] as $p)
            <tr>
                <td>{{ $no++ }} </td>
                <td>{{ $p['tagihan']['pencatatan']['pelanggan']['id'] }}</td>
                <td>{{ $p['tagihan']['pencatatan']['pelanggan']['nama'] }}</td>
                <td> {{ $p['tagihan']['pencatatan']['bulan'] }}-{{ $p['tagihan']['pencatatan']['tahun'] }} </td>
                <td>@rp($p['jumlah'])</td>
                <td>{{ $p['tagihan']['pencatatan']['pelanggan']['golongan']['golongan'] }}</td>
                <td>{{ $p['tagihan']['pencatatan']['pelanggan']['wiljalan']['jalan'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
