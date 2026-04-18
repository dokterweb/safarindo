@extends('layouts.app')

@section('content')
<div class="page-wrapper">
     <!-- Page header -->
     <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Setuju Pembatalan dan Pindah Paket</h2>
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
                    @forelse ($pembatalans as $p)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$p->jamaah->nama_jamaah }} </td>
                            <td>{{$p->jamaah->no_hp }} </td>
                            <td>{{$p->jamaah->kota }} </td>
                            <td class="d-flex align-items-center" style="gap: 5px;">
                                <button class="btn btn-sm btn-info btn-detail" data-id="{{ $p->id }}">
                                    Detail
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

<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formApprove" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Detail Pembatalan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama Jamaah</th>
                            <td id="nama_jamaah"></td>
                        </tr>
                        <tr>
                            <th>Jenis</th>
                            <td id="jenis"></td>
                        </tr>
                        <tr>
                            <th>Paket Awal</th>
                            <td id="paket_awal"></td>
                        </tr>
                        <tr>
                            <th>Paket Tujuan</th>
                            <td id="paket_tujuan"></td>
                        </tr>
                        <tr>
                            <th>Pengembalian Uang</th>
                            <td id="refund"></td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td id="keterangan"></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td id="status"></td>
                        </tr>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" formaction="" id="btnApprove">
                        Setujui
                    </button>
                    <button type="submit" class="btn btn-danger" formaction="" id="btnReject">
                        Tolak
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </form>
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

$(document).on('click', '.btn-detail', function () {
    let id = $(this).data('id');

    $.get('/pembatalans/' + id, function (data) {

        $('#nama_jamaah').text(data.jamaah.nama_jamaah);
        $('#jenis').text(data.jenis);
        $('#paket_awal').text(data.paket.nama_paket);
        $('#paket_tujuan').text(
            data.paket_tujuan ? data.paket_tujuan.nama_paket : '-'
        );
        $('#refund').text(
            'Rp ' + (data.pengembalian_uang ?? 0).toLocaleString()
        );
        $('#keterangan').text(data.keterangan ?? '-');
        $('#status').text(data.status);

        $('#btnApprove').attr(
            'formaction',
            '/pembatalans/' + id + '/approve'
        );

        $('#btnReject').attr(
            'formaction',
            '/pembatalans/' + id + '/reject'
        );

        $('#modalDetail').modal('show');
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