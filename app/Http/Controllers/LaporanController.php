<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function neraca(Request $request)
    {
        // Default tanggal: awal tahun hingga hari ini
        $tanggalMulai = $request->tanggal_mulai 
            ?? Carbon::now()->startOfYear()->toDateString();

        $tanggalSelesai = $request->tanggal_selesai 
            ?? Carbon::now()->toDateString();

        // Ambil data transaksi
        $transaksis = DB::table('transaksis')
            ->join('group_transaksi', 'transaksis.group_id', '=', 'group_transaksi.id')
            ->select(
                'transaksis.id',
                'transaksis.created_at',
                'group_transaksi.nama as group_nama',
                'group_transaksi.jenis',
                'transaksis.keterangan',
                'transaksis.jumlah'
            )
            ->whereBetween('transaksis.created_at', [
                $tanggalMulai . ' 00:00:00',
                $tanggalSelesai . ' 23:59:59'
            ])
            ->orderBy('transaksis.created_at', 'asc')
            ->get();

        // Hitung Debit, Kredit, dan Saldo
        $data = [];
        $saldo = 0;
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($transaksis as $trx) {
            $debit = $trx->jenis === 'masuk' ? $trx->jumlah : 0;
            $kredit = $trx->jenis === 'keluar' ? $trx->jumlah : 0;

            $saldo += $debit - $kredit;
            $totalDebit += $debit;
            $totalKredit += $kredit;

            $data[] = (object)[
                'id' => $trx->id,
                'tanggal' => $trx->created_at,
                'group' => $trx->group_nama,
                'keterangan' => $trx->keterangan,
                'debit' => $debit,
                'kredit' => $kredit,
                'saldo' => $saldo,
            ];
        }

        return view('laporan.neraca', compact(
            'data',
            'tanggalMulai',
            'tanggalSelesai',
            'totalDebit',
            'totalKredit',
            'saldo'
        ));
    }
}
