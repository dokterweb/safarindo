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
          <div class="col-10">
            <div class="card">
            <form action="{{ route('pembelians.update', $pembelian->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-header bg-cyan">
                    <h3 class="card-title">Edit Produk</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Supplier</label>
                            <select name="supplier_id" class="form-select select2  @error('supplier_id') is-invalid @enderror">
                                @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}" 
                                    {{ old('supplier_id', $pembelian->supplier_id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_supplier }}
                                </option>
                            @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">
                                    Supplier wajib dipilih
                                </div>
                            @enderror
                        </div>
                    
                        <div class="col-md-6 mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ $pembelian->tanggal }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <div class="input-group mb-2 position-relative">
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
                        <div class="card mt-3 p-3">
                            <div class="d-flex justify-content-between">
                                <span>Tax (<span id="tax_persen">0</span>%)</span>
                                <span>(+) Rp <span id="tax_rp">0</span></span>
                            </div>
                        
                            <div class="d-flex justify-content-between">
                                <span>Discount</span>
                                <span>(-) Rp <span id="diskon_rp">0</span></span>
                            </div>
                        
                            <div class="d-flex justify-content-between">
                                <span>Shipping</span>
                                <span>(+) Rp <span id="shipping_rp">0</span></span>
                            </div>
                        
                            <hr>
                        
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Grand Total</span>
                                <span>Rp <span id="grand_total">0</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label>Tax (%)</label>
                            <input type="number" id="tax" name="tax" class="form-control" value="{{ $pembelian->tax }}">
                        </div>
                    
                        <div class="col-md-4">
                            <label>Diskon</label>
                            <input type="number" id="diskon_global" name="diskon" class="form-control" value="{{ $pembelian->diskon }}">
                        </div>
                    
                        <div class="col-md-4">
                            <label>Shipping</label>
                            <input type="number" id="shipping" name="shipping" class="form-control" value="{{ $pembelian->shipping }}">
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
    let currentIndex = -1;
    let items = @json($items);
    
    // =======================
    // SEARCH AJAX
    // =======================
    $('#search_produk').on('keyup', function (e) {
          // ❌ skip tombol navigasi
        if (["ArrowUp", "ArrowDown", "Enter"].includes(e.key)) {
            return;
        }
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
                currentIndex = -1;
    
                data.forEach(function (item) {
                    html += `
                        <a href="#" class="list-group-item list-group-item-action pilih-produk"
                            data-id="${item.id}"
                            data-nama="${item.nama_produk}"
                            data-harga="${item.harga_jual}"
                            data-stok="${item.stok}">
                            <strong>${item.nama_produk}</strong><br>
                            <small>Stok: ${item.stok}</small> - Rp ${item.harga_jual}
                        </a>
                    `;
                });
    
                $('#result_produk').html(html);
            }
        });
    });
    
    // =======================
    // KEYBOARD NAVIGATION
    // =======================
    $('#search_produk').on('keydown', function (e) {
        let list = $('#result_produk .list-group-item');
    
        if (list.length === 0) return;
    
        if (e.key === "ArrowDown") {
            e.preventDefault();
            currentIndex = (currentIndex + 1) % list.length;
        }
    
        if (e.key === "ArrowUp") {
            e.preventDefault();
            currentIndex = (currentIndex - 1 + list.length) % list.length;
        }
    
        if (e.key === "Enter") {
            e.preventDefault();
            if (currentIndex >= 0) {
                $(list[currentIndex]).click();
            }
        }
    
        list.removeClass('active');
        $(list[currentIndex]).addClass('active');
    });
    
    $('#search_produk').on('focus', function () {
        currentIndex = -1;
    });

    $(document).ready(function(){
        renderTable();
    });

    // =======================
    // PILIH PRODUK
    // =======================
    $(document).on('click', '.pilih-produk', function (e) {
        e.preventDefault();
    
        let id = $(this).data('id');
        let nama = $(this).data('nama');
        let harga = parseInt($(this).data('harga'));
        let stok = $(this).data('stok');
    
        let existing = items.find(item => item.id == id);
    
        if (existing) {
            existing.qty += 1;
        } else {
            items.push({
                id: id,
                nama: nama,
                harga: harga,
                stok: stok,
                qty: 1,
                diskon: 0
            });
        }
    
        renderTable();
    
        $('#search_produk').val('');
        $('#result_produk').html('');
    });
    
    // =======================
    // RENDER TABLE
    // =======================
    function renderTable() {
        let html = '';
    
        items.forEach((item, index) => {
            let total = (item.harga * item.qty) - item.diskon;
    
            html += `
                <tr>
                    <td>${item.nama}</td>
                    <td>${item.harga}</td>
                    <td>${item.stok}</td>
                    <td>
                        <input type="number" min="1" class="form-control qty" data-index="${index}" value="${item.qty}">
                    </td>
                    <td>
                        <input type="number" min="0" class="form-control diskon" data-index="${index}" value="${item.diskon}">
                    </td>
                    <td>${total}</td>
                    <td>
                        <button class="btn btn-danger btn-sm remove" data-index="${index}">X</button>
                    </td>
                </tr>
            `;
        });
    
        $('#table-produk tbody').html(html);
    
        hitungTotal();
    }
    
    // =======================
    // UPDATE QTY
    // =======================
    $(document).on('change', '.qty', function () {
        let index = $(this).data('index');
        items[index].qty = parseInt($(this).val()) || 1;
        // renderTable();
        hitungTotal();
    });
    
    // =======================
    // UPDATE DISKON
    // =======================
    $(document).on('change', '.diskon', function () {
        let index = $(this).data('index');
        items[index].diskon = parseInt($(this).val()) || 0;
        renderTable();
    });
    
    $(document).on('focus', '.qty, .diskon', function () {
        $(this).select();
    });
    // =======================
    // HAPUS ITEM
    // =======================
    $(document).on('click', '.remove', function () {
        let index = $(this).data('index');
        items.splice(index, 1);
        renderTable();
    });
    
    // =======================
    // HITUNG TOTAL
    // =======================
    function hitungTotal() {
        let subtotal = 0;
    
        items.forEach(item => {
            subtotal += (item.harga * item.qty) - item.diskon;
        });
    
        let tax = parseInt($('#tax').val()) || 0;
        let diskon = parseInt($('#diskon_global').val()) || 0;
        let shipping = parseInt($('#shipping').val()) || 0;
    
        let totalTax = subtotal * (tax / 100);
        let grand = subtotal + totalTax - diskon + shipping;
    
         // tampilkan ke UI
        $('#tax_persen').text(tax);
        $('#tax_rp').text(Math.round(totalTax).toLocaleString());
        $('#diskon_rp').text(diskon.toLocaleString());
        $('#shipping_rp').text(shipping.toLocaleString());
        $('#grand_total').text(Math.round(grand).toLocaleString());
    }
    
    // =======================
    // TRIGGER HITUNG
    // =======================
    $('#tax, #diskon_global, #shipping').on('input', function () {
        hitungTotal();
    });
    
    // =======================
    // HIDE DROPDOWN
    // =======================
    $(document).click(function (e) {
        if (!$(e.target).closest('#search_produk').length) {
            $('#result_produk').html('');
        }
    });
    
    // =======================
    // SUBMIT FORM
    // =======================
    $('form').on('submit', function () {
        $('#items').val(JSON.stringify(items));
    });
    </script>
@endsection