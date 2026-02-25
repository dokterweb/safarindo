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
            <form class="card" method="POST" action="{{route('keluarbulanans.update',$keluarbulanan->id)}}">
              @csrf
              @method('PUT')
              <div class="card-header">
                <h3 class="card-title">Edit Data</h3>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label">Nama Pengeluaran Bulanan</label>
                  <input type="text" name="nama_pengeluaran" class="form-control" value="{{$keluarbulanan->nama_pengeluaran}}">
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
                      <th>Nama Pengeluaran</th>
                      <th class="w-1">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($keluarbulananview as $k)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$k->nama_maskapai}} </td>
                                <td class="d-flex align-items-center" style="gap: 5px;">
                                  <a href="{{route('pengeluaranbulanans.edit',$k->id)}}" class="btn btn-sm btn-info"><i class="far fa-edit"></i>Edit</a>
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