<style>
    body {
        font-family: serif;
        font-size: 12px;
    }

    table {
        width: 100%;
    }

    td {
        vertical-align: top;
        padding: 2px 0;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .bold {
        font-weight: bold;
    }
</style>

<div style="text-align:center; font-weight:bold;">
    SURAT KETERANGAN PINDAH PAKET UMROH
</div>

<br>

<div style="text-align:center;">
    {{ $pembatalan->no_surat }} 
</div>

<br><br>

Dengan Hormat dengan ini kami menerangkan bahwa:

<br><br>

<table>
    <tr>
        <td width="180">Nama Lengkap</td>
        <td>: {{ $jamaah->nama_jamaah }}</td>
    </tr>
    <tr>
        <td>Tempat/Tgl Lahir</td>
        <td>: {{ $jamaah->tempat_lahir }}, {{ \Carbon\Carbon::parse($jamaah->tanggal_lahir)->translatedFormat('d F Y') }}</td>
    </tr>
    <tr>
        <td>No. KTP</td>
        <td>: {{ $jamaah->nik }}</td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>: {{ $jamaah->alamat }}</td>
    </tr>
    <tr>
        <td>No. HP/WA</td>
        <td>: {{ $jamaah->no_hp }}</td>
    </tr>
</table>

<br>

Benar nama tersebut di atas menerangkan sebenar-benarnya dan penuh kesadaran bahwa:

<br><br>

<ol>
    <li>Semula terdaftar sebagai jamaah yang memilih paket Umroh keberangkatan bulan {{ $tglAwal }} di PT. Safarindo Albarokah.</li>
    <li>Dengan ini mengajukan Perpindahan Paket Umroh dengan harga 
        Rp {{ number_format($paketAwal->harga_paket) }} keberangkatan bulan 
        {{ $tglAwal }} ke paket Umroh dengan harga 
        Rp {{ number_format($paketBaru->harga_paket) }} keberangkatan bulan 
        {{ $tglBaru }} di PT. Safarindo Albarokah.</li>
    <li>Saya telah memahami dan menerima seluruh fasilitas, layanan, serta ketentuan
        yang berlaku pada paket Umroh yang saya pilih saat ini, dan tidak akan menuntut
        di kemudian hari atas keputusan perpindahan paket tersebut.</li>
  </ol> 
  <br>
Demikian surat keterangan ini dibuat dengan sebenar-benarnya, tanpa ada paksaan dari pihak manapun,
untuk dapat dipergunakan sebagaimana mestinya.

<br><br>

Atas perhatian dan kerjasamanya, pihak PT. Safarindo Albarokah mengucapkan terima kasih.

<br><br><br>

<table>
    <tr>
        <td class="text-center">
            Jamaah/Pemohon;<br><br><br><br>
            <span style="font-size: 8px;">Materai Rp. 10.000</span>
            <br><br><br><br><br>
            ( {{ $jamaah->nama_jamaah }} )
        </td>
        <td class="text-center">
            Medan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
            Mengetahui;<br><br><br><br><br><br>
            ( Heru Dwi Ariefitya )<br>
            Direktur
        </td>
    </tr>
</table>