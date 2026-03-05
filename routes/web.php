<?php

// Controllers
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CsrfLabController;
use App\Http\Controllers\DemoBladeController;
use App\Http\Controllers\Lab\SecureController;
use App\Http\Controllers\Lab\VulnerableController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecurityTestController;
use App\Http\Controllers\SqliLabController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ValidationLabController;
use App\Http\Controllers\VulnerableAuth\VulnerableLoginController;
use App\Http\Controllers\VulnerableAuth\VulnerableRegisterController;
use App\Http\Controllers\XSSLabController;
use Illuminate\Support\Facades\Route;

// ============================================================================
// BAC/IDOR Lab Routes (Minggu 4 Hari 4 - Broken Access Control)
// ============================================================================

// Public routes (tidak perlu login untuk baca materi)
Route::prefix('bac-lab')->name('bac-lab.')->group(function () {

    // Lab Index - Overview & Pilihan Secure/Vulnerable (public)
    Route::get('/', function () {
        return view('bac-lab.index');
    })->name('home');

    // Comparison Page (public)
    Route::get('/comparison', function () {
        return view('bac-lab.comparison');
    })->name('comparison');

    // Login Pages untuk masing-masing versi (public)
    Route::get('/vulnerable/login', function () {
        return view('bac-lab.vulnerable.login');
    })->name('vulnerable.login');

    Route::get('/secure/login', function () {
        return view('bac-lab.secure.login');
    })->name('secure.login');
});

// Protected routes (perlu login untuk demo)
Route::middleware('auth')->prefix('bac-lab')->name('bac-lab.')->group(function () {

    // ========================================
    // VULNERABLE VERSION (IDOR Demo)
    // ========================================
    // ⚠️ Route ini SENGAJA dibuat vulnerable untuk demonstrasi
    // JANGAN gunakan pattern ini di production!

    Route::prefix('vulnerable')->name('vulnerable.')->group(function () {

        Route::get('/tickets', [VulnerableController::class, 'index'])
            ->name('tickets.index');

        Route::get('/tickets/{id}', [VulnerableController::class, 'show'])
            ->name('tickets.show');

        Route::get('/tickets/{id}/edit', [VulnerableController::class, 'edit'])
            ->name('tickets.edit');

        Route::put('/tickets/{id}', [VulnerableController::class, 'update'])
            ->name('tickets.update');

        Route::delete('/tickets/{id}', [VulnerableController::class, 'destroy'])
            ->name('tickets.destroy');
    });

    // ========================================
    // SECURE VERSION (dengan Policy)
    // ========================================
    // ✅ Route ini menggunakan Policy untuk authorization
    // GUNAKAN pattern ini di production!

    Route::prefix('secure')->name('secure.')->group(function () {

        // Resource route dengan route model binding
        // Policy akan otomatis di-check via authorizeResource()
        Route::resource('tickets', SecureController::class)
            ->parameters(['tickets' => 'ticket']);
    });
});

// ============================================================================
// Secure Auth Routes
// ============================================================================
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});