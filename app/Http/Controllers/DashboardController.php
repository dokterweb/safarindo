<?php

namespace App\Http\Controllers;
use App\Models\Agent;
use App\Models\Jamaah;
use App\Models\Paket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
 
    // Dashboard untuk Admin
    public function indexadmin()
    {
        $totalJamaah = Jamaah::count();
        $totalJamaahNabung = Jamaah::where('paket_id', NULL)->count();
        $totalAgen = Agent::count();
        $paketAktif = Paket::where('status', 'active')->count();
        $listPaket = Paket::orderBy('tgl_berangkat', 'desc')->limit(5)->get();
        return view('dashboard.admin', compact('totalJamaah','totalJamaahNabung','totalAgen','paketAktif','listPaket'));
    }

    // Dashboard untuk Ustadz
    public function indexustadz()
    {
        return view('dashboard.ustadz');  // Halaman dashboard ustadz
    }

    // Dashboard untuk Siswa
    public function indexsiswa()
    {
        return view('dashboard.siswa');  // Halaman dashboard siswa
    }
}
