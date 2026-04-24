@extends('layouts.app')
@section('css')
<style>
    #result_produk {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;

        background: #fff;
        /* border: 1px solid #ddd; */
        border-radius: 6px;

        max-height: 250px;
        overflow-y: auto;

        z-index: 9999; /* 🔥 penting */
    }

    #result_produk .list-group-item {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    #result_produk .list-group-item:hover {
        background-color: #e9f5ff;
        transform: scale(1.01);
    }

    #result_produk .list-group-item.active {
    background-color: #0d6efd;
    color: #fff;
    }
</style>
@endsection
@section('content')
<div class="page-wrapper">
   
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row justify-content-center">
          <div class="col-8">
            <div class="card">
            <form action="{{ route('suratrekoms.store') }}" method="POST">
                @csrf
                <div class="card-header bg-cyan">
                    <h3 class="card-title">Tambah Surat Rekomendasi</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nama Jamaah</label>
                            <input type="text" id="search_jamaah" class="form-control" placeholder="Cari jamaah...">
                            <input type="hidden" name="jamaah_id" id="jamaah_id">
                            <div id="result_jamaah" class="list-group"></div>
                        </div>
                    
                        <div class="col-md-6 mb-3">
                            <label>Nama Paket</label>
                            <input type="text" id="paket_nama" class="form-control" readonly>
                            <input type="hidden" name="paket_id" id="paket_id">
                        </div>
                    
                    </div>
                   
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Kantor Imigrasi</label>
                            <input type="text" name="kantor_imigrasi" class="form-control">
                        </div>
                    
                        <div class="col-md-6 mb-3">
                            <label>Alamat Imigrasi</label>
                            <input type="text" name="alamat_imigrasi" class="form-control">
                        </div>
                    
                       <div class="col-md-12 mb-3">
                            <label>Catatan</label>
                            <input type="text" id="catatan" name="catatan" class="form-control">
                        </div>
                    </div>
                    
                </div>
               
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let selectedIndex = -1;
    let results = [];
    
    $('#search_jamaah').on('keyup', function(e){
    
        // 🔥 skip tombol navigasi
        if (['ArrowDown','ArrowUp','Enter'].includes(e.key)) return;
    
        let keyword = $(this).val();
    
        if(keyword.length < 2){
            $('#result_jamaah').html('');
            return;
        }
    
        $.get('/suratrekoms/search-jamaah?q='+keyword, function(data){
    
            results = data;
            selectedIndex = -1;
    
            let html = '';
            data.forEach((item, index)=>{
                html += `
                    <a href="#" class="list-group-item item-jamaah"
                       data-index="${index}">
                       ${item.nama_jamaah} - ${item.nik}
                    </a>
                `;
            });
    
            $('#result_jamaah').html(html);
        });
    });
    
    
    // 🔥 KEYBOARD NAVIGATION (PASTI JALAN)
    $('#search_jamaah').on('keydown', function(e){
    
        let items = $('.item-jamaah');
    
        if(items.length === 0) return;
    
        if(e.key === 'ArrowDown'){
            e.preventDefault();
            selectedIndex++;
            if(selectedIndex >= items.length) selectedIndex = 0;
        }
    
        if(e.key === 'ArrowUp'){
            e.preventDefault();
            selectedIndex--;
            if(selectedIndex < 0) selectedIndex = items.length - 1;
        }
    
        if(e.key === 'Enter'){
            e.preventDefault();
            if(selectedIndex >= 0){
                items.eq(selectedIndex).trigger('click');
            }
        }
    
        items.removeClass('active');
        if(selectedIndex >= 0){
            items.eq(selectedIndex).addClass('active');
        }
    });
    
    
    // 🔥 CLICK PILIH
    $(document).on('click', '.item-jamaah', function(e){
        e.preventDefault();
    
        let index = $(this).data('index');
        let data = results[index];
    
        $('#search_jamaah').val(data.nama_jamaah);
        $('#jamaah_id').val(data.id);
    
        $('#paket_nama').val(data.paket.nama_paket);
        $('#paket_id').val(data.paket.id);
    
        $('#result_jamaah').html('');
    });
</script>
@endsection