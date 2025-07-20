<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\SuperAdminOnly;


/*
|--------------------------------------------------------------------------
| WELCOME (Tamu)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check()
        ? app(AuthController::class)->redirectByRole()
        : view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| AUTHENTIKASI
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'doRegister'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'doLogin'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| DASHBOARD (Semua role kecuali superadmin masuk ke sini)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| DASHBOARD SUPER ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', SuperAdminOnly::class])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');

    // Manajemen Divisi
    Route::resource('divisions', SuperAdminController::class)->except(['index', 'show']);

    // Ganti password admin
    Route::get('/admins/{id}/edit-password', [SuperAdminController::class, 'editPassword'])->name('superadmin.admins.edit_password');
    Route::put('/admins/{id}/update-password', [SuperAdminController::class, 'updatePassword'])->name('superadmin.admins.update_password');

    // Ganti password super admin sendiri
    Route::get('/superadmin/change-password', [SuperAdminController::class, 'showChangePasswordForm'])->name('superadmin.change-password');
    Route::post('/superadmin/change-password', [SuperAdminController::class, 'changePassword'])->name('superadmin.change-password.store');
});

/*
|--------------------------------------------------------------------------
| MANAJEMEN RUANGAN
|--------------------------------------------------------------------------
*/
// Umum (lihat ruangan)
Route::middleware('auth')->group(function () {
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/filter', [RoomController::class, 'filterByDivision'])->name('rooms.filter');
});

// Admin (kelola ruangan)
Route::middleware(['auth', AdminOnly::class])->group(function () {
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    Route::get('/admin/rooms', [RoomController::class, 'adminIndex'])->name('admin.rooms');
});

/*
|--------------------------------------------------------------------------
| BOOKING
|--------------------------------------------------------------------------
*/
// User
Route::middleware('auth')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
});

// Admin
Route::middleware(['auth', AdminOnly::class])->group(function () {
    Route::get('/admin/bookings', [BookingController::class, 'all'])->name('admin.bookings');
    Route::patch('/admin/bookings/{id}/approve', [BookingController::class, 'approve'])->name('admin.bookings.approve');
    Route::patch('/admin/bookings/{id}/reject', [BookingController::class, 'reject'])->name('admin.bookings.reject');
});

// Admin
// Route::get('bookings/rekap/pdf', [BookingController::class, 'exportPdf'])
//      ->name('bookings.export.pdf');

// SuperAdmin
// Route::get('bookings/rekap/pdf-all', [BookingController::class, 'exportPdfAll'])
//      ->name('bookings.export.pdfAll');

Route::get('/bookings/export-filter', [BookingController::class, 'showExportFilter'])->name('bookings.export-filter');

Route::post('/bookings/rekap/pdf', [BookingController::class, 'exportPdf'])->name('bookings.rekap.pdf');
Route::get('/bookings/rekap/pdf-all', [BookingController::class, 'rekapSemua'])->name('bookings.rekap.pdfAll');

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
Route::get('/api/rooms/by-division/{divisionId}', function ($divisionId) {
    return \App\Models\Room::where('division_id', $divisionId)
        ->where('is_available', true)
        ->select('id', 'name', 'capacity')
        ->get();
})->middleware('auth')->name('api.rooms.byDivision');