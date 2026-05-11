<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jamaah;
use App\Models\Paket;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use App\Models\Diskon;
use App\Models\Jamaah_paket_produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;

class PembayaranController extends Controller
{
    public function detail(Jamaah $jamaah)
    {
        $data = $this->getDataPembayaran($jamaah);
         // 🔥 cek apakah sudah pernah serah terima
        // 🔥 FIX
        $sudahSerahTerima = Jamaah_paket_produk::where('jamaah_id', $jamaah->id)->exists();
        
        $punyaPembayaranPaket = $jamaah->pembayarans
        ->where('jenis', 'pembayaran_paket')
        ->count() > 0;
    
        $bolehSerahTerima = !is_null($jamaah->paket_id) && $punyaPembayaranPaket;

        return view('pembayarans.detail', array_merge($data, [
            'sudahSerahTerima' => $sudahSerahTerima,
            'bolehSerahTerima' => $bolehSerahTerima
        ]));
    }

    private function getDataPembayaran(Jamaah $jamaah)
    {
        $jamaah->load('paket', 'pembayarans');
        $pakets = Paket::where('status', 'active')->get();
        // 🔥 pisahkan
        $tabungan = $jamaah->pembayarans->where('jenis', 'tabungan')->sum('jumlah_bayar');

        $pembayaranPaket = $jamaah->pembayarans->where('jenis', 'pembayaran_paket')->sum('jumlah_bayar');
        $isTabungan = is_null($jamaah->paket_id);
        // 🔥 jika belum punya paket
        if (!$jamaah->paket_id) {
            return [
                'jamaah'        => $jamaah,
                'totalTabungan' => $tabungan,
                'mode'          => 'tabungan',
                'isTabungan'    => $isTabungan,
                'pakets'        => $pakets
            ];
        }

       //  jika sudah paket
        $tagihan = $jamaah->harga_final;

        //  ambil diskon
        $diskon = Diskon::where('jamaah_id', $jamaah->id)
            ->where('paket_id', $jamaah->paket_id)
            ->latest()
            ->first();
          /*   dd([
                'jamaah_id' => $jamaah->id,
                'paket_id' => $jamaah->paket_id,
            ]); */
        //  jika diskon approved
        $jumlahDiskon = 0;

        if ($diskon && $diskon->status == '1') {
            $jumlahDiskon = $diskon->jumlah_diskon;
        }

        //  hitung sisa
        $sisa = ($tagihan - $jumlahDiskon) - $pembayaranPaket;

        $infoDiskon = '<span class="badge bg-secondary">Tidak ada diskon</span>';

        if ($diskon) {
            if ($diskon->status == '0') {
                $infoDiskon = '
                    <span class="badge bg-warning">
                        Menunggu Persetujuan
                    </span>
                    <br>
                    Rp '.number_format($diskon->jumlah_diskon);

            } elseif ($diskon->status == '1') {
                $infoDiskon = '
                    <span class="badge bg-success">
                        Diskon Disetujui
                    </span>
                    <br>
                    Rp '.number_format($diskon->jumlah_diskon);
            }
        }

        return [
            'jamaah'        => $jamaah,
            'totalBayar'    => $pembayaranPaket,
            'tagihan'       => $tagihan,
            'sisa'          => $sisa,
            'totalTabungan' => $tabungan,
            'mode'          => 'paket',
            'isTabungan'    => $isTabungan,
            'pakets'        => $pakets,
            'diskon'        => $diskon,
            'infoDiskon'    => $infoDiskon,
        ];
    }

    public function store(Request $request, Jamaah $jamaah)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_bayar' => 'required|string',
            'bukti_bayar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
         // 🔥 CEK STATUS JAMAAH
        if ($jamaah->status == 'batal') {

            return back()->with(
                'error',
                'Jamaah atas nama ' . $jamaah->nama_jamaah . ' sudah dibatalkan.'
            );
        }

         // 🔥 CEK STATUS JAMAAH
        if ($jamaah->lunas == '1') {

            return back()->with(
                'error',
                'Jamaah atas nama ' . $jamaah->nama_jamaah . ' sudah lunas.'
            );
        }
        
        DB::beginTransaction();
    
