<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Jamaah;
use App\Models\Paket;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use Mpdf\Mpdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreJamaahRequest;
use Illuminate\Http\Request;

class JamaahController extends Controller
{
    public function prospek()
    {
        $jamaahs = Jamaah::with('agent')
            ->where('status', 'prospek')
            ->latest()
            ->get();
    
        return view('jamaahs.prospek', compact('jamaahs'));
    }

    public function createByPaket($id)
    {
        $paket = Paket::findOrFail($id);
        $agents = Agent::all();
        return view('jamaahs.create', compact('paket','agents'));
    }

    public function storeByPaket(StoreJamaahRequest $request, $id)
    {
        $paket = Paket::findOrFail($id);

        // 🔥 VALIDASI KUOTA
        if ($paket->jamaahs()->count() >= $paket->kuota) {
            return back()->with('error', 'Kuota paket sudah penuh');
        }

        // 🔥 HANDLE UPLOAD FILE
        $data = $request->validated();

        $uploadFields = [
            'foto_jamaah',
            'foto_ktp',
            'foto_kk',
            'foto_pasport1',
            'foto_pasport2'
        ];

        foreach ($uploadFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('jamaah', 'public');
            }
        }

        // 🔥 INSERT DATA
        Jamaah::create([
            'paket_id' => $paket->id,
            'agent_id' => $data['agent_id'], // sesuaikan jika relasi agent beda
            'nik' => $data['nik'],
            'nama_jamaah' => $data['nama_jamaah'],
            'no_hp' => $data['no_hp'],
            'kota' => $data['kota'],
            'kelamin' => $data['kelamin'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'alamat' => $data['alamat'],
            'catatan' => $data['catatan'],

            // Passport
            'nama_jamaah_pasport' => $data['nama_jamaah_pasport'] ?? null,
            'no_pasport' => $data['no_pasport'] ?? null,
            'penerbit' => $data['penerbit'] ?? null,
            'pasport_aktif' => $data['pasport_aktif'] ?? null,
            'pasport_expired' => $data['pasport_expired'] ?? null,

            // File
            'foto_jamaah' => $data['foto_jamaah'] ?? null,
            'foto_ktp' => $data['foto_ktp'] ?? null,
            'foto_kk' => $data['foto_kk'] ?? null,
            'foto_pasport1' => $data['foto_pasport1'] ?? null,
            'foto_pasport2' => $data['foto_pasport2'] ?? null,

            // Default
            'status' => 'aktif',
            'lunas' => '0'
        ]);

        return redirect()
            ->route('pakets.jamaah.detail', $paket->id)
            ->with('success', 'Jamaah berhasil ditambahkan');
    }

    public function show($id)
    {
        $jamaah = Jamaah::with('paket', 'agent.user')->findOrFail($id);
        return view('jamaahs.show', compact('jamaah'));
    }

    public function edit($id)
    {
        $jamaah = Jamaah::findOrFail($id);
        $agents = Agent::with('user')->get();

        return view('jamaahs.edit', compact('jamaah', 'agents'));
    }

