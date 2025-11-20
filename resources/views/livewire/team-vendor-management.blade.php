<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Team & Vendor Management</h1>
            <div class="flex space-x-4">
                <!-- View Mode Toggle -->
                <div class="flex items-center">
                    <button wire:click="toggleViewMode" class="flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        @if($viewMode === 'list')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            <span>List Mode</span>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            <span>Grid Mode</span>
                        @endif
                    </button>
                </div>
                @if($activeTab === 'team')
                    <button wire:click="openCreateUserModal" class="text-white py-2 px-4 rounded-lg transition duration-200" style="background-color: #012A4A;">
                        Add New User
                    </button>
                @elseif($activeTab === 'vendor')
                    <button wire:click="openCreateVendorModal" class="text-white py-2 px-4 rounded-lg transition duration-200" style="background-color: #012A4A;">
                        Add New Vendor
                    </button>
                @endif
            </div>
        </div>

        @if(session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('message') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabs -->
        <div class="mb-6 border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg {{ $activeTab === 'team' ? 'border-blue-600 text-blue-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}"
                            wire:click="switchTab('team')" type="button" role="tab" aria-controls="team" aria-selected="{{ $activeTab === 'team' ? 'true' : 'false' }}">
                        Team Members
                    </button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg {{ $activeTab === 'vendor' ? 'border-blue-600 text-blue-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}"
                            wire:click="switchTab('vendor')" type="button" role="tab" aria-controls="vendor" aria-selected="{{ $activeTab === 'vendor' ? 'true' : 'false' }}">
                        Vendors
                    </button>
                </li>
            </ul>
        </div>

        <!-- Search and Filters -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0 sm:space-x-4">
            <div class="w-full sm:w-1/3">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search..." class="form-input w-full rounded-md shadow-sm">
            </div>
            <div class="flex space-x-4">
                @if($activeTab === 'team')
                    <select wire:model.live="filterRole" class="form-select rounded-md shadow-sm">
                        <option value="">All Roles</option>
                        @foreach($roles as $roleName => $roleLabel)
                            <option value="{{ $roleName }}">{{ $roleLabel }}</option>
                        @endforeach
                    </select>
                @endif
                <select wire:model.live="filterStatus" class="form-select rounded-md shadow-sm">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Content based on active tab -->
        @if($activeTab === 'team')
            @if($users->count() > 0)
                @if($viewMode === 'list')
                    <!-- Team Members Table View -->
                    <div class="bg-white shadow-soft-shadow rounded-2xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @foreach($user->roles as $role)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($user->status == 'pending' || $user->status == null)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                @elseif($user->status == 'approved')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Approved
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Rejected
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $user->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                @if($user->status == 'pending' || $user->status == null)
                                                    <button wire:click="approveUser({{ $user->id }})" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                                    <button wire:click="rejectUser({{ $user->id }})" class="text-red-600 hover:text-red-900 mr-3">Reject</button>
                                                @endif
                                                <button wire:click="openEditUserModal({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                                @if(!$user->hasRole('SuperUser') && $user->id !== auth()->id())
                                                    <button wire:click="confirmDeleteItem('user', {{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')" class="text-red-600 hover:text-red-900">Delete</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <!-- Team Members Grid View -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            <div class="bg-white rounded-2xl shadow-soft-shadow hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-200">
                                <div class="p-6">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center mb-4">
                                            <span class="text-2xl font-bold text-blue-800">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                                        <p class="text-sm text-gray-500 truncate w-full text-center">{{ $user->email }}</p>

                                        @if($user->status == 'pending' || $user->status == null)
                                            <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending Approval
                                            </span>
                                        @elseif($user->status == 'approved')
                                            <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        @else
                                            <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        @endif

                                        @if($user->roles->first())
                                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $user->roles->first()->name }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-6 flex justify-center space-x-2">
                                        @if($user->status == 'pending' || $user->status == null)
                                            <button wire:click="approveUser({{ $user->id }})" class="px-3 py-1 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600 transition">
                                                Approve
                                            </button>
                                            <button wire:click="rejectUser({{ $user->id }})" class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition">
                                                Reject
                                            </button>
                                        @endif
                                        <button wire:click="openEditUserModal({{ $user->id }})" class="px-3 py-1 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        @if(!$user->hasRole('SuperUser') && $user->id !== auth()->id())
                                            <button wire:click="confirmDeleteItem('user', {{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')" class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="bg-white shadow-soft-shadow rounded-2xl overflow-hidden p-12 text-center">
                    <div class="flex justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No Team Members Found</h3>
                    <p class="text-gray-500">There are currently no team members to display. Please add some team members to get started.</p>
                </div>
            @endif
            <div class="mt-6">
                {{ $users->links('pagination::tailwind') }}
            </div>
        @elseif($activeTab === 'vendor')
            @if($vendors->count() > 0)
                @if($viewMode === 'list')
                    <!-- Vendors Table View -->
                    <div class="bg-white shadow-soft-shadow rounded-2xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($vendors as $vendor)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $vendor->company_name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $vendor->user->email ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    {{ $vendor->serviceType->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($vendor->status == 'pending' || $vendor->status == null)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                @elseif($vendor->status == 'approved')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Approved
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Rejected
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $vendor->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                @if($vendor->status == 'pending' || $vendor->status == null)
                                                    <button wire:click="approveVendor({{ $vendor->id }})" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                                    <button wire:click="rejectVendor({{ $vendor->id }})" class="text-red-600 hover:text-red-900 mr-3">Reject</button>
                                                @endif
                                                <button wire:click="openEditVendorModal({{ $vendor->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                                <button wire:click="confirmDeleteItem('vendor', {{ $vendor->id }}, '{{ $vendor->company_name }}', '{{ $vendor->user->email ?? 'N/A' }}')" class="text-red-600 hover:text-red-900">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <!-- Vendors Grid View -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($vendors as $vendor)
                            <div class="bg-white rounded-2xl shadow-soft-shadow hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-200">
                                <div class="p-6">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 rounded-full bg-purple-100 flex items-center justify-center mb-4">
                                            <span class="text-2xl font-bold text-purple-800">
                                                {{ strtoupper(substr($vendor->company_name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $vendor->company_name }}</h3>
                                        <p class="text-sm text-gray-500 truncate w-full text-center">{{ $vendor->user->email ?? 'N/A' }}</p>

                                        @if($vendor->status == 'pending' || $vendor->status == null)
                                            <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending Approval
                                            </span>
                                        @elseif($vendor->status == 'approved')
                                            <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        @else
                                            <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        @endif

                                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $vendor->serviceType->name ?? 'N/A' }}
                                        </span>
                                    </div>

                                    <div class="mt-6 flex justify-center space-x-2">
                                        @if($vendor->status == 'pending' || $vendor->status == null)
                                            <button wire:click="approveVendor({{ $vendor->id }})" class="px-3 py-1 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600 transition">
                                                Approve
                                            </button>
                                            <button wire:click="rejectVendor({{ $vendor->id }})" class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition">
                                                Reject
                                            </button>
                                        @endif
                                        <button wire:click="openEditVendorModal({{ $vendor->id }})" class="px-3 py-1 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDeleteItem('vendor', {{ $vendor->id }}, '{{ $vendor->company_name }}', '{{ $vendor->user->email ?? 'N/A' }}')" class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="bg-white shadow-soft-shadow rounded-2xl overflow-hidden p-12 text-center">
                    <div class="flex justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No Vendors Found</h3>
                    <p class="text-gray-500">There are currently no vendors to display. Please add some vendors to get started.</p>
                </div>
            @endif
            <div class="mt-6">
                {{ $vendors->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>
