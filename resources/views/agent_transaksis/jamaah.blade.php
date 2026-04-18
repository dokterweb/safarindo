@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   
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
                        <th>Nama Paket</th>
                        <th>Harga Paket</th>
                        <th>Fee Agent</th>
                        <th>Dibayar</th>
                        <th class="w-1">Act</th>
                      </tr>
                  </thead>
                  <tbody>
                    @foreach($jamaahs as $j)

                    @php
                        $fee = $j->agent->fee_agent ?? 0;
                        $dibayar = $j->total_bayar ?? 0;
                    @endphp
                    
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $j->nama_jamaah }}</td>
                        <td>{{ $j->paket->nama_paket }}</td>
                        <td>{{ number_format($j->paket->harga_paket) }}</td>
                    
                        <!-- 🔥 FEE DARI AGENT -->
                        <td>{{ number_format($fee) }}</td>
                    
                        <td>{{ number_format($dibayar) }}</td>
                    
                        <td>
                          @if($dibayar >= $fee)
                              <span class="badge bg-success">Lunas</span>
                          @else
                              <button class="btn btn-warning btn-sm btn-bayar"
                                  data-agent="{{ $agent->id }}"
                                  data-jamaah="{{ $j->id }}"
                                  data-paket="{{ $j->paket->id }}"
                                  data-fee="{{ $fee }}">
                                  Bayar Fee
                              </button>
                          @endif
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
</div>

<div class="modal fade" id="modalBayarFee" tabindex="-1">
  <div class="modal-dialog">
      <form method="POST" action="{{ route('agent_transaksis.bayar') }}" enctype="multipart/form-data">
          @csrf
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Bayar Fee Agent</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">
                  <input type="hidden" name="agent_id" id="agent_id">
                  <input type="hidden" name="jamaah_id" id="jamaah_id">
                  <input type="hidden" name="paket_id" id="paket_id">

                  <div class="mb-3">
                      <label>Jumlah Fee</label>
                      <input type="number" name="jumlah" id="jumlah"
                             class="form-control" required>
                  </div>

                  <div class="mb-3">
                      <label>Bukti Bayar</label>
                      <input type="file" name="bukti_bayar"
                             class="form-control">
                  </div>
              </div>

              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">
                      Simpan
                  </button>
                  <button type="button" class="btn btn-secondary"
                          data-bs-dismiss="modal">
                      Batal
                  </button>
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

  $(document).on('click', '.btn-bayar', function () {
    $('#agent_id').val($(this).data('agent'));
    $('#jamaah_id').val($(this).data('jamaah'));
    $('#paket_id').val($(this).data('paket'));
    $('#jumlah').val($(this).data('fee'));

    $('#modalBayarFee').modal('show');
  });
</script>
@endsection