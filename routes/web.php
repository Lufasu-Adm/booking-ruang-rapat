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
| WELCOME (Untuk Tamu)
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
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'doRegister'])->name('register.store');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.store');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| DASHBOARD USER
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN
|--------------------------------------------------------------------------
*/
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard-admin');
})->middleware(['auth', AdminOnly::class])->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| DASHBOARD SUPER ADMIN
|--------------------------------------------------------------------------
*/
Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])
    ->middleware(['auth', SuperAdminOnly::class]) // gunakan nama string middleware
    ->name('superadmin.dashboard');

// SUPER ADMIN: Manajemen Divisi
Route::middleware(['auth', SuperAdminOnly::class])->group(function () {
    Route::get('/divisions/create', [SuperAdminController::class, 'create'])->name('divisions.create');
    Route::post('/divisions', [SuperAdminController::class, 'store'])->name('divisions.store');
});

/*
|--------------------------------------------------------------------------
| MANAJEMEN RUANGAN
|--------------------------------------------------------------------------
*/
// USER melihat daftar ruangan
Route::middleware('auth')->group(function () {
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    // Halaman filter ruangan berdasarkan divisi (dengan dropdown)
    Route::get('/rooms/filter', [RoomController::class, 'filterByDivision'])->name('rooms.filter');

});

// SUPER ADMIN: CRUD ruangan
Route::middleware(['auth', 'superadminonly'])->group(function () {
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
});

// ADMIN: hanya melihat ruangan divisinya
Route::get('/admin/rooms', [RoomController::class, 'adminIndex'])
    ->middleware(['auth', AdminOnly::class])
    ->name('admin.rooms');

/*
|--------------------------------------------------------------------------
| BOOKING UNTUK USER
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
});

/*
|--------------------------------------------------------------------------
| BOOKING UNTUK ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', AdminOnly::class])->group(function () {
    Route::get('/admin/bookings', [BookingController::class, 'all'])->name('admin.bookings');
    Route::patch('/admin/bookings/{id}/approve', [BookingController::class, 'approve'])->name('admin.bookings.approve');
    Route::patch('/admin/bookings/{id}/reject', [BookingController::class, 'reject'])->name('admin.bookings.reject');
});

// API: Ambil daftar ruangan berdasarkan division_id
Route::get('/api/rooms/by-division/{divisionId}', function ($divisionId) {
    return \App\Models\Room::where('division_id', $divisionId)
        ->where('is_available', true)
        ->select('id', 'name', 'capacity') // cukup data yang dibutuhkan
        ->get();
})->middleware('auth');
