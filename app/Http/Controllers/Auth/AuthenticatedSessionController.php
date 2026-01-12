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
    public function create(Request $request): View
    {
        // Check if there's an item parameter (user coming from booking)
        $itemId = $request->query('item');

        return view('auth.login', ['itemId' => $itemId]);
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

    // Check if user came from booking a specific item
    $itemId = $request->query('item');
    if ($itemId) {
        // Redirect to authenticated item details page to continue booking
        return redirect()->route('item.details', ['id' => $itemId]);
    }

    // Get the intended URL and check if it's an API endpoint
    $intendedUrl = $request->session()->get('url.intended');
    if ($intendedUrl && (str_contains($intendedUrl, '/api/') || str_contains($intendedUrl, 'unavailable-dates'))) {
        // Clear the intended URL if it's an API endpoint
        $request->session()->forget('url.intended');
        return redirect('/homepage');
    }

    // Redirect normal user to homepage (or intended URL, but not if it's the welcome page)
    $intended = $request->session()->get('url.intended');
    if (!$intended || $intended === url('/')) {
        // If no intended URL or intended URL is the welcome page, go to homepage
        return redirect('/homepage');
    }

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

        // Clear any intended URL to prevent redirect to API endpoints
        $request->session()->forget('url.intended');

        return redirect('/');
    }
}
