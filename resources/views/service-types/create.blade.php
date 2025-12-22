<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add New Service Type') }}
            </h2>
            <a href="{{ route('service-types.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('service-types.store') }}">
                        @csrf

                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Service Type Name')" />
                            <x-text-input id="name" 
                                          class="block mt-1 w-full" 
                                          type="text" 
                                          name="name" 
                                          :value="old('name')" 
                                          required 
                                          autofocus 
                                          placeholder="e.g., Wedding Organizer, Catering, MUA" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Description (Optional)')" />
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                      placeholder="Brief description of this service type...">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('service-types.index') }}" 
                               class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 underline">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Create Service Type') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Common Examples -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">
                    💡 Common Service Types Examples:
                </h4>
                <div class="flex flex-wrap gap-2">
                    @php
                        $examples = ['Wedding Organizer', 'Catering', 'MUA (Makeup Artist)', 'Photography', 'Videography', 'Decoration', 'MC (Master of Ceremony)', 'Entertainment', 'Sound System', 'Lighting', 'Florist', 'Bridal Boutique', 'Wedding Car', 'Invitation', 'Cake & Dessert'];
                    @endphp
                    @foreach($examples as $example)
                        <span class="inline-block px-2 py-1 text-xs bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded border border-gray-200 dark:border-gray-600">
                            {{ $example }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
