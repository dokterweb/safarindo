<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class PembayaranController extends Controller
{
   /*  public function detail($id)
    {
        $jamaah = Jamaah::with(['paket', 'pembayarans'])->findOrFail($id);

        $totalBayar = $jamaah->pembayarans->sum('jumlah_bayar');
        $tagihan = $jamaah->paket->harga_paket;
        $sisa = $tagihan - $totalBayar;

        return view('pembayarans.detail', compact('jamaah', 'totalBayar', 'tagihan', 'sisa'));
    } */

    public function detail(Jamaah $jamaah)
    {
        $jamaah->load('paket', 'pembayarans');

        $totalBayar = $jamaah->pembayarans->sum('jumlah_bayar');
        $tagihan = $jamaah->paket->harga_paket;
        $sisa = $tagihan - $totalBayar;

        return view('pembayarans.detail', compact('jamaah','totalBayar','tagihan','sisa'));
    }

    public function store(Request $request, Jamaah $jamaah)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_bayar' => 'required|string',
            'bukti_bayar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        DB::beginTransaction();
    
        try {
    
            $totalBayar = $jamaah->pembayarans()->sum('jumlah_bayar');
            $tagihan = $jamaah->paket->harga_paket;
    
            // 🔥 VALIDASI OVERPAYMENT
            if (($totalBayar + $request->jumlah_bayar) > $tagihan) {
                return back()->with('error', 'Pembayaran melebihi tagihan!');
            }
    
            // upload bukti
            $bukti = null;
            if ($request->hasFile('bukti_bayar')) {
                $bukti = $request->file('bukti_bayar')->store('pembayaran', 'public');
            }
    
            // ✅ SIMPAN PEMBAYARAN
            $pembayaran = Pembayaran::create([
                'jamaah_id' => $jamaah->id,
                'paket_id' => $jamaah->paket_id,
                'user_id' => auth()->id(),
                'jumlah_bayar' => $request->jumlah_bayar,
                'metode_bayar' => $request->metode_bayar,
                'bukti_bayar' => $bukti,
            ]);
    
            // ✅ SIMPAN TRANSAKSI
            Transaksi::create([
                'group_id' => 4,
                'referensi_id' => $pembayaran->id,
                'keterangan' => 'Pembayaran cicilan jamaah ' . $jamaah->nama_jamaah . ' - id_jamaah: ' . $jamaah->id,
                'jumlah' => $pembayaran->jumlah_bayar,
                'paket_id' => $jamaah->paket_id,
            ]);
    
            // 🔥 OPTIONAL: UPDATE STATUS LUNAS
            $totalBaru = $totalBayar + $request->jumlah_bayar;
            if ($totalBaru >= $tagihan) {
                $jamaah->update(['lunas' => '1']);
            }
    
            DB::commit();
    
            return back()->with('success', 'Pembayaran berhasil ditambahkan');
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_bayar' => 'required|string',
            'bukti_bayar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        DB::beginTransaction();
    
        try {
    
            $jamaah = $pembayaran->jamaah;
    
            // 🔥 HITUNG ULANG
            $totalLain = $jamaah->pembayarans()
                ->where('id', '!=', $pembayaran->id)
                ->sum('jumlah_bayar');
    
            $tagihan = $jamaah->paket->harga_paket;
    
            if (($totalLain + $request->jumlah_bayar) > $tagihan) {
                return back()->with('error', 'Pembayaran melebihi tagihan!');
            }
    
            // 🔥 HANDLE UPLOAD BARU
            $bukti = $pembayaran->bukti_bayar;
    
            if ($request->hasFile('bukti_bayar')) {
    
                // hapus lama
                if ($bukti && Storage::disk('public')->exists($bukti)) {
                    Storage::disk('public')->delete($bukti);
                }
    
                // upload baru
                $bukti = $request->file('bukti_bayar')->store('pembayaran', 'public');
            }
    
            // 🔥 UPDATE PEMBAYARAN
            $pembayaran->update([
                'jumlah_bayar' => $request->jumlah_bayar,
                'metode_bayar' => $request->metode_bayar,
                'bukti_bayar' => $bukti,
            ]);
    
            // 🔥 HAPUS TRANSAKSI LAMA
            Transaksi::where('referensi_id', $pembayaran->id)
                ->where('group_id', 4)
                ->delete();
    
            // 🔥 INSERT TRANSAKSI BARU
            Transaksi::create([
                'group_id' => 4,
                'referensi_id' => $pembayaran->id,
                'keterangan' => 'Update pembayaran jamaah ' . $jamaah->nama_jamaah,
                'jumlah' => $request->jumlah_bayar,
                'paket_id' => $jamaah->paket_id,
            ]);
    
            // 🔥 UPDATE STATUS
            $totalBaru = $totalLain + $request->jumlah_bayar;
    
            $jamaah->update([
                'lunas' => $totalBaru >= $tagihan ? '1' : '0'
            ]);
    
            DB::commit();
    
            return back()->with('success', 'Pembayaran berhasil diupdate');
    
        } catch (\Exception $e) {
    
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
