<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse|View
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

    // Check for admin email
        $isAdmin = $request->email === 'admin@example.com'; // or any email you choose

        $user = User::create([
            'UserName' => $request->name,
            'Email' => $request->email,
            'PasswordHash' => Hash::make($request->password),
            'UserType' => $isAdmin ? 'Admin' : 'Student',
            'IsAdmin' => $isAdmin,
        ]);

    

         event(new Registered($user));

            if ($isAdmin) {
                Auth::login($user);
                return redirect(route('admin.AdminDashboard', absolute: false));
            }

            // For normal users, show registration success page (do NOT log them in)
            // They must verify email before they can login
            return view('auth.registration-success', ['email' => $user->Email]);
    }
}
