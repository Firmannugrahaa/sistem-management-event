<nav
    x-data="{ open: false }"
    x-init="() => {
        // Initialize sidebar state based on screen size
        if (window.innerWidth >= 1024) {
            open = true;
        } else {
            open = false;
        }

        $watch('open', value => {
            document.body.dispatchEvent(new CustomEvent('sidebar-toggled', { detail: { expanded: value } }));
        });

        // Handle window resize to adjust sidebar state
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                open = true;
            } else {
                open = false;
            }
        });
    }"
    :class="{
        'w-64': open,
        'w-20': !open && window.innerWidth >= 1024,
        '-translate-x-full': !open && window.innerWidth < 1024,
        'translate-x-0': open && window.innerWidth < 1024,
        'block': open || window.innerWidth >= 1024
    }"
    class="fixed top-0 left-0 h-full bg-[#FFFFFF] text-[#1A1A1A] z-50 transition-all duration-300 ease-in-out lg:block">

    <div class="flex flex-col h-full">
        <!-- Logo Section -->
        <div class="p-6 border-b border-[#E0E0E0] flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center flex-1">
                <div class="flex items-center justify-center font-bold text-sm" x-show="open" x-transition>
                    Sistem Management Event
                </div>
            </a>
            <button @click="open = !open" class="p-2 rounded-lg hover:bg-[#F0F0F0] transition duration-200 text-[#1A1A1A]">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path x-show="!open" x-transition stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="open" x-transition stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 flex flex-col justify-between py-6 px-2">
            <div class="space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('dashboard') ? 'bg-[#012A4A] text-white' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('dashboard') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                        <path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>Dashboard</span>
                </a>

                <!-- Manage Team/Vendor Dropdown (for Owners) -->
                @if(auth()->user()->hasRole('Owner')|| auth()->user()->hasRole('SuperUser') || auth()->user()->hasRole('Admin'))
                <div x-data="{ open: {{ request()->routeIs('team-vendor.*') || request()->routeIs('team.*') || request()->routeIs('vendors.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] w-full text-left {{ request()->routeIs('team-vendor.*') || request()->routeIs('team.*') || request()->routeIs('vendors.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                        <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('team-vendor.*') || request()->routeIs('team.*') || request()->routeIs('vendors.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span class="text-sm font-medium">Manage Team/Vendor</span>
                        @if(isset($pendingApprovalCount) && $pendingApprovalCount > 0)
                        <span class="ml-auto inline-flex items-center justify-center h-6 w-6 text-xs font-bold text-white bg-red-500 rounded-full">{{ $pendingApprovalCount }}</span>
                        @endif
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" x-show="open" x-transition>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="mt-2 space-y-2 pl-4">
                        <a href="{{ route('team-vendor.index', ['view' => 'team']) }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('team-vendor.index') && request('view') == 'team' ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                            <span class="text-sm font-medium" x-show="open" x-transition>Team Members</span>
                        </a>
                        <a href="{{ route('team-vendor.index', ['view' => 'vendor']) }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('team-vendor.index') && request('view') == 'vendor' ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                            <span class="text-sm font-medium" x-show="open" x-transition>Vendors</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Venue -->
                @hasrole('SuperUser')
                <a href="{{ route('venues.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('venues.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('venues.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z" />
                        <path d="M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2" />
                        <path d="M18 22v-3" />
                        <circle cx="10" cy="10" r="3" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>Venue</span>
                </a>
                @else
                @can('view_venues')
                <a href="{{ route('venues.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] text-[#1A1A1A]">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded bg-[#012A4A] text-white" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z" />
                        <path d="M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2" />
                        <path d="M18 22v-3" />
                        <circle cx="10" cy="10" r="3" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>Venue</span>
                </a>
                @endcan
                @endhasrole

                <!-- Event -->
                @hasrole('SuperUser')
                <a href="{{ route('events.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('events.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('events.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 2v4" />
                        <path d="M16 2v4" />
                        <rect width="18" height="18" x="3" y="4" rx="2" />
                        <path d="M3 10h18" />
                        <path d="M8 14h.01" />
                        <path d="M12 14h.01" />
                        <path d="M16 14h.01" />
                        <path d="M8 18h.01" />
                        <path d="M12 18h.01" />
                        <path d="M16 18h.01" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>Event</span>
                </a>
                @else
                @can('view_events')
                <a href="{{ route('events.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] text-[#1A1A1A]">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded bg-[#012A4A] text-white" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 2v4" />
                        <path d="M16 2v4" />
                        <rect width="18" height="18" x="3" y="4" rx="2" />
                        <path d="M3 10h18" />
                        <path d="M8 14h.01" />
                        <path d="M12 14h.01" />
                        <path d="M16 14h.01" />
                        <path d="M8 18h.01" />
                        <path d="M12 18h.01" />
                        <path d="M16 18h.01" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>Event</span>
                </a>
                @endcan
                @endhasrole

                <!-- My Invoices -->
                @unlessrole('SuperUser')
                <a href="{{ route('invoices.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('invoices.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('invoices.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" x2="8" y1="13" y2="13" />
                        <line x1="16" x2="8" y1="17" y2="17" />
                        <line x1="10" x2="8" y1="9" y2="9" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>My Invoices</span>
                </a>
                @endunlessrole

                <!-- Vendors -->
                @hasrole('SuperUser')
                <a href="{{ route('vendors.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('vendors.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('vendors.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m11 17 2 2a1 1 0 1 0 3-3" />
                        <path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4" />
                        <path d="m21 3 1 11h-2" />
                        <path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3" />
                        <path d="M3 4h8" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>Vendors</span>
                </a>
                @else
                @can('view_vendors')
                <a href="{{ route('vendors.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('vendors.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('vendors.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m11 17 2 2a1 1 0 1 0 3-3" />
                        <path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4" />
                        <path d="m21 3 1 11h-2" />
                        <path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3" />
                        <path d="M3 4h8" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>Vendors</span>
                </a>
                @endcan
                @endhasrole

                <!-- Voucher -->
                @hasrole('SuperUser')
                <a href="{{ route('vouchers.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('vouchers.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('vouchers.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 000 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 000-4V7a2 2 0 012-2h14z" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>Voucher</span>
                </a>
                @else
                @can('view_vouchers')
                <a href="{{ route('vouchers.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('vouchers.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('vouchers.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 000 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 000-4V7a2 2 0 012-2h14z" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>Voucher</span>
                </a>
                @endcan
                @endhasrole

                <!-- Tickets -->
                @hasrole('SuperUser')
                <a href="{{ route('events.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('tickets.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('tickets.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                        <path d="M13 5v2" />
                        <path d="M13 17v2" />
                        <path d="M13 11v2" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>Tickets</span>
                </a>
                @else
                @can('view_tickets')
                <a href="{{ route('events.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] text-[#1A1A1A]">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded bg-[#012A4A] text-white" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                        <path d="M13 5v2" />
                        <path d="M13 17v2" />
                        <path d="M13 11v2" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>Tickets</span>
                </a>
                @endcan
                @endhasrole
            </div>

            <!-- SuperUser Menu -->
            @hasrole('SuperUser')
            <div class="border-t border-[#E0E0E0] pt-2 mt-2">
                <a href="{{ route('superuser.dashboard.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('superuser.dashboard.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('superuser.dashboard.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                        <line x1="16" x2="16" y1="2" y2="6" />
                        <line x1="8" x2="8" y1="2" y2="6" />
                        <line x1="3" x2="21" y1="10" y2="10" />
                        <path d="M10 16H8" />
                        <path d="M16 16H14" />
                        <path d="M10 12H8" />
                        <path d="M16 12H14" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>SuperUser Dashboard</span>
                </a>

                <a href="{{ route('superuser.users.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('superuser.users.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }} ml-4">
                    <svg class="w-5 h-5 flex-shrink-0 p-1 rounded {{ request()->routeIs('superuser.users.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="text-xs font-medium" x-show="open" x-transition>Manage Users</span>
                </a>

                <a href="{{ route('superuser.roles.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('superuser.roles.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }} ml-4">
                    <svg class="w-5 h-5 flex-shrink-0 p-1 rounded {{ request()->routeIs('superuser.roles.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="text-xs font-medium" x-show="open" x-transition>Manage Roles</span>
                </a>

                <a href="{{ route('superuser.invoices.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('superuser.invoices.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }} ml-4">
                    <svg class="w-5 h-5 flex-shrink-0 p-1 rounded {{ request()->routeIs('superuser.invoices.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" x2="8" y1="13" y2="13" />
                        <line x1="16" x2="8" y1="17" y2="17" />
                        <line x1="10" x2="8" y1="9" y2="9" />
                    </svg>
                    <span class="text-xs font-medium" x-show="open" x-transition>Manage Invoices</span>
                </a>

                <a href="{{ route('superuser.permissions.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('superuser.permissions.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }} ml-4">
                    <svg class="w-5 h-5 flex-shrink-0 p-1 rounded {{ request()->routeIs('superuser.permissions.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                    </svg>
                    <span class="text-xs font-medium" x-show="open" x-transition>Permissions Matrix</span>
                </a>

                <a href="{{ route('superuser.settings.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('superuser.settings.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }} ml-4">
                    <svg class="w-5 h-5 flex-shrink-0 p-1 rounded {{ request()->routeIs('superuser.settings.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M12 1v6m0 6v6m11-7h-6m-6 0H1" />
                    </svg>
                    <span class="text-xs font-medium" x-show="open" x-transition>Company Settings</span>
                </a>
            </div>
            @endhasrole

            <!-- Settings menu visible to both SuperUsers and Owners -->
            @if(auth()->user()->hasRole('Super User') || auth()->user()->hasRole('Owner'))
            <div class="border-t border-[#E0E0E0] pt-2 mt-2">
                <a href="{{ route('superuser.settings.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('superuser.settings.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('superuser.settings.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M12 1v6m0 6v6m11-7h-6m-6 0H1" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>Company Settings</span>
                </a>
            </div>
            @endif
        </div>

        <!-- Profile Section -->
        <div class="mx-2 bg-[#012A4A] rounded-lg px-3 py-2 flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-[#E0E0E0] flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-semibold text-[#1A1A1A]">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </span>
            </div>

            <div class="flex-1 min-w-0" x-show="open" x-transition>
                <p class="text-sm font-medium truncate text-white flex items-center gap-2">
                    {{ Auth::user()->name }}

                    @hasrole('SuperUser')
                    <span class="bg-red-500 text-white text-xs font-medium px-2.5 py-0.5 rounded">
                        SuperUser
                    </span>
                    @endhasrole
                </p>

                <p class="text-xs text-gray-300 truncate">
                    {{ Auth::user()->email ?? 'user@example.com' }}
                </p>
            </div>
        </div>



        <div x-show="open" x-transition>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-lg text-sm text-[#666666] hover:bg-[#F0F0F0] transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Pengaturan</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 rounded-lg text-sm text-[#666666] hover:bg-red-500/10 hover:text-red-400 transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>
    </div>
</nav>

<!-- Backdrop untuk mobile ketika sidebar terbuka -->
<div x-show="open && window.innerWidth < 1024"
    x-transition:enter="transition-opacity ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="open = false"
    class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>