@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-lg-12">
            <div class="card">
            <form action="{{route('pakets.jamaah.store',$paket->id)}}" method="POST" enctype="multipart/form-data"  class="card">
                @csrf
                <div class="card-header bg-yellow">
                    <h3 class="card-title">Data Jamaah</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Nama Jamaah</label>
                            <input type="text" name="nama_jamaah" class="form-control" value="{{ old('nama_jamaah') }}">
                            @error('nama_jamaah')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" maxlength="16" pattern="\d{16}" inputmode="numeric">
                            @error('nik')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">
                            @error('tempat_lahir')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
                            @error('tanggal_lahir')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Kelamin</label>
                            <select name="kelamin" class="form-select">
                                <option value="laki-laki" {{ old('kelamin') == 'laki-laki' ? 'selected' : '' }}>laki-laki</option>
                                <option value="perempuan" {{ old('kelamin') == 'perempuan' ? 'selected' : '' }}>perempuan</option>
                            </select>
                            @error('kelamin')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Kota</label>
                            <input type="text" name="kota" class="form-control" value="{{ old('kota') }}">
                            @error('kota')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">No. HP</label>
                            <input type="number" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                            @error('no_hp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Nama Agent</label>
                            <select name="agent_id" id="agent_id" class="form-select" required>
                                <option value="NULL">Tidak ada</option>
                                @foreach ($agents as $a)
                                    <option value="{{ $a->id }}" {{ old('agent_id') == $a->id ? 'selected' : '' }}>{{ $a->user->name }}</option>
                                @endforeach
                            </select>   
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">alamat</label>
                            <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}">
                            @error('alamat')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">catatan</label>
                            <input type="text" name="catatan" class="form-control" value="{{ old('catatan') }}">
                            @error('catatan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-header bg-lime">
                    <h3 class="card-title">Data di Pasport</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nama di Pasport</label>
                            <input type="text" name="nama_jamaah_pasport" class="form-control" value="{{ old('nama_jamaah_pasport') }}">
                            @error('nama_jamaah_pasport')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">No. Pasport</label>
                            <input type="text" name="no_pasport" class="form-control" value="{{ old('no_pasport') }}">
                            @error('no_pasport')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control" value="{{ old('penerbit') }}">
                            @error('penerbit')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                       <div class="col-md-4 mb-3">
                            <label class="form-label">Pasport Aktif</label>
                            <input type="date" name="pasport_aktif" class="form-control" value="{{ old('pasport_aktif') }}">
                            @error('pasport_aktif')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Pasport Expired</label>
                            <input type="date" name="pasport_expired" class="form-control" value="{{ old('pasport_expired') }}">
                            @error('pasport_expired')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Foto Jamaah</label>
                            <input type="file" name="foto_jamaah" class="form-control" value="{{ old('foto_jamaah') }}">
                            @error('foto_jamaah')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Foto KTP</label>
                            <input type="file" name="foto_ktp" class="form-control" value="{{ old('foto_ktp') }}">
                            @error('foto_ktp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Foto KK</label>
                            <input type="file" name="foto_kk" class="form-control" value="{{ old('foto_kk') }}">
                            @error('foto_kk')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Foto Pasport 1</label>
                            <input type="file" name="foto_pasport1" class="form-control" value="{{ old('foto_pasport1') }}">
                            @error('foto_pasport1')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Foto Pasport 2</label>
                            <input type="file" name="foto_pasport2" class="form-control" value="{{ old('foto_pasport2') }}">
                            @error('foto_pasport2')
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