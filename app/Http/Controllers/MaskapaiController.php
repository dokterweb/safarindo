<?php

namespace App\Http\Controllers;

use App\Models\Maskapai;
use Illuminate\Http\Request;

class MaskapaiController extends Controller
{
    public function index()
    {
        $maskapais = Maskapai::all();
        return view('maskapais.index',compact('maskapais'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_maskapai'         => 'required|string|max:255',
            'rute_terbang'          => 'required|in:direct,transit',
            'lama_perjalanan'       => 'required|integer|min:1',
            'harga_tiket'           => 'required|integer|min:0',
            'catatan_keberangkatan' => 'nullable|string',
        ]);
        try {
            Maskapai::create([
                'nama_maskapai'         => $request->nama_maskapai,
                'rute_terbang'          => $request->rute_terbang,
                'lama_perjalanan'       => $request->lama_perjalanan,
                'harga_tiket'           => $request->harga_tiket,
                'catatan_keberangkatan' => $request->catatan_keberangkatan,
            ]);
            // Notifikasi sukses
            return redirect()->route('maskapais')
                             ->with('success', 'Data kelasnya berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('maskapais')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Maskapai $maskapai)
    {
        $maskapaiview = Maskapai::all();
        // dd($Maskapai);
        return view('maskapais.edit',compact('maskapai','maskapaiview'));
    }

    public function update(Request $request, Maskapai $maskapai)
    {
        $request->validate([
            'nama_maskapai'           => 'required|string|max:255',
            'rute_terbang'            => 'required|in:direct,transit',
            'lama_perjalanan'         => 'required|integer|min:1',
            'harga_tiket'             => 'required|integer|min:0',
            'catatan_keberangkatan'   => 'nullable|string',
        ]);

        try {
            $maskapai->update([
                'nama_maskapai'         => $request->nama_maskapai,
                'rute_terbang'          => $request->rute_terbang,
                'lama_perjalanan'       => $request->lama_perjalanan,
                'harga_tiket'           => $request->harga_tiket,
                'catatan_keberangkatan' => $request->catatan_keberangkatan,
            ]);

            return redirect()
                ->route('maskapais')
                ->with('success', 'Data maskapai berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Temukan maskapai berdasarkan ID
            $maskapai = Maskapai::findOrFail($id);
            
            // Hapus data
            $maskapai->delete();

            // Redirect dengan notifikasi sukses
            return redirect()->route('maskapais')
                            ->with('success', 'Data maskapai berhasil dihapus.');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan
            return redirect()->route('maskapais')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
