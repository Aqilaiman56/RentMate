{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- Include Navigation Component -->
    <x-navigation />
    
    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Heading -->
        @isset($header)
            <header class="page-header">
                <div class="header-container">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="page-main">
            {{ $slot }}
        </main>
    </div>

    <style>
        /* Main content layout */
        .main-content {
            margin-top: 64px; /* Height of top nav */
            min-height: calc(100vh - 64px);
        }

        .page-header {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 0;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .page-main {
            padding: 2rem 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Desktop layout with sidebar */
        @media (min-width: 1024px) {
            .main-content {
                margin-left: 280px; /* Width of sidebar */
            }
        }

        /* Mobile layout with bottom nav */
        @media (max-width: 1023px) {
            .main-content {
                margin-bottom: 80px; /* Height of bottom nav */
            }
        }
    </style>
</body>
</html>