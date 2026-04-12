<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;

use App\Http\Controllers\Kasir\DashboardController as KasirDashboard;
use App\Http\Controllers\Kasir\ProductController;
use App\Http\Controllers\Kasir\TransactionController;
use App\Http\Controllers\Kasir\ActivityLogController as KasirActivityLogController;

use App\Http\Controllers\Owner\DashboardController as OwnerDashboard;
use App\Http\Controllers\Owner\TransactionController as OwnerTransactionController;
use App\Http\Controllers\Owner\ProductController as OwnerProductController;
use App\Http\Controllers\Owner\ActivityLogController as OwnerActivityLogController;
use App\Http\Controllers\Owner\UserController as OwnerUserController;
use App\Http\Controllers\Owner\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::controller(LoginController::class)->group(function () {
    // Tambahkan prevent-back-history agar user yg udah login ga bisa back ke halaman login
    Route::get('/login', 'index')->name('login')->middleware('prevent-back-history');
    Route::post('/login', 'authenticate')->name('login.process');
    Route::post('/logout', 'logout')->middleware('auth')->name('logout');
});

// BUNGKUS SEMUA ROUTE DALAM SISTEM DENGAN AUTH & PREVENT-BACK-HISTORY
Route::middleware(['auth', 'prevent-back-history'])->group(function () {

    // =====================
    // ADMIN (Dikunci khusus role admin)
    // =====================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        Route::resource('categories', CategoryController::class);
        Route::resource('courses', CourseController::class);

        Route::resource('instructors', InstructorController::class)->except(['show']);
        Route::get('instructors/{id}/profile', [InstructorController::class, 'profile'])->name('instructors.profile');

        Route::resource('users', AdminUserController::class);

        Route::post('/mapel/store-quick', [CourseController::class, 'storeMapel'])
            ->name('mapel.store-quick');

        Route::get('/get-mata-pelajaran-by-jenjang', [CategoryController::class, 'getMataPelajaranByJenjang'])
            ->name('get-mata-pelajaran-by-jenjang');

        Route::post('/categories/store-quick', [CourseController::class, 'storeQuick'])
            ->name('categories.store-quick');

        Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])
            ->name('activity-logs.index');
    });

    // =====================
    // KASIR (Dikunci khusus role kasir)
    // =====================
    Route::middleware(['role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {

        Route::get('/dashboard', [KasirDashboard::class, 'index'])->name('dashboard');

        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/{id}/print', [TransactionController::class, 'print'])->name('transactions.print');

        Route::post('/transactions/store-customer', [TransactionController::class, 'storeCustomer'])
            ->name('transactions.store-customer');
    });

    // =====================
    // OWNER (Dikunci khusus role owner)
    // =====================
    Route::middleware(['role:owner'])->prefix('owner')->name('owner.')->group(function () {

        Route::get('/dashboard', [OwnerDashboard::class, 'index'])->name('dashboard');

        Route::get('/products', [OwnerProductController::class, 'index'])->name('products.index');
        Route::get('/products/{id}', [OwnerProductController::class, 'show'])->name('products.show');

        Route::get('/transactions', [OwnerTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/export/pdf', [OwnerTransactionController::class, 'exportPdf'])->name('transactions.export.pdf');
        Route::get('/transactions/{id}', [OwnerTransactionController::class, 'show'])->name('transactions.show');

        Route::get('/activity-logs', [OwnerActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/{id}', [OwnerActivityLogController::class, 'show'])->name('activity-logs.show');

        // ✅ ROUTES UNTUK USER MANAGEMENT
        Route::resource('users', OwnerUserController::class);

        // ✅ ROUTES UNTUK PROFILE OWNER (DARI TOPBAR DROPDOWN)
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });
});
