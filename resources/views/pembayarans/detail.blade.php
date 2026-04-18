@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
            <div class="col-lg-8">
                <div class="row row-cards">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-yellow-lt">
                                <h3 class="card-title">Detail Jamaah</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <dt class="col-5">Nama Jamaah:</dt>
                                            <dd class="col-7">{{$jamaah->nama_jamaah}}</dd>
                                            <dt class="col-5">No.HP:</dt>
                                            <dd class="col-7">{{$jamaah->no_hp}}</dd>
                                            <dt class="col-5">Kelamin:</dt>
                                            <dd class="col-7">{{$jamaah->kelamin}}</dd>
                                            <dt class="col-5">Tempat/Tgl Lahir</dt>
                                            <dd class="col-7">{{$jamaah->tempat_lahir.' / '.$jamaah->tanggal_lahir}}</dd>
                                            <dt class="col-5">Alamat:</dt>
                                            <dd class="col-7">{{$jamaah->alamat}}</dd>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        @if($jamaah->foto_ktp)
                                        <img src="{{ asset('storage/'.$jamaah->foto_ktp) }}" width="200">
                                      @endif    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-green-lt">
                                <h3 class="card-title">Data Pembayaran</h3>
                            </div>
                            <div class="table-responsive p-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tgl</th>
                                            <th>Jenis</th>
                                            <th>Bukti</th>
                                            <th>Jumlah</th>
                                            <th>Act</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jamaah->pembayarans as $p)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $p->created_at->format('Y-m-d') }}</td>
                                                <td>{{ $p->metode_bayar }}</td>
                                                <td>
                                                    @if($p->bukti_bayar)
                                                        <a href="{{ asset('storage/'.$p->bukti_bayar) }}" target="_blank">Lihat</a>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($p->jumlah_bayar) }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-danger btn-edit" data-bs-toggle="modal"
                                                        data-id="{{ $p->id }}"
                                                        data-jumlah="{{ $p->jumlah_bayar }}"
                                                        data-metode="{{ $p->metode_bayar }}"
                                                        data-bukti="{{ $p->bukti_bayar }}">
                                                        Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <table class="table">
                                    <tr>
                                        <td>Total Bayar:</td>
                                        <td>{{ number_format($totalBayar) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Tagihan:</td>
                                        <td>{{ number_format($tagihan) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sisa:</td>
                                        <td>{{ number_format($sisa) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status:</td>
                                        <td>
                                            @if($sisa <= 0)
                                                <span class="text-success">Lunas</span>
                                            @else
                                                <span class="text-danger">Belum Lunas</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-lg-4">
                <div class="row row-cards">
                    <div class="col-md-12">
                        <form class="card" action="{{ route('pembayarans.store', $jamaah) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header bg-blue-lt">
                                <h3 class="card-title">Input Pembayaran</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label>Jumlah Bayar</label>
                                    <input type="number" name="jumlah_bayar" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Metode</label>
                                    <select name="metode_bayar" class="form-control">
                                        <option value="transfer">Transfer</option>
                                        <option value="tunai">Tunai</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Bukti Bayar</label>
                                    <input type="file" name="bukti_bayar" class="form-control">
                                </div>
                                <button class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12">
                        <form class="card" action="{{ route('diskons.store', $jamaah->id) }}" method="POST">
                            @csrf
                            <div class="card-header bg-red-lt">
                                <h3 class="card-title">Ajukan Diskon</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label>Jumlah Bayar</label>
                                    <input type="number" name="diskon" class="form-control">
                                </div>
                                <button class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    
</div>

<div class="modal modal-blur fade" id="modalEdit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="formEdit" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="id" id="edit_id">

                    <div class="mb-3">
                        <label>Jumlah Bayar</label>
                        <input type="number" name="jumlah_bayar" id="edit_jumlah" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Metode</label>
                        <select name="metode_bayar" id="edit_metode" class="form-select">
                            <option value="transfer">Transfer</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Bukti Bayar</label>
                        <input type="file" name="bukti_bayar" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Update</button>
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

    $(document).on('click', '.btn-edit', function () {
        let id = $(this).data('id');
        let jumlah = $(this).data('jumlah');
        let metode = $(this).data('metode');
        let bukti = $(this).data('bukti');

        $('#edit_id').val(id);
        $('#edit_jumlah').val(jumlah);
        $('#edit_metode').val(metode);

        // 🔥 preview gambar
        if (bukti) {
            $('#preview_bukti').attr('src', '/storage/' + bukti);
        } else {
            $('#preview_bukti').attr('src', '');
        }

        // 🔥 set action form
        $('#formEdit').attr('action', '/pembayarans/' + id + '/update');

        $('#modalEdit').modal('show');
    });
</script>
@endsection