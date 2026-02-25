<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index',compact('suppliers'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_supplier'     => 'required|string|max:255',
            'no_hp'             => 'required|string|max:255',
            'email'             => 'required|email',
            'kota'              => 'required|string|max:255',
            'alamat'            => 'nullable|string',
            'catatan'           => 'nullable|string',
        ]);
        try {
            Supplier::create([
                'nama_supplier' => $request->nama_supplier,
                'no_hp'         => $request->no_hp,
                'email'         => $request->email,
                'kota'          => $request->kota,
                'alamat'        => $request->alamat,
                'catatan'       => $request->catatan,
            ]);
            // Notifikasi sukses
            return redirect()->route('suppliers')
                             ->with('success', 'Data kelasnya berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('suppliers')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Supplier $supplier)
    {
        $supplierview = Supplier::all();
        // dd($Maskapai);
        return view('suppliers.edit',compact('supplier','supplierview'));
    }

    public function update(Request $request, Supplier $supplier)
    {
         // Validasi input
         $request->validate([
            'nama_supplier'     => 'required|string|max:255',
            'no_hp'             => 'required|string|max:255',
            'email'             => 'required|email',
            'kota'              => 'required|string|max:255',
            'alamat'            => 'nullable|string',
            'catatan'           => 'nullable|string',
        ]);
        try {
            $supplier->update([
                'nama_supplier' => $request->nama_supplier,
                'no_hp'         => $request->no_hp,
                'email'         => $request->email,
                'kota'          => $request->kota,
                'alamat'        => $request->alamat,
                'catatan'       => $request->catatan,
                
            ]);
            // Notifikasi sukses
            return redirect()->route('suppliers')->with('success', 'Data Mitra berhasil diupdate.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('suppliers')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Temukan maskapai berdasarkan ID
            $maskapai = Supplier::findOrFail($id);
            
            // Hapus data
            $maskapai->delete();

            // Redirect dengan notifikasi sukses
            return redirect()->route('suppliers')
                            ->with('success', 'Data maskapai berhasil dihapus.');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan
            return redirect()->route('suppliers')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
