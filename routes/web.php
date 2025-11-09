<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Admin\ListingsController as AdminListingsController;
use App\Http\Controllers\Admin\DepositsController as AdminDepositsController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;
use App\Http\Controllers\Admin\PenaltiesController as AdminPenaltiesController;
use App\Http\Controllers\Admin\ServiceFeesController as AdminServiceFeesController;
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

// Welcome/Landing Page (Public with search and category filter)
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Public Item Details (No auth required - guests can browse)
Route::get('/item/{id}', [ItemController::class, 'showPublicDetails'])->name('welcome.item.details');

// Public User Profile (No auth required - anyone can view)
Route::get('/user-profile/{id}', [App\Http\Controllers\UserProfileController::class, 'show'])->name('user.public.profile');

// Terms of Service (Public)
Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

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

    return redirect()->route('user.HomePage');
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

// Authenticated Item Details Page (with booking functionality)
Route::middleware('auth')->group(function () {
    Route::get('/items/{id}/details', [ItemController::class, 'show'])->name('item.details');
});

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
| Booking Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/booking/confirm', [BookingController::class, 'confirm'])->name('booking.confirm');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/create-and-pay', [BookingController::class, 'createAndPay'])->name('bookings.create_and_pay');
    Route::post('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::post('/booking/{id}/complete', [BookingController::class, 'complete'])->name('booking.complete');
    Route::post('/booking/{id}/approve', [BookingController::class, 'approve'])->name('booking.approve');
    Route::post('/booking/{id}/reject', [BookingController::class, 'reject'])->name('booking.reject');
    Route::get('/api/items/{itemId}/unavailable-dates', [BookingController::class, 'getUnavailableDates'])->name('booking.unavailable_dates');
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
| Payment Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('payment.show');
    Route::get('/payment/status/{bookingId}', [PaymentController::class, 'checkStatus'])->name('payment.status');
    Route::post('/payment/{id}/refund', [PaymentController::class, 'refund'])->name('payment.refund');
    Route::get('/payment/history/{bookingId}', [PaymentController::class, 'history'])->name('payment.history');
});

// Payment callback (no auth required)
Route::any('/payment/callback', [PaymentController::class, 'paymentCallback'])->name('payment.callback');

// Test payment page (for testing during ToyyibPay verification)
Route::get('/payment/test/{bill_code}', [PaymentController::class, 'testPayment'])->name('payment.test');

