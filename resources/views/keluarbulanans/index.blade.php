@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   <!-- Page header -->
   <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <h2 class="page-title">
            Pengeluaran
          </h2>
        </div>
      </div>
    </div>
  </div>
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-lg-4">
            <form class="card" method="POST" action="{{route('pengeluaranbulanans.store')}}">
              @csrf
              <div class="card-header bg-red-lt">
                <h3 class="card-title">Input Data</h3>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label">Nama Pengeluaran</label>
                  <input type="text" name="nama_pengeluaran" class="form-control" value="{{ old('nama_pengeluaran') }}">
                  @error('nama_pengeluaran')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>

          <div class="col-lg-8">
            <div class="card">
              <div class="card-header bg-blue-lt">
                <h3 class="card-title">Data Pengeluaran</h3>
              </div>
              <div class="table-responsive">
                <table id="mytable" class="table table-vcenter card-table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama Pengeluaran</th>
                      <th class="w-1">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($keluarbulanans as $k)
                      <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$k->nama_pengeluaran}}</td>
                        <td class="d-flex align-items-center">
                          <a href="{{route('pengeluaranbulanans.edit',$k->id)}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i>Edit</a>
                          <form method="POST" action="{{ route('pengeluaranbulanans.destroy', $k->id) }}" id="delete-form-{{ $k->id }}"> 
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation({{ $k->id }})">
                              <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                          </form>
                        </td>
                      </tr>
                    @empty
                    <tr>
                        <td colspan="3">No Data</td>
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