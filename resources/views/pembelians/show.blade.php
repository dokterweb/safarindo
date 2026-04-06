@extends('layouts.app')

@section('content')
<div class="page-wrapper">
   
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row justify-content-center">
          <div class="col-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="h3">Safarido</p>
                            <address>
                              Street Address<br>
                              State, City<br>
                              Region, Postal Code<br>
                              ltd@example.com
                            </address>
                          </div>
                          <div class="col-6 text-end">
                            <p class="h3">Supplier</p>
                            <address>
                                {{ $pembelian->supplier->nama_supplier }}<br>
                                {{ $pembelian->supplier->no_hp }}<br>
                                {{ $pembelian->supplier->alamat }}<br>
                                {{ $pembelian->supplier->kota }}<br>
                            </address>
                          </div>
                          <div class="col-12 my-5">
                            <h1>Invoice #PB-{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}</h1>
                          </div>
                        </div>
                        <table class="table table-transparent table-responsive">
                            <thead>
                                <tr>
                                <th class="text-center" style="width: 1%"></th>
                                <th>Produk</th>
                                <th class="text-center" style="width: 1%">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Diskon</th>
                                <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            @foreach ($pembelian->details as $i => $d)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>
                                    <p class="strong mb-1">{{ $d->produk->nama_produk }}</p>
                                </td>
                                <td class="text-center">{{ $d->qty }}</td>
                                <td class="text-end">Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($d->diskon, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    Rp {{ number_format(($d->harga * $d->qty) - $d->diskon, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                          <tr>
                            <td colspan="5" class="strong text-end">Subtotal</td>
                            <td class="text-end">Rp. {{ number_format($subtotal, 0, ',', '.') }}</td>
                          </tr>
                          <tr>
                            <td colspan="5" class="strong text-end">Tax ({{ $tax }}%)</td>
                            <td class="text-end">Rp. {{ number_format($totalTax, 0, ',', '.') }}</td>
                          </tr>
                          <tr>
                            <td colspan="5" class="strong text-end">Diskon</td>
                            <td class="text-end">Rp. {{ number_format($diskon, 0, ',', '.') }}</td>
                          </tr>
                          <tr>
                            <td colspan="5" class="strong text-end">Shipping</td>
                            <td class="text-end">Rp. {{ number_format($shipping, 0, ',', '.') }}</td>
                          </tr>
                          <tr>
                            <td colspan="5" class="strong font-weight-bold text-uppercase text-end">Grand Total</td>
                            <td class="font-weight-bold text-end">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                          </tr>
                        </table>
                        <p class="text-muted text-center mt-5">Thank you very much for doing business with us. We look forward to working with
                          you again!</p>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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