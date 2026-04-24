<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use App\Models\Suratrekom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class SuratrekomController extends Controller
{
    public function index()
    {
        $suratrekoms = Suratrekom::with('jamaah')->get();
        return view('suratrekoms.index', compact('suratrekoms'));
    }

    public function create()
    {
        return view('suratrekoms.create');
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
            'jamaah_id'       => 'required|exists:jamaahs,id',
            'paket_id'        => 'required|exists:pakets,id',
            'kantor_imigrasi' => 'required|string',
            'alamat_imigrasi'       => 'required|string',
            'catatan'         => 'nullable|string',
        ]);
    
        DB::transaction(function () use ($request) {
    
            $now = now();
    
            // 🔥 Lock data biar tidak bentrok
            $count = Suratrekom::whereYear('created_at', $now->year)
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
            $noSurat = "{$idFormat}/SR/SAU/{$bulan}/{$tahun}";
    
            // Simpan data
            Suratrekom::create([
                'no_surat'        => $noSurat,
                'jamaah_id'       => $request->jamaah_id,
                'paket_id'        => $request->paket_id,
                'kantor_imigrasi' => $request->kantor_imigrasi,
                'alamat_imigrasi' => $request->alamat_imigrasi,
                'catatan'         => $request->catatan,
            ]);
        });
    
        return redirect()
            ->route('suratrekoms')
            ->with('success', 'Surat rekomendasi berhasil dibuat');
    }

    public function edit($id)
    {
        $surat = Suratrekom::with(['jamaah.paket'])->findOrFail($id);
        return view('suratrekoms.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jamaah_id'         => 'required',
            'paket_id'          => 'required',
            'kantor_imigrasi'   => 'required',
            'alamat_imigrasi'   => 'required'
        ]);

        DB::beginTransaction();

        try {

            $surat = Suratrekom::findOrFail($id);

            // 🔥 UPDATE TANPA UBAH NO_SURAT
            $surat->update([
                'jamaah_id'         => $request->jamaah_id,
                'paket_id'          => $request->paket_id,
                'kantor_imigrasi'   => $request->kantor_imigrasi,
                'alamat_imigrasi'   => $request->alamat_imigrasi,
                'catatan'           => $request->catatan,
            ]);

            DB::commit();

            return redirect()->route('suratrekoms')
                ->with('success', 'Data berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }


    public function print(Suratrekom $suratrekom)
    {
        $suratrekom->load(['jamaah.paket']);

        $jamaah = $suratrekom->jamaah;
        $paket  = $suratrekom->paket;

        // Format tanggal Indonesia
        $tanggalSurat = Carbon::parse($suratrekom->created_at)
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
        $html = view('suratrekoms.pdf', compact(
            'suratrekom',
            'jamaah',
            'paket',
            'tanggalSurat'
        ))->render();

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

    public function destroy($id)
    {
        $data = Suratrekom::findOrFail($id);

        $data->delete(); // 🔥 ini soft delete

        return redirect()
            ->route('suratrekoms')
            ->with('success', 'Data berhasil dihapus');
    }
}
