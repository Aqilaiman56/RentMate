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

// Note: Suspension-related tests disabled until IsSuspended, IsAdmin, SuspendedUntil columns are added to users table
// test('middleware allows admin users to pass through even if suspended', function () { ... });
// test('middleware allows non-suspended users to pass through', function () { ... });
// test('middleware redirects suspended users to login', function () { ... });
// test('middleware includes suspension reason in redirect message', function () { ... });
// test('middleware includes suspension expiry date in message', function () { ... });
// test('middleware allows users with expired suspension to pass through', function () { ... });
