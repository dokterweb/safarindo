<?php

use App\Http\Controllers\AgenDashboardController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AgentTransaksiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\JamaahController;
use App\Http\Controllers\KeluarprodukController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MaskapaiController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PembatalanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PengeluaranbulananController;
use App\Http\Controllers\PengeluaranbulanantrxController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'handleLogin']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk Admin
Route::middleware(['auth', 'role:admin'])->get('/admin/dashboard', [DashboardController::class, 'indexadmin'])->name('admin.dashboard');

// Route untuk management
Route::middleware(['auth', 'role:management'])->get('/management/dashboard', [DashboardController::class, 'indexmanagement'])->name('management.dashboard');

// Route untuk staff
Route::middleware(['auth', 'role:staff'])->get('/staff/dashboard', [DashboardController::class, 'indexstaff'])->name('staff.dashboard');

// Route untuk agen
/* Route::middleware(['auth', 'role:agen'])->get('/agen/dashboard', [DashboardController::class, 'indexagen'])->name('agen.dashboard'); */

Route::get('/users',[UserController::class, 'index'])->middleware('role:admin')->name('users');
Route::post('users/store', [UserController::class, 'store'])->middleware('role:admin')->name('users.store');
Route::get('users/{user}/edit', [UserController::class, 'edit'])->middleware('role:admin')->name('users.edit');
Route::put('users/{user}', [UserController::class, 'update'])->middleware('role:admin')->name('users.update');
Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('role:admin')->name('users.destroy');

Route::get('/agents',[AgentController::class, 'index'])->middleware('role:admin')->name('agents');
Route::get('/agents/create',[AgentController::class, 'create'])->middleware('role:admin')->name('agents.create');
Route::post('agents/store', [AgentController::class, 'store'])->middleware('role:admin')->name('agents.store');
Route::get('agents/{agent}/edit', [AgentController::class, 'edit'])->middleware('role:admin')->name('agents.edit');
Route::put('agents/{agent}', [AgentController::class, 'update'])->middleware('role:admin')->name('agents.update');
Route::delete('agents/{agent}', [AgentController::class, 'destroy'])->middleware('role:admin')->name('agents.destroy');

Route::get('/agent_transaksis',[AgentTransaksiController::class, 'index'])->middleware('role:admin')->name('agent_transaksis');
Route::get('/agent/{agent}/jamaah', [AgentTransaksiController::class, 'jamaah'])->middleware('role:admin')->name('agent_transaksis.jamaah');
Route::post('/agent-transaksis/bayar', [AgentTransaksiController::class, 'bayarFee'])->middleware('role:admin')->name('agent_transaksis.bayar');
    
Route::get('/maskapais',[MaskapaiController::class, 'index'])->middleware('role:admin')->name('maskapais');
Route::post('maskapais/store', [MaskapaiController::class, 'store'])->middleware('role:admin')->name('maskapais.store');
Route::get('maskapais/{maskapai}/edit', [MaskapaiController::class, 'edit'])->middleware('role:admin')->name('maskapais.edit');
Route::put('maskapais/{maskapai}', [MaskapaiController::class, 'update'])->middleware('role:admin')->name('maskapais.update');
Route::delete('maskapais/{maskapai}', [MaskapaiController::class, 'destroy'])->middleware('role:admin')->name('maskapais.destroy');

Route::get('/hotels',[HotelController::class, 'index'])->middleware('role:admin')->name('hotels');
Route::post('hotels/store', [HotelController::class, 'store'])->middleware('role:admin')->name('hotels.store');
Route::get('hotels/{hotel}/edit', [HotelController::class, 'edit'])->middleware('role:admin')->name('hotels.edit');
Route::put('hotels/{hotel}', [HotelController::class, 'update'])->middleware('role:admin')->name('hotels.update');

Route::resource('mitras',MitraController::class)->middleware('role:admin');
Route::get('/suppliers',[SupplierController::class, 'index'])->middleware('role:admin')->name('suppliers');
Route::post('suppliers/store', [SupplierController::class, 'store'])->middleware('role:admin')->name('suppliers.store');
Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->middleware('role:admin')->name('suppliers.edit');
Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])->middleware('role:admin')->name('suppliers.update');
Route::delete('suppliers/{maskapai}', [SupplierController::class, 'destroy'])->middleware('role:admin')->name('suppliers.destroy');

