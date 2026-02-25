@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-lg-12">
            <div class="card">
            <form action="{{ route('pakets.update',$paket->id) }}" method="POST" enctype="multipart/form-data"  class="card">
                @csrf
                @method('PUT')
                <div class="card-header bg-cyan">
                    <h3 class="card-title">Update Paket</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Nama Paket</label>
                            <input type="text" name="nama_paket" class="form-control" value="{{ $paket->nama_paket}}">
                            @error('nama_paket')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tanggal Berangkat</label>
                            <input type="date" name="tgl_berangkat" class="form-control" value="{{ $paket->tgl_berangkat}}">
                            @error('tgl_berangkat')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jumlah Hari</label>
                            <input type="text" name="jlh_hari" class="form-control" value="{{ $paket->jlh_hari}}">
                            @error('jlh_hari')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ $paket->status == 'active' ? 'selected' : '' }}>active</option>
                                <option value="completed" {{ $paket->status == 'completed' ? 'selected' : '' }}>completed</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Maskapai</label>
                            <select name="maskapai_id" id="maskapai_id" class="form-select" required>
                                <option value="">Pilih Hotel</option>
                                @foreach ($maskapais as $maskapai)
                                <option value="{{ $maskapai->id }}"{{ $paket->maskapai_id == $maskapai->id ? 'selected' : ''; }}>
                                    {{ $maskapai->nama_maskapai }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Rute</label>
                            <select name="rute" class="form-select">
                                <option value="direct" {{ $paket->rute == 'direct' ? 'selected' : '' }}>direct</option>
                                <option value="transit" {{ $paket->rute == 'transit' ? 'selected' : '' }}>transit</option>
                            </select>
                            @error('rute')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Lokasi Berangkat</label>
                            <input type="text" name="lokasi_berangkat" class="form-control" value="{{ $paket->lokasi_berangkat}}">
                            @error('lokasi_berangkat')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Kuota</label>
                            <input type="number" name="kuota" class="form-control" value="{{ $paket->kuota}}">
                            @error('kuota')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jenis Paket</label>
                            <input type="text" name="jenis_paket" class="form-control" value="{{ $paket->jenis_paket}}">
                            @error('jenis_paket')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Hotel Mekah</label>
                            <select name="hotel_makah_id" id="hotel_makah_id" class="form-select" required>
                                <option value="">Pilih Hotel</option>
                                @foreach ($hotelmakahs as $makah)
                                    <option value="{{ $makah->id }}" {{ $paket->hotel_makah_id == $makah->id ? 'selected' : '' }}>{{ $makah->nama_hotel }}</option>
                                @endforeach
                               
                            </select>   
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Hotel Madinah</label>
                            <select name="hotel_madinah_id" id="hotel_madinah_id" class="form-select" required>
                                <option value="">Pilih Hotel</option>
                                @foreach ($hotelmadinahs as $madinah)
                                    <option value="{{ $madinah->id }}" {{  $paket->hotel_madinah_id == $madinah->id ? 'selected' : '' }}>{{ $madinah->nama_hotel }}</option>
                                @endforeach
                            </select>   
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Hotel Transit</label>
                            <select name="hotel_transit_id" id="hotel_transit_id" class="form-select" required>
                                <option value="">Pilih Hotel</option>
                                @foreach ($hoteltransits as $transit)
                                    <option value="{{ $transit->id }}" {{ $paket->hotel_transit_id == $transit->id ? 'selected' : '' }}>{{ $transit->nama_hotel }}</option>
                                @endforeach
                            </select>   
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Paket</label>
                            <input type="number" name="harga_paket" class="form-control" value="{{ $paket->harga_paket}}">
                            @error('harga_paket')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Foto Paket</label>
                            <input type="file" name="foto_paket" class="form-control">
                            @error('foto_paket')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Termasuk Paket (Include)</label>
                            <textarea rows="5" name="include_desc" class="form-control">{{ $paket->include_desc}}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tidak Termasuk Paket (Exclude)</label>
                            <textarea rows="5" name="exclude_desc" class="form-control">{{ $paket->exclude_desc}}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Syarat dan Ketentuan (Term & Condition)</label>
                            <textarea rows="5" name="syaratketentuan" class="form-control">{{ $paket->syaratketentuan}}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Catatan Paket (Notes)</label>
                            <textarea rows="5" name="catatan" class="form-control">{{ $paket->catatan}}</textarea>
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