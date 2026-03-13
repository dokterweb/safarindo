@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-lg-4">
            <form class="card" method="POST" action="{{route('pengeluaranbulanantrxs.store')}}">
              @csrf
              <div class="card-header bg-red-lt">
                <h3 class="card-title">Input Data</h3>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <select name="pengeluaran_id" id="pengeluaran_id" class="form-select" required>
                    <option value="">Pilih Pengeluaran</option>
                    @foreach ($databulanan as $d)
                      <option value="{{ $d->id }}" {{ old('pengeluaran_id') == $d->id ? 'selected' : '' }}>
                        {{ $d->nama_pengeluaran }}
                      </option>
                    @endforeach
                  </select>   
                </div>
                <div class="mb-3">
                  <label class="form-label">Jumlah</label>
                  <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah') }}">
                  @error('jumlah')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="mb-3">
                  <label class="form-label">keterangan</label>
                  <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}">
                  @error('keterangan')
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
                <h3 class="card-title">Daa Pengeluaran</h3>
              </div>
              <div class="table-responsive">
                <table id="mytable" class="table table-vcenter card-table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama Pengeluaran</th>
                      <th>Jumlah </th>
                      <th>Keterangan</th>
                      <th class="w-1">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($pengeluaranbulanantrxs as $m)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$m->pengeluaran->nama_pengeluaran}} </td>
                                <td>{{$m->jumlah}} </td>
                                <td>{{$m->keterangan}} </td>
                                <td class="d-flex align-items-center">
                                  <a href="{{route('pengeluaranbulanantrxs.edit',$m->id)}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i></a>
                                  <form method="POST" action="{{ route('pengeluaranbulanantrxs.destroy',$m->id) }}" id="delete-form-{{ $m->id }}"> 
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