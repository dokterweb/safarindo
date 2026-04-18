<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Jamaah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Agent_transaksi;

class AgenDashboardController extends Controller
{

    public function index(Request $request)
    {
        $agent = Auth::user()->agent;

        // 🔥 Default tanggal: dari tanggal 1 hingga hari ini
        $tanggalMulai = $request->tanggal_mulai 
            ?? Carbon::now()->startOfMonth()->toDateString();

        $tanggalSelesai = $request->tanggal_selesai 
            ?? Carbon::now()->toDateString();

        // 🔥 Ambil transaksi fee sesuai filter tanggal
        $transaksis = Agent_transaksi::where('agent_id', $agent->id)
            ->whereBetween('created_at', [
                $tanggalMulai . ' 00:00:00',
                $tanggalSelesai . ' 23:59:59'
            ])
            ->get()
            ->keyBy('jamaah_id');

        // 🔥 Ambil jamaah yang memiliki transaksi dalam periode tersebut
        $jamaahs = Jamaah::with('paket')
            ->where('agent_id', $agent->id)
            ->whereHas('agentTransaksis', function ($q) use ($agent, $tanggalMulai, $tanggalSelesai) {
                $q->where('agent_id', $agent->id)
                ->whereBetween('created_at', [
                    $tanggalMulai . ' 00:00:00',
                    $tanggalSelesai . ' 23:59:59'
                ]);
            })
            ->get();

        // 🔥 Statistik Dashboard
        $totalJamaah = $jamaahs->count();
        $totalDisetujui = $transaksis->sum('jumlah');
        $totalFee = $totalJamaah * $agent->fee_agent;
        $totalBelumDisetujui = $totalFee - $totalDisetujui;
        $totalPendapatan = Agent_transaksi::where('agent_id', $agent->id)->sum('jumlah');

        return view('agen.dashboard', compact(
            'agent',
            'jamaahs',
            'transaksis',
            'totalJamaah',
            'totalFee',
            'totalDisetujui',
            'totalBelumDisetujui',
            'tanggalMulai',
            'tanggalSelesai',
            'totalPendapatan'
        ));
    }

    public function jamaah()
    {
        $agent = Auth::user()->agent;

        $jamaahs = Jamaah::with('paket')
            ->where('agent_id', $agent->id)
            ->get();

        return view('agen.jamaah', compact('jamaahs'));
    }

    public function show(Jamaah $jamaah)
    {
        $agent = Auth::user()->agent;
    
        // Pastikan jamaah milik agen yang sedang login
        if ($jamaah->agent_id !== $agent->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    
        // Load relasi yang dibutuhkan
        $jamaah->load([
            'paket',
            'pembayarans',
            'agentTransaksis'
        ]);
    
        // Hitung total pembayaran jamaah
        $totalBayar = $jamaah->pembayarans->sum('jumlah_bayar');
        $totalTagihan = $jamaah->paket->harga_paket;
        $sisaTagihan = $totalTagihan - $totalBayar;
    
        // Hitung fee agen
        $feeAgent = $agent->fee_agent ?? 0;
        $feeDibayar = $jamaah->agentTransaksis
            ->where('agent_id', $agent->id)
            ->sum('jumlah');
    
        return view('agen.jamaah_show', compact(
            'jamaah',
            'totalBayar',
            'totalTagihan',
            'sisaTagihan',
            'feeAgent',
            'feeDibayar'
        ));
    }

    public function pendapatan(Request $request)
    {
        $agent = Auth::user()->agent;
    
        // 🔥 Default tanggal: dari tanggal 1 sampai hari ini
        $tanggalMulai = $request->tanggal_mulai 
            ?? Carbon::now()->startOfMonth()->toDateString();
    
        $tanggalSelesai = $request->tanggal_selesai 
            ?? Carbon::now()->toDateString();
    
        // 🔥 Ambil data jamaah
        $query = Jamaah::with(['paket'])
            ->where('agent_id', $agent->id);
    
        // Filter berdasarkan tanggal transaksi
        $query->whereHas('agentTransaksis', function ($q) use ($tanggalMulai, $tanggalSelesai) {
            $q->whereBetween('created_at', [
                $tanggalMulai . ' 00:00:00',
                $tanggalSelesai . ' 23:59:59'
            ]);
        });
    
        $jamaahs = $query->get();
    
        // 🔥 Ambil transaksi sesuai filter tanggal
        $transaksi = Agent_transaksi::where('agent_id', $agent->id)
            ->whereBetween('created_at', [
                $tanggalMulai . ' 00:00:00',
                $tanggalSelesai . ' 23:59:59'
            ])
            ->get();
    
        // Total fee disetujui
        $totalDisetujui = $transaksi->sum('jumlah');
    
        // Total fee yang seharusnya diterima
        $totalFeeSeharusnya = $jamaahs->count() * $agent->fee_agent;
    
        // Fee yang belum disetujui
        $totalBelumDisetujui = $totalFeeSeharusnya - $totalDisetujui;
    
        return view('agen.pendapatan', compact(
            'jamaahs',
            'agent',
            'totalDisetujui',
            'totalBelumDisetujui',
            'tanggalMulai',
            'tanggalSelesai'
        ));
    }
}
