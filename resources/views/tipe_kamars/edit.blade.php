@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-lg-4">
            <form class="card" method="POST" action="{{route('kamars.update',$tipe_kamar->id)}}">
              @csrf
              @method('PUT')
              <div class="card-header bg-red-lt">
                <h3 class="card-title">Edit Data</h3>
              </div>
              <div class="card-body">
                <div class="row row-cards">
                  <div class="mb-3">
                    <label class="form-label">Nama hotel</label>
                    <input type="text" name="nama_kamar" class="form-control" value="{{ $tipe_kamar->nama_kamar }}">
                    @error('nama_kamar')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Harga Kamar</label>
                    <input type="number" name="harga_kamar" class="form-control" value="{{ $tipe_kamar->harga_kamar }}">
                    @error('harga_kamar')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
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
                <h3 class="card-title">Data Kamar</h3>
              </div>
              <div class="table-responsive">
                <table id="mytable" class="table table-vcenter card-table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama Kamar</th>
                      <th>Harga Kamar</th>
                      <th class="w-1">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($tipe_kamarview as $m)
                      <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{$m->nama_kamar}} </td>
                          <td>{{number_format($m->harga_kamar)}} </td>
                          <td>
                            <div class="btn-list flex-nowrap">
                              <a href="{{route('kamars.edit',$m->id)}}" class="btn btn-sm btn-info btn-icon"><i class="far fa-edit"></i></a>
                              <form method="POST" action="{{ route('kamars.destroy', $m->id) }}" id="delete-form-{{ $m->id }}"> 
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation({{ $m->id }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                              </form>
                            </div>
                          </td>
                      </tr>
                    @empty
                      <tr>
                          <td colspan="4">No Data</td>
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