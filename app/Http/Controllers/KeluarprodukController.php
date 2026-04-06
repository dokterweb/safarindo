<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use App\Models\Keluarproduk_detail;
use App\Models\Keluarproduk;
use App\Models\Paket;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class KeluarprodukController extends Controller
{
    public function index()
    {
        $keluarproduks = Keluarproduk::with('jamaah','paket')
        ->withCount('details') // jumlah produk
        ->withSum('details', 'total') // total harga
        ->latest()
        ->get();
        return view('keluarproduks.index', compact('keluarproduks'));
    }

    public function create(Request $request)
    {
        $jamaah = Jamaah::findOrFail($request->jamaah_id);
        $paket = Paket::findOrFail($request->paket_id);
    
        return view('keluarproduks.create', [
            'jamaah' => $jamaah,
            'paket' => $paket,
            'jamaah_id' => $jamaah->id,
            'paket_id' => $paket->id
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'paket_id' => 'required|exists:pakets,id',
            'tanggal' => 'required|date',
            'items' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $items = json_decode($request->items, true);

            if (!$items || count($items) == 0) {
                throw new \Exception('Item kosong');
            }

            // 🔥 HITUNG
            $subtotal = 0;
            foreach ($items as $i) {
                $subtotal += ($i['harga'] * $i['qty']) - $i['diskon'];
            }

            $tax = (int) $request->input('tax', 0);
            $diskonGlobal = (int) $request->input('diskon', 0);
            $shipping = (int) $request->input('shipping', 0);

            $totalTax = $subtotal * ($tax / 100);
            $grandTotal = $subtotal + $totalTax - $diskonGlobal + $shipping;

            // 🔥 INSERT HEADER
            $keluar = Keluarproduk::create([
                'jamaah_id'     => $request->jamaah_id,
                'paket_id'      => $request->paket_id,
                'tanggal'       => $request->tanggal,
                'tax'           => $tax,
                'diskon'        => $diskonGlobal,
                'shipping'      => $shipping,
                'statuskeluar'  => $request->statuskeluar,
                'metodekirim'   => $request->metodekirim,
                'grand_total'   =>  $grandTotal,
                'alamat'        => $request->alamat,
                'catatan'       => $request->catatan
            ]);

            $jamaah = Jamaah::find($request->jamaah_id);
            // 🔥 DETAIL + KURANGI STOK
            foreach ($items as $i) {

                $total = ($i['harga'] * $i['qty']) - $i['diskon'];

                Keluarproduk_detail::create([
                    'keluarproduk_id' => $keluar->id,
                    'produk_id' => $i['id'],
                    'harga' => $i['harga'],
                    'qty' => $i['qty'],
                    'diskon' => $i['diskon'],
                    'total' => $total
                ]);

                // 🔥 KURANGI STOK (beda dari pembelian!)
                $produk = Produk::find($i['id']);
                $produk->decrement('stok', $i['qty']);
                

                // 🔥 TRANSAKSI
                Transaksi::create([
                    'group_id' => 6, // penjualan
                    'referensi_id' => $keluar->id,
                    'paket_id' => $request->paket_id,
                    'jumlah' => $total,
                    'keterangan' => 'Penjualan ' . $produk->nama_produk .' Jamaah = '.$jamaah->nama_jamaah,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Penjualan berhasil');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $keluarproduk = keluarproduk::with(['jamaah', 'details.produk'])->findOrFail($id);

        // hitung subtotal dari detail
        $subtotal = $keluarproduk->details->sum(function ($item) {
            return ($item->harga * $item->qty) - $item->diskon;
        });

        $tax = $keluarproduk->tax;
        $diskon = $keluarproduk->diskon;
        $shipping = $keluarproduk->shipping;

        $totalTax = $subtotal * ($tax / 100);
        $grandTotal = $subtotal - $totalTax - $diskon - $shipping;

        return view('keluarproduks.show', compact(
            'keluarproduk',
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
        $keluar = Keluarproduk::with(['details.produk', 'jamaah'])->findOrFail($id);
    
        $items = $keluar->details->map(function($d){
            return [
                'id' => $d->produk_id,
                'nama' => $d->produk->nama_produk,
                'harga' => $d->harga,
                'stok' => $d->produk->stok,
                'qty' => $d->qty,
                'diskon' => $d->diskon
            ];
        });
    
        return view('keluarproduks.edit', compact('keluar','items'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
    
        try {
    
            $keluar = Keluarproduk::with('details')->findOrFail($id);
    
            // 🔥 1. BALIKKAN STOK LAMA
            foreach ($keluar->details as $d) {
                $produk = Produk::find($d->produk_id);
                $produk->increment('stok', $d->qty);
            }
    
            // 🔥 2. HAPUS DETAIL LAMA
            $keluar->details()->delete();
    
            // 🔥 3. HAPUS TRANSAKSI LAMA
            Transaksi::where('referensi_id', $keluar->id)
                ->where('group_id', 6) // penting biar tidak ganggu transaksi lain
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
            $keluar->update([
                'tax' => $tax,
                'diskon' => $diskonGlobal,
                'shipping' => $shipping,
                'grand_total' => $grandTotal,
                'alamat' => $request->alamat,
                'catatan' => $request->catatan
            ]);
            $jamaah = Jamaah::find($request->jamaah_id);

            // 🔥 7. INSERT DETAIL BARU + TRANSAKSI + STOK
            foreach ($items as $i) {
    
                $total = ($i['harga'] * $i['qty']) - $i['diskon'];
    
                // INSERT DETAIL
                Keluarproduk_detail::create([
                    'keluarproduk_id' => $keluar->id,
                    'produk_id' => $i['id'],
                    'harga' => $i['harga'],
                    'qty' => $i['qty'],
                    'diskon' => $i['diskon'],
                    'total' => $total
                ]);
    
                $produk = Produk::find($i['id']);
    
                // 🔥 VALIDASI STOK
                if ($produk->stok < $i['qty']) {
                    throw new \Exception('Stok tidak cukup untuk ' . $produk->nama_produk);
                }
    
                // 🔥 KURANGI STOK
                $produk->decrement('stok', $i['qty']);
    
                // 🔥 INSERT TRANSAKSI BARU
                Transaksi::create([
                    'group_id' => 6,
                    'referensi_id' => $keluar->id,
                    'paket_id' => $keluar->paket_id,
                    'jumlah' => $total,
                    'keterangan' => 'Penjualan ' . $produk->nama_produk.' qty ='.$i['qty'].' Jamaah = '.$jamaah->nama_jamaah,
                ]);
            }
    
            DB::commit();
    
            return redirect()->route('keluarproduks')
                ->with('success', 'Data berhasil diupdate');
    
        } catch (\Exception $e) {
    
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
