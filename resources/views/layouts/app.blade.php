<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js x-cloak style -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="font-sans antialiased bg-[#F7F7F7] text-[#1A1A1A]"
    x-data="{ sidebarExpanded: true }"
    x-init="() => {
        document.addEventListener('sidebar-toggled', (event) => {
            sidebarExpanded = event.detail.expanded;
        });
    }">
    <!-- Fixed navigation sidebar -->
    @include('layouts.navigation')

    <!-- Main content area that shifts based on sidebar -->
    <div id="main-content"
        :class="{ 'lg:ml-64': sidebarExpanded, 'lg:ml-20': !sidebarExpanded }"
        class="transition-all duration-300">

        <!-- Page Heading -->
        @isset($header)
        <header class="p-2 border-b border-[#E0E0E0] bg-[#FFFFFF] shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div>
                    {{ $header }}
                </div>
                <div>
                    <!-- Notification Bell -->
                    <!-- TEMP DISABLED: <x-notification-bell /> -->
                </div>
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main class="min-h-screen bg-[#F7F7F7] pt-4">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>
    <x-alert-modal /> {{-- Our confirmation modal --}}
    <x-toast-alert type="success" />
    <x-loading />
    @stack('scripts')
</body>

</html>