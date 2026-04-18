@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row justify-content-center">
          <div class="col-10">
            <div class="row row-cards">
              <div class="col-sm-6 col-lg-4">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-primary text-white avatar">
                          <i class="nav-icon fas fa-cog"></i>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          Total Jamaah
                        </div>
                        <div class="text-muted">
                          {{ $totalJamaah }} Orang
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-lg-4">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-green text-white avatar">
                          <i class="nav-icon fas fa-cog"></i>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          Total Fee
                        </div>
                        <div class="text-muted">
                          Rp {{ number_format($totalFee, 0, ',', '.') }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-lg-4">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-twitter text-white avatar">
                          <i class="nav-icon fas fa-cog"></i>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                          Fee Dibayarkan
                        </div>
                        <div class="text-muted">
                          Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('agen.dashboard') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="form-control">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-primary me-2">Filter</button>
                                <a href="{{ route('agen.dashboard') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                    <h5>Data Pendapatan Fee Agen</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Jamaah</th>
                                    <th>No HP</th>
                                    <th>Nama Paket</th>
                                    <th>Harga Paket</th>
                                    <th>Fee Agen</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jamaahs as $j)
                                    @php
                                        $transaksi = $transaksis[$j->id] ?? null;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $j->nama_jamaah }}</td>
                                        <td>{{ $j->no_hp }}</td>
                                        <td>{{ $j->paket->nama_paket }}</td>
                                        <td>Rp {{ number_format($j->paket->harga_paket, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($agent->fee_agent, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            @if ($transaksi)
                                                <span class="badge bg-success">Disetujui</span>
                                            @else
                                                <span class="badge bg-danger">Belum Disetujui</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Tidak ada data
                                        </td>
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
    
</div>
@endsection