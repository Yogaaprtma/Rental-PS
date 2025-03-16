<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Authentikasi
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.page');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.page');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('home.admin');
    Route::get('/admin/list-booking', [AdminController::class, 'listBooking'])->name('booking.admin.page');
    Route::get('/admin/service', [AdminController::class, 'service'])->name('service.page');

    // CRUD Service
    Route::get('/admin/service/create', [ServiceController::class, 'createService'])->name('service.create');
    Route::post('/admin/service', [ServiceController::class, 'storeService'])->name('service.store');
    Route::get('/admin/service/{id}/edit', [ServiceController::class, 'editService'])->name('service.edit');
    Route::put('/admin/service/{id}', [ServiceController::class, 'updateService'])->name('service.update');
    Route::delete('/admin/service/{id}', [ServiceController::class, 'destroyService'])->name('service.destroy');
});

// Customer
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/customer/home', [CustomerController::class, 'index'])->name('home.customer');
    Route::get('/customer/booking', [BookingController::class, 'booking'])->name('booking.page');
    Route::post('/customer/booking', [BookingController::class, 'storeBooking'])->name('booking.store');
    Route::get('/customer/booking/confirmation/{id}', [BookingController::class, 'confirmation'])->name('booking.confirmation.page');
    Route::post('/update-payment-status/{id}', [BookingController::class, 'updatePaymentStatus'])->name('payment.update.status');
});

// Midtrans Callback (tanpa middleware auth karena dipanggil oleh Midtrans)
Route::post('/midtrans-callback', [BookingController::class, 'midtransCallback'])->name('midtrans.callback');