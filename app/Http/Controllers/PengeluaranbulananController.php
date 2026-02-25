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
}
