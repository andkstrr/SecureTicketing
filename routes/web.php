<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DemoBladeController;
use App\Http\Controllers\XSSLabController;
use App\Http\Controllers\ValidationLabController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SecurityTestController;
use App\Http\Controllers\CsrfLabController;
use App\Http\Controllers\SqliLabController;

/*
|--------------------------------------------------------------------------
| Web Routes - Contoh untuk Hari 3 MVC Laravel
|--------------------------------------------------------------------------
|
| Tambahkan route di bawah ini ke file routes/web.php di proyek Laravel Anda
|
*/

// ============================================
// BASIC ROUTES (Contoh)
// ============================================

Route::get('/', function () {
    return view('welcome');
});

// Route sederhana dengan Closure
Route::get('/hello', function () {
    return 'Hello World! Selamat datang di Bootcamp Secure Coding!';
});

// Route yang mengembalikan JSON
Route::get('/api/status', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'Server berjalan dengan baik',
        'time' => now()->toDateTimeString(),
    ]);
});

// ============================================
// RESOURCE ROUTES - TICKETS
// ============================================

// Route::resource() otomatis membuat 7 routes:
// GET    /tickets           → TicketController@index    (tickets.index)
// GET    /tickets/create    → TicketController@create   (tickets.create)
// POST   /tickets           → TicketController@store    (tickets.store)
// GET    /tickets/{ticket}  → TicketController@show     (tickets.show)
// GET    /tickets/{ticket}/edit → TicketController@edit (tickets.edit)
// PUT    /tickets/{ticket}  → TicketController@update   (tickets.update)
// DELETE /tickets/{ticket}  → TicketController@destroy  (tickets.destroy)

Route::resource('tickets', TicketController::class);

// ============================================
// ALTERNATIVE: ROUTES MANUAL
// ============================================
// Jika ingin mendefinisikan secara manual:
// 
// Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
// Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
// Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
// Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
// Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
// Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
// Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

// Demo Blade Templating
Route::prefix('demo-blade')->name('demo-blade.')->group(function () {
    Route::get('/', [DemoBladeController::class, 'index'])->name('index');
    Route::get('/directives', [DemoBladeController::class, 'directives'])->name('directives');
    Route::get('/components', [DemoBladeController::class, 'components'])->name('components');
    Route::get('/includes', [DemoBladeController::class, 'includes'])->name('includes');
    Route::get('/stacks', [DemoBladeController::class, 'stacks'])->name('stacks');
});

// Store comment (POST)
Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])
    ->name('comments.store');

// Delete comment (DELETE)
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
    ->name('comments.destroy');

// Update comment (optional) - (PUT/PATCH)
Route::put('/comments/{comment}', [CommentController::class, 'update'])
    ->name('comments.update');

// =========================================
// SECURITY TESTING ROUTES
// =========================================
// Dashboard untuk testing keamanan aplikasi
// PENTING: Jangan aktifkan di production!

Route::prefix('security-testing')->name('security-testing.')->group(function () {
    // Dashboard index
    Route::get('/', [SecurityTestController::class, 'index'])->name('index');
    
    // XSS Testing
    Route::get('/xss', [SecurityTestController::class, 'xssTest'])->name('xss');
    
    // CSRF Testing
    Route::get('/csrf', [SecurityTestController::class, 'csrfTest'])->name('csrf');
    Route::post('/csrf', [SecurityTestController::class, 'csrfTestPost'])->name('csrf.post');
    
    // Security Headers Testing
    Route::get('/headers', [SecurityTestController::class, 'headersTest'])->name('headers');
    
    // Audit Checklist
    Route::get('/audit', [SecurityTestController::class, 'auditChecklist'])->name('audit');
});

// XSS Lab
Route::prefix('xss-lab')->name('xss-lab.')->group(function () {
    Route::get('/', [XSSLabController::class, 'index'])->name('index');
    Route::post('/reset-comments', [XSSLabController::class, 'resetComments'])->name('reset-comments');

    // Reflected XSS
    Route::get('/reflected/vulnerable', [XSSLabController::class, 'reflectedVulnerable'])->name('reflected.vulnerable');
    Route::get('/reflected/secure', [XSSLabController::class, 'reflectedSecure'])->name('reflected.secure');

    // Stored XSS
    Route::get('/stored/vulnerable', [XSSLabController::class, 'storedVulnerable'])->name('stored.vulnerable');
    Route::post('/stored/vulnerable', [XSSLabController::class, 'storedVulnerableStore'])->name('stored.vulnerable.store');
    Route::get('/stored/secure', [XSSLabController::class, 'storedSecure'])->name('stored.secure');
    Route::post('/stored/secure', [XSSLabController::class, 'storedSecureStore'])->name('stored.secure.store');

    // DOM-Based XSS
    Route::get('/dom/vulnerable', [XSSLabController::class, 'domVulnerable'])->name('dom.vulnerable');
    Route::get('/dom/secure', [XSSLabController::class, 'domSecure'])->name('dom.secure');
});

