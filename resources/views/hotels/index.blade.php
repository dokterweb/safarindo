@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-lg-4">
            <form class="card" method="POST" action="{{route('hotels.store')}}">
              @csrf
              <div class="card-header bg-red-lt">
                <h3 class="card-title">Input Data</h3>
              </div>
              <div class="card-body">
                <div class="row row-cards">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Nama hotel</label>
                    <input type="text" name="nama_hotel" class="form-control" value="{{ old('nama_hotel') }}">
                    @error('nama_hotel')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Lokasi Hotel</label>
                    <select name="lokasi_hotel" class="form-select">
                        <option value="mekkah" {{ old('lokasi_hotel') == 'mekkah' ? 'selected' : '' }}>mekkah</option>
                        <option value="madinah" {{ old('lokasi_hotel') == 'madinah' ? 'selected' : '' }}>madinah</option>
                        <option value="jeddah" {{ old('lokasi_hotel') == 'jeddah' ? 'selected' : '' }}>jeddah</option>
                        <option value="transit" {{ old('lokasi_hotel') == 'transit' ? 'selected' : '' }}>transit</option>
                    </select>
                    @error('lokasi_hotel')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Kontak Hotel</label>
                    <input type="text" name="kontak_hotel" class="form-control" value="{{ old('kontak_hotel') }}">
                    @error('kontak_hotel')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Email Hotel</label>
                    <input type="text" name="email_hotel" class="form-control" value="{{ old('email_hotel') }}">
                    @error('email_hotel')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Rating Hotel</label>
                    <select name="rating_hotel" class="form-select">
                        <option value="bintang 5" {{ old('rating_hotel') == 'bintang 5' ? 'selected' : '' }}>bintang 5</option>
                        <option value="bintang 4" {{ old('rating_hotel') == 'bintang 4' ? 'selected' : '' }}>bintang 4</option>
                        <option value="bintang 3" {{ old('rating_hotel') == 'bintang 3' ? 'selected' : '' }}>bintang 3</option>
                        <option value="bintang 2" {{ old('rating_hotel') == 'bintang 2' ? 'selected' : '' }}>bintang 2</option>
                        <option value="bintang 1" {{ old('rating_hotel') == 'bintang 1' ? 'selected' : '' }}>bintang 1</option>
                    </select>
                    @error('rating_hotel')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Hotel</label>
                    <input type="number" name="harga_hotel" class="form-control" value="{{ old('harga_hotel') }}">
                    @error('harga_hotel')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Catatan Keberangkatan</label>
                    <input type="text" name="catatan_hotel" class="form-control" value="{{ old('catatan_hotel') }}">
                    @error('catatan_hotel')
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
              <div class="table-responsive">
                <table id="mytable" class="table table-vcenter card-table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama Hotel</th>
                      <th>Lokasi Hotel </th>
                      <th>Kontak/th>
                      <th>Harga Tiket</th>
                      <th class="w-1">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($hotels as $m)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$m->nama_hotel}} </td>
                                <td>{{$m->lokasi_hotel}} </td>
                                <td>{{$m->kontak_hotel}} </td>
                                <td>{{number_format($m->harga_hotel)}} </td>
                                <td class="d-flex align-items-center">
                                 <a href="{{route('hotels.edit',$m->id)}}" class="btn btn-sm btn-info w-64 btn-icon"><i class="far fa-edit"></i></a>
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