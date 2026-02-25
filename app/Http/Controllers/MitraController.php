<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class MitraController extends Controller
{
    public function index()
    {
        $mitras = Mitra::all();
        return view('mitras.index',compact('mitras'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_mitra'        => 'required|string|max:255',
            'no_hp'             => 'required|string|max:255',
            'email'             => 'required|email',
            'kota'              => 'required|string|max:255',
            'kelamin'           => 'required|string|in:laki-laki,perempuan', 
            'status'            => 'required|string|in:active,non_active', 
            'alamat'            => 'nullable|string',
            'catatan'           => 'nullable|string',
        ]);
        try {
            Mitra::create([
                'nama_mitra'    => $request->nama_mitra,
                'no_hp'         => $request->no_hp,
                'email'         => $request->email,
                'kota'          => $request->kota,
                'kelamin'       => $request->kelamin,
                'status'        => $request->status,
                'alamat'        => $request->alamat,
                'catatan'       => $request->catatan,
                
            ]);
            // Notifikasi sukses
            return redirect()->route('mitras.index')
                             ->with('success', 'Data kelasnya berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('mitras.index')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Mitra $mitra)
    {
        //
    }

    public function edit(Mitra $mitra)
    {
        $mitraview = Mitra::all();
        // dd($Maskapai);
        return view('mitras.edit',compact('mitra','mitraview'));
    }

    public function update(Request $request, Mitra $mitra)
    {
         // Validasi input
         $request->validate([
            'nama_mitra'        => 'required|string|max:255',
            'no_hp'             => 'required|string|max:255',
            'email'             => 'required|email',
            'kota'              => 'required|string|max:255',
            'kelamin'           => 'required|string|in:laki-laki,perempuan', 
            'status'            => 'required|string|in:active,non_active', 
            'alamat'            => 'nullable|string',
            'catatan'           => 'nullable|string',
        ]);
        try {
            $mitra->update([
                'nama_mitra'    => $request->nama_mitra,
                'no_hp'         => $request->no_hp,
                'email'         => $request->email,
                'kota'          => $request->kota,
                'kelamin'       => $request->kelamin,
                'status'        => $request->status,
                'alamat'        => $request->alamat,
                'catatan'       => $request->catatan,
                
            ]);
            // Notifikasi sukses
            return redirect()->route('mitras.index')->with('success', 'Data Mitra berhasil diupdate.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('mitras.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Mitra $mitra)
    {
        DB::beginTransaction();
        try {
            $mitra->delete();
            DB::commit();
            return redirect()->route('mitras.index')->with('success', 'mitra berhasil di hapus.');;
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('mitras.index');
        }
    }
}
