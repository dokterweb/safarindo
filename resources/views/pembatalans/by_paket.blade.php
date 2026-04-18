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
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-lg-12">
            <div class="card">
              
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
                                <!-- Tombol Pembatalan -->
                                <button class="btn btn-sm btn-danger btn-pembatalan"
                                    data-jamaah_id="{{ $p->id }}" data-paket_id="{{ $paket->id }}"
                                    data-nama="{{ $p->nama_jamaah }}">
                                    Pembatalan
                                </button>

                                <!-- Tombol Pindah Paket -->
                                <button class="btn btn-sm btn-primary btn-pindah"
                                    data-jamaah_id="{{ $p->id }}" data-paket_id="{{ $paket->id }}"
                                    data-nama="{{ $p->nama_jamaah }}">
                                    Pindah Paket
                                </button>
                               
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

<!-- Modal Pembatalan -->
<div class="modal fade" id="modalPembatalan" tabindex="-1">
  <div class="modal-dialog">
      <form action="{{ route('pembatalans.store') }}" method="POST">
          @csrf
          <input type="hidden" name="jamaah_id" id="batal_jamaah_id">
          <input type="hidden" name="paket_id" id="batal_paket_id">
          <input type="hidden" name="jenis" value="pembatalan">

          <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                  <h5 class="modal-title">Pembatalan Jamaah</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">
                  <p><strong>Nama Jamaah:</strong> 
                      <span id="batal_nama"></span>
                  </p>

                  <div class="mb-3">
                      <label>Pengembalian Uang</label>
                      <input type="number" name="pengembalian_uang" class="form-control" value="0">
                  </div>

                  <div class="mb-3">
                      <label>Keterangan</label>
                      <textarea name="keterangan" class="form-control" required></textarea>
                  </div>
              </div>

              <div class="modal-footer">
                  <button class="btn btn-danger">Simpan</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              </div>
          </div>
      </form>
  </div>
</div>

<!-- Modal Pindah Paket -->
<div class="modal fade" id="modalPindah" tabindex="-1">
  <div class="modal-dialog modal-lg">
      <form action="{{ route('pembatalans.store') }}" method="POST">
          @csrf
          <input type="hidden" name="jamaah_id" id="pindah_jamaah_id">
          <input type="hidden" name="paket_id" id="pindah_paket_id">
          <input type="hidden" name="jenis" value="pemindahan">

          <div class="modal-content">
              <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title">Pindah Paket</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">
                  <p><strong>Nama Jamaah:</strong> 
                      <span id="pindah_nama"></span>
                  </p>

                  <div class="mb-3">
                      <label>Keterangan</label>
                      <textarea name="keterangan" class="form-control" required></textarea>
                  </div>

                  <label>Pilih Paket Baru</label>
                  <table class="table table-bordered">
                      <thead>
                          <tr>
                              <th>Pilih</th>
                              <th>Nama Paket</th>
                              <th>Tanggal</th>
                              <th>Harga</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach(App\Models\Paket::all() as $pkt)
                          <tr>
                              <td>
                                  <input type="radio" name="paket_tujuan_id" value="{{ $pkt->id }}" required>
                              </td>
                              <td>{{ $pkt->nama_paket }}</td>
                              <td>{{ $pkt->tgl_berangkat }}</td>
                              <td>Rp {{ number_format($pkt->harga_paket) }}</td>
                          </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>

              <div class="modal-footer">
                  <button class="btn btn-primary">Simpan</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              </div>
          </div>
      </form>
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

    $(document).ready(function(){

        // Modal Pembatalan
        $('.btn-pembatalan').click(function(){
            $('#batal_jamaah_id').val($(this).data('jamaah_id'));
            $('#batal_paket_id').val($(this).data('paket_id'));
            $('#batal_nama').text($(this).data('nama'));
            $('#modalPembatalan').modal('show');
        });

        // Modal Pindah Paket
        $('.btn-pindah').click(function(){
            $('#pindah_jamaah_id').val($(this).data('jamaah_id'));
            $('#pindah_paket_id').val($(this).data('paket_id'));
            $('#pindah_nama').text($(this).data('nama'));
            $('#modalPindah').modal('show');
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