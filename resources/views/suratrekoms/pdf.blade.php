<style>
    body {
        font-family: serif;
        font-size: 12px;
        line-height: 1.8;
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
        <td width="70%">
            Nomor &nbsp;&nbsp;&nbsp;&nbsp;: {{ $suratrekom->no_surat }} <br>
            Perihal &nbsp;&nbsp;&nbsp;&nbsp;: Surat Permohonan Pembuatan / <br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Perpanjangan Paspor / Endorse <br>
            Lampiran : 1 Berkas
        </td>
        <td class="text-right">
            Medan, {{ $tanggalSurat }}
        </td>
    </tr>
</table>

<br>

Kepada Yth : <br>
<b>{{ strtoupper($suratrekom->kantor_imigrasi) }}</b><br>
{{($suratrekom->alamat_imigrasi) }}
{{-- <small>Alamat kantor imigrasi</small> --}}

<br><br>

Assalamu’alaikum, Wr, Wb.

<br><br>

&nbsp;&nbsp;&nbsp;&nbsp;Salam silaturrahmi kami haturkan kepada Bapak/Ibu Pimpinan, semoga dalam menjalankan tugas dan aktivitas sehari-hari senantiasa diberikan kesehatan dan keberkahan oleh Allah SWT, Aamiin.

<br><br>

&nbsp;&nbsp;&nbsp;&nbsp;Kami yang bertanda tangan di bawah ini atas nama PT. SAFARINDO ALBAROKAH UMROH mengajukan permohonan Pembuatan / Perpanjangan Paspor / Endorse untuk berangkat melaksanakan perjalanan ibadah umroh bersama kami yaitu atas nama :

<br><br>

<table>
    <tr>
        <td width="100">Nama</td>
        <td>: <span class="bold">{{ strtoupper($jamaah->nama_jamaah) }}</span></td>
    </tr>
</table>

<br><br>

&nbsp;&nbsp;&nbsp;&nbsp;Demikian surat ini dapat kami buat dengan yang sebenar-benarnya, atas terkabulnya permohonan ini kami ucapkan terima kasih.

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