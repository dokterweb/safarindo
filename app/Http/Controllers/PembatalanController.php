<?php

namespace App\Http\Controllers;

use App\Models\Agent_transaksi;
use App\Models\Pembatalan;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class PembatalanController extends Controller
{

    public function indexsetuju()
    {
        $pembatalans = Pembatalan::with('jamaah')->get();
        return view('pembatalans.indexsetuju', compact('pembatalans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'paket_id' => 'required|exists:pakets,id',
            'jenis' => 'required|in:pemindahan,pembatalan',
            'paket_tujuan_id' => 'required_if:jenis,pemindahan|nullable|exists:pakets,id',
            'pengembalian_uang' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string'
        ]);

        Pembatalan::create([
            'jamaah_id' => $request->jamaah_id,
            'paket_id' => $request->paket_id,
            'paket_tujuan_id' => $request->paket_tujuan_id,
            'jenis' => $request->jenis,
            'pengembalian_uang' => $request->pengembalian_uang,
            'keterangan' => $request->keterangan,
            'status' => 'pending',
            'disetujui_oleh' => NULL,
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim dan menunggu persetujuan admin.');
    }

    public function show($id)
    {
        $pembatalan = Pembatalan::with(['jamaah', 'paket', 'paketTujuan'])->findOrFail($id);
        return response()->json($pembatalan);
    }

    public function approve($id)
    {
        DB::beginTransaction();
    
        try {
            $pembatalan = Pembatalan::with(['jamaah', 'paket', 'paketTujuan'])
                ->findOrFail($id);
    
            if ($pembatalan->status !== 'pending') {
                throw new \Exception('Permintaan sudah diproses.');
            }
    
            $jamaah = $pembatalan->jamaah;
    
            // 🔹 Jika Pembatalan
            if ($pembatalan->jenis === 'pembatalan') {
    
                $jamaah->update([
                    'status' => 'batal'
                ]);
    
                // Simpan transaksi pengembalian dana
                Transaksi::create([
                    'group_id' => 6,
                    'referensi_id' => $pembatalan->id,
                    'paket_id' => $pembatalan->paket_id,
                    'jumlah' => $pembatalan->pengembalian_uang ?? 0,
                    'keterangan' => 'Pembatalan ' . $jamaah->nama_jamaah .
                        ' id_jamaah ' . $jamaah->id .
                        ' : paket = ' . $pembatalan->paket->nama_paket,
                ]);
            }
    
            // 🔹 Jika Pemindahan Paket
            if ($pembatalan->jenis === 'pemindahan') {

                $jamaah = $pembatalan->jamaah;
            
                // Update paket pada tabel jamaahs
                $jamaah->update([
                    'paket_id' => $pembatalan->paket_tujuan_id,
                    'status' => 'aktif'
                ]);
            
                // Ambil ID pembayaran yang terkait dengan paket lama
                $pembayaranIds = DB::table('pembayarans')
                    ->where('jamaah_id', $jamaah->id)
                    ->where('paket_id', $pembatalan->paket_id)
                    ->pluck('id');
            
                // Update paket_id pada tabel pembayarans
                DB::table('pembayarans')
                    ->whereIn('id', $pembayaranIds)
                    ->update([
                        'paket_id' => $pembatalan->paket_tujuan_id
                    ]);
            
                // Update paket_id pada tabel transaksis
                DB::table('transaksis')
                    ->where('group_id', 4) // transaksi pembayaran jamaah
                    ->whereIn('referensi_id', $pembayaranIds)
                    ->update([
                        'paket_id' => $pembatalan->paket_tujuan_id
                    ]);
            }
    
            // Update status persetujuan
            $pembatalan->update([
                'status' => 'disetujui',
                'disetujui_oleh' => Auth::id(),
                'disetujui_pada' => Carbon::now(),
            ]);
    
            DB::commit();
    
            return redirect()->back()->with('success', 'Permintaan berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function reject($id)
    {
        $pembatalan = Pembatalan::findOrFail($id);

        if ($pembatalan->status !== 'pending') {
            return back()->with('error', 'Permintaan sudah diproses.');
        }

        $pembatalan->update([
            'status' => 'ditolak'
        ]);

        return back()->with('success', 'Permintaan berhasil ditolak.');
    }

    public function indexprint()
    {
        $data = Pembatalan::with(['jamaah.paket', 'paket'])
        ->where('jenis', 'pemindahan')
        ->latest()->get();
        return view('pembatalans.index_print', compact('data'));
    }

    public function print(Pembatalan $pembatalan)
    {
        $pembatalan->load(['jamaah', 'paket', 'paketTujuan']);

        $jamaah = $pembatalan->jamaah;
        $paketAwal = $pembatalan->paket;
        $paketBaru = $pembatalan->paketTujuan;

        // 🔥 Format tanggal
        $tanggalSurat = Carbon::now()->translatedFormat('d F Y');

        $tglAwal = Carbon::parse($paketAwal->tgl_berangkat)
            ->translatedFormat('d F Y');

        $tglBaru = Carbon::parse($paketBaru->tgl_berangkat)
            ->translatedFormat('d F Y');

        // INIT PDF
        $mpdf = new Mpdf([
            'format' => 'A4',
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
        ]);

        // Background full (kop surat kamu)
        $mpdf->showImageErrors = true;
        $mpdf->SetDefaultBodyCSS('background', "url('" . public_path('storage/img/backgroundsurat.png') . "')");
        $mpdf->SetDefaultBodyCSS('background-image-resize', 6);

        // Ambil view
        $html = view('pembatalans.pdf', compact('pembatalan','jamaah','paketAwal','paketBaru','tglAwal', 'tglBaru'))->render();

        // Wrapper supaya tidak nabrak header/footer
        $content = '
            <div style="
                padding-top: 180px;
                padding-left: 60px;
                padding-right: 60px;
                padding-bottom: 120px;
                font-family: sans-serif;
                font-size: 12px;
                line-height: 1.8;
            ">
                '.$html.'
            </div>
        ';

        $mpdf->WriteHTML($content);

        return response($mpdf->Output(
            'surat-rekom-'.$jamaah->nama_jamaah.'.pdf',
            'I'
        ))->header('Content-Type', 'application/pdf');
    }
}