/*
|--------------------------------------------------------------------------
| Message Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
    Route::get('/messages/new/{userId}', [MessageController::class, 'getNewMessages'])->name('messages.new');
    Route::get('/messages/unread-count', [MessageController::class, 'getUnreadCount'])->name('messages.unreadCount');
});

/*
|--------------------------------------------------------------------------
| Notification Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

/*
|--------------------------------------------------------------------------
| User Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    
    // User Profile Settings
    Route::get('/profile', [ProfileController::class, 'userProfile'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'userUpdateProfile'])->name('profile.update');
    Route::patch('/profile/bank', [ProfileController::class, 'updateBankDetails'])->name('profile.bank.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // User Listings Management
    Route::get('/listings', [ItemController::class, 'userListings'])->name('listings');

    // View Bookings for a Listing
    Route::get('/listings/{id}/bookings', [ItemController::class, 'viewItemBookings'])->name('listings.bookings');

    // Add New Listing
    Route::get('/add-listing', [ItemController::class, 'create'])->name('add-listing');
    
    // User Bookings
    Route::get('/bookings', [BookingController::class, 'userBookings'])->name('bookings');
    
    // User Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');

    // Report Submission
    Route::get('/report', [ProfileController::class, 'showReportForm'])->name('report');
    Route::post('/report', [ProfileController::class, 'submitReport'])->name('report.submit');
});

/*
|--------------------------------------------------------------------------
| Wishlist Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/wishlist/toggle/{itemId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/add/{itemId}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{itemId}', [WishlistController::class, 'remove'])->name('wishlist.remove');
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
        return app(AdminDashboardController::class)->index();
    })->name('dashboard');
    
    // Users Management
    Route::get('/users', function(Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminUsersController::class)->index($request);
    })->name('users');
    
    Route::get('/users/export', function() {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminUsersController::class)->export();
    })->name('users.export');
    
    Route::get('/users/{id}', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminUsersController::class)->show($id);
    })->name('users.show');
    
    Route::delete('/users/{id}', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminUsersController::class)->destroy($id);
    })->name('users.destroy');

    Route::post('/users/{id}/suspend', function($id, Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminUsersController::class)->suspend($request, $id);
    })->name('users.suspend');

    Route::post('/users/{id}/unsuspend', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminUsersController::class)->unsuspend($id);
    })->name('users.unsuspend');

    Route::post('/users/{id}/reset-password', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminUsersController::class)->resetPassword($id);
    })->name('users.resetPassword');

    Route::get('/users/{id}/activity-log', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminUsersController::class)->activityLog($id);
    })->name('users.activityLog');
    
    // Listings Management
    Route::get('/listings', function(Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminListingsController::class)->index($request);
    })->name('listings');

    Route::get('/listings/export', function() {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminListingsController::class)->export();
    })->name('listings.export');

    Route::get('/listings/{id}', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminListingsController::class)->show($id);
    })->name('listings.show');

    Route::delete('/listings/{id}', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminListingsController::class)->destroy($id);
    })->name('listings.destroy');
    
    // Deposits Management
    Route::get('/deposits', function(Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminDepositsController::class)->index($request);
    })->name('deposits');

    Route::get('/deposits/export', function() {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminDepositsController::class)->export();
    })->name('deposits.export');

    Route::get('/deposits/report', function() {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminDepositsController::class)->generateReport();
    })->name('deposits.report');

    Route::get('/deposits/{id}', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminDepositsController::class)->show($id);
    })->name('deposits.show');

    Route::post('/deposits/{id}/refund', function($id, Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminDepositsController::class)->refund($request, $id);
    })->name('deposits.refund');

    Route::post('/deposits/{id}/forfeit', function($id, Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(AdminDepositsController::class)->forfeit($request, $id);
    })->name('deposits.forfeit');

    // Refund Queue Management
    Route::get('/refund-queue', function(Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(\App\Http\Controllers\Admin\RefundQueueController::class)->index($request);
    })->name('refund-queue');

    Route::post('/refund-queue/{id}/processing', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(\App\Http\Controllers\Admin\RefundQueueController::class)->markProcessing($id);
    })->name('refund-queue.processing');

    Route::post('/refund-queue/{id}/complete', function($id, Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(\App\Http\Controllers\Admin\RefundQueueController::class)->complete($request, $id);
    })->name('refund-queue.complete');

    Route::post('/refund-queue/{id}/failed', function($id, Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        return app(\App\Http\Controllers\Admin\RefundQueueController::class)->markFailed($request, $id);
    })->name('refund-queue.failed');

    // Reports Management
    Route::get('/reports', function(Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        $controller = new AdminReportsController();
        return $controller->index($request);
    })->name('reports');

    Route::get('/reports/{id}', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        $controller = new AdminReportsController();
        return $controller->show($id);
    })->name('reports.show');

    Route::post('/reports/{id}/resolve', function($id, Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        $controller = new AdminReportsController();
        return $controller->resolve($request, $id);
    })->name('reports.resolve');

    Route::post('/reports/{id}/dismiss', function($id, Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        $controller = new AdminReportsController();
        return $controller->dismiss($request, $id);
    })->name('reports.dismiss');

    Route::post('/reports/{id}/suspend-user', function($id, Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        $controller = new AdminReportsController();
        return $controller->suspendUser($request, $id);
    })->name('reports.suspend-user');

    Route::post('/reports/{id}/issue-warning', function($id, Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        $controller = new AdminReportsController();
        return $controller->issueWarning($request, $id);
    })->name('reports.issue-warning');

    Route::post('/reports/{id}/hold-deposit', function($id, Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        $controller = new AdminReportsController();
        return $controller->holdDeposit($request, $id);
    })->name('reports.hold-deposit');

    Route::get('/reports-export', function() {
        if (!auth()->user()->IsAdmin) {
            abort(403, 'Unauthorized access. Admin only.');
        }
        $controller = new AdminReportsController();
        return $controller->export();
    })->name('reports.export');
        
    // Penalties Management
    Route::get('/penalties', function(Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403);
        }
        $controller = new AdminPenaltiesController();
        return $controller->index($request);
    })->name('penalties');

    Route::get('/penalties/{id}', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403);
        }
        $controller = new AdminPenaltiesController();
        return $controller->show($id);
    })->name('penalties.show');

    Route::post('/penalties/{id}/resolve', function($id) {
        if (!auth()->user()->IsAdmin) {
            abort(403);
        }
        $controller = new AdminPenaltiesController();
        return $controller->resolve($id);
    })->name('penalties.resolve');

    Route::get('/penalties-export', function() {
        if (!auth()->user()->IsAdmin) {
            abort(403);
        }
        $controller = new AdminPenaltiesController();
        return $controller->export();
    })->name('penalties.export');

    // Service Fees Management
    Route::get('/service-fees', function(Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403);
        }
        $controller = new AdminServiceFeesController();
        return $controller->index($request);
    })->name('service-fees');

    Route::get('/service-fees-export', function(Request $request) {
        if (!auth()->user()->IsAdmin) {
            abort(403);
        }
        $controller = new AdminServiceFeesController();
        return $controller->export($request);
    })->name('service-fees.export');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';