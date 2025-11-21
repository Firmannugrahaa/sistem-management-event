<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Team & Vendor') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Alert for success messages -->
            @if(session('success'))
            <x-inline-alert type="success" :message="session('success')" class="mb-6" />
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <a href="{{ route('team-vendor.index', ['view' => 'team', 'layout' => $layout]) }}"
                           class="{{ $view === 'team' ? 'border-[#012A4A] text-[#012A4A] dark:text-white dark:border-white' : 'border-transparent text-[#1A1A1A] hover:text-gray-700 hover:border-gray-300 dark:text-gray-300 dark:hover:text-gray-200' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Team Members
                        </a>
                        <a href="{{ route('team-vendor.index', ['view' => 'vendor', 'layout' => $layout]) }}"
                           class="{{ $view === 'vendor' ? 'border-[#012A4A] text-[#012A4A] dark:text-white dark:border-white' : 'border-transparent text-[#1A1A1A] hover:text-gray-700 hover:border-gray-300 dark:text-gray-300 dark:hover:text-gray-200' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Vendors
                        </a>
                    </nav>
                </div>

                <!-- Team Members Section -->
                @if ($view === 'team')
                <div class="p-6">
                    <!-- Header & View Switcher -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                            Team Members
                        </h3>
                        <div class="flex items-center gap-4">
                            <!-- View Switcher -->
                            <div class="flex items-center gap-2">
                                <a href="{{ route('team-vendor.index', array_merge(request()->query(), ['view' => 'team', 'layout' => 'grid'])) }}"
                                   class="p-2 rounded-md {{ $layout === 'grid' ? 'bg-[#012A4A] text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-300' }} transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                </a>
                                <a href="{{ route('team-vendor.index', array_merge(request()->query(), ['view' => 'team', 'layout' => 'table'])) }}"
                                   class="p-2 rounded-md {{ $layout === 'table' ? 'bg-[#012A4A] text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-300' }} transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path></svg>
                                </a>
                            </div>
                            <x-primary-button tag="a" :href="route('team.create')" class="justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Add Team Member
                            </x-primary-button>
                        </div>
                    </div>

                    <!-- Search and Filter Form -->
                    <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <form method="GET" action="{{ route('team-vendor.index', ['view' => 'team', 'layout' => $layout]) }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <input type="hidden" name="view" value="team">
                            <input type="hidden" name="layout" value="{{ $layout }}">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name, email, username..." class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-[#012A4A] focus:border-[#012A4A] dark:bg-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                                <select name="role" id="role" class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-[#012A4A] focus:border-[#012A4A] dark:bg-gray-600 dark:text-white">
                                    <option value="">All Roles</option>
                                    @foreach($allRoles as $role)
                                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex space-x-2">
                                <button type="submit" class="w-full flex justify-center items-center px-4 py-2 bg-[#012A4A] text-white rounded-lg hover:bg-[#012A4A]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#012A4A] transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    Filter
                                </button>
                                <a href="{{ route('team-vendor.index', ['view' => 'team', 'layout' => $layout]) }}" class="w-full flex justify-center items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    @if ($layout === 'grid')
                        <!-- Team Members Grid View -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @forelse ($teamMembers as $member)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 ease-in-out overflow-hidden flex flex-col">
                                <div class="p-6 flex-grow">
                                    <div class="flex flex-col items-center text-center">
                                        <div class="w-24 h-24 rounded-full bg-gray-200 dark:bg-gray-700 mb-4 flex items-center justify-center">
                                            <span class="text-3xl font-bold text-gray-500 dark:text-gray-400">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                                        </div>
                                                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $member->name }}</h3>
                                                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $member->email }}</p>
                                                                            @if($member->status === 'pending')
                                                                                <span class="mt-2 inline-block bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-yellow-900 dark:text-yellow-300">
                                                                                    Pending Approval
                                                                                </span>
                                                                            @endif
                                                                            <div class="mt-3 space-x-1">
                                                                                @foreach($member->roles as $role)
                                                                                <span class="inline-block bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-green-900 dark:text-green-300">
                                                                                    {{ $role->name }}
                                                                                </span>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-center items-center space-x-3">
                                                                        @if($member->status === 'pending' && auth()->user()->can('user.approve'))
                                                                            <form action="{{ route('team.approve', $member) }}" method="POST">
                                                                                @csrf
                                                                                <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-200" title="Approve">
                                                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                                </button>
                                                                            </form>
                                                                            <form action="{{ route('team.reject', $member) }}" method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200" title="Reject">
                                                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                                </button>
                                                                            </form>
                                                                        @else
                                                                            <a href="{{ route('team.edit', $member) }}" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors duration-200" title="Edit">
                                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                                            </a>
                                                                            @can('user.delete')
                                                                            <button @click="window.dispatchEvent(new CustomEvent('open-confirmation-data', { detail: { formId: 'delete-form-{{ $member->id }}', title: 'Remove Team Member', message: 'Are you sure you want to remove {{ $member->name }} from your team? This action cannot be undone.' } }))" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors duration-200" title="Delete">
                                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                            </button>
                                                                            <form id="delete-form-{{ $member->id }}" action="{{ route('team.destroy', $member) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                                                            @endcan
                                                                        @endif
                                                                    </div>
                                                                </div>                            @empty
                            <div class="sm:col-span-2 lg:col-span-3 xl:col-span-4 text-center py-16">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">No Team Members Found</h3>
                                    <p class="text-gray-500 dark:text-gray-400">Get started by creating a new team member.</p>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    @else
                        <!-- Team Members Table View -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($teamMembers as $member)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-[#012A4A] rounded-full flex items-center justify-center text-white font-medium">
                                                    {{ substr($member->name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $member->name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $member->username }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $member->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @foreach($member->roles as $role)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                {{ $role->name }}
                                            </span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if($member->status === 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                    Pending
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                    Approved
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($member->status === 'pending' && auth()->user()->can('user.approve'))
                                                <form action="{{ route('team.approve', $member) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-200" title="Approve">Approve</button>
                                                </form>
                                                <form action="{{ route('team.reject', $member) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200" title="Reject">Reject</button>
                                                </form>
                                            @else
                                                <a href="{{ route('team.edit', $member) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3 transition-colors duration-200">Edit</a>
                                                @can('user.delete')
                                                <button @click="window.dispatchEvent(new CustomEvent('open-confirmation-data', { detail: { formId: 'delete-form-{{ $member->id }}', title: 'Remove Team Member', message: 'Are you sure you want to remove {{ $member->name }} from your team?' } }))" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200">Delete</button>
                                                <form id="delete-form-{{ $member->id }}" action="{{ route('team.destroy', $member) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">No Team Members Found</h3>
                                            <p class="text-gray-500 dark:text-gray-400">Get started by creating a new team member.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($teamMembers->count() > 0) <div class="mt-6">{{ $teamMembers->links() }}</div> @endif
                </div>
                @endif

                <!-- Vendors Section -->
                @if ($view === 'vendor')
                <div class="p-6">
                    <!-- Header & View Switcher -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                            Vendors
                        </h3>
                        <div class="flex items-center gap-4">
                            <!-- View Switcher -->
                            <div class="flex items-center gap-2">
                                <a href="{{ route('team-vendor.index', array_merge(request()->query(), ['view' => 'vendor', 'layout' => 'grid'])) }}"
                                   class="p-2 rounded-md {{ $layout === 'grid' ? 'bg-[#012A4A] text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-300' }} transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                </a>
                                <a href="{{ route('team-vendor.index', array_merge(request()->query(), ['view' => 'vendor', 'layout' => 'table'])) }}"
                                   class="p-2 rounded-md {{ $layout === 'table' ? 'bg-[#012A4A] text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-300' }} transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path></svg>
                                </a>
                            </div>
                            <x-primary-button tag="a" :href="route('team.vendors.create')" class="justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Add Vendor
                            </x-primary-button>
                        </div>
                    </div>

                    <!-- Search and Filter Form -->
                    <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <form method="GET" action="{{ route('team-vendor.index', ['view' => 'vendor', 'layout' => $layout]) }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                            <input type="hidden" name="view" value="vendor">
                            <input type="hidden" name="layout" value="{{ $layout }}">
                            <div class="lg:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Business name, category, contact..." class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-[#012A4A] focus:border-[#012A4A] dark:bg-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label for="service_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Service Type</label>
                                <select name="service_type" id="service_type" class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-[#012A4A] focus:border-[#012A4A] dark:bg-gray-600 dark:text-white">
                                    <option value="">All Service Types</option>
                                    @foreach($allServiceTypes as $serviceType)
                                        <option value="{{ $serviceType->id }}" {{ request('service_type') == $serviceType->id ? 'selected' : '' }}>{{ $serviceType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex space-x-2">
                                <button type="submit" class="w-full flex justify-center items-center px-4 py-2 bg-[#012A4A] text-white rounded-lg hover:bg-[#012A4A]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#012A4A] transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    Filter
                                </button>
                                <a href="{{ route('team-vendor.index', ['view' => 'vendor', 'layout' => $layout]) }}" class="w-full flex justify-center items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    @if ($layout === 'grid')
                        <!-- Vendors Grid View -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @forelse ($vendors as $vendor)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 ease-in-out overflow-hidden flex flex-col">
                                <div class="p-6 flex-grow">
                                                                    <div class="flex flex-col items-center text-center">
                                                                        <div class="w-24 h-24 rounded-full bg-gray-200 dark:bg-gray-700 mb-4 flex items-center justify-center">
                                                                            <span class="text-3xl font-bold text-gray-500 dark:text-gray-400">{{ strtoupper(substr($vendor->user->name ?? $vendor->name, 0, 1)) }}</span>
                                                                        </div>
                                                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $vendor->user->name ?? $vendor->name }}</h3>
                                                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $vendor->contact_person }}</p>
                                                                        @if($vendor->user->status === 'pending')
                                                                            <span class="mt-2 inline-block bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-yellow-900 dark:text-yellow-300">
                                                                                Pending Approval
                                                                            </span>
                                                                        @endif
                                                                        <div class="mt-3">
                                                                            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-1 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                                                                {{ $vendor->serviceType->name ?? 'N/A' }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-center items-center space-x-3">
                                                                    @if($vendor->user->status === 'pending' && auth()->user()->can('vendor.approve'))
                                                                        <form action="{{ route('vendor.approve', $vendor->user) }}" method="POST">
                                                                            @csrf
                                                                            <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-200" title="Approve">
                                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                            </button>
                                                                        </form>
                                                                        <form action="{{ route('vendor.reject', $vendor->user) }}" method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200" title="Reject">
                                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                            </button>
                                                                        </form>
                                                                    @else
                                                                        <a href="{{ route('vendors.edit', $vendor->id) }}" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors duration-200" title="Edit">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                                        </a>
                                                                        @can('vendor.delete')
                                                                        <button @click="window.dispatchEvent(new CustomEvent('open-confirmation-data', { detail: { formId: 'delete-vendor-form-{{ $vendor->id }}', title: 'Delete Vendor', message: 'Are you sure you want to delete vendor {{ $vendor->name ?? $vendor->user->name }}? This action cannot be undone.' } }))" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors duration-200" title="Delete">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                        </button>
                                                                        <form id="delete-vendor-form-{{ $vendor->id }}" action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                                                        @endcan
                                                                    @endif
                                                                </div>
                                                            </div>                            @empty
                            <div class="sm:col-span-2 lg:col-span-3 xl:col-span-4 text-center py-16">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">No Vendors Found</h3>
                                    <p class="text-gray-500 dark:text-gray-400">Get started by creating a new vendor.</p>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    @else
                        <!-- Vendors Table View -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Business Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contact Person</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Service Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($vendors as $vendor)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-[#012A4A] rounded-full flex items-center justify-center text-white font-medium">
                                                    {{ substr($vendor->user->name ?? $vendor->name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $vendor->user->name ?? $vendor->name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $vendor->category }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $vendor->contact_person }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $vendor->serviceType->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if($vendor->user->status === 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                    Pending
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                    Approved
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($vendor->user->status === 'pending' && auth()->user()->can('vendor.approve'))
                                                <form action="{{ route('vendor.approve', $vendor->user) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition-colors duration-200" title="Approve">Approve</button>
                                                </form>
                                                <form action="{{ route('vendor.reject', $vendor->user) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200" title="Reject">Reject</button>
                                                </form>
                                            @else
                                                <a href="{{ route('vendors.edit', $vendor->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3 transition-colors duration-200">Edit</a>
                                                @can('vendor.delete')
                                                <button @click="window.dispatchEvent(new CustomEvent('open-confirmation-data', { detail: { formId: 'delete-vendor-form-{{ $vendor->id }}', title: 'Delete Vendor', message: 'Are you sure you want to delete vendor {{ $vendor->name ?? $vendor->user->name }}?' } }))" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200">Delete</button>
                                                <form id="delete-vendor-form-{{ $vendor->id }}" action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">No Vendors Found</h3>
                                            <p class="text-gray-500 dark:text-gray-400">Get started by creating a new vendor.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($vendors->count() > 0) <div class="mt-6">{{ $vendors->links() }}</div> @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>