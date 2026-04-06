@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <h2 class="page-title">
              Kelas
            </h2>
          </div>
        </div>
      </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-lg-4">
            <form class="card" method="POST" action="{{route('maskapais.update',$maskapai->id)}}">
              @csrf
              @method('PUT')
              <div class="card-header">
                <h3 class="card-title">Edit Data</h3>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label">Nama Maskapai</label>
                  <input type="text" name="nama_maskapai" class="form-control" value="{{$maskapai->nama_maskapai}}">
                </div>

                <div class="mb-3">
                  <label>Kelamin</label>
                  <select name="rute_terbang" class="form-select">
                      <option value="direct" {{ $maskapai->rute_terbang == 'direct' ? 'selected' : '' }}>direct</option>
                      <option value="transit" {{ $maskapai->rute_terbang == 'transit' ? 'selected' : '' }}>transit</option>
                  </select>
                  @error('rute_terbang')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="mb-3">
                  <label class="form-label">Lama Perjalanan (jam)</label>
                  <input type="number" name="lama_perjalanan" class="form-control" value="{{ $maskapai->lama_perjalanan }}">
                  @error('lama_perjalanan')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="mb-3">
                  <label class="form-label">Harga Tiket</label>
                  <input type="number" name="harga_tiket" class="form-control" value="{{ $maskapai->harga_tiket }}">
                  @error('harga_tiket')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="mb-3">
                  <label class="form-label">Catatan Keberangkatan</label>
                  <input type="text" name="catatan_keberangkatan" class="form-control" value="{{ $maskapai->catatan_keberangkatan}}">
                  @error('catatan_keberangkatan')
                      <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                
              </div>
              <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>

          <div class="col-lg-8">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data Kelas</h3>
              </div>
              <div class="table-responsive">
                <table class="table table-vcenter card-table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama Maskapai</th>
                      <th>Rute </th>
                      <th>Lama Perjalanan</th>
                      <th>Harga Tiket</th>
                      <th class="w-1">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($maskapaiview as $m)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$m->nama_maskapai}} </td>
                                <td>{{$m->rute_terbang}} </td>
                                <td>{{$m->lama_perjalanan.' Jam'}} </td>
                                <td>{{$m->harga_tiket}} </td>
                                <td class="d-flex align-items-center" style="gap: 5px;">
                                  <a href="{{route('maskapais.edit',$m->id)}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i>Edit</a>
                                    {{-- <form method="POST" action="{{ route('maskapais.destroy', $m->id) }}" style="display: inline;" id="delete-form-{{ $m->id }}">
                                      @csrf
                                      @method('DELETE')
                                      <button type="button" class="btn btn-sm btn-danger" onclick="deleteConfirmation({{ $m->id }})">
                                          <i class="fas fa-trash-alt"></i> Hapus
                                      </button>
                                    </form> --}}
                                    
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="3">No Data</td>
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
@endsection