// Validation Lab
Route::prefix('validation-lab')->name('validation-lab.')->group(function () {
    Route::get('/', [ValidationLabController::class, 'index'])->name('index');
    
    // Vulnerable
    Route::get('/vulnerable', [ValidationLabController::class, 'vulnerableForm'])->name('vulnerable');
    Route::post('/vulnerable', [ValidationLabController::class, 'vulnerableSubmit'])->name('vulnerable.submit');
    Route::post('/vulnerable/clear', [ValidationLabController::class, 'vulnerableClear'])->name('vulnerable.clear');
    
    // Secure
    Route::get('/secure', [ValidationLabController::class, 'secureForm'])->name('secure');
    Route::post('/secure', [ValidationLabController::class, 'secureSubmit'])->name('secure.submit');
    Route::post('/secure/clear', [ValidationLabController::class, 'secureClear'])->name('secure.clear');
});

Route::prefix('csrf-lab')->name('csrf-lab.')->group(function () {
    // Index - Menu Lab
    Route::get('/', [CsrfLabController::class, 'index'])
        ->name('index');
    
    // How It Works - Penjelasan CSRF
    Route::get('/how-it-works', [CsrfLabController::class, 'howItWorks'])
        ->name('how-it-works');
    
    // Attack Demo - Simulasi serangan
    Route::get('/attack-demo', [CsrfLabController::class, 'attackDemo'])
        ->name('attack-demo');
    
    // Protection Demo - Demo protection
    Route::get('/protection-demo', [CsrfLabController::class, 'protectionDemo'])
        ->name('protection-demo');
    
    // AJAX Demo - CSRF untuk AJAX
    Route::get('/ajax-demo', [CsrfLabController::class, 'ajaxDemo'])
        ->name('ajax-demo');
    
    // ----- ACTION ROUTES -----
    
    // Secure transfer (dengan CSRF protection normal)
    Route::post('/secure-transfer', [CsrfLabController::class, 'secureTransfer'])
        ->name('secure-transfer');
    
    // Protected action
    Route::post('/protected-action', [CsrfLabController::class, 'protectedAction'])
        ->name('protected-action');
    
    // AJAX action
    Route::post('/ajax-action', [CsrfLabController::class, 'ajaxAction'])
        ->name('ajax-action');
    
    // Reset demo data
    Route::post('/reset', [CsrfLabController::class, 'resetDemo'])
        ->name('reset');
});

// ================================================================
// VULNERABLE ROUTE (untuk demo - di-exclude dari CSRF middleware)
// ⚠️ JANGAN GUNAKAN PATTERN INI DI PRODUCTION!
// ================================================================
// Route ini perlu di-exclude dari VerifyCsrfToken middleware
// untuk demonstrasi serangan CSRF
//
// NOTE: Di Laravel 11+, gunakan Illuminate\Foundation\Http\Middleware
// karena App\Http\Middleware\VerifyCsrfToken tidak ada by default
Route::post('/csrf-lab/vulnerable-transfer', [CsrfLabController::class, 'vulnerableTransfer'])
    ->name('csrf-lab.vulnerable-transfer')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Route untuk demo PROTECTED transfer (DENGAN CSRF - akan return 419 jika tanpa token)
Route::post('/csrf-lab/protected-transfer', [CsrfLabController::class, 'protectedTransfer'])
    ->name('csrf-lab.protected-transfer');


// ============================================
// SQL INJECTION LAB ROUTES
// ============================================
Route::prefix('sqli-lab')->name('sqli-lab.')->group(function () {

    // Menu utama
    Route::get('/', [SqliLabController::class, 'index'])->name('index');

    // Halaman edukasi
    Route::get('/how-it-works', [SqliLabController::class, 'howItWorks'])->name('how-it-works');
    Route::get('/cheatsheet', [SqliLabController::class, 'cheatsheet'])->name('cheatsheet');

    // ============================================
    // VULNERABLE ENDPOINTS (UNTUK DEMO)
    // ============================================
    // PERINGATAN: Endpoint ini SENGAJA VULNERABLE!
    // Hanya untuk pembelajaran - JANGAN gunakan di production!

    // Vulnerable Search - String concatenation
    Route::get('/vulnerable-search', [SqliLabController::class, 'vulnerableSearch'])
        ->name('vulnerable-search');

    // Vulnerable Login - Authentication bypass
    Route::get('/vulnerable-login', [SqliLabController::class, 'vulnerableLogin'])
        ->name('vulnerable-login');
    Route::post('/vulnerable-login', [SqliLabController::class, 'vulnerableLoginSubmit'])
        ->name('vulnerable-login-submit');

        // Blind SQL Injection Demo
    Route::get('/blind-sqli', [SqliLabController::class, 'blindSqli'])
        ->name('blind-sqli');
    Route::post('/blind-sqli/boolean', [SqliLabController::class, 'blindSqliBooleanCheck'])
        ->name('blind-sqli-boolean');
    Route::post('/blind-sqli/time', [SqliLabController::class, 'blindSqliTimeCheck'])
        ->name('blind-sqli-time');

    // ============================================
    // SECURE ENDPOINTS (BEST PRACTICE)
    // ============================================

    // Secure Search - 4 metode aman
    Route::get('/secure-search', [SqliLabController::class, 'secureSearch'])
        ->name('secure-search');

    // ============================================
    // UTILITY ROUTES
    // ============================================

    // Seed demo data
    Route::get('/seed-data', [SqliLabController::class, 'seedData'])
        ->name('seed');

    // Reset data
    Route::get('/reset-data', [SqliLabController::class, 'resetData'])
        ->name('reset');
});
