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
    <!-- Fixed navigation sidebar -->
    @include('layouts.navigation')
    
    <!-- Main content area that shifts based on sidebar -->
    <div class="sm:ml-64">
        <!-- SuperUser Alert Bar -->
        @hasrole('SuperUser')
        <div class="bg-red-600 text-white text-center py-2">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="font-medium">SuperUser Access - You have global permissions</p>
            </div>
        </div>
        @endhasrole

        <!-- Page Heading -->
        @isset($header)
        <header class="p-2 border-b border-slate-700 bg-white dark:bg-gray-900 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main class="min-h-screen bg-gray-100 dark:bg-gray-900 pt-4">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>
</body>

</html>