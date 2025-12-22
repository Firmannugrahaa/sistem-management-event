<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $header ?? __('Vendor Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Sidebar Navigation -->
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Sidebar -->
                        <div class="md:w-1/4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold mb-4">Menu Vendor</h3>
                                <ul class="space-y-2">
                                    <li>
                                        <a href="{{ route('vendor.profile') }}" 
                                           class="block py-2 px-4 rounded {{ request()->routeIs('vendor.profile') ? 'bg-primary text-white' : 'hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                            Profil Saya
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('vendor.services.index') }}" 
                                           class="block py-2 px-4 rounded {{ request()->routeIs('vendor.services*') ? 'bg-primary text-white' : 'hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                            Layanan Saya
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('vendor.events.index') }}" 
                                           class="block py-2 px-4 rounded {{ request()->routeIs('vendor.events*') ? 'bg-primary text-white' : 'hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                            Event Saya
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('vendor.reviews.index') }}" 
                                           class="block py-2 px-4 rounded {{ request()->routeIs('vendor.reviews*') ? 'bg-primary text-white' : 'hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                            Ulasan & Rating
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Main Content -->
                        <div class="md:w-3/4">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>