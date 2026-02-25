@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
            <div class="col-lg-12">
                <div class="card">
                <div class="card-header bg-cyan-lt">
                    <h3 class="card-title">Data Agent</h3>
                </div>
                <div class="table-responsive p-3">
                    <table id="mytable" class="table table-vcenter card-table">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th>Nama Agent</th>
                        <th>Email</th>
                        <th>Kelamin</th>
                        <th>No HP</th>
                        <th>Umur</th>
                        <th>Kota</th>
                        <th class="w-1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agents as $p)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$p->user->name}} </td>
                                <td>{{$p->user->email}} </td>
                                <td>{{$p->kelamin}} </td>
                                <td>{{$p->no_hp}} </td>
                                <td>{{$p->tanggal_lahir}} </td>
                                <td>{{$p->kota}} </td>
                                <td class="d-flex align-items-center" style="gap: 5px;">
                                   {{-- <a href="{{route('santris.edit',$p->id)}}" class="btn btn-sm btn-info">Edit</a>
                                    <form method="POST" action="{{ route('santris.destroy', $p->id) }}" style="display: inline;" id="delete-form-{{ $p->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation({{ $p->id }})">Del</button>
                                    </form> --}}
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

<div class="modal modal-blur fade" id="modalnya" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="santri-details">
            
            <!-- Konten Ustadz akan dimuat melalui JavaScript -->
            <div class="row">
                <div class="col-md-4">
                    <img id="santri-avatar" src="" alt="Avatar" class="img-fluid rounded-circle">
                </div>
                <div class="col-md-8">
                    <table class="table table-vcenter card-table">
                        <tr>
                            <td>Nama Ustadz</td>
                            <td>:</td>
                            <td><span id="santri-name"></span></td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td>:</td>
                            <td><span id="santri-kelasnya"></span></td>
                        </tr>
                        <tr>
                            <td>Kelompok</td>
                            <td>:</td>
                            <td><span id="santri-kelompok"></span></td>
                        </tr>
                        <tr>
                            <td>Kelamin</td>
                            <td>:</td>
                            <td><span id="santri-kelamin"></span></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td><span id="santri-email"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
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

    function showSantriDetails(id) {
        // Melakukan request AJAX untuk mendapatkan detail santri berdasarkan ID
        $.ajax({
            url: '/santris/' + id, // Rute untuk mendapatkan detail santri berdasarkan ID
            type: 'GET',
            success: function(response) {
                // Mengisi modal dengan data santri
                $('#santri-name').text(response.name);
                $('#santri-kelasnya').text(response.kelasnya);
                $('#santri-kelompok').text(response.kelompok);
                $('#santri-kelamin').text(response.kelamin);
                $('#santri-email').text(response.email);
                
                // Menampilkan avatar jika ada
                if (response.avatar) {
                    $('#santri-avatar').attr('src', response.avatar); // Mengatur URL avatar
                } else {
                    $('#santri-avatar').attr('src', '/storage/avatar/default-avatar.png'); // Default avatar jika tidak ada
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat memuat data');
            }
        });
    }

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