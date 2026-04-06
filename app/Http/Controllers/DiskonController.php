<?php

namespace App\Http\Controllers;

use App\Models\Diskon;
use App\Models\Jamaah;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiskonController extends Controller
{
    public function index()
    {
        $diskons = Diskon::with(['paket', 'jamaah'])->get();
        return view('diskons.index', compact('diskons'));
    }

    public function store(Request $request, Jamaah $jamaah)
    {
        $request->validate([
            'diskon' => 'nullable|numeric|min:0'
        ]);

        if ($request->diskon > 0) {
            Diskon::create([
                'paket_id'      => $jamaah->paket_id,
                'jamaah_id'     => $jamaah->id,
                'user_id'       => auth()->id(),
                'jumlah_diskon' => $request->diskon, // 🔥 pastikan kolom ini ada
                'status'        => '0' // pending
            ]);
        }

        return back()->with('success', 'Request diskon berhasil dikirim');
    }

    public function approve($id)
    {
        DB::beginTransaction();

        try {
            $diskon = Diskon::with('paket', 'jamaah')->findOrFail($id);

            // 🔥 Cegah double approve
            if ($diskon->status == '1') {
                return back()->with('error', 'Diskon sudah di-approve');
            }

            // ✅ Update status
            $diskon->update([
                'status' => '1'
            ]);

            // ✅ Insert ke transaksi
            Transaksi::create([
                'group_id' => 7,
                'referensi_id' => $diskon->id,
                'paket_id' => $diskon->paket_id,
                'jumlah' => $diskon->jumlah_diskon,
                'keterangan' => 'Diskon jamaah ' . $diskon->jamaah->nama_jamaah
            ]);

            DB::commit();

            return back()->with('success', 'Diskon berhasil di-approve');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
}
