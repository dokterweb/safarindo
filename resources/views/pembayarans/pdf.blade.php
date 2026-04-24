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
        <td class="text-end">Data Travel</td>
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

<table width="100%" style="line-height: 2em">
    <tr>
        <td><b>{{strtoupper($jamaah->paket->nama_paket)}}</b> Total Tagihan</td>
        <td class="text-end">Rp {{ number_format($tagihan) }}</td>
    </tr>
    <tr>
        <td>Total Bayar</td>
        <td class="text-end">Rp {{ number_format($totalBayar) }}</td>
    </tr>
    <tr>
        <td class="fw-bold">Sisa</td>
        <td class="text-end fw-bold">Rp {{ number_format($sisa) }}</td>
    </tr>
</table>