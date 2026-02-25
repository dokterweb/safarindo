@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-lg-4">
            <form class="card" method="POST" action="{{route('suppliers.update',$supplier->id)}}">
              @csrf
              @method('PUT')
              <div class="card-header bg-red-lt">
                <h3 class="card-title">Edit Data</h3>
              </div>
              <div class="card-body">
                <div class="row row-cards">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Nama supplier</label>
                    <input type="text" name="nama_supplier" class="form-control" value="{{ $supplier->nama_supplier }}">
                    @error('nama_supplier')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">No. HP</label>
                    <input type="number" name="no_hp" class="form-control" value="{{ $supplier->no_hp }}">
                    @error('no_hp')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $supplier->email }}">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Kota</label>
                    <input type="text" name="kota" class="form-control" value="{{ $supplier->kota }}">
                    @error('kota')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label class="form-label">alamat</label>
                    <input type="text" name="alamat" class="form-control" value="{{ $supplier->alamat }}">
                    @error('alamat')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="mb-3">
                      <label class="form-label">catatan</label>
                      <input type="text" name="catatan" class="form-control" value="{{ $supplier->catatan }}">
                      @error('catatan')
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
                <h3 class="card-title">Data hotel</h3>
              </div>
              <div class="table-responsive p-3">
                <table id="mytable" class="table table-vcenter card-table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama supplier</th>
                      <th>Kontak</th>
                      <th>Kalamin</th>
                      <th>Kota</th>
                      <th class="w-1">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($supplierview as $m)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$m->nama_supplier}} </td>
                                <td>{{$m->no_hp}} </td>
                                <td>{{$m->kelamin}} </td>
                                <td>{{$m->kota}} </td>
                                <td class="d-flex align-items-center">
                                 <a href="{{route('suppliers.edit',$m->id)}}" class="btn btn-sm btn-info w-64 btn-icon"><i class="far fa-edit"></i></a>
                                    {{-- <form method="POST" action="{{ route('hotels.destroy', $m->id) }}" id="delete-form-{{ $m->id }}"> 
                                      @csrf
                                      @method('DELETE')
                                      <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation({{ $m->id }})">
                                          <i class="fas fa-trash-alt"></i>
                                      </button>
                                    </form> --}}
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="6">No Data</td>
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