<style>
    
body {
    font-family: sans-serif;
    font-size: 12px;
}

/* 🔥 paksa semua */
* {
    line-height: 2em;
}

.text-center { text-align: center; }
.text-end { text-align: right; }

.fw-bold { font-weight: bold; }
.mb-2 { margin-bottom: 15px; }
.mb-3 { margin-bottom: 20px; }

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    border: 1px solid #000;
    padding: 10px;
    line-height: 2em; /* 🔥 ini penting */
}

.table th {
    background: #f2f2f2;
}

</style>

<div class="text-center mb-3">
    <div class="fw-bold" style="font-size:18px;">INVOICE PEMBAYARAN</div>
</div>

<table width="100%" class="mb-3">
    <tr>
        <td>Data Travel</td>
        <td class="text-end">Data Jamaah</td>
    </tr>
    <tr>
        <td class="fw-bold">SAFARINDO ALBAROKAH UMROH</td>
        <td class="fw-bold text-end">{{ strtoupper($jamaah->nama_jamaah) }}</td>
    </tr>
    <tr>
        <td>Jl. Karya No.99, Karang Berombak, <br>
            Kec. Medan Barat, Medan, Sumatera Utara<br>
            Telp: 061-6614312 / 0812-3098-8862<br>
            Email: salbarokahumroh@gmail.com</td>
        <td class="text-end">{{ $jamaah->alamat }}</td>
    </tr>
</table>
<br>
<h4>Data Paket</h4>
<table width="65%" class="mb-3">
    <tr>
        <td>Data Paket</td>
        <td>{{$jamaah->paket->nama_paket}}</td>
    </tr>
    <tr>
        <td>Harga Paket</td>
        <td>Rp. {{number_format($jamaah->paket->harga_paket)}}</td>
    </tr>
    <tr>
        <td>Tanggal Berangkat</td>
        <td>{{$jamaah->paket->tgl_berangkat->translatedFormat('d F Y')}}</td>
    </tr>
    <tr>
        <td>Tipe Kamar</td>
        <td>{{$jamaah->tipeKamar->nama_kamar}}</td>
    </tr>
    <tr>
        <td>Harga Kamar</td>
        <td>Rp. {{number_format($jamaah->tipeKamar->harga_kamar)}}</td>
    </tr>
    <tr>
        <td>Total Bayar</td>
        <td>Rp {{ number_format($totalBayar) }}</td>
    </tr>
    <tr>
        <td>Total Tagihan</td>
        <td>Rp {{ number_format($tagihan) }}</td>
    </tr>
    <tr>
        <td>Diskon</td>
        <td>Rp {{ number_format($jumlahDiskon) }}</td>
    </tr>
    <tr>
        <td>Sisa Tagihan</td>
        <td>Rp {{ number_format($sisa) }}</td>
    </tr>
    
</table>
<h4>Catatan Manifest Jamaah</h4>
<table class="table mb-3">
    <thead>
        <tr>
            <th>No</th>
            <th>Tgl Pembayaran</th>
            <th>Metode</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jamaah->pembayarans as $p)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $p->created_at->format('d-m-Y') }}</td>
            <td>{{ $p->metode_bayar }}</td>
            <td class="text-end">Rp {{ number_format($p->jumlah_bayar) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table width="100%">
    <tr>
        <td>
            <span style="font-size: 10px;">
            Ketentuan Pembatalan:<br>
            Akan dikenakan biaya administrasi dari harga paket bila:<br>
            a.10 % setelah proses administrasi<br>
            b.50 % sejak 45 Hari sebelum keberangkatan.<br>
            c.75% sejak 3 Minggu sebelum keberangkatan<br>
            </span>
        </td>
        <td class="text-center">
            Medan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
            SAFARINDO ALBAROKAH UMROH<br><br><br><br><br><br>
            Rina Halizah Nasution, S.Akun<br>
            SPV-Safarindo Albarokah Umroh
        </td>
    </tr>
</table>