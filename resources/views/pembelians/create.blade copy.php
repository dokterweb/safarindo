@extends('layouts.app')
@section('css')
<style>
    #result_produk {
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 6px;
    max-height: 250px;
    overflow-y: auto;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
          <div class="col-10">
            <div class="card">
            <form action="{{ route('pembelians.store') }}" method="POST">
                @csrf
                <div class="card-header bg-cyan">
                    <h3 class="card-title">Tambah Produk</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Supplier</label>
                            <select name="supplier_id" class="form-control select2">
                                <option value="">Pilih Supplier</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="col-md-6 mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <div class="input-group mb-2">
                                <input type="text" id="search_produk" class="form-control" placeholder="Cari Produk…">
                                <button class="btn" type="button">Go!</button>
                                <div id="result_produk" class="list-group position-absolute w-100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table mt-3" id="table-produk">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Qty</th>
                                    <th>Diskon</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <input type="hidden" name="items" id="items">
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label>Tax (%)</label>
                            <input type="number" id="tax" class="form-control" value="0">
                        </div>
                    
                        <div class="col-md-3">
                            <label>Diskon</label>
                            <input type="number" id="diskon_global" class="form-control" value="0">
                        </div>
                    
                        <div class="col-md-3">
                            <label>Shipping</label>
                            <input type="number" id="shipping" class="form-control" value="0">
                        </div>
                    </div>
                    
                    <h4 class="mt-3">Grand Total: Rp <span id="grand_total">0</span></h4>
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

    let produks = @json($produks);
    let items = [];

    $('#search_produk').on('keyup', function () {
        let query = $(this).val();

        if (query.length < 1) {
            $('#result_produk').html('');
            return;
        }

        $.ajax({
            url: "{{ route('produk.search') }}",
            type: "GET",
            data: { q: query },
            success: function (data) {
                let html = '';
                currentIndex = -1; // reset
                data.forEach(function (item) {
                    html += `
                        <a href="#" class="list-group-item list-group-item-action pilih-produk"
                            data-id="${item.id}"
                            data-nama_produk="${item.nama_produk}"
                            data-harga_jual="${item.harga_jual}"
                            data-stok="${item.stok}">
                            ${item.nama_produk} - Rp ${item.harga_jual}
                        </a>
                    `;
                });

                $('#result_produk').html(html);
            }
        });
    });
    $(document).on('click', '.pilih-produk', function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let nama_produk = $(this).data('nama_produk');
        let harga_jual = $(this).data('harga_jual');
        let stok = $(this).data('stok');

        let row = `
            <tr>
                <td>${nama_produk}</td>
                <td>${harga_jual}</td>
                <td>${stok}</td>
                <td><input type="number" class="form-control qty" value="1"></td>
                <td><input type="number" class="form-control diskon" value="0"></td>
                <td class="total">${harga_jual}</td>
                <td><button class="btn btn-danger btn-sm hapus">X</button></td>
            </tr>
        `;

        $('#table-produk tbody').append(row);

        // kosongkan search + hasil
        $('#search_produk').val('');
        $('#result_produk').html('');
    });

    $(document).click(function (e) {
        if (!$(e.target).closest('#search_produk').length) {
            $('#result_produk').html('');
        }
    });
/* 
    function addItem(p)
    {
        let exist = items.find(i => i.id == p.id);
        if (exist) {
            exist.qty += 1;
        } else {
            items.push({
                id: p.id,
                nama: p.nama_produk,
                harga: parseInt(p.harga_beli),
                qty: 1,
                diskon: 0,
                stok: p.stok
            });
        }
        renderTable();
    }

    function renderTable()
    {
        let tbody = $('#table-produk tbody');
        tbody.empty();
        $.each(items, function(index, item){
            let total = (item.harga * item.qty) - item.diskon;
            let row = `
            <tr>
                <td>${item.nama}</td>
                <td>Rp ${item.harga.toLocaleString()}</td>
                <td>${item.stok}</td>
                <td>
                    <input type="number" class="form-control qty" data-index="${index}" value="${item.qty}">
                </td>
                <td>
                    <input type="number" class="form-control diskon" data-index="${index}" value="${item.diskon}">
                </td>
                <td>Rp ${total.toLocaleString()}</td>
                <td>
                    <button class="btn btn-danger btn-sm remove" data-index="${index}">X</button>
                </td>
            </tr>
            `;
            tbody.append(row);
        });
        hitungTotal();
    } */

    $(document).on('input', '.qty', function () {
        let index = $(this).data('index');
        let val = parseInt($(this).val()) || 1;
        items[index].qty = val;
        renderTable();
    });

    $(document).on('input', '.diskon', function () {
        let index = $(this).data('index');
        let val = parseInt($(this).val()) || 0;
        items[index].diskon = val;
        renderTable();
    });

    $(document).on('click', '.remove', function () {
        let index = $(this).data('index');
        items.splice(index, 1);
        renderTable();
    });

    function hitungTotal()
    {
        let subtotal = 0;

        $.each(items, function(i, item){
            subtotal += (item.harga * item.qty) - item.diskon;
        });
        let tax = parseInt($('#tax').val()) || 0;
        let diskon = parseInt($('#diskon_global').val()) || 0;
        let shipping = parseInt($('#shipping').val()) || 0;
        let totalTax = subtotal * (tax / 100);
        let grand = subtotal + totalTax - diskon + shipping;
        $('#grand_total').text(grand.toLocaleString());
    }

    $('#tax, #diskon_global, #shipping').on('input', function(){
        hitungTotal();
    });

    $('form').on('submit', function () {
        $('#items').val(JSON.stringify(items));
    });
</script>
@endsection