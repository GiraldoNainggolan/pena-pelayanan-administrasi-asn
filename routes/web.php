<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect / directly to login or role-specific dashboard
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return auth()->user()->role === 'viewer'
        ? redirect()->route('viewer.dashboard')
        : redirect()->route('admin.dashboard');
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');
    Route::get('/lupa-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/lupa-password', [ForgotPasswordController::class, 'store'])
        ->middleware('throttle:password-reset')
        ->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])
        ->middleware('throttle:password-reset')
        ->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::put('/profil/password', [ProfileController::class, 'updatePassword'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('profile.password.update');

// ADMIN & OPERATOR CONSOLE
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin,operator', 'password.changed'])
    ->group(function (): void {
        // Dashboard
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        
        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

        // Input & Data Layanan (Admin, Operator can modify)
        Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
        Route::get('/layanan/create', [LayananController::class, 'create'])->name('layanan.create');
        Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
        Route::get('/layanan/{id}/edit', [LayananController::class, 'edit'])->name('layanan.edit');
        Route::put('/layanan/{id}', [LayananController::class, 'update'])->name('layanan.update');
        
        Route::middleware('role:admin')->group(function (): void {
            Route::delete('/layanan/{id}', [LayananController::class, 'destroy'])->name('layanan.destroy');
        });

        // Detail Layanan
        Route::get('/layanan/{id}', [LayananController::class, 'show'])->name('layanan.show');

        // Export routes
        Route::get('/export/excel', [LayananController::class, 'exportExcel'])->name('export.excel');
        Route::get('/print/{id}', [LayananController::class, 'print'])->name('print');

        // Laporan
        Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');

        // Master Data (Admin only)
        Route::middleware('role:admin')->group(function (): void {
            Route::get('/master', [MasterController::class, 'index'])->name('master.index');
            Route::post('/master/kecamatan', [MasterController::class, 'storeKecamatan'])->name('master.kecamatan.store');
            Route::post('/master/sekolah', [MasterController::class, 'storeSekolah'])->name('master.sekolah.store');
            Route::delete('/master/kecamatan/{id}', [MasterController::class, 'destroyKecamatan'])->name('master.kecamatan.destroy');
            Route::delete('/master/sekolah/{id}', [MasterController::class, 'destroySekolah'])->name('master.sekolah.destroy');
            
            // User Management (Admin only)
            Route::get('/users', [MasterController::class, 'usersIndex'])->name('users.index');
            Route::post('/users', [MasterController::class, 'usersStore'])->name('users.store');
            Route::delete('/users/{id}', [MasterController::class, 'usersDestroy'])->name('users.destroy');
            Route::put('/users/{id}/password', [MasterController::class, 'usersPasswordUpdate'])
                ->middleware('throttle:6,1')
                ->name('users.password.update');
        });
    });

// VIEWER CONSOLE (Kabid/Kadin - Read-only)
Route::prefix('viewer')
    ->name('viewer.')
    ->middleware(['auth', 'role:viewer', 'password.changed'])
    ->group(function (): void {
        // Dashboard
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        
        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

        // Data Layanan & Details (Read-only)
        Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
        Route::get('/layanan/{id}', [LayananController::class, 'show'])->name('layanan.show');

        // Export PDF
        Route::get('/print/{id}', [LayananController::class, 'print'])->name('print');

        // Laporan
        Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    });
