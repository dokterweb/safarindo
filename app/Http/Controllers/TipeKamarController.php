<?php

namespace App\Http\Controllers;
use App\Models\Tipe_kamar;
use Illuminate\Http\Request;

class TipeKamarController extends Controller
{
    public function index()
    {
        $tipe_kamars = Tipe_kamar::all();
        return view('tipe_kamars.index',compact('tipe_kamars'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_kamar'        => 'required|string|max:255',
            'harga_kamar'       => 'required|numeric|min:0',
        ]);
        try {
            Tipe_kamar::create([
                'nama_kamar'    => $request->nama_kamar,
                'harga_kamar'   => $request->harga_kamar,
            ]);
            // Notifikasi sukses
            return redirect()->route('kamars')
                             ->with('success', 'Data Hotel berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('kamars')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Tipe_kamar $tipe_kamar)
    {
        $tipe_kamarview = Tipe_kamar::all();
        // dd($tipe_kamar);
        return view('tipe_kamars.edit',compact('tipe_kamar','tipe_kamarview'));
    }

    public function update(Request $request, Tipe_kamar $tipe_kamar)
    {
        $request->validate([
            'nama_kamar'        => 'required|string|max:255',
            'harga_kamar'       => 'required|numeric|min:0',
        ]);

        try {
            $tipe_kamar->update([
                'nama_kamar'    => $request->nama_kamar,
                'harga_kamar'   => $request->harga_kamar,
            ]);

            return redirect()
                ->route('kamars')
                ->with('success', 'Data maskapai berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Temukan maskapai berdasarkan ID
            $tipe_kamar = Tipe_kamar::findOrFail($id);
            
            // Hapus data
            $tipe_kamar->delete();

            // Redirect dengan notifikasi sukses
            return redirect()->route('kamars')
                            ->with('success', 'Data tipe_kamar berhasil dihapus.');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan
            return redirect()->route('kamars')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
