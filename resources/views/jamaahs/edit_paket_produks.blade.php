@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header bg-yellow-lt">
                        <h3 class="card-title">Detail Jamaah</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
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
                            <a href="{{ route('pembayarans.detail', $jamaah->id) }}" class="btn btn-sm btn-primary">
                                Lihat Jamaah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <div class="card">
                    <div class="card-body">
                    <form action="{{ route('jamaah.paket.produk.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="jamaah_id" value="{{ $jamaah->id }}">
                        <input type="hidden" name="paket_id" value="{{ $jamaah->paket_id }}">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Barang</th>
                                    <th>Stok</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produks as $i => $p)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $p->nama_produk }}</td>
                                    <td>{{ $p->stok }}</td>
                                    <td>
                                        <input type="number" 
                                        name="qty[{{ $p->id }}]" 
                                        value="{{ $riwayat[$p->id]->qty_diambil ?? 0 }}"
                                        class="form-control">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <button type="submit" class="btn btn-success">Update</button>
                    </form>
                    
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


@endsection