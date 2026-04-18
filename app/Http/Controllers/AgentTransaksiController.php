<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Jamaah;
use App\Models\Agent_transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentTransaksiController extends Controller
{
    public function index()
    {
        $agents = Agent::withCount('jamaahs')->get();
        return view('agent_transaksis.index', compact('agents'));
    }

    public function jamaah(Agent $agent)
    {
        $jamaahs = Jamaah::with(['paket','agent'])
            ->withSum('agentTransaksis as total_bayar', 'jumlah')
            ->where('agent_id', $agent->id)
            ->get();
    
        return view('agent_transaksis.jamaah', compact('agent','jamaahs'));
    }

    public function bayarFee(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'jamaah_id' => 'required|exists:jamaahs,id',
            'paket_id' => 'required|exists:pakets,id',
            'jumlah' => 'required|numeric|min:1',
            'bukti_bayar' => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $jamaah = Jamaah::with('agent')->findOrFail($request->jamaah_id);

            $feeAgent = $jamaah->agent->fee_agent ?? 0;
            $totalDibayar = Agent_transaksi::where([
                'agent_id' => $request->agent_id,
                'jamaah_id' => $request->jamaah_id,
                'paket_id' => $request->paket_id,
            ])->sum('jumlah');

            // Validasi agar tidak melebihi fee
            if (($totalDibayar + $request->jumlah) > $feeAgent) {
                return back()->with('error', 'Pembayaran melebihi fee agen!');
            }

            // Upload bukti bayar
            $bukti = null;
            if ($request->hasFile('bukti_bayar')) {
                $bukti = $request->file('bukti_bayar')
                    ->store('agent_transaksis', 'public');
            }

            // Simpan transaksi
            Agent_transaksi::create([
                'agent_id' => $request->agent_id,
                'jamaah_id' => $request->jamaah_id,
                'paket_id' => $request->paket_id,
                'jumlah' => $request->jumlah,
                'bukti_bayar' => $bukti,
            ]);

            DB::commit();

            return back()->with('success', 'Fee agent berhasil dibayarkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