Route::get('/pakets',[PaketController::class, 'index'])->middleware('role:admin')->name('pakets');
Route::get('/pakets/create',[PaketController::class, 'create'])->middleware('role:admin')->name('pakets.create');
Route::post('pakets/store', [PaketController::class, 'store'])->middleware('role:admin')->name('pakets.store');
Route::get('pakets/{paket}/edit', [PaketController::class, 'edit'])->middleware('role:admin')->name('pakets.edit');
Route::put('pakets/{paket}', [PaketController::class, 'update'])->middleware('role:admin')->name('pakets.update');
Route::delete('pakets/{paket}', [PaketController::class, 'destroy'])->middleware('role:admin')->name('pakets.destroy');
Route::get('/pakets/jamaah',[PaketController::class, 'indexjamaah'])->middleware('role:admin')->name('pakets.jamaah');
Route::get('/pakets/{id}/jamaah', [PaketController::class, 'byPaket'])->middleware('role:admin')->name('pakets.jamaah.detail');
Route::get('/pakets/pembayaran',[PaketController::class, 'indexpembayaran'])->middleware('role:admin')->name('pakets.pembayaran');
Route::get('/pakets/{id}/pembayaran', [PaketController::class, 'detailPembayaran'])->middleware('role:admin')->name('pakets.pembayaran.detail');

Route::get('/pengeluaranbulanans',[PengeluaranbulananController::class, 'index'])->middleware('role:admin')->name('pengeluaranbulanans');
Route::post('pengeluaranbulanans/store', [PengeluaranbulananController::class, 'store'])->middleware('role:admin')->name('pengeluaranbulanans.store');
Route::get('pengeluaranbulanans/{pengeluaranbulanan}/edit', [PengeluaranbulananController::class, 'edit'])->middleware('role:admin')->name('pengeluaranbulanans.edit');
Route::put('pengeluaranbulanans/{pengeluaranbulanan}', [PengeluaranbulananController::class, 'update'])->middleware('role:admin')->name('pengeluaranbulanans.update');
Route::delete('pengeluaranbulanans/{pengeluaranbulanan}', [PengeluaranbulananController::class, 'destroy'])->middleware('role:admin')->name('pengeluaranbulanans.destroy');

Route::get('/pengeluaranbulanantrxs',[PengeluaranbulanantrxController::class, 'index'])->middleware('role:admin')->name('pengeluaranbulanantrxs');
Route::post('pengeluaranbulanantrxs/store', [PengeluaranbulanantrxController::class, 'store'])->middleware('role:admin')->name('pengeluaranbulanantrxs.store');
Route::get('pengeluaranbulanantrxs/{pengeluaranbulanantrx}/edit', [PengeluaranbulanantrxController::class, 'edit'])->middleware('role:admin')->name('pengeluaranbulanantrxs.edit');
Route::put('pengeluaranbulanantrxs/{id}', [PengeluaranbulanantrxController::class, 'update'])
    ->name('pengeluaranbulanantrxs.update');
Route::delete('pengeluaranbulanantrxs/{id}', [PengeluaranbulanantrxController::class, 'destroy'])
    ->name('pengeluaranbulanantrxs.destroy');
    
Route::get('/pakets/{id}/jamaah/create', [JamaahController::class, 'createByPaket'])->middleware('role:admin')->name('pakets.jamaah.create');
Route::post('/pakets/{id}/jamaah/store', [JamaahController::class, 'storeByPaket'])->middleware('role:admin')->name('pakets.jamaah.store');
Route::get('/jamaahs/{id}', [JamaahController::class, 'show'])->middleware('role:admin')->name('jamaahs.show');
Route::get('/jamaahs/{id}/edit', [JamaahController::class, 'edit'])->middleware('role:admin')->name('jamaahs.edit');
Route::put('/jamaahs/{id}', [JamaahController::class, 'update'])->middleware('role:admin')->name('jamaahs.update');

// Route::get('/pembayarans/{id}', [PembayaranController::class, 'detail'])->middleware('role:admin')->name('pembayarans.detail');

Route::get('/pembayarans/{jamaah}', [PembayaranController::class, 'detail'])->middleware('role:admin')->name('pembayarans.detail');
Route::post('/pembayarans/{jamaah}/store', [PembayaranController::class, 'store'])->middleware('role:admin')->name('pembayarans.store');
Route::put('/pembayarans/{pembayaran}/update', [PembayaranController::class, 'update'])
    ->name('pembayarans.update');

Route::get('/diskons',[DiskonController::class, 'index'])->middleware('role:admin')->name('diskons');
Route::post('/diskons/{jamaah}', [DiskonController::class, 'store'])->name('diskons.store');
Route::post('/diskons/{id}/approve', [DiskonController::class, 'approve'])->middleware('role:admin')->name('diskons.approve');

