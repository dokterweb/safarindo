<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('unit')->get();
        return view('produks.index', compact('produks'));
    }

    public function create()
    {
        $units = Unit::all();
        return view('produks.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'unit_id' => 'required|exists:units,id',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'foto_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $foto = null;

        if ($request->hasFile('foto_produk')) {
            $foto = $request->file('foto_produk')->store('produk', 'public');
        }

        Produk::create([
            'nama_produk' => $request->nama_produk,
            'standar_stok' => $request->standar_stok,
            'stok' => $request->stok,
            'unit_id' => $request->unit_id,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'catatan' => $request->catatan,
            'foto_produk' => $foto
        ]);

        return redirect()->route('produks')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $units = Unit::all();

        return view('produks.edit', compact('produk','units'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required',
            'unit_id' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'foto_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();

        // 🔥 HANDLE FOTO
        if ($request->hasFile('foto_produk')) {

            // hapus foto lama
            if ($produk->foto_produk && Storage::disk('public')->exists($produk->foto_produk)) {
                Storage::disk('public')->delete($produk->foto_produk);
            }

            $data['foto_produk'] = $request->file('foto_produk')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('produks')
            ->with('success', 'Produk berhasil diupdate');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // hapus file foto
        if ($produk->foto_produk && Storage::disk('public')->exists($produk->foto_produk)) {
            Storage::disk('public')->delete($produk->foto_produk);
        }

        $produk->delete();

        return back()->with('success', 'Produk berhasil dihapus');
    }

    public function search(Request $request)
    {
        $search = $request->q;

        $produks = Produk::where('nama_produk', 'like', "%$search%")
            ->limit(10)
            ->get();

        return response()->json($produks);
    }
}
