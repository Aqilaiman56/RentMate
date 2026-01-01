<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = auth()->user();

    // Check if email is verified (non-admin users only)
    if (!$user->IsAdmin && !$user->hasVerifiedEmail()) {
        // Redirect to verification notice page
        return redirect()->route('verification.notice');
    }

    // Check if user is suspended (non-admin users only)
    if (!$user->IsAdmin && $user->isCurrentlySuspended()) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Prepare suspension message
        $message = 'Your account has been suspended.';

        if ($user->SuspensionReason) {
            $message .= ' Reason: ' . $user->SuspensionReason;
        }

        if ($user->SuspendedUntil) {
            $message .= ' Suspension expires on: ' . $user->SuspendedUntil->format('M d, Y h:i A');
        } else {
            $message .= ' This suspension is permanent.';
        }

        return redirect()->route('login')->withErrors(['email' => $message]);
    }

    // Redirect admin to admin dashboard
    if ($user->IsAdmin == 1 || $user->IsAdmin === true) {
        return redirect()->route('admin.dashboard');
    }

    // Redirect normal user to user home page
    return redirect()->intended('/homepage');
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
