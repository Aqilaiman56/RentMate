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

Route::get('/homepage', function () {
    return view('user.HomePage');
})->middleware(['auth', 'verified'])->name('user.HomePage');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/AdminDashboard', function () {
    return view('admin.AdminDashboard');
})->middleware(['auth', 'verified'])->name('admin.AdminDashboard');




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

    return redirect('admin.AdminDashboard'); // or wherever you want
});

// this one for navigation direction 
Route::middleware('auth')->group(function () {
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/chat', function() {
        return view('chat.index');
    })->name('chat');
    Route::get('/notifications', function() {
        return view('notifications.index'); 
    })->name('notifications');
});

require __DIR__.'/auth.php';
