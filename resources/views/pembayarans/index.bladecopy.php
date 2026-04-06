@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-lg-12">
            <div class="card">
              
              <div class="table-responsive p-3">
                <table id="mytable" class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Jamaah</th>
                        <th>No HP</th>
                        <th>Tagihan</th>
                        <th>Telah Dibayar</th>
                        <th>Sisa</th>
                        <th class="w-1">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jamaahs as $j)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{ $j->nama_jamaah }}</td>
                        <td>{{ $j->no_hp }}</td>
                        <td>{{ number_format($j->paket->harga_paket) }}</td>
                        <td>{{ number_format($j->total_bayar ?? 0) }}</td>
                        <td>{{ number_format(($j->paket->harga_paket) - ($j->total_bayar ?? 0)) }}</td>
                        <td>
                            <a href="{{ route('pembayarans.detail', $j->id) }}" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
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