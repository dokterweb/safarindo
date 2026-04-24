<style>
     body, table, tr, td, p, span, div {
        font-family: sans-serif;
        font-size: 12px !important;
        line-height: 1.8;
    }

    b, strong {
        font-size: 12px !important;
        font-weight: bold;
    }
    
    .text-center { text-align: center; }
    .text-end { text-align: right; }
    .fw-bold { font-weight: bold; }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 3px 0;
        vertical-align: top;
    }
</style>

<!-- HEADER -->
<table>
    <tr>
        <td width="50%">
            Nomor &nbsp;&nbsp;&nbsp;: {{ $suratrekom->no_surat }} <br>
            Perihal &nbsp;&nbsp;: <b>Permohonan Pembuatan Paspor</b>
        </td>
        <td class="text-end">
            Sumatera Utara, {{ $tanggalSurat }}
        </td>
    </tr>
</table>

<br>

Kepada Yth : <br>
<b>{{ strtoupper($suratrekom->kantor_imigrasi) }}</b><br>
Di Tempat

<br><br>

Assalamualaikum wr. wb.,

<br><br>

Segala puji dan syukur kita panjatkan kehadirat Allah subhanahu wata’ala, atas rahmat dan karunia-Nya
untuk kita yang begitu banyak. Bersama dengan surat ini, kami bermaksud menyampaikan surat pengantar
pembuatan paspor untuk jamaah umroh perusahaan travel kami atas nama :

<br><br>

<table>
    <tr>
        <td width="150">NIK</td>
        <td>: {{ $jamaah->nik }}</td>
    </tr>
    <tr>
        <td>Nama</td>
        <td>: {{ $jamaah->nama_jamaah }}</td>
    </tr>
    <tr>
        <td>Tempat Tgl Lahir</td>
        <td>: {{ $jamaah->tempat_lahir }}, {{ \Carbon\Carbon::parse($jamaah->tanggal_lahir)->format('d F Y') }}</td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>: {{ $jamaah->alamat }}</td>
    </tr>
    <tr>
        <td>Keberangkatan</td>
        <td>: {{ \Carbon\Carbon::parse($paket->tgl_berangkat)->format('d F Y') }}</td>
    </tr>
</table>

<br>

Dengan ini kami menyatakan bahwa nama tersebut di atas adalah benar calon jamaah umroh travel kami
dari perusahaan travel kami, yang terdaftar secara resmi yaitu :

<br><br>

<table>
    <tr>
        <td width="150">Travel</td>
        <td>: SAFARINDO ALBAROKAH UMROH</td>
    </tr>
    <tr>
        <td>Izin PPIU</td>
        <td>: (isi izin disini jika ada)</td>
    </tr>
    <tr>
        <td>Kontak</td>
        <td>: 061-6614312 / 0812-3098-8862</td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>: Jl. Karya No.99, Karang Berombak, Medan</td>
    </tr>
</table>

<br>

Untuk itu kami mohon agar nama tersebut diatas bisa dibantu dalam proses pembuatan paspor.
Demikian surat rekomendasi ini kami buat dengan sebenar-benarnya dan dapat dipergunakan sebagaimana mestinya.

<br><br><br>

<table>
    <tr>
        <td width="50%">
            
        </td>
        <td class="text-center">
            Sumatera Utara, {{ $tanggalSurat }} <br><br><br><br>

            <b>SAFARINDO ALBAROKAH UMROH</b>
        </td>
    </tr>
</table>