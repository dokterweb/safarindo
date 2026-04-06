<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('units.index',compact('units'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_unit'         => 'required|string|max:255',
            'satuan_unit'         => 'required|string|max:255',
        ]);
        try {
            Unit::create([
                'nama_unit'         => $request->nama_unit,
                'satuan_unit'         => $request->satuan_unit,
            ]);
            // Notifikasi sukses
            return redirect()->route('units')
                             ->with('success', 'Data Unit berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('units')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Unit $unit)
    {
        $unitview = Unit::all();
        return view('units.edit',compact('unit','unitview'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'nama_unit'     => 'required|string|max:255',
            'satuan_unit'   => 'required|string|max:255',
        ]);

        try {
            $unit->update([
                'nama_unit'     => $request->nama_unit,
                'satuan_unit'   => $request->satuan_unit,
            ]);

            return redirect()
                ->route('units')
                ->with('success', 'Data Unit berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Temukan maskapai berdasarkan ID
            $unit = Unit::findOrFail($id);
            
            // Hapus data
            $unit->delete();

            // Redirect dengan notifikasi sukses
            return redirect()->route('units')
                            ->with('success', 'Data unit berhasil dihapus.');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan
            return redirect()->route('units')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
