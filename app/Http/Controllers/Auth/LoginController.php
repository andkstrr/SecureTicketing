<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Login Controller - SECURE IMPLEMENTATION
 *
 * Controller ini mengimplementasikan best practices:
 * 1. Rate limiting via LoginRequest
 * 2. Session regeneration setelah login
 * 3. Proper session invalidation saat logout
 * 4. CSRF protection (via middleware)
 */
class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login', [
            'isSecure' => true,
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * SECURITY FEATURES:
     * 1. LoginRequest handles validation + rate limiting
     * 2. Session regeneration mencegah session fixation
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate (dengan rate limiting dari LoginRequest)
        $request->authenticate();

        // SECURITY: Regenerate session ID setelah login
        // Mencegah session fixation attack
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     *
     * SECURITY FEATURES:
     * 1. Session invalidation
     * 2. Token regeneration
     * 3. Complete logout flow
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        // SECURITY: Invalidate session untuk clear semua data
        $request->session()->invalidate();

        // SECURITY: Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show login status/info (untuk demo)
     */
    public function status(Request $request): View
    {
        $user = Auth::user();
        $email = $user ? $user->email : $request->input('email', '');

        $loginAttempts = \App\Models\LoginAttempt::secure()
        ->where('email', $email)
        ->latest()
        ->take(10)
        ->get();

        /** @var view-string $view */
    $view = 'auth.status';

    return view($view, [
        'attempts' => $loginAttempts,
        'isSecure' => true,
    ]);
    }
}
