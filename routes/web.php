<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Admin\ListingsController as AdminListingsController;
use App\Http\Controllers\Admin\DepositsController as AdminDepositsController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;
use App\Http\Controllers\Admin\PenaltiesController as AdminPenaltiesController;
use App\Http\Controllers\Admin\TaxesController as AdminTaxesController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome/Landing Page
Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

// User Homepage (Authenticated)
Route::get('/homepage', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('user.HomePage');

// Profile Management Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Dashboard (Legacy - might be duplicate)
Route::get('/AdminDashboard', function () {
    return view('admin.AdminDashboard');
})->middleware(['auth', 'verified'])->name('admin.AdminDashboard');

// Custom Registration Route
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
        'UserType' => 'Student',
        'IsAdmin' => false,
    ]);

    Auth::login($user);

    return redirect()->route('admin.AdminDashboard');
});

/*
|--------------------------------------------------------------------------
| Item Routes
|--------------------------------------------------------------------------
*/

// Browse Items (Authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
});

// Item Details Page (Public but some features require auth)
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.details');

// User Item Management (List, Create, Edit, Delete)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-items', [ItemController::class, 'myItems'])->name('items.my');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items/store', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
});

/*
|--------------------------------------------------------------------------
| Wishlist Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{itemId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/add/{itemId}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{itemId}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

/*
|--------------------------------------------------------------------------
| Booking Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
});

/*
|--------------------------------------------------------------------------
| Message/Chat Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/chat', function() {
        return view('chat.index');
    })->name('chat');
    
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
});

/*
|--------------------------------------------------------------------------
| Review Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/review/add', [ItemController::class, 'addReview'])->name('review.add');
});

/*
|--------------------------------------------------------------------------
| Notification Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/notifications', function() {
        return view('notifications.index'); 
    })->name('notifications');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth', 'verified'])->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', function() {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\DashboardController::class)->index();
    })->name('dashboard');
    
    // Users Management
    Route::get('/users', function(Illuminate\Http\Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\UsersController::class)->index($request);
    })->name('users');
    
    Route::get('/users/export', function() {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\UsersController::class)->export();
    })->name('users.export');
    
    Route::get('/users/{id}', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\UsersController::class)->show($id);
    })->name('users.show');
    
    Route::delete('/users/{id}', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\UsersController::class)->destroy($id);
    })->name('users.destroy');
    
    // Listings
    Route::get('/listings', function() {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\ListingsController::class)->index();
    })->name('listings');
    
    // Deposits
    Route::get('/deposits', function(Illuminate\Http\Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\DepositsController::class)->index($request);
    })->name('deposits');

    Route::get('/deposits/export', function() {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\DepositsController::class)->export();
    })->name('deposits.export');

    Route::get('/deposits/report', function() {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\DepositsController::class)->generateReport();
    })->name('deposits.report');

    Route::get('/deposits/{id}', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\DepositsController::class)->show($id);
    })->name('deposits.show');

    Route::post('/deposits/{id}/refund', function($id, Illuminate\Http\Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\DepositsController::class)->refund($request, $id);
    })->name('deposits.refund');

    Route::post('/deposits/{id}/forfeit', function($id, Illuminate\Http\Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\DepositsController::class)->forfeit($request, $id);
    })->name('deposits.forfeit');


    // Reports
    Route::get('/reports', function() {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\ReportsController::class)->index();
    })->name('reports');
    
    // Penalties
    Route::get('/penalties', function() {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\PenaltiesController::class)->index();
    })->name('penalties');
    
    // Taxes
    Route::get('/taxes', function() {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(App\Http\Controllers\Admin\TaxesController::class)->index();
    })->name('taxes');

        // Listings Management
        Route::get('/listings', function(Illuminate\Http\Request $request) {
            if (!auth()->user()->IsAdmin) {
                abort(403, 'Unauthorized access. Admin only.');
            }
            return app(App\Http\Controllers\Admin\ListingsController::class)->index($request);
        })->name('listings');

        Route::get('/listings/export', function() {
            if (!auth()->user()->IsAdmin) {
                abort(403, 'Unauthorized access. Admin only.');
            }
            return app(App\Http\Controllers\Admin\ListingsController::class)->export();
        })->name('listings.export');

        Route::delete('/listings/{id}', function($id) {
            if (!auth()->user()->IsAdmin) {
                abort(403, 'Unauthorized access. Admin only.');
            }
            return app(App\Http\Controllers\Admin\ListingsController::class)->destroy($id);
        })->name('listings.destroy');
 });

/*
|--------------------------------------------------------------------------
| User Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    
    // User Profile Settings (Different from Admin Profile)
    Route::get('/profile', [ProfileController::class, 'userProfile'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'userUpdateProfile'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // User Listings Management
    Route::get('/listings', [ItemController::class, 'userListings'])->name('listings');
    
    // Add New Listing
    Route::get('/add-listing', [ItemController::class, 'create'])->name('add-listing');
    
    // User Bookings
    Route::get('/bookings', [BookingController::class, 'userBookings'])->name('bookings');
    
    // User Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    
});

// Inside the user middleware group
Route::post('/report', [ProfileController::class, 'submitReport'])->name('report.submit');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';