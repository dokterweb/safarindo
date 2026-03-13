<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaranbulanan;
use Illuminate\Http\Request;

class PengeluaranbulananController extends Controller
{
    public function index()
    {
        $keluarbulanans = Pengeluaranbulanan::all();
        return view('keluarbulanans.index',compact('keluarbulanans'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_pengeluaran'         => 'required|string|max:255',
        ]);
        try {
            Pengeluaranbulanan::create([
                'nama_pengeluaran'         => $request->nama_pengeluaran,
            ]);
            // Notifikasi sukses
            return redirect()->route('pengeluaranbulanans')
                             ->with('success', 'Data kelasnya berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('pengeluaranbulanans')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Pengeluaranbulanan $pengeluaranbulanan)
    {
        $keluarbulananview = Pengeluaranbulanan::all();
        // dd($Maskapai);
        return view('keluarbulanans.edit',compact('pengeluaranbulanan','keluarbulananview'));
    }

    public function update(Request $request, Pengeluaranbulanan $pengeluaranbulanan)
    {
        $request->validate([
            'nama_pengeluaran'         => 'required|string|max:255',
        ]);

        try {
            $pengeluaranbulanan->update([
                'nama_pengeluaran'         => $request->nama_pengeluaran,
            ]);

            return redirect()
                ->route('pengeluaranbulanans')
                ->with('success', 'Data maskapai berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Temukan maskapai berdasarkan ID
            $pengeluaranbulanan = Pengeluaranbulanan::findOrFail($id);
            
            // Hapus data
            $pengeluaranbulanan->delete();

            // Redirect dengan notifikasi sukses
            return redirect()->route('pengeluaranbulanans')
                            ->with('success', 'Data maskapai berhasil dihapus.');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan
            return redirect()->route('pengeluaranbulanans')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
