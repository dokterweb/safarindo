@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row justify-content-center">
          <div class="col-10">
            <div class="row row-cards">
              <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('laporan.neraca') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}" class="form-control">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-primary me-2">Filter</button>
                                <a href="{{ route('laporan.neraca') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
              </div>
              <div class="card">
                <div class="card-header bg-red-lt">
                  <h3 class="card-title">Input Data</h3>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead class="table-light text-center">
                        <tr>
                          <th>No.</th>
                          <th>Id.Trx</th>
                          <th>Tanggal</th>
                          <th>Group Trx</th>
                          <th>Keterangan</th>
                          <th class="text-end">Debet</th>
                          <th class="text-end">Kredit</th>
                          <th class="text-end">Saldo</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($data as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $row->group }}</td>
                            <td>{{ $row->keterangan }}</td>
                            <td class="text-end">
                                {{ $row->debit ? number_format($row->debit, 0, ',', '.') : '0' }}
                            </td>
                            <td class="text-end">
                                {{ $row->kredit ? number_format($row->kredit, 0, ',', '.') : '0' }}
                            </td>
                            <td class="text-end">
                                {{ number_format($row->saldo, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                      </tbody>
                      <tfoot>
                        <tr class="fw-bold">
                            <td colspan="5" class="text-end">Total</td>
                            <td class="text-end">
                                Rp {{ number_format($totalDebit, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($totalKredit, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($saldo, 0, ',', '.') }}
                            </td>
                        </tr>
                      </tfoot>
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