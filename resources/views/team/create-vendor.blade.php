<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Vendor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Please correct the following errors:
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('team.vendors.store') }}">
                        @csrf

                        <!-- Business Name -->
                        <div>
                            <x-input-label for="business_name" :value="__('Business Name')" />
                            <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name" :value="old('business_name')" required autofocus />
                            <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
                        </div>

                        <!-- Username -->
                        <div class="mt-4">
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email Address')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Service Type -->
                        <div class="mt-4" x-data="serviceTypeSelector()">
                            <x-input-label for="service_type_id" :value="__('Service Type')" />
                            <div class="flex gap-2 mt-1">
                                <select id="service_type_id" name="service_type_id" required 
                                        x-model="selectedServiceType"
                                        class="block flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Select a service type</option>
                                    @foreach($serviceTypes as $serviceType)
                                        <option value="{{ $serviceType->id }}" {{ old('service_type_id') == $serviceType->id ? 'selected' : '' }}>
                                            {{ $serviceType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" 
                                        @click="showAddModal = true"
                                        class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 transition"
                                        title="Add new service type">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('service_type_id')" class="mt-2" />
                            
                            <!-- Add Service Type Modal -->
                            <div x-show="showAddModal" 
                                 x-cloak
                                 class="fixed inset-0 z-50 overflow-y-auto" 
                                 aria-labelledby="modal-title" 
                                 role="dialog" 
                                 aria-modal="true">
                                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="showAddModal" 
                                         x-transition:enter="ease-out duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="ease-in duration-200"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                                         @click="showAddModal = false"></div>
                                    
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                    
                                    <div x-show="showAddModal"
                                         x-transition:enter="ease-out duration-300"
                                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave="ease-in duration-200"
                                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4" id="modal-title">
                                                Add New Service Type
                                            </h3>
                                            <div class="space-y-4">
                                                <div>
                                                    <label for="new_service_type_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name *</label>
                                                    <input type="text" 
                                                           id="new_service_type_name" 
                                                           x-model="newServiceTypeName"
                                                           class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                           placeholder="e.g., Wedding Organizer">
                                                </div>
                                                <div>
                                                    <label for="new_service_type_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (Optional)</label>
                                                    <textarea id="new_service_type_description" 
                                                              x-model="newServiceTypeDescription"
                                                              rows="2"
                                                              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                              placeholder="Brief description..."></textarea>
                                                </div>
                                                <div x-show="errorMessage" class="text-red-600 text-sm" x-text="errorMessage"></div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                                            <button type="button" 
                                                    @click="createServiceType()"
                                                    :disabled="isLoading"
                                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                                                <span x-show="!isLoading">Create</span>
                                                <span x-show="isLoading">Creating...</span>
                                            </button>
                                            <button type="button" 
                                                    @click="showAddModal = false; resetForm()"
                                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            function serviceTypeSelector() {
                                return {
                                    showAddModal: false,
                                    selectedServiceType: '{{ old('service_type_id', '') }}',
                                    newServiceTypeName: '',
                                    newServiceTypeDescription: '',
                                    isLoading: false,
                                    errorMessage: '',
                                    
                                    async createServiceType() {
                                        if (!this.newServiceTypeName.trim()) {
                                            this.errorMessage = 'Please enter a service type name';
                                            return;
                                        }
                                        
                                        this.isLoading = true;
                                        this.errorMessage = '';
                                        
                                        try {
                                            const response = await fetch('{{ route('service-types.quick-store') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Accept': 'application/json'
                                                },
                                                body: JSON.stringify({
                                                    name: this.newServiceTypeName,
                                                    description: this.newServiceTypeDescription
                                                })
                                            });
                                            
                                            const data = await response.json();
                                            
                                            if (data.success) {
                                                // Add new option to select
                                                const select = document.getElementById('service_type_id');
                                                const option = new Option(data.data.name, data.data.id, true, true);
                                                select.add(option);
                                                
                                                // Select the new option
                                                this.selectedServiceType = data.data.id;
                                                
                                                // Close modal and reset form
                                                this.showAddModal = false;
                                                this.resetForm();
                                                
                                                // Show success message
                                                alert(data.message);
                                            } else {
                                                this.errorMessage = data.message || 'Failed to create service type';
                                            }
                                        } catch (error) {
                                            console.error('Error:', error);
                                            this.errorMessage = 'An error occurred. Please try again.';
                                        } finally {
                                            this.isLoading = false;
                                        }
                                    },
                                    
                                    resetForm() {
                                        this.newServiceTypeName = '';
                                        this.newServiceTypeDescription = '';
                                        this.errorMessage = '';
                                    }
                                }
                            }
                        </script>

                        <!-- Contact Person -->
                        <div class="mt-4">
                            <x-input-label for="contact_person" :value="__('Contact Person')" />
                            <x-text-input id="contact_person" class="block mt-1 w-full" type="text" name="contact_person" :value="old('contact_person')" required />
                            <x-input-error :messages="$errors->get('contact_person')" class="mt-2" />
                        </div>

                        <!-- Phone Number -->
                        <div class="mt-4">
                            <x-input-label for="phone_number" :value="__('Phone Number')" />
                            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Address')" />
                            <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address') }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- Generated Temporary Password Info -->
                        <div class="mt-4">
                            <x-input-label :value="__('Temporary Password')" />
                            <div class="block mt-1 w-full bg-gray-100 dark:bg-gray-700 p-2 rounded-md">
                                <span class="text-gray-700 dark:text-gray-300">Temporary password will be: &lt;username&gt;{{ date('Y') }} (e.g., myvendor2025)</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('team-vendor.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                                Cancel
                            </a>

                            <x-primary-button class="ms-4" id="add-vendor-btn">
                                {{ __('Add Vendor') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to show error alerts when there are validation errors -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Only show error toast if the error summary block is present
            // This block is only rendered when $errors->any() is true
            const errorSummary = document.querySelector('.bg-red-50.border-red-200');
            if (errorSummary) {
                // Show error toast notification for validation errors
                document.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        message: 'Please fix the errors in the form below',
                        type: 'success' // Using 'danger' type for error alerts
                    }
                }));
            }
        });
    </script>
</x-app-layout>
