@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <h2 class="page-title">
              Welcome {{ Auth::user()->name }}
            </h2>
          </div>
          <!-- Page title actions -->
          <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
              <div id="real-date" style="font-size: 1.2rem;">
                  {{ date('l, d F Y') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-deck row-cards">
          <div class="col-12">
            <div class="row row-cards">
              <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-primary text-white avatar">
                          <i class="fas fa-users" style="font-size: 24px;"></i>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          Jamaah
                        </div>
                        <div class="text-muted">
                          {{$totalJamaah}}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-success text-white avatar">
                          <i class="fas fa-users" style="font-size: 24px;"></i>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          Agent
                        </div>
                        <div class="text-muted">
                          {{'totalAgen'}}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-danger text-white avatar">
                          <i class="fa-solid fa-plane" style="font-size: 24px;"></i>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          Paket Aktif
                        </div>
                        <div class="text-muted">
                          {{$paketAktif}}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-warning text-white avatar">
                          <i class="fa-solid fa-hand-holding-dollar" style="font-size: 24px;"></i>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          Jamaah Nabung
                        </div>
                        <div class="text-muted">
                          {{$totalJamaahNabung}}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
        </div>
        <div class="row row-cards mt-2">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header bg-cyan-lt">
                <h3 class="card-title">Paket yang aktif</h3>
              </div>
              <div class="card-body">
                <div class="table-responsive p-3">
                  <table id="mytable" class="table table-vcenter card-table">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th>Nama Paket</th>
                        <th>Tgl Berangkat</th>
                        <th>Jlh Hari</th>
                        <th>Maskapai</th>
                        <th>Rute</th>
                        <th>Lokasi Berangkat</th>
                        <th>Harga Paket</th>
                        <th>Kuota</th>
                    </thead>
                    <tbody>
                      @forelse ($listPaket as $p)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{$p->nama_paket}} </td>
                          <td>{{$p->tgl_berangkat}} </td>
                          <td>{{$p->jlh_hari}} </td>
                          <td>{{$p->maskapai->nama_maskapai}} </td>
                          <td>{{$p->rute}} </td>
                          <td>{{$p->lokasi_berangkat}} </td>
                          <td>{{$p->harga_paket}} </td>
                          <td>{{$p->kuota}} </td>
                        </tr>
                      @empty
                      <tr>
                          <td colspan="9">No Data</td>
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

</script>
@endsection