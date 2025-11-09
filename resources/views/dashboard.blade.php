<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
                {{ __('Dashboard') }}
            </h2>
            @hasrole('SuperUser')
                <span class="bg-red-500 text-white text-xs font-medium px-2.5 py-0.5 rounded">SuperUser Access</span>
            @endhasrole
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 border border-gray-900 dark:border-gray-700 overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="p-6 text-gray-900 dark:text-white">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>