        try {
    
            // 🔥 DETEKSI JENIS
            $jenis = $jamaah->paket_id ? 'pembayaran_paket' : 'tabungan';
    
            $totalBayar = $jamaah->pembayarans()->sum('jumlah_bayar');
    
            // 🔥 VALIDASI hanya untuk paket
            if ($jenis === 'pembayaran_paket') {
                $tagihan = $jamaah->harga_final ?? $jamaah->paket->harga_paket;
    
                if (($totalBayar + $request->jumlah_bayar) > $tagihan) {
                    DB::rollBack();
                    return back()->with('error', 'Pembayaran melebihi tagihan!');
                }
            }
    
            // upload bukti
            $bukti = null;
            if ($request->hasFile('bukti_bayar')) {
                $bukti = $request->file('bukti_bayar')->store('pembayaran', 'public');
            }
    
            // ✅ SIMPAN PEMBAYARAN
            $pembayaran = Pembayaran::create([
                'jamaah_id' => $jamaah->id,
                'paket_id' => $jamaah->paket_id, // bisa null
                'user_id' => auth()->id(),
                'jumlah_bayar' => $request->jumlah_bayar,
                'metode_bayar' => $request->metode_bayar,
                'bukti_bayar' => $bukti,
                'jenis' => $jenis, // 🔥 tambahan penting
            ]);
    
            // ✅ SIMPAN TRANSAKSI (ledger tetap jalan)
            Transaksi::create([
                'group_id' => 4, // pemasukan
                'referensi_id' => $pembayaran->id,
                'keterangan' => ($jenis === 'tabungan' 
                    ? 'Tabungan jamaah ' 
                    : 'Pembayaran paket jamaah ')
                    . $jamaah->nama_jamaah . ' - id: ' . $jamaah->id,
                'jumlah' => $pembayaran->jumlah_bayar,
                'paket_id' => $jamaah->paket_id,
            ]);
    
            // 🔥 UPDATE LUNAS (hanya paket)
            if ($jenis === 'pembayaran_paket') {
                $totalBaru = $totalBayar + $request->jumlah_bayar;
    
                if ($totalBaru >= $tagihan) {
                    $jamaah->update(['lunas' => '1']);
                }
            }
    
            DB::commit();
    
            return back()->with('success', 'Pembayaran berhasil ditambahkan');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_bayar' => 'required|string',
            'bukti_bayar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {

            $jamaah = $pembayaran->jamaah;

            $isTabungan = is_null($jamaah->paket_id);

            // 🔥 HITUNG TOTAL TANPA DATA INI
            $totalLain = $jamaah->pembayarans()
                ->where('id', '!=', $pembayaran->id)
                ->sum('jumlah_bayar');

            // =========================
            // 🔥 KHUSUS PAKET
            // =========================
            if (!$isTabungan) {

                $tagihan = $jamaah->paket->harga_paket;

                if (($totalLain + $request->jumlah_bayar) > $tagihan) {
                    return back()->with('error', 'Pembayaran melebihi tagihan!');
                }
            }

            // =========================
            // 🔥 UPLOAD FILE
            // =========================
            $bukti = $pembayaran->bukti_bayar;

            if ($request->hasFile('bukti_bayar')) {

                if ($bukti && Storage::disk('public')->exists($bukti)) {
                    Storage::disk('public')->delete($bukti);
                }

                $bukti = $request->file('bukti_bayar')->store('pembayaran', 'public');
            }

            // =========================
            // 🔥 UPDATE PEMBAYARAN
            // =========================
            $pembayaran->update([
                'jumlah_bayar' => $request->jumlah_bayar,
                'metode_bayar' => $request->metode_bayar,
                'bukti_bayar' => $bukti,
            ]);

            // =========================
            // 🔥 UPDATE TRANSAKSI
            // =========================
            Transaksi::where('referensi_id', $pembayaran->id)
                ->where('group_id', 4)
                ->delete();

            Transaksi::create([
                'group_id' => 4,
                'referensi_id' => $pembayaran->id,
                'keterangan' => 'Update pembayaran jamaah ' . $jamaah->nama_jamaah,
                'jumlah' => $request->jumlah_bayar,
                'paket_id' => $jamaah->paket_id, // null kalau tabungan (OK)
            ]);

            // =========================
            // 🔥 UPDATE STATUS (HANYA PAKET)
            // =========================
            if (!$isTabungan) {

                $totalBaru = $totalLain + $request->jumlah_bayar;

                $jamaah->update([
                    'lunas' => $totalBaru >= $tagihan ? '1' : '0'
                ]);
            }

            DB::commit();

            return back()->with('success', 'Pembayaran berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function print(Jamaah $jamaah)
    {
        $jamaah->load(['paket', 'pembayarans']);

        // 🔥 TOTAL PEMBAYARAN
        $totalBayar = $jamaah->pembayarans->sum('jumlah_bayar');

        // 🔥 DETEKSI TABUNGAN / PAKET
        $isTabungan = is_null($jamaah->paket_id);

        // 🔥 DEFAULT
        $tagihan = 0;
        $sisa = 0;
        $jumlahDiskon = 0;
        // 🔥 JIKA SUDAH PAKET
        if (!$isTabungan && $jamaah->paket) {

            $tagihan = $jamaah->harga_final;
        
            // 🔥 AMBIL DISKON APPROVED
            $jumlahDiskon = Diskon::where('jamaah_id', $jamaah->id)
                ->where('paket_id', $jamaah->paket_id)
                ->where('status', '1')
                ->value('jumlah_diskon') ?? 0;
        
            // 🔥 HITUNG SISA
            $sisa = ($tagihan - $jumlahDiskon) - $totalBayar;
        }

        // 🔥 INIT PDF
        $mpdf = new Mpdf([
            'format' => 'A4',
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
        ]);

        // 🔥 BACKGROUND
        $mpdf->showImageErrors = true;

        $mpdf->SetDefaultBodyCSS(
            'background',
            "url('" . public_path('storage/img/backgroundsurat.png') . "')"
        );

        $mpdf->SetDefaultBodyCSS('background-image-resize', 6);

        // 🔥 PILIH VIEW
        $view = $isTabungan
            ? 'pembayarans.pdf_tabungan'
            : 'pembayarans.pdf';

        // 🔥 RENDER VIEW
        $html = view($view, compact('jamaah','totalBayar','tagihan','sisa','isTabungan','jumlahDiskon'))->render();

        // 🔥 WRAPPER
        $content = '
            <div style="
                padding-top: 200px;
                padding-left: 40px;
                padding-right: 40px;
                padding-bottom: 120px;
                font-family: sans-serif;
                font-size: 12px;
            ">
                '.$html.'
            </div>
        ';

        $mpdf->WriteHTML($content);

        // 🔥 NAMA FILE
        $namaFile = $isTabungan
            ? 'tabungan-'.$jamaah->nama_jamaah.'.pdf'
            : 'invoice-'.$jamaah->nama_jamaah.'.pdf';

        return response(
            $mpdf->Output($namaFile, 'I')
        )->header('Content-Type', 'application/pdf');
    }

}
