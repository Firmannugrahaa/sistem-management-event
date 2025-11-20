<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Team & Vendor') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert for success messages -->
            @if(session('success'))
            <x-inline-alert type="success" :message="session('success')" class="mb-6" />
            @endif
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if ($view === 'team')
                <!-- Team Members List -->
                <h3 class="text-lg font-semibold px-6 pt-6 text-gray-900 dark:text-gray-100">Team Members</h3>
                <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <x-primary-button tag="a" :href="route('team.create')">
                            Add Team Member
                        </x-primary-button>
                        <form method="GET" action="{{ route('team-vendor.index') }}">
                            <input type="hidden" name="view" value="team">
                            <div class="flex items-center">
                                <input type="text" name="search" class="form-input rounded-md shadow-sm" placeholder="Search by name..." value="{{ $search ?? '' }}">
                                <button type="submit" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Search</button>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('team-vendor.index', ['view' => 'team', 'sortBy' => 'name', 'sortDirection' => $sortBy === 'name' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                            Name
                                            @if($sortBy === 'name')
                                                <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('team-vendor.index', ['view' => 'team', 'sortBy' => 'email', 'sortDirection' => $sortBy === 'email' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                            Email
                                            @if($sortBy === 'email')
                                                <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('team-vendor.index', ['view' => 'team', 'sortBy' => 'username', 'sortDirection' => $sortBy === 'username' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                            Username
                                            @if($sortBy === 'username')
                                                <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created By</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created At</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Updated At</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($teamMembers as $member)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $member->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $member->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $member->username }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @foreach($member->roles as $role)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $role->name }}
                                        </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $member->owner->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $member->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $member->updated_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('team.edit', $member) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-4">
                                            Edit
                                        </a>
                                        
                                        <button
                                            @click="window.dispatchEvent(new CustomEvent('open-confirmation-data', {
                                                detail: {
                                                    formId: 'delete-form-{{ $member->id }}',
                                                    title: 'Remove Team Member',
                                                    message: 'Are you sure you want to remove {{ $member->name }} from your team? This action cannot be undone.'
                                                }
                                            }))"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                        >
                                            Remove
                                        </button>
                                        <form id="delete-form-{{ $member->id }}" action="{{ route('team.destroy', $member) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        You have not added any team members yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($teamMembers->count() > 0)
                    <div class="mt-4">
                        {{ $teamMembers->appends(['view' => 'team', 'search' => $search, 'sortBy' => $sortBy, 'sortDirection' => $sortDirection])->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if ($view === 'vendor')
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-semibold px-6 pt-6 text-gray-900 dark:text-gray-100">Vendors</h3>
                <div class="p-1 border-t border-gray-200 dark:border-gray-700">

                    <!-- Vendors Accordion Content -->
                    <div>
                        <div class="p-3">
                            <div class="flex justify-between items-center mb-6">
                                <x-primary-button tag="a" :href="route('team.vendors.create')">
                                    Add Vendor
                                </x-primary-button>
                                <form method="GET" action="{{ route('team-vendor.index') }}">
                                    <input type="hidden" name="view" value="vendor">
                                    <div class="flex items-center">
                                        <input type="text" name="search" class="form-input rounded-md shadow-sm" placeholder="Search by name..." value="{{ $search ?? '' }}">
                                        <button type="submit" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Search</button>
                                    </div>
                                </form>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                <a href="{{ route('team-vendor.index', ['view' => 'vendor', 'sortBy' => 'business_name', 'sortDirection' => $sortBy === 'business_name' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                                    Business Name
                                                    @if($sortBy === 'business_name')
                                                        <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                                    @endif
                                                </a>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                <a href="{{ route('team-vendor.index', ['view' => 'vendor', 'sortBy' => 'category', 'sortDirection' => $sortBy === 'category' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                                    Category
                                                    @if($sortBy === 'category')
                                                        <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                                    @endif
                                                </a>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                <a href="{{ route('team-vendor.index', ['view' => 'vendor', 'sortBy' => 'contact_person', 'sortDirection' => $sortBy === 'contact_person' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                                                    Contact Person
                                                    @if($sortBy === 'contact_person')
                                                        <span class="ml-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                                    @endif
                                                </a>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Phone Number</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Service Type</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created At</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Updated At</th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse ($vendors as $vendor)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $vendor->user->name ?? $vendor->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $vendor->category }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $vendor->contact_person }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $vendor->phone_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $vendor->serviceType->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $vendor->created_at->format('Y-m-d H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $vendor->updated_at->format('Y-m-d H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('vendors.edit', $vendor->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-4">
                                                    Edit
                                                </a>
                                                
                                                <button
                                                    @click="window.dispatchEvent(new CustomEvent('open-confirmation-data', {
                                                        detail: {
                                                            formId: 'delete-vendor-form-{{ $vendor->id }}',
                                                            title: 'Delete Vendor',
                                                            message: 'Are you sure you want to delete vendor {{ $vendor->name ?? $vendor->user->name }}? This action cannot be undone.'
                                                        }
                                                    }))"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                >
                                                    Delete
                                                </button>
                                                <form id="delete-vendor-form-{{ $vendor->id }}" action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                You have not added any vendors yet.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($vendors->count() > 0)
                            <div class="mt-4">
                                {{ $vendors->appends(['view' => 'vendor', 'search' => $search, 'sortBy' => $sortBy, 'sortDirection' => $sortDirection])->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>