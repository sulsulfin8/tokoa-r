<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\EoqController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\PenggunaanBahanController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Fitur Lupa Kata Sandi (OTP)
Route::get('/forgot-password', [App\Http\Controllers\ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\ForgotPasswordController::class, 'sendOtp'])->name('password.email');
Route::get('/verify-otp', [App\Http\Controllers\ForgotPasswordController::class, 'showOtpForm'])->name('password.otp');
Route::post('/verify-otp', [App\Http\Controllers\ForgotPasswordController::class, 'verifyOtpAndReset'])->name('password.update');
Route::post('/resend-otp', [App\Http\Controllers\ForgotPasswordController::class, 'resendOtp'])->name('password.resend');

// Kita abaikan dulu middleware auth agar Anda mudah testing
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('bahan-baku', BahanBakuController::class);
    Route::get('/api/produk/{nama_produk}', [ProdukController::class, 'getByName']);
    Route::resource('produk', ProdukController::class);
    Route::resource('pemasok', PemasokController::class);
    Route::resource('reseller', ResellerController::class);
    Route::resource('stok-masuk', StokMasukController::class);
    Route::resource('penggunaan-bahan', PenggunaanBahanController::class);
    
    // Penjualan Controller untuk transaksi offline & otomatisasi potong bahan
    Route::get('penjualan/{penjualan}/cek-bahan', [PenjualanController::class, 'cekBahan'])->name('penjualan.cek_bahan');
    Route::post('penjualan/{penjualan}/approve', [PenjualanController::class, 'approve'])->name('penjualan.approve');
    Route::resource('penjualan', PenjualanController::class);
    
    // EOQ Calculation
    Route::get('/eoq', [EoqController::class, 'index'])->name('eoq.index');
    Route::post('/eoq/hitung', [EoqController::class, 'hitung'])->name('eoq.hitung');
    Route::get('/eoq/{id}/edit', [EoqController::class, 'edit'])->name('eoq.edit');
    Route::put('/eoq/{id}', [EoqController::class, 'update'])->name('eoq.update');
    Route::delete('/eoq/{id}', [EoqController::class, 'destroy'])->name('eoq.destroy');
    
    // Prediksi SMA
    Route::get('/sma', [App\Http\Controllers\SmaController::class, 'index'])->name('sma.index');
    Route::post('/sma/hitung', [App\Http\Controllers\SmaController::class, 'hitung'])->name('sma.hitung');

    // Perencanaan Produksi / Pengambilan Keputusan
    Route::get('/perencanaan', [App\Http\Controllers\PerencanaanController::class, 'index'])->name('perencanaan.index');
    Route::post('/perencanaan/hitung', [App\Http\Controllers\PerencanaanController::class, 'hitung'])->name('perencanaan.hitung');

    // Edit Profil
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('pemilik')->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/download/{jenis}/{format}', [LaporanController::class, 'download'])->name('laporan.download');
});
