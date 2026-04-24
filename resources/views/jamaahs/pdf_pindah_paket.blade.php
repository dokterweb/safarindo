<style>
    body {
        font-family: sans-serif;
        font-size: 12px;
        line-height: 1.8;
    }
    .text-center { text-align: center; }
    .fw-bold { font-weight: bold; }
    .mt-3 { margin-top: 20px; }
    </style>
    
    <div class="text-center fw-bold">
        SURAT PERNYATAAN PINDAH {{strtoupper($paket->nama_paket)}}
    </div>
    
    <div class="text-center">
        (Tabungan Umroh ke Paket Umroh)
    </div>
    
    <br>
    
    Yang bertanda tangan di bawah ini:
    
    <br><br>
    
    <table width="100%">
    <tr><td width="200">Nama Lengkap</td><td>: {{ strtoupper($jamaah->nama_jamaah) }}</td></tr>
    <tr><td>Tempat/Tgl Lahir</td><td>: {{ $jamaah->tempat_lahir }}, {{ \Carbon\Carbon::parse($jamaah->tanggal_lahir)->translatedFormat('d F Y') }}</td></tr>
    <tr><td>No. KTP</td><td>: {{ $jamaah->nik }}</td></tr>
    <tr><td>Alamat</td><td>: {{ $jamaah->alamat }}</td></tr>
    <tr><td>No. HP/WA</td><td>: {{ $jamaah->no_hp }}</td></tr>
    </table>
    
    <br>
    
    Dengan ini menyatakan dengan sebenar-benarnya dan penuh kesadaran bahwa saya:
    
    <ol>
        <li>Semula terdaftar sebagai jamaah Yang menabung di Umroh di PT.Safarindo Albarokah,-.</li>
        <li>Dengan ini mengajukan Perpindah tabungan ke paket Umroh, yaitu menjadi {{$paket->nama_paket.' Rp.'.number_format($paket->harga_paket)}}</li>
        <li>Perpindahan paket ini berlaku untuk Keberangkatan Umroh Akbar bulan {{$tanggalpergi}}</li>
        <li>Saya telah memahami seluruh fasilitas, layanan, serta ketentuan yang berlaku pada Paket {{$paket->nama_paket}}, dan tidak akan menuntut di kemudian hari atas keputusan perpindahan tabungan  ke  paket ini.</li>
        <li>Total tabungan saat ini sebesar <b>Rp {{ number_format($totalTabungan) }}</b>.</li>
    </ol>
    
    <br>
    
    Demikian surat pernyataan ini saya buat dengan sebenarnya, tanpa ada paksaan dari pihak manapun, untuk dapat dipergunakan sebagaimana mestinya.
    
    <br><br>
    
    Atas perhatian dan kerja sama PT. Safarindo Albarokah, saya ucapkan terima kasih.
    
    <br><br><br>
    
    <table width="100%">
    <tr>
        <td></td>
        <td class="text-center">
            Medan, {{ $tanggal }} <br><br><br><br>
            <span style="font-size:8px">materai 10.000</span>
            <br><br><br><br>
            ( {{ $jamaah->nama_jamaah }} )
        </td>
    </tr>
    </table>