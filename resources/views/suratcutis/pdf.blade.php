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

<!-- HEADER -->
<table>
    <tr>
        <td width="70%"></td>
        <td class="text-right">
            Medan, {{ $tanggalSurat }}
        </td>
    </tr>
</table>
<table>
    <tr>
        <td>Nomor</td>
        <td>: {{ $suratcuti->no_surat }} </td>
    </tr>
    <tr>
        <td>Lamp </td>
        <td>: -</td>
    </tr>
    <tr>
        <td>Hal </td>
        <td>: :	Permohonan Cuti Umroh</td>
    </tr>
</table>
<br>

Kepada Yth : <br>
<b>{{ strtoupper($suratcuti->nama_kantor) }}</b><br>
{{($suratcuti->alamat_kantor) }}
di Tempat
<br><br>
Saya yang bertanda tangan di bawah ini.
<br>
<table>
    <tr>
        <td>Nama</td>
        <td>: Heru Dwi Arieftiya</td>
    </tr>
    <tr>
        <td>Jabatan </td>
        <td>: Direktur Utama PT. SAFARINDO ALBAROKAH UMROH</td>
    </tr>
</table>

<br>
Dengan ini menerangkan dengan sesungguhnya bahwa :


<table>
    <tr>
        <td width="30%">Nama</td>
        <td>: {{ $jamaah->nama_jamaah }}</td>
    </tr>
    <tr>
        <td>Jabatan </td>
        <td>: {{ $suratcuti->jabatan }}</td>
    </tr>
    <tr>
        <td>Tempat Bekerja  </td>
        <td>: {{ $suratcuti->nama_kantor }}</td>
    </tr>
    <tr>
        <td>Alamat Instansi </td>
        <td>: {{ $suratcuti->alamat_kantor }}</td>
    </tr>
</table><br>
Adalah benar salah seorang jamaah umrah PT. SAFARINDO ALBAROKAH UMROH yang InsyaAllah akan berangkat ke Negara Saudi Arabia guna untuk menjalankan Perjalanan Ibadah Umrah mulai dari tanggal {{ $tanggalpergi }} sampai dengan tanggal  {{ $tglkembali }}.

<br><br>

Demikian surat ini dapat kami buat dengan yang sebenar-benarnya, atas terkabulnya permohonan ini kami ucapkan terima kasih.

<br><br>

Wassalamu’alaikum, Wr, Wb

<br><br><br>

<table>
    <tr>
        
        <td class="text-center">
           Hormat Kami <br><br><br><br><br><br>

            <b><u>Heru Dwi Arieftiya</u></b><br>
            <b>Direktur Utama</b>
        </td>
        <td width="60%"></td>
    </tr>
</table>