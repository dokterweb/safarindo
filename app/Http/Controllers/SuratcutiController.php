<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use App\Models\Suratcuti;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class SuratcutiController extends Controller
{
    public function index()
    {
        $suratcutis = Suratcuti::with('jamaah')->get();
        return view('suratcutis.index', compact('suratcutis'));
    }

    public function create()
    {
        return view('suratcutis.create');
    }

    public function searchJamaah(Request $request)
    {
        $q = $request->q;

        $jamaahs = Jamaah::with('paket')
            ->where('nama_jamaah', 'like', "%$q%")
            ->orWhere('nik', 'like', "%$q%")
            ->limit(10)
            ->get();

        return response()->json($jamaahs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jamaah_id'         => 'required|exists:jamaahs,id',
            'paket_id'          => 'required|exists:pakets,id',
            'nama_kantor'       => 'required|string',
            'alamat_kantor'     => 'required|string',
            'jabatan'           => 'required|string',
            'catatan'           => 'nullable|string',
        ]);
    
        DB::transaction(function () use ($request) {
    
            $now = now();
    
            // 🔥 Lock data biar tidak bentrok
            $count = Suratcuti::whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->lockForUpdate()
                ->count();
    
            $noUrut = $count + 1;
    
            // Format 6 digit
            $idFormat = str_pad($noUrut, 3, '0', STR_PAD_LEFT);
    
            // Bulan Romawi
            $bulanRomawi = [
                1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',
                7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'
            ];
    
            $bulan = $bulanRomawi[$now->month];
            $tahun = $now->year;
    
            // Generate nomor surat
            $noSurat = "{$idFormat}/SP/RKM/{$bulan}/{$tahun}";
    
            // Simpan data
            Suratcuti::create([
                'no_surat'          => $noSurat,
                'jamaah_id'         => $request->jamaah_id,
                'paket_id'          => $request->paket_id,
                'nama_kantor'       => $request->nama_kantor,
                'alamat_kantor'     => $request->alamat_kantor,
                'jabatan'           => $request->jabatan,
                'catatan'           => $request->catatan,
            ]);
        });
    
        return redirect()
            ->route('suratcutis')
            ->with('success', 'Surat rekomendasi berhasil dibuat');
    }

    public function edit($id)
    {
        $surat = Suratcuti::with(['jamaah.paket'])->findOrFail($id);
        return view('suratcutis.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jamaah_id'         => 'required',
            'paket_id'          => 'required',
            'nama_kantor'       => 'required|string',
            'alamat_kantor'     => 'required|string',
            'jabatan'           => 'required|string',
            'catatan'           => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {

            $surat = Suratcuti::findOrFail($id);

            // 🔥 UPDATE TANPA UBAH NO_SURAT
            $surat->update([
                'jamaah_id'         => $request->jamaah_id,
                'paket_id'          => $request->paket_id,
                'nama_kantor'       => $request->nama_kantor,
                'alamat_kantor'     => $request->alamat_kantor,
                'jabatan'           => $request->jabatan,
                'catatan'           => $request->catatan,
            ]);

            DB::commit();

            return redirect()->route('suratcutis')
                ->with('success', 'Data berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function print(Suratcuti $suratcuti)
    {
        $suratcuti->load(['jamaah.paket']);

        $jamaah = $suratcuti->jamaah;
        $paket  = $suratcuti->paket;

        // Format tanggal Indonesia
        $tanggalSurat = Carbon::parse($suratcuti->created_at)
            ->translatedFormat('d F Y');

            // 🔥 Tanggal berangkat (MASIH OBJECT)
        $tanggalPergiCarbon = Carbon::parse($paket->tgl_berangkat);

        // 🔥 Tambah jumlah hari dari paket
        $tanggalKembaliCarbon = $tanggalPergiCarbon->copy()->addDays($paket->jlh_hari);

        // 🔥 Format ke Indonesia
        $tanggalpergi = $tanggalPergiCarbon->translatedFormat('d F Y');
        $tglkembali   = $tanggalKembaliCarbon->translatedFormat('d F Y');

        $tanggalpergi = Carbon::parse($paket->tgl_berangkat)
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
        $html = view('suratcutis.pdf', compact('suratcuti','jamaah','paket','tanggalSurat','tanggalpergi', 'tglkembali'))->render();

        // Wrapper supaya tidak nabrak header/footer
        $content = '
            <div style="
                padding-top: 200px;
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
