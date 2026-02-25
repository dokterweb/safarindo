@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
          <div class="row g-2 align-items-center">
            <div class="col">
              <h2 class="page-title">
                Data Paket
              </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
              <div class="btn-list">
                <a href="{{route('pakets.create')}}" class="btn btn-primary d-none d-sm-inline-block">Create new Agent</a>
              </div>
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
                    <th>Nama Paket</th>
                    <th>Tgl Berangkat</th>
                    <th>Jlh Hari</th>
                    <th>Maskapai</th>
                    <th>Rute</th>
                    <th>Lokasi Berangkat</th>
                    <th>Harga Paket</th>
                    <th>Kuota</th>
                    <th class="w-1">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pakets as $p)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$p->nama_paket}} </td>
                            <td>{{$p->tgl_berangkat}} </td>
                            <td>{{$p->jlh_hari}} </td>
                            <td>{{$p->maskapai_id}} </td>
                            <td>{{$p->rute}} </td>
                            <td>{{$p->lokasi_berangkat}} </td>
                            <td>{{$p->harga_paket}} </td>
                            <td>{{$p->kuota}} </td>
                            <td class="d-flex align-items-center" style="gap: 5px;">
                              <a href="{{route('pakets.edit',$p->id)}}" class="btn btn-sm btn-info">Edit</a>
                              <form method="POST" action="{{ route('pakets.destroy', $p->id) }}" style="display: inline;" id="delete-form-{{ $p->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation({{ $p->id }})">Del</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="10">No Data</td>
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