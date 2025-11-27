<?php

use App\Http\Middleware\CheckSuspension;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

test('middleware allows non-authenticated users to pass through', function () {
    $middleware = new CheckSuspension();
    $request = Request::create('/test', 'GET');

    $response = $middleware->handle($request, function ($req) {
        return response('Next middleware called', 200);
    });

    expect($response->getContent())->toBe('Next middleware called');
});

test('middleware allows admin users to pass through even if suspended', function () {
    $admin = User::factory()->create([
        'IsAdmin' => true,
        'IsSuspended' => true,
    ]);

    Auth::login($admin);

    $middleware = new CheckSuspension();
    $request = Request::create('/test', 'GET');

    $response = $middleware->handle($request, function ($req) {
        return response('Admin passed through', 200);
    });

    expect($response->getContent())->toBe('Admin passed through');
});

test('middleware allows non-suspended users to pass through', function () {
    $user = User::factory()->create([
        'IsAdmin' => false,
        'IsSuspended' => false,
    ]);

    Auth::login($user);

    $middleware = new CheckSuspension();
    $request = Request::create('/test', 'GET');

    $response = $middleware->handle($request, function ($req) {
        return response('User passed through', 200);
    });

    expect($response->getContent())->toBe('User passed through');
});

test('middleware redirects suspended users to login', function () {
    $user = User::factory()->create([
        'IsAdmin' => false,
        'IsSuspended' => true,
        'SuspendedUntil' => null,
        'SuspensionReason' => 'Violation of terms',
    ]);

    // Start session
    $request = Request::create('/test', 'GET');
    $request->setLaravelSession(session());

    Auth::login($user);

    $middleware = new CheckSuspension();

    $response = $middleware->handle($request, function ($req) {
        return response('Should not reach here', 200);
    });

    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class)
        ->and($response->getTargetUrl())->toContain('login')
        ->and(Auth::check())->toBeFalse();
});

test('middleware includes suspension reason in redirect message', function () {
    $user = User::factory()->create([
        'IsAdmin' => false,
        'IsSuspended' => true,
        'SuspendedUntil' => null,
        'SuspensionReason' => 'Multiple violations',
    ]);

    $request = Request::create('/test', 'GET');
    $request->setLaravelSession(session());

    Auth::login($user);

    $middleware = new CheckSuspension();
    $response = $middleware->handle($request, function ($req) {
        return response('Should not reach here', 200);
    });

    $session = $request->session();
    $errorMessage = $session->get('error');

    expect($errorMessage)->toContain('Multiple violations')
        ->and($errorMessage)->toContain('permanent');
});

test('middleware includes suspension expiry date in message', function () {
    $expiryDate = now()->addDays(7);
    $user = User::factory()->create([
        'IsAdmin' => false,
        'IsSuspended' => true,
        'SuspendedUntil' => $expiryDate,
        'SuspensionReason' => 'Temporary suspension',
    ]);

    $request = Request::create('/test', 'GET');
    $request->setLaravelSession(session());

    Auth::login($user);

    $middleware = new CheckSuspension();
    $response = $middleware->handle($request, function ($req) {
        return response('Should not reach here', 200);
    });

    $session = $request->session();
    $errorMessage = $session->get('error');

    expect($errorMessage)->toContain('Temporary suspension')
        ->and($errorMessage)->toContain('Suspension expires on:');
});

test('middleware allows users with expired suspension to pass through', function () {
    $user = User::factory()->create([
        'IsAdmin' => false,
        'IsSuspended' => true,
        'SuspendedUntil' => now()->subDay(), // Expired yesterday
    ]);

    Auth::login($user);

    $middleware = new CheckSuspension();
    $request = Request::create('/test', 'GET');

    $response = $middleware->handle($request, function ($req) {
        return response('User passed after auto-unsuspend', 200);
    });

    // User should be auto-unsuspended and allowed through
    expect($response->getContent())->toBe('User passed after auto-unsuspend')
        ->and(Auth::check())->toBeTrue();

    $user->refresh();
    expect($user->IsSuspended)->toBeFalse();
});
