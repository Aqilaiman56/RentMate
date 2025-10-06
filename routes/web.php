<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Location;

/*
|--------------------------------------------------------------------------
| Existing Routes (Keep these)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/homepage', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('user.HomePage');

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

// Existing navigation routes
Route::middleware('auth')->group(function () {
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/chat', function() {
        return view('chat.index');
    })->name('chat');
    Route::get('/notifications', function() {
        return view('notifications.index'); 
    })->name('notifications');
});

/*
|--------------------------------------------------------------------------
| NEW RentMate Routes (Add these)
|--------------------------------------------------------------------------
*/



// Item Details Page (Public but some features require auth)
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.details');

// Protected RentMate Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Wishlist Routes
    Route::post('/wishlist/toggle/{itemId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/add/{itemId}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{itemId}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    
    // Booking Routes
    Route::post('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    
    // Message Routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
    
    // User Item Management (List, Create, Edit, Delete)
    Route::get('/my-items', [ItemController::class, 'myItems'])->name('items.my');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items/store', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
    
    // Review Routes
    Route::post('/review/add', [ItemController::class, 'addReview'])->name('review.add');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/listings', [AdminController::class, 'listings'])->name('listings');
    Route::get('/deposits', [AdminController::class, 'deposits'])->name('deposits');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/penalties', [AdminController::class, 'penalties'])->name('penalties');
    Route::get('/taxes', [AdminController::class, 'taxes'])->name('taxes');

      // Actions
    Route::post('/reports/{penalty}/approve', [AdminController::class, 'approveReport'])->name('reports.approve');
    Route::post('/reports/{penalty}/reject', [AdminController::class, 'rejectReport'])->name('reports.reject');
});


require __DIR__.'/auth.php';