Route::get('/units',[UnitController::class, 'index'])->middleware('role:admin')->name('units');
Route::post('units/store', [UnitController::class, 'store'])->middleware('role:admin')->name('units.store');
Route::get('units/{unit}/edit', [UnitController::class, 'edit'])->middleware('role:admin')->name('units.edit');
Route::put('units/{unit}', [UnitController::class, 'update'])->middleware('role:admin')->name('units.update');
Route::delete('units/{unit}', [UnitController::class, 'destroy'])->middleware('role:admin')->name('units.destroy');

Route::get('/produks',[ProdukController::class, 'index'])->middleware('role:admin')->name('produks');
Route::get('/produks/create',[ProdukController::class, 'create'])->middleware('role:admin')->name('produks.create');
Route::post('produks/store', [ProdukController::class, 'store'])->middleware('role:admin')->name('produks.store');
Route::get('produks/{id}/edit', [ProdukController::class, 'edit'])->middleware('role:admin')->name('produks.edit');
Route::put('produks/{id}', [ProdukController::class, 'update'])->middleware('role:admin')->name('produks.update');
Route::delete('produks/{id}', [ProdukController::class, 'destroy'])->middleware('role:admin')->name('produks.destroy');
Route::get('/produk/search', [ProdukController::class, 'search'])->name('produk.search');

Route::get('/pembelians', [PembelianController::class, 'index'])->name('pembelians');
Route::get('/pembelians/create', [PembelianController::class, 'create'])->name('pembelians.create');
Route::post('/pembelians', [PembelianController::class, 'store'])->name('pembelians.store');
Route::get('/pembelians/{id}', [PembelianController::class, 'show'])->name('pembelians.show');
Route::get('/pembelians/{id}/edit', [PembelianController::class, 'edit'])->name('pembelians.edit');
Route::put('/pembelians/{id}', [PembelianController::class, 'update'])->name('pembelians.update');

Route::get('/keluarproduks', [KeluarprodukController::class, 'index'])->name('keluarproduks');
Route::get('/keluarproduks/create', [KeluarprodukController::class, 'create'])->name('keluarproduks.create');
Route::post('/keluarproduks', [KeluarprodukController::class, 'store'])->name('keluarproduks.store');
Route::get('/keluarproduks/{id}', [KeluarprodukController::class, 'show'])->name('keluarproduks.show');
Route::get('/keluarproduks/{id}/edit', [KeluarprodukController::class, 'edit'])->name('keluarproduks.edit');
Route::put('/keluarproduks/{id}', [KeluarprodukController::class, 'update'])->name('keluarproduks.update');

Route::get('/pakets/pembatalan',[PaketController::class, 'indexpembatalan'])->middleware('role:admin')->name('pakets.pembatalan');
Route::get('/pakets/{id}/pembatalan', [PaketController::class, 'detailpembatalan'])->middleware('role:admin')->name('pakets.pembatalan.detail');
Route::get('/pembatalans', [PembatalanController::class, 'indexsetuju'])->middleware('role:admin')->name('pembatalans');
Route::post('/pembatalans/store', [PembatalanController::class, 'store'])->middleware('role:admin')->name('pembatalans.store');
Route::post('/pembatalans/{id}/approve', [PembatalanController::class, 'approve'])->middleware('role:admin')->name('pembatalans.approve');
Route::prefix('pembatalans')->middleware('role:admin')->group(function () {
    Route::get('/{id}', [PembatalanController::class, 'show'])->name('pembatalans.show');
    Route::post('/{id}/approve', [PembatalanController::class, 'approve'])->name('pembatalans.approve');
    Route::post('/{id}/reject', [PembatalanController::class, 'reject'])->name('pembatalans.reject');
});

Route::get('/laporan/neraca', [LaporanController::class, 'neraca'])->middleware('role:admin')->name('laporan.neraca');
    
Route::middleware(['auth', 'role:agen'])->group(function () {
    Route::get('/agen/dashboard', [AgenDashboardController::class, 'index'])->name('agen.dashboard');
    Route::get('/agen/jamaah', [AgenDashboardController::class, 'jamaah'])->name('agen.jamaah');
    Route::get('/agen/pendapatan', [AgenDashboardController::class, 'pendapatan'])->name('agen.pendapatan');
    Route::get('/agen/jamaah/{jamaah}', [AgenDashboardController::class, 'show'])->name('agen.jamaah.show');
});