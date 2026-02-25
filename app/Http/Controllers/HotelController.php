<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::all();
        return view('hotels.index',compact('hotels'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_hotel'        => 'required|string|max:255',
            'lokasi_hotel'      => 'required|string|max:255',
            'kontak_hotel'      => 'required|string|max:255',
            'rating_hotel'      => 'required|string|max:255',
            'email_hotel'       => 'required|email',
            'harga_hotel'       => 'required|integer|min:0',
            'catatan_hotel'     => 'nullable|string',
        ]);
        try {
            Hotel::create([
                'nama_hotel'        => $request->nama_hotel,
                'lokasi_hotel'      => $request->lokasi_hotel,
                'kontak_hotel'      => $request->kontak_hotel,
                'rating_hotel'      => $request->rating_hotel,
                'email_hotel'       => $request->email_hotel,
                'harga_hotel'       => $request->harga_hotel,
                'catatan_hotel'     => $request->catatan_hotel,
            ]);
            // Notifikasi sukses
            return redirect()->route('hotels')
                             ->with('success', 'Data Hotel berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Notifikasi error jika terjadi kesalahan
            return redirect()->route('hotels')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Hotel $hotel)
    {
        $hotelview = Hotel::all();
        // dd($Maskapai);
        return view('hotels.edit',compact('hotel','hotelview'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $request->validate([
            'nama_hotel'        => 'required|string|max:255',
            'lokasi_hotel'      => 'required|string|max:255',
            'kontak_hotel'      => 'required|string|max:255',
            'rating_hotel'      => 'required|string|max:255',
            'email_hotel'       => 'required|email',
            'harga_hotel'       => 'required|integer|min:0',
            'catatan_hotel'     => 'nullable|string',
        ]);

        try {
            $hotel->update([
                'nama_hotel'        => $request->nama_hotel,
                'lokasi_hotel'      => $request->lokasi_hotel,
                'kontak_hotel'      => $request->kontak_hotel,
                'rating_hotel'      => $request->rating_hotel,
                'email_hotel'       => $request->email_hotel,
                'harga_hotel'       => $request->harga_hotel,
                'catatan_hotel'     => $request->catatan_hotel,
            ]);

            return redirect()
                ->route('hotels')
                ->with('success', 'Data Hotel berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
