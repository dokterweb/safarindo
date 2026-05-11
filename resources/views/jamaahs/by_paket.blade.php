@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
          <div class="row g-2 align-items-center">
            <div class="col">
              <h2 class="page-title">
                Data Jamaah
              </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
              <div class="btn-list">
                <a href="{{route('pakets.jamaah.create', $paket->id)}}" class="btn btn-primary d-none d-sm-inline-block">Tambah Jamaah</a>
              </div>
            </div>
          </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-md-8">
            <div class="card">
              <div class="card-body">
                <dl class="row">
                  <dt class="col-5">Nama Paket:</dt>
                  <dd class="col-7">{{$paket->nama_paket}}</dd>
                  <dt class="col-5">Tgl Berangkat:</dt>
                  <dd class="col-7">{{$paket->tgl_berangkat}}</dd>
                  <dt class="col-5">Jumlah Hari:</dt>
                  <dd class="col-7">{{$paket->jlh_hari}}</dd>
                  <dt class="col-5">Status:</dt>
                  <dd class="col-7">{{$paket->status}}</dd>
                  <dt class="col-5">Maskapai</dt>
                  <dd class="col-7">{{$paket->maskapai->nama_maskapai}}</dd>
                  <dt class="col-5">Rute:</dt>
                  <dd class="col-7">{{$paket->rute}}</dd>
                  <dt class="col-5">Lokasi Berangkat:</dt>
                  <dd class="col-7">{{$paket->lokasi_berangkat}}</dd>
                  <dt class="col-5">Kuota:</dt>
                  <dd class="col-7">{{$paket->kuota}}</dd>
                  <dt class="col-5">Jenis Paket:</dt>
                  <dd class="col-7">{{$paket->jenis_paket}}</dd>
                  <dt class="col-5">Hotel Mekah:</dt>
                  <dd class="col-7">{{$paket->hotelMakah->nama_hotel}}</dd>
                  <dt class="col-5">Hotel Madinah:</dt>
                  <dd class="col-7">{{$paket->hotelMadinah->nama_hotel}}</dd>
                  <dt class="col-5">Hotel Transit:</dt>
                  <dd class="col-7">{{$paket->hotelTransit->nama_hotel}}</dd>
                  <dt class="col-5">Harga Paket:</dt>
                  <dd class="col-7">{{$paket->harga_paket}}</dd>
                </dl>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                @if($paket->foto_paket)
                    <img src="{{ asset('storage/'.$paket->foto_paket) }}" width="200">
                @endif    
              </div>
            </div>
          </div>
        </div>
        <div class="row row-cards mt-2">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <dl class="row">
                  <dt class="col-2">Include Desc</dt>
                  <dd class="col-10">{{$paket->include_desc}}</dd>
                  <dt class="col-2">Include Desc</dt>
                  <dd class="col-10">{{$paket->include_desc}}</dd>
                  <dt class="col-2">Exclude Desc</dt>
                  <dd class="col-10">{{$paket->exclude_desc}}</dd>
                  <dt class="col-2">Syarat dan Ketentuan</dt>
                  <dd class="col-10">{{$paket->syaratketentuan}}</dd>
                  <dt class="col-2">Catatan</dt>
                  <dd class="col-10">{{$paket->catatan}}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="row row-cards mt-2">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive p-3">
                  <table id="mytable" class="table table-vcenter card-table">
                  <thead>
                      <tr>
                      <th>#</th>
                      <th>Nama Jamaah</th>
                      <th>No HP</th>
                      <th>Kota</th>
                      <th class="w-1">Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      @forelse ($paket->jamaahs as $p)
                          <tr>
                              <td>{{$loop->iteration}}</td>
                              <td>{{$p->nama_jamaah }} </td>
                              <td>{{$p->no_hp }} </td>
                              <td>{{$p->kota }} </td>
                              <td class="d-flex align-items-center" style="gap: 5px;">
                                <a href="{{ route('jamaahs.show', $p->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <a href="{{ route('jamaahs.edit', $p->id) }}" class="btn btn-sm btn-warning">Edit</a>
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