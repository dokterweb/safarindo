@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('agen.pendapatan') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="form-control">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-primary me-2">Filter</button>
                                <a href="{{ route('agen.pendapatan') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Pendapatan -->
            <div class="card">
                <div class="card-header bg-light">
                    <h3 class="card-title">Data Pendapatan Fee Agen</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Jamaah</th>
                                <th>No HP</th>
                                <th>Nama Paket</th>
                                <th>Harga Paket</th>
                                <th>Fee Agen</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jamaahs as $j)
                                @php
                                    $transaksi = $j->agentTransaksis
                                        ->where('agent_id', $agent->id)
                                        ->first();

                                    $status = $transaksi ? 'Disetujui' : 'Belum Disetujui';
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $j->nama_jamaah }}</td>
                                    <td>{{ $j->no_hp }}</td>
                                    <td>{{ $j->paket->nama_paket }}</td>
                                    <td>Rp {{ number_format($j->paket->harga_paket, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($agent->fee_agent, 0, ',', '.') }}</td>
                                    <td>
                                        @if($transaksi)
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Belum Disetujui</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ringkasan -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h3 class="card-title">Ringkasan Fee Agen</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Total Fee Disetujui</th>
                            <th class="text-success">
                                Rp {{ number_format($totalDisetujui, 0, ',', '.') }}
                            </th>
                        </tr>
                        <tr>
                            <th>Total Fee Belum Disetujui</th>
                            <th class="text-danger">
                                Rp {{ number_format($totalBelumDisetujui, 0, ',', '.') }}
                            </th>
                        </tr>
                    </table>
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