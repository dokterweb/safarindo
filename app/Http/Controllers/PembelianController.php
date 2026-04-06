<?php

namespace App\Http\Controllers;

use App\Models\Pembelian_detail;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\Supplier;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{

    public function index()
    {
        $pembelians = Pembelian::with('supplier')
        ->withCount('details') // jumlah produk
        ->withSum('details', 'total') // total harga
        ->latest()
        ->get();
        return view('pembelians.index', compact('pembelians'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $produks = Produk::all(); // nanti bisa diganti AJAX

        return view('pembelians.create', compact('suppliers','produks'));
    }


    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'items' => 'required'
        ]);

        DB::beginTransaction();

        try {

            // 🔥 decode items dari frontend
            $items = json_decode($request->items, true);

            if (!$items || count($items) == 0) {
                throw new \Exception('Item kosong');
            }

            // 🔥 HITUNG SUBTOTAL
            $subtotal = 0;

            foreach ($items as $i) {
                $subtotal += ($i['harga'] * $i['qty']) - $i['diskon'];
            }

           /*  $tax = $request->tax ?? 0;
            $diskonGlobal = $request->diskon ?? 0;
            $shipping = $request->shipping ?? 0; */

            $tax = (int) $request->input('tax', 0);
            $diskonGlobal = (int) $request->input('diskon', 0);
            $shipping = (int) $request->input('shipping', 0);

            $totalTax = $subtotal * ($tax / 100);
            $grandTotal = $subtotal + $totalTax - $diskonGlobal + $shipping;

            // 🔥 INSERT PEMBELIAN
            $pembelian = Pembelian::create([
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
                'tax' => $tax,
                'diskon' => $diskonGlobal,
                'shipping' => $shipping,
                'grand_total' => $grandTotal,
                'catatan' => $request->catatan
            ]);

            // 🔥 LOOP DETAIL
            foreach ($items as $i) {

                $total = ($i['harga'] * $i['qty']) - $i['diskon'];

                Pembelian_detail::create([
                    'pembelian_id' => $pembelian->id,
                    'produk_id' => $i['id'],
                    'harga' => $i['harga'],
                    'qty' => $i['qty'],
                    'diskon' => $i['diskon'],
                    'total' => $total
                ]);

                // 🔥 UPDATE STOK
                $produk = Produk::find($i['id']);
                $produk->increment('stok', $i['qty']);

                $totaltrx = $i['harga'] * $i['qty'];

                // 🔥 INSERT TRANSAKSI (PER PRODUK)
                Transaksi::create([
                    'group_id' => 3,
                    'referensi_id' => $pembelian->id,
                    'paket_id' => NULL,
                    'jumlah' => $totaltrx,
                    'keterangan' => 'Pembelian ' . $produk->nama_produk . ' (ID: ' . $produk->id . ')'
                ]);
            }

            DB::commit();

            return redirect()
                ->route('pembelians')
                ->with('success', 'Pembelian berhasil disimpan');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $pembelian = Pembelian::with(['supplier', 'details.produk'])->findOrFail($id);

        // hitung subtotal dari detail
        $subtotal = $pembelian->details->sum(function ($item) {
            return ($item->harga * $item->qty) - $item->diskon;
        });

        $tax = $pembelian->tax;
        $diskon = $pembelian->diskon;
        $shipping = $pembelian->shipping;

        $totalTax = $subtotal * ($tax / 100);
        $grandTotal = $subtotal - $totalTax - $diskon - $shipping;

        return view('pembelians.show', compact(
            'pembelian',
            'subtotal',
            'tax',
            'diskon',
            'shipping',
            'totalTax',
            'grandTotal'
        ));
    }

    public function edit($id)
    {
        $pembelian = Pembelian::with(['details.produk', 'supplier'])->findOrFail($id);

        $items = $pembelian->details->map(function($d){
            return [
                'id' => $d->produk_id,
                'nama' => $d->produk->nama_produk,
                'harga' => $d->harga,
                'stok' => $d->produk->stok,
                'qty' => $d->qty,
                'diskon' => $d->diskon
            ];
        })->values();

         // 🔥 TAMBAHAN INI
         $suppliers = Supplier::select('id','nama_supplier')->get();

        return view('pembelians.edit', compact('pembelian','items','suppliers'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $pembelian = Pembelian::with('details')->findOrFail($id);

            // 🔥 1. KURANGI STOK LAMA (karena dulu nambah)
            foreach ($pembelian->details as $d) {
                $produk = Produk::find($d->produk_id);
                $produk->decrement('stok', $d->qty);
            }

            // 🔥 2. HAPUS DETAIL LAMA
            $pembelian->details()->delete();

            // 🔥 3. HAPUS TRANSAKSI LAMA
            Transaksi::where('referensi_id', $pembelian->id)
                ->where('group_id', 3)
                ->delete();

            // 🔥 4. AMBIL DATA BARU
            $items = json_decode($request->items, true);

            if (!$items || count($items) == 0) {
                throw new \Exception('Item kosong');
            }

            // 🔥 5. HITUNG ULANG
            $subtotal = 0;
            foreach ($items as $i) {
                $subtotal += ($i['harga'] * $i['qty']) - $i['diskon'];
            }

            $tax = (int) $request->tax;
            $diskonGlobal = (int) $request->diskon;
            $shipping = (int) $request->shipping;

            $totalTax = $subtotal * ($tax / 100);
            $grandTotal = $subtotal + $totalTax - $diskonGlobal + $shipping;

            // 🔥 6. UPDATE HEADER
            $pembelian->update([
                'supplier_id' => $request->supplier_id,
                'tanggal' => $request->tanggal,
                'tax' => $tax,
                'diskon' => $diskonGlobal,
                'shipping' => $shipping,
                'catatan' => $request->catatan
            ]);

            // 🔥 7. INSERT DETAIL + TAMBAH STOK + TRANSAKSI
            foreach ($items as $i) {

                $total = ($i['harga'] * $i['qty']) - $i['diskon'];

                // INSERT DETAIL
                Pembelian_detail::create([
                    'pembelian_id' => $pembelian->id,
                    'produk_id' => $i['id'],
                    'harga' => $i['harga'],
                    'qty' => $i['qty'],
                    'diskon' => $i['diskon'],
                    'total' => $total
                ]);

                $produk = Produk::find($i['id']);

                // 🔥 TAMBAH STOK
                $produk->increment('stok', $i['qty']);

                // 🔥 TRANSAKSI BARU
                Transaksi::create([
                    'group_id' => 3,
                    'referensi_id' => $pembelian->id,
                    'paket_id' => null,
                    'jumlah' => $total,
                    'keterangan' => 'Pembelian ' . $produk->nama_produk
                ]);
            }

            DB::commit();

            return redirect()->route('pembelians')
                ->with('success', 'Pembelian berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