    public function update(StoreJamaahRequest $request, $id)
    {
        $jamaah = Jamaah::findOrFail($id);
        $data = $request->validated();

        $uploadFields = [
            'foto_jamaah',
            'foto_ktp',
            'foto_kk',
            'foto_pasport1',
            'foto_pasport2'
        ];

        foreach ($uploadFields as $field) {
            if ($request->hasFile($field)) {

                // hapus file lama jika ada
                if ($jamaah->$field && Storage::disk('public')->exists($jamaah->$field)) {
                    Storage::disk('public')->delete($jamaah->$field);
                }

                $data[$field] = $request->file($field)->store('jamaah', 'public');
            }
        }

        $jamaah->update([
            'agent_id' => $data['agent_id'],
            'nik' => $data['nik'],
            'nama_jamaah' => $data['nama_jamaah'],
            'no_hp' => $data['no_hp'],
            'kota' => $data['kota'],
            'kelamin' => $data['kelamin'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'alamat' => $data['alamat'],
            'catatan' => $data['catatan'],

            // passport
            'nama_jamaah_pasport' => $data['nama_jamaah_pasport'] ?? null,
            'no_pasport' => $data['no_pasport'] ?? null,
            'penerbit' => $data['penerbit'] ?? null,
            'pasport_aktif' => $data['pasport_aktif'] ?? null,
            'pasport_expired' => $data['pasport_expired'] ?? null,

            // file
            'foto_jamaah' => $data['foto_jamaah'] ?? $jamaah->foto_jamaah,
            'foto_ktp' => $data['foto_ktp'] ?? $jamaah->foto_ktp,
            'foto_kk' => $data['foto_kk'] ?? $jamaah->foto_kk,
            'foto_pasport1' => $data['foto_pasport1'] ?? $jamaah->foto_pasport1,
            'foto_pasport2' => $data['foto_pasport2'] ?? $jamaah->foto_pasport2,
        ]);

        return redirect()
            ->route('jamaahs.show', $jamaah->id)
            ->with('success', 'Data jamaah berhasil diupdate');
    }

    public function createTabungan()
    {
        $agents = Agent::all();
        return view('jamaahs.create_tabungan', compact('agents'));
    }

    public function storeTabungan(StoreJamaahRequest $request)
    {
        // 🔥 HANDLE UPLOAD FILE
        $data = $request->validated();

        $uploadFields = [
            'foto_jamaah',
            'foto_ktp',
            'foto_kk',
            'foto_pasport1',
            'foto_pasport2'
        ];

        foreach ($uploadFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('jamaah', 'public');
            }
        }

        // 🔥 INSERT DATA
        Jamaah::create([
            'paket_id' => null,
            'agent_id' => $data['agent_id'], // sesuaikan jika relasi agent beda
            'nik' => $data['nik'],
            'nama_jamaah' => $data['nama_jamaah'],
            'no_hp' => $data['no_hp'],
            'kota' => $data['kota'],
            'kelamin' => $data['kelamin'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'alamat' => $data['alamat'],
            'catatan' => $data['catatan'],

            // Passport
            'nama_jamaah_pasport' => $data['nama_jamaah_pasport'] ?? null,
            'no_pasport' => $data['no_pasport'] ?? null,
            'penerbit' => $data['penerbit'] ?? null,
            'pasport_aktif' => $data['pasport_aktif'] ?? null,
            'pasport_expired' => $data['pasport_expired'] ?? null,

            // File
            'foto_jamaah' => $data['foto_jamaah'] ?? null,
            'foto_ktp' => $data['foto_ktp'] ?? null,
            'foto_kk' => $data['foto_kk'] ?? null,
            'foto_pasport1' => $data['foto_pasport1'] ?? null,
            'foto_pasport2' => $data['foto_pasport2'] ?? null,

            // Default
            'status' => 'prospek',
            'lunas' => '0'
        ]);

        return redirect()
            ->route('jamaahs.prospek')
            ->with('success', 'Jamaah berhasil ditambahkan');
    }

    public function ambilPaket(Request $request, Jamaah $jamaah)
    {
        $request->validate([
            'paket_id' => 'required|exists:pakets,id'
        ]);

        DB::beginTransaction();

        try {

            // 🔥 ambil paket tujuan
            $paket = Paket::findOrFail($request->paket_id);

            // 🔥 hitung total tabungan
            $totalTabungan = $jamaah->pembayarans()->whereNull('paket_id')->sum('jumlah_bayar');

            // =========================
            // 🔥 UPDATE JAMAAH
            // =========================
            $jamaah->update(['paket_id' => $paket->id,'status'   => 'aktif']);

            // =========================
            // 🔥 PINDAHKAN TABUNGAN → PAKET
            // =========================
            $pembayaranIds = Pembayaran::where('jamaah_id', $jamaah->id)->whereNull('paket_id')->pluck('id');

            Pembayaran::whereIn('id', $pembayaranIds)
                ->update([
                    'paket_id' => $paket->id,
                    'jenis'    => 'pembayaran_paket'
                ]);

            // =========================
            // 🔥 UPDATE TRANSAKSI
            // =========================
            Transaksi::whereIn('referensi_id', $pembayaranIds)->update(['paket_id' => $paket->id]);

            DB::commit();

            return back()->with('success', 'Berhasil mengambil paket & saldo dipindahkan');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
/* 
    public function printPindahPaket(Jamaah $jamaah)
    {
        // 🔥 format tanggal
        $tanggal = Carbon::now()->translatedFormat('d F Y');

        // 🔥 ambil total tabungan
        $totalTabungan = $jamaah->pembayarans()
            ->where('jenis', 'tabungan')
            ->sum('jumlah_bayar');

        // INIT PDF
        $mpdf = new Mpdf([
            'format' => 'A4',
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
        ]);

        // 🔥 background surat (opsional kalau mau pakai template)
        $mpdf->SetDefaultBodyCSS('background', "url('" . public_path('storage/img/backgroundsurat.png') . "')");
        $mpdf->SetDefaultBodyCSS('background-image-resize', 6);

        // 🔥 render blade
        $html = view('jamaahs.pdf_pindah_paket', compact(
            'jamaah',
            'tanggal',
            'totalTabungan'
        ))->render();

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
            'surat-pindah-paket-'.$jamaah->nama_jamaah.'.pdf',
            'I'
        ))->header('Content-Type', 'application/pdf');
    }
 */
    public function draftPaket(Request $request, Jamaah $jamaah)
    {
        $request->validate([
            'paket_id' => 'required|exists:pakets,id'
        ]);

        // tidak boleh kalau sudah punya paket
        if ($jamaah->paket_id) {
            return back()->with('error', 'Jamaah sudah memiliki paket!');
        }

        $jamaah->update([
            'paket_id_draft' => $request->paket_id
        ]);

        return back()->with('success', 'Paket berhasil dipilih (draft)');
    }

    public function printPindahPaket(Jamaah $jamaah)
    {
        if (!$jamaah->paket_id_draft) {
            return back()->with('error', 'Silakan pilih paket dulu!');
        }

        $paket = Paket::findOrFail($jamaah->paket_id_draft);

        $tanggal = now()->translatedFormat('d F Y');
        $tanggalpergi = Carbon::parse($paket->tgl_berangkat)
        ->translatedFormat('F Y');

        $totalTabungan = $jamaah->pembayarans()
            ->where('jenis', 'tabungan')
            ->sum('jumlah_bayar');

        // contoh promo (bisa kamu ubah dinamis)
        $promo = 1000000;
        $harga = $paket->harga_paket;
        $hargaFinal = $harga - $promo;

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
        ]);

        $mpdf->SetDefaultBodyCSS('background', "url('" . public_path('storage/img/backgroundsurat.png') . "')");
        $mpdf->SetDefaultBodyCSS('background-image-resize', 6);

        $html = view('jamaahs.pdf_pindah_paket', compact(
            'jamaah','paket','tanggal','totalTabungan','harga','promo','hargaFinal','tanggalpergi'
        ))->render();

        $mpdf->WriteHTML("
            <div style='padding:180px 60px 120px 60px; font-size:12px; line-height:1.8;'>
                $html
            </div>
        ");

        return $mpdf->Output('surat-'.$jamaah->nama_jamaah.'.pdf', 'I');
    }

    public function konfirmasiPaket(Jamaah $jamaah)
    {
        if (!$jamaah->paket_id_draft) {
            return back()->with('error', 'Tidak ada paket draft!');
        }

        DB::beginTransaction();

        try {

            $paket = Paket::findOrFail($jamaah->paket_id_draft);

            // 🔥 pindahkan paket
            $jamaah->update([
                'paket_id' => $paket->id,
                'paket_id_draft' => null,
                'status' => 'aktif'
            ]);

            // 🔥 ambil pembayaran tabungan dulu
            $pembayarans = $jamaah->pembayarans()
                ->where('jenis', 'tabungan')
                ->get();

            // 🔥 update jadi pembayaran paket
            foreach ($pembayarans as $p) {

                // update pembayaran
                $p->update([
                    'jenis' => 'pembayaran_paket',
                    'paket_id' => $paket->id
                ]);

                // 🔥 update transaksi terkait
                DB::table('transaksis')
                    ->where('referensi_id', $p->id)
                    ->where('group_id', 4)
                    ->update([
                        'paket_id' => $paket->id,
                        'keterangan' => 'Pembayaran paket jamaah ' . $jamaah->nama_jamaah . ' (convert dari tabungan)'
                    ]);
            }

            // 🔥 hitung total bayar
            $totalBayar = $jamaah->pembayarans()
                ->where('jenis', 'pembayaran_paket')
                ->sum('jumlah_bayar');

            // 🔥 update status lunas
            if ($totalBayar >= $paket->harga_paket) {
                $jamaah->update(['lunas' => '1']);
            }

            DB::commit();

            return back()->with('success', 'Paket berhasil dikonfirmasi');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
