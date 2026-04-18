@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
          <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                      Detail Jamaah
                    </h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-5">Nama Jamaah:</dt>
                        <dd class="col-7">{{$jamaah->nama_jamaah}}</dd>
                        <dt class="col-5">NIK:</dt>
                        <dd class="col-7">{{$jamaah->nik}}</dd>
                        <dt class="col-5">No.HP:</dt>
                        <dd class="col-7">{{$jamaah->no_hp}}</dd>
                        <dt class="col-5">Kelamin:</dt>
                        <dd class="col-7">{{$jamaah->kelamin}}</dd>
                        <dt class="col-5">Tempat/Tgl Lahir</dt>
                        <dd class="col-7">{{$jamaah->tempat_lahir.' / '.$jamaah->tanggal_lahir}}</dd>
                        <dt class="col-5">Alamat:</dt>
                        <dd class="col-7">{{$jamaah->alamat}}</dd>
                    </dl>
                    <div class="hr-text"><h5>Data Passport</h5></div>
                    <dl class="row">
                        <dt class="col-5">Nama Jamaah:</dt>
                        <dd class="col-7">{{$jamaah->nama_jamaah_pasport}}</dd>
                        <dt class="col-5">No. Pasport:</dt>
                        <dd class="col-7">{{$jamaah->no_pasport}}</dd>
                        <dt class="col-5">Penerbit:</dt>
                        <dd class="col-7">{{$jamaah->penerbit}}</dd>
                        <dt class="col-5">Pasport Aktif:</dt>
                        <dd class="col-7">{{$jamaah->pasport_aktif}}</dd>
                        <dt class="col-5">Pasport Expired:</dt>
                        <dd class="col-7">{{$jamaah->pasport_expired}}</dd>
                        <dt class="col-5">Agent:</dt>
                        <dd class="col-7">{{$jamaah->agent->user->name}}</dd>
                    </dl>
                </div>
             
            </div>
          </div>
          <div class="col-lg-6">
            <div class="row row-cards">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      Foto KTP
                    </h3>
                  </div>
                  <div class="card-body">
                    @if($jamaah->foto_ktp)
                      <img src="{{ asset('storage/'.$jamaah->foto_ktp) }}" width="200">
                    @endif      
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      Foto Kartu Keluarga
                    </h3>
                  </div>
                  <div class="card-body">
                    @if($jamaah->foto_kk)
                      <img src="{{ asset('storage/'.$jamaah->foto_kk) }}" width="200">
                    @endif      
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      Foto Passport 1
                    </h3>
                  </div>
                  <div class="card-body">
                    @if($jamaah->foto_pasport1)
                      <img src="{{ asset('storage/'.$jamaah->foto_pasport1) }}" width="200">
                    @endif      
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">
                      Foto Passport 2
                    </h3>
                  </div>
                  <div class="card-body">
                    @if($jamaah->foto_pasport2)
                      <img src="{{ asset('storage/'.$jamaah->foto_pasport2) }}" width="200">
                    @endif      
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

@section('scripts')

<script>

</script>
@endsection