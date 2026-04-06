@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row justify-content-center">
          <div class="col-8">
            <div class="card">
            <form action="{{ route('produks.update', $produk->id)}}" method="POST" enctype="multipart/form-data"  class="card">
                @csrf
                @method('PUT')
                <div class="card-header bg-cyan">
                    <h3 class="card-title">Tambah Produk</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" value="{{$produk->nama_produk}}">
                            @error('nama_produk')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Standar Stok</label>
                            <input type="number" name="standar_stok" class="form-control" value="{{$produk->standar_stok}}">
                            @error('standar_stok')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Aktual Stok</label>
                            <input type="number" name="stok" class="form-control" value="{{$produk->stok}}">
                            @error('stok')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Unit</label>
                            <select name="unit_id" class="form-select">
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}" {{ $produk->unit_id == $u->id ? 'selected' : '' }}>
                                        {{ $u->nama_unit }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Harga Beli</label>
                            <input type="number" name="harga_beli" class="form-control" value="{{$produk->harga_beli}}">
                            @error('harga_beli')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>  <div class="col-md-4 mb-3">
                            <label class="form-label">Harga Jual</label>
                            <input type="number" name="harga_jual" class="form-control" value="{{$produk->harga_jual}}">
                            @error('harga_jual')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">catatan</label>
                            <input type="text" name="catatan" class="form-control" value="{{$produk->catatan}}">
                            @error('catatan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Foto Produk</label><br>
                        
                            @if($produk->foto_produk)
                                <span class="avatar avatar-xl"
                                      style="background-image: url('{{ asset('storage/'.$produk->foto_produk) }}')">
                                </span>
                            @endif
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">foto_produk</label>
                            <input type="file" name="foto_produk" class="form-control" value="{{ old('foto_produk') }}">
                            @error('foto_produk')
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