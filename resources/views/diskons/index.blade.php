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
                    <th>Paket</th>
                    <th>Harga Paket</th>
                    <th>Diskon</th>
                    <th>Status</th>
                    <th class="w-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($diskons as $d)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{ $d->jamaah->nama_jamaah }}</td>
                        <td>{{ $d->paket->nama_paket }}</td>
                        <td>{{ number_format($d->paket->harga_paket) }}</td>
                        <td>{{ number_format($d->jumlah_diskon) }}</td>
            
                        <td>
                            @if($d->status == '0')
                                <span class="text-warning">Pending</span>
                            @else
                                <span class="text-success">Approved</span>
                            @endif
                        </td>
            
                        <td>
                            @if($d->status == '0')
                                <form action="{{ route('diskons.approve', $d->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Approve</button>
                                </form>
                            @else
                                <span>-</span>
                            @endif
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