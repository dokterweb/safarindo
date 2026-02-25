@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-lg-4">
            <form class="card" method="POST" action="{{route('maskapais.store')}}">
              @csrf
              <div class="card-header bg-red-lt">
                <h3 class="card-title">Input Data</h3>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label">Nama Maskapai</label>
                  <input type="text" name="nama_maskapai" class="form-control" value="{{ old('nama_maskapai') }}">
                  @error('nama_maskapai')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="mb-3">
                  <label>Kelamin</label>
                  <select name="rute_terbang" class="form-select">
                      <option value="direct" {{ old('rute_terbang') == 'direct' ? 'selected' : '' }}>direct</option>
                      <option value="transit" {{ old('rute_terbang') == 'transit' ? 'selected' : '' }}>transit</option>
                  </select>
                  @error('rute_terbang')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="mb-3">
                  <label class="form-label">Lama Perjalanan (jam)</label>
                  <input type="number" name="lama_perjalanan" class="form-control" value="{{ old('lama_perjalanan') }}">
                  @error('lama_perjalanan')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="mb-3">
                  <label class="form-label">Harga Tiket</label>
                  <input type="number" name="harga_tiket" class="form-control" value="{{ old('harga_tiket') }}">
                  @error('harga_tiket')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="mb-3">
                  <label class="form-label">Catatan Keberangkatan</label>
                  <input type="text" name="catatan_keberangkatan" class="form-control" value="{{ old('catatan_keberangkatan') }}">
                  @error('catatan_keberangkatan')
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
                <h3 class="card-title">Data Maskapai</h3>
              </div>
              <div class="table-responsive">
                <table id="mytable" class="table table-vcenter card-table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama Maskapai</th>
                      <th>Rute </th>
                      <th>Lama Perjalanan</th>
                      <th>Harga Tiket</th>
                      <th class="w-1">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($maskapais as $m)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$m->nama_maskapai}} </td>
                                <td>{{$m->rute_terbang}} </td>
                                <td>{{$m->lama_perjalanan.' Jam'}} </td>
                                <td>{{number_format($m->harga_tiket)}} </td>
                                <td class="d-flex align-items-center">
                                 <a href="{{route('maskapais.edit',$m->id)}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>
                                    <form method="POST" action="{{ route('maskapais.destroy', $m->id) }}" id="delete-form-{{ $m->id }}"> 
                                      @csrf
                                      @method('DELETE')
                                      <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation({{ $m->id }})">
                                          <i class="fas fa-trash-alt"></i>
                                      </button>
                                    </form>
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