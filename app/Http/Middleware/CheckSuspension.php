<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspension
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and not an admin
        if (auth()->check() && !auth()->user()->IsAdmin) {
            $user = auth()->user();

            // Check if user is currently suspended
            if ($user->isCurrentlySuspended()) {
                // Log the user out
                auth()->logout();

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

                // Redirect to login with error message
                return redirect()->route('login')
                    ->with('error', $message);
            }
        }

        return $next($request);
    }
}
