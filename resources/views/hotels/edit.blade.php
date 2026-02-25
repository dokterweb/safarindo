@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-lg-5">
            <form class="card" method="POST" action="{{route('hotels.update',$hotel->id)}}">
              @csrf
              @method('PUT')
              <div class="card-header bg-red-lt">
                <h3 class="card-title">Edit Data</h3>
              </div>
              <div class="card-body">
                <div class="row row-cards">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Nama hotel</label>
                    <input type="text" name="nama_hotel" class="form-control" value="{{ $hotel->nama_hotel }}">
                    @error('nama_hotel')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Lokasi Hotel</label>
                    <select name="lokasi_hotel" class="form-select">
                        <option option value="mekkah" {{ $hotel->lokasi_hotel == 'mekkah' ? 'selected' : '' }}>mekkah</option>
                        <option option value="madinah" {{ $hotel->lokasi_hotel == 'madinah' ? 'selected' : '' }}>madinah</option>
                        <option option value="jeddah" {{ $hotel->lokasi_hotel == 'jeddah' ? 'selected' : '' }}>jeddah</option>
                        <option option value="transit" {{ $hotel->lokasi_hotel == 'transit' ? 'selected' : '' }}>transit</option>
                    </select>
                    @error('lokasi_hotel')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Kontak Hotel</label>
                    <input type="text" name="kontak_hotel" class="form-control" value="{{ $hotel->kontak_hotel }}">
                    @error('kontak_hotel')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Email Hotel</label>
                    <input type="text" name="email_hotel" class="form-control" value="{{ $hotel->email_hotel }}">
                    @error('email_hotel')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Rating Hotel</label>
                    <select name="rating_hotel" class="form-select">
                        <option option value="bintang 5" {{ $hotel->rating_hotel == 'bintang 5' ? 'selected' : '' }}>bintang 5</option>
                        <option option value="bintang 4" {{ $hotel->rating_hotel == 'bintang 4' ? 'selected' : '' }}>bintang 4</option>
                        <option option value="bintang 3" {{ $hotel->rating_hotel == 'bintang 3' ? 'selected' : '' }}>bintang 3</option>
                        <option option value="bintang 2" {{ $hotel->rating_hotel == 'bintang 2' ? 'selected' : '' }}>bintang 2</option>
                        <option option value="bintang 1" {{ $hotel->rating_hotel == 'bintang 1' ? 'selected' : '' }}>bintang 1</option>
                    </select>
                    @error('rating_hotel')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Hotel</label>
                    <input type="number" name="harga_hotel" class="form-control" value="{{ $hotel->harga_hotel }}">
                    @error('harga_hotel')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Catatan Keberangkatan</label>
                    <input type="text" name="catatan_hotel" class="form-control"value="{{ $hotel->catatan_hotel }}">
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

          <div class="col-lg-7">
            <div class="card">
              <div class="card-header bg-blue-lt">
                <h3 class="card-title">Data hotel</h3>
              </div>
              <div class="table-responsive">
                <table id="mytable" class="table table-vcenter card-table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama hotel</th>
                      <th>Rute </th>
                      <th>Lama Perjalanan</th>
                      <th>Harga Tiket</th>
                      <th class="w-1">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($hotelview as $m)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$m->nama_hotel}} </td>
                                <td>{{$m->rute_terbang}} </td>
                                <td>{{$m->lama_perjalanan.' Jam'}} </td>
                                <td>{{number_format($m->harga_tiket)}} </td>
                                <td class="d-flex align-items-center">
                                 <a href="{{route('hotels.edit',$m->id)}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>
                                   {{--  <form method="POST" action="{{ route('hotels.destroy', $m->id) }}" id="delete-form-{{ $m->id }}"> 
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