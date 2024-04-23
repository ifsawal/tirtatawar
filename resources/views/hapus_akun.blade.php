<br><br><br>
<center>
    PDAM Tirta Tawar<br><Br>
    Form permintaan penghapusan akun<br><br>
    <form method=post action={{ route('proses_hapus_akun') }}>
        @csrf
        <input type=number name=idpel placeholder="Nomor Pelanggan"><br><br>
        <input type="submit" value=Proses>


    </form>
