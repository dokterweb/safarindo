<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Http\Requests\PaketStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Models\Jamaah;
use App\Models\Maskapai;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaketController extends Controller
{
    public function index()
    {
        $pakets = Paket::all();
        return view('pakets.index',compact('pakets'));
    }


  /*   public function indexpembayaran()
    {
        $jamaahs = Jamaah::with('paket')
            ->withSum('pembayarans as total_bayar', 'jumlah_bayar')
            ->get();
    
        return view('pembayarans.index', compact('jamaahs'));
    } */

    public function create()
    {
        $maskapais = Maskapai::all();
        $hotelmakahs = DB::table('hotels')->where('lokasi_hotel', '=', 'mekkah')->get();
        $hotelmadinahs = DB::table('hotels')->where('lokasi_hotel', '=', 'madinah')->get();
        $hoteltransits = DB::table('hotels')->where('lokasi_hotel', '=', 'transit')->get();
        return view('pakets.create',compact('maskapais','hotelmakahs','hotelmadinahs','hoteltransits'));
    }

    public function store(PaketStoreRequest $request)
    {
        try {
            // 1. Upload avatar jika ada
            if ($request->hasFile('foto_paket')) {
                $foto_paketPath = $request->file('foto_paket')->store('foto_pakets', 'public');
            } else {
                $foto_paketPath = null; // Jika tidak ada avatar
            }

            // 2. Membuat agent terkait dengan user
            $paket = Paket::create([
                'nama_paket'       => $request->nama_paket,
                'tgl_berangkat'    => $request->tgl_berangkat,
                'jlh_hari'         => $request->jlh_hari,
                'status'           => $request->status,
                'maskapai_id'      => $request->maskapai_id,
                'rute'             => $request->rute,
                'lokasi_berangkat' => $request->lokasi_berangkat,
                'kuota'            => $request->kuota,
                'jenis_paket'      => $request->jenis_paket,
                'hotel_makah_id'   => $request->hotel_makah_id,
                'hotel_madinah_id' => $request->hotel_madinah_id,
                'hotel_transit_id' => $request->hotel_transit_id,
                'harga_paket'      => $request->harga_paket,
                'include_desc'     => $request->include_desc,
                'exclude_desc'     => $request->exclude_desc,
                'syaratketentuan'  => $request->syaratketentuan,
                'catatan'          => $request->catatan,
                'foto_paket'       => $foto_paketPath,
            ]);
            
            
            // 5. Redirect dengan pesan sukses
            return redirect()->route('pakets')->with('success', 'paket berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Tangani error dan redirect kembali dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Paket $paket)
    {
        $maskapais = Maskapai::all();
        $hotelmakahs = DB::table('hotels')->where('lokasi_hotel', '=', 'mekkah')->get();
        $hotelmadinahs = DB::table('hotels')->where('lokasi_hotel', '=', 'madinah')->get();
        $hoteltransits = DB::table('hotels')->where('lokasi_hotel', '=', 'transit')->get();
        return view('pakets.edit',compact('maskapais','hotelmakahs','hotelmadinahs','hoteltransits','paket'));
    }

    public function update(UpdateStoreRequest $request, Paket $paket)
    {
        try {

            // 1️⃣ Handle upload foto jika ada file baru
            if ($request->hasFile('foto_paket')) {

                // Hapus foto lama jika ada
                if ($paket->foto_paket && Storage::exists('public/' . $paket->foto_paket)) {
                    Storage::delete('public/' . $paket->foto_paket);
                }

                // Upload foto baru
                $foto_paketPath = $request->file('foto_paket')->store('foto_pakets', 'public');

            } else {
                // Jika tidak upload baru, pakai foto lama
                $foto_paketPath = $paket->foto_paket;
            }

            // 2️⃣ Update data paket
            $paket->update([
                'nama_paket'       => $request->nama_paket,
                'tgl_berangkat'    => $request->tgl_berangkat,
                'jlh_hari'         => $request->jlh_hari,
                'status'           => $request->status,
                'maskapai_id'      => $request->maskapai_id,
                'rute'             => $request->rute,
                'lokasi_berangkat' => $request->lokasi_berangkat,
                'kuota'            => $request->kuota,
                'jenis_paket'      => $request->jenis_paket,
                'hotel_makah_id'   => $request->hotel_makah_id,
                'hotel_madinah_id' => $request->hotel_madinah_id,
                'hotel_transit_id' => $request->hotel_transit_id,
                'harga_paket'      => $request->harga_paket,
                'include_desc'     => $request->include_desc,
                'exclude_desc'     => $request->exclude_desc,
                'syaratketentuan'  => $request->syaratketentuan,
                'catatan'          => $request->catatan,
                'foto_paket'       => $foto_paketPath,
            ]);

            return redirect()->route('pakets')->with('success', 'Paket berhasil diperbarui!');

        } catch (\Exception $e) {

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Paket $paket)
    {
        $paket->load(['maskapai','hotelMakah','hotelMadinah','hotelTransit']);
        return view('pakets.show', compact('paket'));
    }


    public function destroy(Paket $paket)
    {
        try {
            // 1️⃣ Hapus foto jika ada
            if ($paket->foto_paket && Storage::exists('public/' . $paket->foto_paket)) {
                Storage::delete('public/' . $paket->foto_paket);
            }
            // 2️⃣ Hapus data paket
            $paket->delete();
            return redirect()->route('pakets')->with('success', 'Paket berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    
    public function indexjamaah()
    {
        $pakets = Paket::withCount('jamaahs')->get();
        return view('jamaahs.index', compact('pakets'));
    }

    public function indexpembayaran()
    {
        $pakets = Paket::withCount('jamaahs')->get();
        return view('pembayarans.index', compact('pakets'));
    }

    public function byPaket($id)
    {
        $paket = Paket::with('jamaahs')->findOrFail($id);
        return view('jamaahs.by_paket', compact('paket'));
    }

    public function detailPembayaran($id)
    {
        $paket = Paket::with([
            'jamaahs' => function ($q) {
                $q->withSum('pembayarans as total_bayar', 'jumlah_bayar');
            }
        ])->findOrFail($id);
        return view('pembayarans.by_paket', compact('paket'));
    }

    public function indexpembatalan()
    {
        $pakets = Paket::withCount('jamaahs')->get();
        return view('pembatalans.index', compact('pakets'));
    }

    public function detailPembatalan($id)
    {
        $paket = Paket::with([
            'jamaahs' => function ($q) {
                $q->withSum('pembayarans as total_bayar', 'jumlah_bayar');
            }
        ])->findOrFail($id);
        return view('pembatalans.by_paket', compact('paket'));
    }
}
