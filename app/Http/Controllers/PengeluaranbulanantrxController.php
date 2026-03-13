<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaranbulanan;
use App\Models\Pengeluaranbulanantrx;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengeluaranbulanantrxController extends Controller
{
    public function index()
    {
        $databulanan = Pengeluaranbulanan::all();
        $pengeluaranbulanantrxs = Pengeluaranbulanantrx::with('pengeluaran')->latest()->get();
        return view('keluarbulanantrxs.index',compact('pengeluaranbulanantrxs','databulanan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jumlah'            => ['required', 'integer', 'min:0'],
            'pengeluaran_id'    => ['required', 'integer', 'exists:pengeluaranbulanans,id'],
            'keterangan'        => ['nullable', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
    
                // Ambil master pengeluaran untuk dapat nama_pengeluaran
                $pengeluaran = Pengeluaranbulanan::findOrFail($validated['pengeluaran_id']);
    
                // 1) insert ke pengeluaranbulanantrxes
                $trx = Pengeluaranbulanantrx::create([
                    'jumlah'         => $validated['jumlah'],
                    'pengeluaran_id' => $validated['pengeluaran_id'],
                    'keterangan'     => $validated['keterangan'] ?? null,
                ]);
    
                // 2) insert ke transaksis
                Transaksi::create([
                    'group_id'     => 10,
                    'referensi_id' => (string) $trx->id, // karena di tabel transaksis varchar(255)
                    'paket_id'     => null,
                    'keterangan'   => $pengeluaran->nama_pengeluaran . ' - ' . $trx->jumlah,
    
                    // kalau kolom ini ada di transaksis, isi juga (sesuaikan kebutuhan)
                    // 'harga_paket' => $trx->jumlah,
                ]);
            });
    
            return redirect()
                ->route('pengeluaranbulanantrxs')
                ->with('success', 'Data pengeluaran berhasil ditambahkan.');
    
        } catch (\Throwable $e) {
            return redirect()
                ->route('pengeluaranbulanantrxs')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Pengeluaranbulanantrx $pengeluaranbulanantrx)
    {
        $databulanan = Pengeluaranbulanan::all();
        $keluarbulanantrxs = Pengeluaranbulanantrx::latest()->get();
        return view('keluarbulanantrxs.edit',compact('keluarbulanantrxs','pengeluaranbulanantrx','databulanan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jumlah'         => ['required', 'integer', 'min:0'],
            'pengeluaran_id' => ['required', 'integer', 'exists:pengeluaranbulanans,id'],
            'keterangan'     => ['nullable', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function () use ($validated, $id) {

                // 1) ambil trx yang mau diupdate
                $trx = Pengeluaranbulanantrx::findOrFail($id);

                // 2) update trx
                $trx->update([
                    'jumlah'         => $validated['jumlah'],
                    'pengeluaran_id' => $validated['pengeluaran_id'],
                    'keterangan'     => $validated['keterangan'] ?? null,
                ]);

                // 3) ambil master pengeluaran untuk nama_pengeluaran (buat keterangan transaksi)
                $pengeluaran = Pengeluaranbulanan::findOrFail($validated['pengeluaran_id']);

                // 4) update transaksis yang refer ke trx ini
                $transaksi = Transaksi::where('group_id', 10)
                    ->where('referensi_id', (string) $trx->id)
                    ->first();

                // kalau belum ada (misalnya data lama), buat baru
                if (!$transaksi) {
                    Transaksi::create([
                        'group_id'     => 10,
                        'referensi_id' => (string) $trx->id,
                        'paket_id'     => null,
                        'keterangan'   => $pengeluaran->nama_pengeluaran . ' - ' . $trx->jumlah,
                    ]);
                } else {
                    $transaksi->update([
                        'paket_id'   => null,
                        'keterangan' => $pengeluaran->nama_pengeluaran . ' - ' . $trx->jumlah,
                    ]);
                }
            });

            return redirect()
                ->route('pengeluaranbulanantrxs')
                ->with('success', 'Data pengeluaran berhasil diupdate.');

        } catch (\Throwable $e) {
            return redirect()
                ->route('pengeluaranbulanantrxs')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
    
                $trx = Pengeluaranbulanantrx::findOrFail($id);
    
                // soft delete transaksi terkait
                Transaksi::where('group_id', 10)
                    ->where('referensi_id', (string) $trx->id)
                    ->get()
                    ->each
                    ->delete();
    
                // soft delete trx utama
                $trx->delete();
            });
    
            return redirect()
                ->route('pengeluaranbulanantrxs')
                ->with('success', 'Data pengeluaran berhasil dihapus.');
    
        } catch (\Throwable $e) {
            return redirect()
                ->route('pengeluaranbulanantrxs')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
