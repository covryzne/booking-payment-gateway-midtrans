<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Redirect setelah login
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/dashboard', function () {
    return redirect('/redirect-role');
})->name('dashboard');

Route::middleware('auth')->get('/redirect-role', function () {
    $role = request()->user()?->role;

    return match ($role) {
        'admin' => redirect('/admin/dashboard'),
        'petugas' => redirect('/petugas/dashboard'),
        default => redirect('/penyewa/dashboard'),
    };
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin', 'nocache'])->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/booking', [BookingController::class, 'index'])
        ->name('admin.booking');

    Route::get('/laporan/pdf/{tahun}', [BookingController::class, 'laporanPdf'])
        ->name('admin.laporan.pdf');

    Route::get('/laporan', [BookingController::class, 'laporan'])
        ->name('admin.laporan');

    Route::delete('/admin/booking/{id}', [BookingController::class, 'destroy'])
        ->name('admin.booking.delete');
});

/*
|--------------------------------------------------------------------------
| PETUGAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas', 'nocache'])->prefix('petugas')->group(function () {

    Route::get('/dashboard', [BookingController::class, 'petugas'])
        ->name('petugas.dashboard');

    Route::post('/scan', [BookingController::class, 'validateQr'])
        ->name('petugas.scan');
});

/*
|--------------------------------------------------------------------------
| PENYEWA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:penyewa', 'nocache'])->prefix('penyewa')->group(function () {

    Route::get('/dashboard', [BookingController::class, 'dashboard'])
        ->name('penyewa.dashboard');

    Route::get('/riwayat', [BookingController::class, 'riwayat'])
        ->name('penyewa.riwayat');

    Route::get('/bayar/{id}', [BookingController::class, 'bayar'])
        ->name('penyewa.bayar');

    Route::get('/qr/download/{id}', [BookingController::class, 'downloadQrPng'])
        ->name('penyewa.download.qr');
});

Route::post('/midtrans/notification', [BookingController::class, 'midtransNotification'])
    ->name('midtrans.notification');

/*
|--------------------------------------------------------------------------
| BOOKING (GLOBAL - masih butuh login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'nocache'])->group(function () {

    Route::get('/booking', [BookingController::class, 'create'])
        ->name('penyewa.booking.create');

    Route::post('/booking', [BookingController::class, 'store'])
        ->name('penyewa.booking.store');

    Route::get('/booking/{id}/qr', [BookingController::class, 'qr'])
        ->name('booking.qr');
});

require __DIR__ . '/auth.php';
