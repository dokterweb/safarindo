@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h3 class="card-title"> Informasi Jamaah</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <dl class="row">
                                    <dt class="col-5">Nama Jamaah:</dt>
                                    <dd class="col-7">{{$jamaah->nama_jamaah}}</dd>
                                    <dt class="col-5">NIK:</dt>
                                    <dd class="col-7">{{$jamaah->nik}}</dd>
                                    <dt class="col-5">No.HP:</dt>
                                    <dd class="col-7">{{$jamaah->no_hp}}</dd>
                                    <dt class="col-5">Kelamin:</dt>
                                    <dd class="col-7">{{$jamaah->kelamin}}</dd>
                                    <dt class="col-5">Tempat/Tgl Lahir</dt>
                                    <dd class="col-7">{{$jamaah->tempat_lahir.' / '.$jamaah->tanggal_lahir}}</dd>
                                    <dt class="col-5">Alamat:</dt>
                                    <dd class="col-7">{{$jamaah->alamat}}</dd>
                                </dl>
                                <div class="hr-text"><h5>Data Passport</h5></div>
                                <dl class="row">
                                    <dt class="col-5">Nama Jamaah:</dt>
                                    <dd class="col-7">{{$jamaah->nama_jamaah_pasport}}</dd>
                                    <dt class="col-5">No. Pasport:</dt>
                                    <dd class="col-7">{{$jamaah->no_pasport}}</dd>
                                    <dt class="col-5">Penerbit:</dt>
                                    <dd class="col-7">{{$jamaah->penerbit}}</dd>
                                    <dt class="col-5">Pasport Aktif:</dt>
                                    <dd class="col-7">{{$jamaah->pasport_aktif}}</dd>
                                    <dt class="col-5">Pasport Expired:</dt>
                                    <dd class="col-7">{{$jamaah->pasport_expired}}</dd>
                                    <dt class="col-5">Agent:</dt>
                                    <dd class="col-7">{{$jamaah->agent->user->name}}</dd>
                                </dl>
                            </div>
                            <div class="col-4">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Nama Paket</th>
                                        <td>{{ $jamaah->paket->nama_paket }}</td>
                                    </tr>
                                    <tr>
                                        <th>Harga Paket</th>
                                        <td>Rp {{ number_format($jamaah->paket->harga_paket, 0, ',', '.') }}</td>
                                    </tr>
                                </table>
                                <div class="text-center">
                                    @if($jamaah->foto_ktp)
                                        <img src="{{ asset('storage/'.$jamaah->foto_ktp) }}" width="200">
                                    @endif      
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Ringkasan -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h3 class="card-title">Data Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Metode</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jamaah->pembayarans as $pembayaran)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pembayaran->created_at->format('d-m-Y') }}</td>
                                        <td>{{ ucfirst($pembayaran->metode_bayar) }}</td>
                                        <td>Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada pembayaran</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
            
                        <table class="table">
                            <tr>
                                <th>Total Tagihan</th>
                                <td>Rp {{ number_format($totalTagihan, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Total Bayar</th>
                                <td>Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Sisa Tagihan</th>
                                <td class="text-danger">
                                    Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(document).ready(function() {
    // Inisialisasi DataTables
    $('#mytable').DataTable({
        "processing": true,   // Menampilkan loading saat memproses data
        "serverSide": false,  // Tentukan apakah menggunakan server-side processing
        "paging": true,       // Menampilkan pagination
        "lengthChange": false // Menonaktifkan pengaturan jumlah baris per halaman
    });
});

    function deleteConfirmation(id) {
        // SweetAlert2 konfirmasi
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirimkan form untuk menghapus data jika dikonfirmasi
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection