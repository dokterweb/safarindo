<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\MaskapaiController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PengeluaranbulananController;
use App\Http\Controllers\SupplierController;
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
Route::middleware(['auth', 'role:agen'])->get('/agen/dashboard', [DashboardController::class, 'indexagen'])->name('agen.dashboard');

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

Route::get('/pengeluaranbulanans',[PengeluaranbulananController::class, 'index'])->middleware('role:admin')->name('pengeluaranbulanans');
Route::post('pengeluaranbulanans/store', [PengeluaranbulananController::class, 'store'])->middleware('role:admin')->name('pengeluaranbulanans.store');
Route::get('pengeluaranbulanans/{pengeluaranbulanan}/edit', [PengeluaranbulananController::class, 'edit'])->middleware('role:admin')->name('pengeluaranbulanans.edit');
Route::put('pengeluaranbulanans/{pengeluaranbulanan}', [PengeluaranbulananController::class, 'update'])->middleware('role:admin')->name('pengeluaranbulanans.update');

