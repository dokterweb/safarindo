@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
          <div class="row g-2 align-items-center">
            <div class="col">
              <h2 class="page-title">
                Data Penjualan
              </h2>
            </div>
          </div>
        </div>
      </div>
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
                        <th>ID</th>
                        <th>Tgl Pembelian</th>
                        <th>Nama Jamaah</th>
                        <th>Jumlah Produk</th>
                        <th>Total Harga</th>
                        <th class="w-1">Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      @forelse ($keluarproduks as $p)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{$p->id}} </td>
                          <td>{{$p->tanggal}} </td>
                          <td>{{$p->jamaah->nama_jamaah}} </td>
                          <td>{{ $p->details_count }}</td>
                          <td>Rp {{ number_format($p->details_sum_total, 0, ',', '.') }}</td>
                          <td>
                            <div class="btn-list flex-nowrap">
                              <a href="{{ route('keluarproduks.show', $p->id) }}" class="btn btn-sm btn-info">Detail</a>
                              <a href="{{ route('keluarproduks.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            </div>
                          </td>
                        </tr>
                      @empty
                      <tr>
                          <td colspan="7">No Data</td>
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