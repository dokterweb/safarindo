<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use App\Models\Produk;
use App\Models\Jamaah_paket_produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class JamaahPaketProdukController extends Controller
{
    public function index($jamaah_id)
    {
        $jamaah = Jamaah::findOrFail($jamaah_id);

        // 🔥 ambil produk include
        $produks = Produk::where('include_paket', '1')->get();

        // 🔥 ambil data yang sudah pernah diambil
        $riwayat = Jamaah_paket_produk::where('jamaah_id', $jamaah_id)
                    ->get()
                    ->keyBy('produk_id');
        
        return view('jamaahs.paket_produks', compact('jamaah', 'produks', 'riwayat'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $jamaah_id = $request->jamaah_id;
            $paket_id = $request->paket_id;

            foreach ($request->qty as $produk_id => $qty) {

                if ($qty <= 0) continue;

                $produk = Produk::findOrFail($produk_id);

                // 🔥 VALIDASI STOK
                if ($produk->stok < $qty) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak cukup");
                }

                // 🔥 SIMPAN / UPDATE
                $data = Jamaah_paket_produk::firstOrNew([
                    'jamaah_id' => $jamaah_id,
                    'paket_id' => $paket_id,
                    'produk_id' => $produk_id
                ]);
                
                $data->qty_diambil += $qty;
                $data->tanggal = now();
                $data->save();

                // 🔥 KURANGI STOK
                $produk->decrement('stok', $qty);
            }

            DB::commit();

            return back()->with('success', 'Barang berhasil diserahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($jamaah_id)
    {
        $jamaah = Jamaah::findOrFail($jamaah_id);

        $produks = Produk::where('include_paket', '1')->get();

        $riwayat = Jamaah_paket_produk::where('jamaah_id', $jamaah_id)->get()->keyBy('produk_id');

        return view('jamaahs.edit_paket_produks', compact('jamaah', 'produks', 'riwayat'));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {

            $jamaah_id = $request->jamaah_id;
            $paket_id  = $request->paket_id;

            foreach ($request->qty as $produk_id => $qty_baru) {

                $qty_baru = (int) $qty_baru;

                $produk = Produk::findOrFail($produk_id);

                $data = Jamaah_paket_produk::where([
                    'jamaah_id' => $jamaah_id,
                    'paket_id'  => $paket_id,
                    'produk_id' => $produk_id
                ])->first();

                $qty_lama = $data->qty_diambil ?? 0;

                // =========================
                // 🔥 KASUS 1: QTY = 0 → HAPUS
                // =========================
                if ($qty_baru == 0) {

                    if ($data) {
                        // balikin stok
                        $produk->increment('stok', $qty_lama);

                        // hapus data
                        $data->delete();
                    }

                    continue;
                }

                // =========================
                // 🔥 HITUNG SELISIH
                // =========================
                $selisih = $qty_baru - $qty_lama;

                // =========================
                // 🔥 VALIDASI STOK
                // =========================
                if ($selisih > 0 && $produk->stok < $selisih) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak cukup");
                }

                // =========================
                // 🔥 UPDATE / INSERT
                // =========================
                if ($data) {
                    $data->update([
                        'qty_diambil' => $qty_baru,
                        'tanggal' => now()
                    ]);
                } else {
                    Jamaah_paket_produk::create([
                        'jamaah_id' => $jamaah_id,
                        'paket_id'  => $paket_id,
                        'produk_id' => $produk_id,
                        'qty_diambil' => $qty_baru,
                        'tanggal' => now()
                    ]);
                }

                // =========================
                // 🔥 ADJUST STOK
                // =========================
                if ($selisih > 0) {
                    $produk->decrement('stok', $selisih);
                } elseif ($selisih < 0) {
                    $produk->increment('stok', abs($selisih));
                }
            }

            DB::commit();

            return back()->with('success', 'Data berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
