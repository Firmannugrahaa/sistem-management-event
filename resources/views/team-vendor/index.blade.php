<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Team & Vendor') }}
            </h2>
            <x-breadcrumb :items="[
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Manage Team & Vendor', 'url' => route('team-vendor.index')],
                ['name' => $view === 'team' ? 'Team Members' : 'Vendors']
            ]"/>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Alert for success messages -->
            @if(session('success'))
            <x-inline-alert type="success" :message="session('success')" class="mb-6" />
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Tab Navigation & Actions Row -->
                <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <!-- Modern Pill Tabs -->
                    <nav class="flex p-1 space-x-1 bg-gray-200/50 dark:bg-gray-700/50 rounded-lg" aria-label="Tabs">
                        <a href="{{ route('team-vendor.index', ['view' => 'team', 'layout' => $layout]) }}"
                           class="{{ $view === 'team' ? 'bg-white dark:bg-gray-600 text-[#012A4A] dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }} px-4 py-2 rounded-md font-medium text-sm transition-all duration-200">
                            Team Members
                        </a>
                        <a href="{{ route('team-vendor.index', ['view' => 'vendor', 'layout' => $layout]) }}"
                           class="{{ $view === 'vendor' ? 'bg-white dark:bg-gray-600 text-[#012A4A] dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }} px-4 py-2 rounded-md font-medium text-sm transition-all duration-200">
                            Vendors
                        </a>
                    </nav>

                    <!-- View Switcher & Primary Action -->
                    <div class="flex items-center gap-3">
                        <!-- View Switcher -->
                        <div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                            <a href="{{ route('team-vendor.index', array_merge(request()->query(), ['view' => $view, 'layout' => 'grid'])) }}"
                               class="p-1.5 rounded {{ $layout === 'grid' ? 'bg-white dark:bg-gray-600 text-[#012A4A] dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700' }} transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </a>
                            <a href="{{ route('team-vendor.index', array_merge(request()->query(), ['view' => $view, 'layout' => 'table'])) }}"
                               class="p-1.5 rounded {{ $layout === 'table' ? 'bg-white dark:bg-gray-600 text-[#012A4A] dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700' }} transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path></svg>
                            </a>
                        </div>

                        <!-- Add Button -->
                        @if($view === 'team')
                            <x-primary-button tag="a" :href="route('team.create')" class="justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Add Member
                            </x-primary-button>
                        @else
                            <x-primary-button tag="a" :href="route('team.vendors.create')" class="justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Add Vendor
                            </x-primary-button>
                        @endif
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                    <form method="GET" action="{{ route('team-vendor.index', ['view' => $view, 'layout' => $layout]) }}" class="flex flex-col md:flex-row gap-4">
                        <input type="hidden" name="view" value="{{ $view }}">
                        <input type="hidden" name="layout" value="{{ $layout }}">
                        
                        <div class="flex-1 relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="{{ $view === 'team' ? 'Search by name, email...' : 'Search business, contact...' }}" 
                                class="pl-10 w-full border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-[#012A4A] focus:border-[#012A4A] dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="w-full md:w-48">
                            @if($view === 'team')
                                <select name="role" class="w-full border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-[#012A4A] focus:border-[#012A4A] dark:bg-gray-700 dark:text-white">
                                    <option value="">All Roles</option>
                                    @foreach($allRoles as $role)
                                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <select name="service_type" class="w-full border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-[#012A4A] focus:border-[#012A4A] dark:bg-gray-700 dark:text-white">
                                    <option value="">All Services</option>
                                    @foreach($allServiceTypes as $serviceType)
                                        <option value="{{ $serviceType->id }}" {{ request('service_type') == $serviceType->id ? 'selected' : '' }}>{{ $serviceType->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 bg-[#012A4A] text-white text-sm font-medium rounded-lg hover:bg-[#012A4A]/90 transition">
                                Filter
                            </button>
                            <a href="{{ route('team-vendor.index', ['view' => $view, 'layout' => $layout]) }}" class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-300">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Content Area -->
                <div class="p-6 bg-gray-50 dark:bg-gray-900/50 min-h-[400px]">
                    @if ($view === 'team')
                        @if ($layout === 'grid')
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                @forelse ($teamMembers as $member)
                                <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 overflow-hidden relative">
                                    <!-- Status Badge (Top Right) -->
                                    <div class="absolute top-3 right-3 z-10">
                                        @if($member->status === 'pending')
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">
                                                Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                                Active
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Color Border Top -->
                                    <div class="h-1 w-full bg-[#012A4A] group-hover:bg-[#023E6C] transition-colors"></div>

                                    <div class="p-6 flex flex-col items-center text-center">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4 text-xl font-bold text-gray-500 dark:text-gray-400 border-2 border-white dark:border-gray-600 shadow-sm">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 truncate w-full">{{ $member->name }}</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 truncate w-full">{{ $member->email }}</p>
                                        
                                        <div class="flex flex-wrap gap-1 justify-center">
                                            @foreach($member->roles as $role)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                {{ $role->name }}
                                            </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- Actions Footer -->
                                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700 flex justify-center gap-3">
                                        @if($member->status === 'pending' && auth()->user()->can('user.approve'))
                                            <form action="{{ route('team.approve', $member) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 dark:text-green-400 text-xs font-medium transition" title="Approve">Verify</button>
                                            </form>
                                            <span class="text-gray-300">|</span>
                                            <form action="{{ route('team.reject', $member) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 text-xs font-medium transition" title="Reject">Reject</button>
                                            </form>
                                        @else
                                            <a href="{{ route('team.edit', $member) }}" class="text-gray-500 hover:text-[#012A4A] dark:text-gray-400 dark:hover:text-blue-300 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            @can('user.delete')
                                                <button @click="window.dispatchEvent(new CustomEvent('open-confirmation-data', { detail: { formId: 'delete-form-{{ $member->id }}', title: 'Remove {{ $member->name }}', message: 'Irreversible action.' } }))" class="text-gray-400 hover:text-red-500 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                                <form id="delete-form-{{ $member->id }}" action="{{ route('team.destroy', $member) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                                @empty
                                    <div class="col-span-full py-12 text-center text-gray-500 dark:text-gray-400">
                                        No team members found.
                                    </div>
                                @endforelse
                            </div>
                        @else
                            <!-- Table View -->
                            <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Member</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse($teamMembers as $member)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="w-8 h-8 rounded-full bg-[#012A4A] text-white flex items-center justify-center text-xs font-bold mr-3">
                                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->name }}</div>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $member->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @foreach($member->roles as $role)
                                                        <span class="text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-600">{{ $role->name }}</span>
                                                    @endforeach
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                     @if($member->status === 'pending')
                                                        <span class="text-xs font-medium text-yellow-600 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/20 px-2 py-0.5 rounded">Pending</span>
                                                    @else
                                                        <span class="text-xs font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded">Active</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                    <!-- Simplified actions for table -->
                                                    <a href="{{ route('team.edit', $member) }}" class="text-[#012A4A] hover:underline text-xs font-medium">Edit</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No members.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <div class="mt-4">{{ $teamMembers->links() }}</div>

                    @else
                        <!-- Vendor View -->
                        @if ($layout === 'grid')
                             <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                @forelse ($vendors as $vendor)
                                <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 overflow-hidden relative">
                                    <div class="absolute top-3 right-3 z-10">
                                         @if($vendor->user->status === 'pending')
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">Vendor</span>
                                        @endif
                                    </div>
                                    <div class="h-1 w-full bg-blue-600 group-hover:bg-blue-700 transition-colors"></div>

                                    <div class="p-6 flex flex-col items-center text-center">
                                        <div class="w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center mb-4 text-xl font-bold text-blue-600 dark:text-blue-400 border-2 border-white dark:border-gray-600 shadow-sm">
                                            {{ strtoupper(substr($vendor->user->name ?? $vendor->name, 0, 1)) }}
                                        </div>
                                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 truncate w-full">{{ $vendor->user->name ?? $vendor->name }}</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $vendor->contact_person }}</p>
                                        <p class="text-xs font-medium text-blue-600 dark:text-blue-400">{{ $vendor->serviceType->name ?? 'Service' }}</p>
                                    </div>
                                    
                                     <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700 flex justify-center gap-3">
                                        <a href="{{ route('vendors.edit', $vendor->id) }}" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-300 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        @can('vendor.delete')
                                            <button @click="window.dispatchEvent(new CustomEvent('open-confirmation-data', { detail: { formId: 'delete-vendor-form-{{ $vendor->id }}', title: 'Delete Vendor', message: 'Irreversible.' } }))" class="text-gray-400 hover:text-red-500 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                            <form id="delete-vendor-form-{{ $vendor->id }}" action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                        @endcan
                                     </div>
                                </div>
                                @empty
                                    <div class="col-span-full py-12 text-center text-gray-500">No vendors found.</div>
                                @endforelse
                             </div>
                        @else
                             <!-- Vendor Table -->
                             <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Business</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse($vendors as $vendor)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="px-6 py-4 whitespace-nowrap flex items-center gap-3">
                                                 <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                                                    {{ strtoupper(substr($vendor->user->name ?? $vendor->name, 0, 1)) }}
                                                </div>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $vendor->user->name ?? $vendor->name }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $vendor->contact_person }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $vendor->serviceType->name ?? '-' }}</td>
                                            <td class="px-6 py-4 text-right text-sm">
                                                <a href="{{ route('vendors.edit', $vendor->id) }}" class="text-blue-600 hover:underline text-xs font-medium">Edit</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4" class="px-6 py-4 text-center">No data.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                             </div>
                        @endif
                         <div class="mt-4">{{ $vendors->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>