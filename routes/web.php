<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/admin', function () {
    return view('admin');
})->middleware(['auth', 'verified'])->name('admin.dashboard');

Route::get('/home', [ItemController::class, 'index'])->name('user.home');


Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,Email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $user = User::create([
        'UserName' => $request->name,
        'Email' => $request->email,
        'PasswordHash' => Hash::make($request->password),
        'UserType' => 'Student',  // default user type
        'IsAdmin' => false,       // default is not admin
    ]);

    Auth::login($user);

    return redirect('/user/home'); // or wherever you want
});

require __DIR__.'/auth.php';
