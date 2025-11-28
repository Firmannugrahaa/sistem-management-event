<nav
    x-data="{ sidebarOpen: false }"
    x-init="() => {
        // Initialize sidebar state based on screen size
        if (window.innerWidth >= 1024) {
            sidebarOpen = true;
        } else {
            sidebarOpen = false;
        }

        $watch('sidebarOpen', value => {
            document.body.dispatchEvent(new CustomEvent('sidebar-toggled', { detail: { expanded: value } }));
        });

        // Handle window resize to adjust sidebar state
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebarOpen = true;
            } else {
                sidebarOpen = false;
            }
        });
    }"
    :class="{
        'w-64': sidebarOpen,
        'w-20': !sidebarOpen && window.innerWidth >= 1024,
        '-translate-x-full': !sidebarOpen && window.innerWidth < 1024,
        'translate-x-0': sidebarOpen && window.innerWidth < 1024,
        'block': sidebarOpen || window.innerWidth >= 1024
    }"
    class="fixed top-0 left-0 h-full bg-[#FFFFFF] text-[#1A1A1A] z-50 lg:block transition-all duration-300">

    <div class="flex flex-col h-full">
        {{-- Header Section --}}
        <div class="p-6 border-b border-[#E0E0E0] flex items-center justify-between">
            {{-- Logo/Title - Clickable on Desktop --}}
            <div 
                @click="if (window.innerWidth >= 1024) { sidebarOpen = !sidebarOpen }"
                class="flex items-center justify-center flex-1 lg:cursor-pointer lg:hover:opacity-80 transition-opacity"
            >
                <div class="flex items-center justify-center font-bold text-sm" x-show="sidebarOpen" x-transition>
                    Sistem Management Event
                </div>
            </div>
            
            {{-- Toggle Icons --}}
            <div class="flex items-center">
                {{-- Desktop: Panel Icon (Chevron) - Only visible on lg screens --}}
                <div 
                    @click="sidebarOpen = !sidebarOpen"
                    class="hidden lg:block cursor-pointer hover:bg-[#F0F0F0] p-2 rounded-lg transition-colors"
                >
                    {{-- Panel Right Icon (when collapsed) --}}
                    <svg x-show="!sidebarOpen" x-cloak class="h-6 w-6 text-[#1A1A1A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    {{-- Panel Left Icon (when expanded) --}}
                    <svg x-show="sidebarOpen" x-cloak class="h-6 w-6 text-[#1A1A1A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                
                {{-- Mobile: Hamburger/Close Icon - Only visible below lg --}}
                <button 
                    @click="sidebarOpen = !sidebarOpen" 
                    class="lg:hidden p-2 rounded-lg hover:bg-[#F0F0F0] transition-colors"
                >
                    {{-- Hamburger (when closed) --}}
                    <svg x-show="!sidebarOpen" x-cloak class="h-6 w-6 text-[#1A1A1A]" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    {{-- Close X (when open) --}}
                    <svg x-show="sidebarOpen" x-cloak class="h-6 w-6 text-[#1A1A1A]" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Navigation Links --}}
        <div class="flex-1 flex flex-col justify-between py-6 px-3 overflow-y-auto">
            <div class="space-y-2">
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('dashboard') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('dashboard') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                        <path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Dashboard</span>
                </a>

                {{-- Client Portal (For Clients/Users) --}}
                @if(auth()->user()->hasRole(['Client', 'User']))
                <a href="{{ route('client.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('client.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('client.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>My Requests</span>
                </a>
                @endif

                {{-- Manage Team/Vendor Dropdown --}}
                @if(auth()->user()->hasRole('Owner') || auth()->user()->hasRole('SuperUser') || auth()->user()->hasRole('Admin'))
                <div x-data="{ dropdownOpen: {{ request()->routeIs('team-vendor.*') || request()->routeIs('team.*') ? 'true' : 'false' }} }">
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] w-full text-left {{ request()->routeIs('team-vendor.*') || request()->routeIs('team.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                        <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('team-vendor.*') || request()->routeIs('team.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span class="text-sm font-medium whitespace-nowrap flex-1" x-show="sidebarOpen" x-transition>Manage Team/Vendor</span>
                        @if(isset($pendingApprovalCount) && $pendingApprovalCount > 0)
                        <span class="inline-flex items-center justify-center h-6 w-6 text-xs font-bold text-white bg-red-500 rounded-full" x-show="sidebarOpen">{{ $pendingApprovalCount }}</span>
                        @endif
                        <svg :class="{'rotate-180': dropdownOpen}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="sidebarOpen" x-transition>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="dropdownOpen" x-transition class="mt-1 space-y-1 pl-4">
                        <a href="{{ route('team-vendor.index', ['view' => 'team']) }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('team-vendor.index') && request('view') == 'team' ? 'bg-[#E8F5E9] text-[#27AE60]' : 'text-[#666666]' }}">
                            <span class="text-sm whitespace-nowrap" x-show="sidebarOpen" x-transition>Team Members</span>
                        </a>
                        <a href="{{ route('team-vendor.index', ['view' => 'vendor']) }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('team-vendor.index') && request('view') == 'vendor' ? 'bg-[#E8F5E9] text-[#27AE60]' : 'text-[#666666]' }}">
                            <span class="text-sm whitespace-nowrap" x-show="sidebarOpen" x-transition>Vendors</span>
                        </a>
                    </div>
                </div>
                @endif

                {{-- Client Requests / Leads --}}
                @if(auth()->user()->hasAnyRole(['SuperUser', 'Owner', 'Admin', 'Staff', 'Vendor']))
                <a href="{{ route('client-requests.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('client-requests.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('client-requests.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.5 12H16l-2 3h-4l-2-3H2.5"></path>
                        <path d="M5.5 5.1L2 12v6a2 2 0 002 2h16a2 2 0 002-2v-6l-3.5-6.9A2 2 0 0016.8 4H7.2a2 2 0 00-1.7 1.1z"></path>
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Leads / Inbox</span>
                </a>
                @endif

                {{-- Trash Management --}}
                @if(auth()->user()->hasRole('SuperUser'))
                <a href="{{ route('admin.trash.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('admin.trash.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('admin.trash.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Trash Management</span>
                </a>
                @endif

                {{-- Portfolio / Galeri --}}
                @if(auth()->user()->hasRole('Owner') || auth()->user()->hasRole('SuperUser') || auth()->user()->hasRole('Admin'))
                <a href="{{ route('vendor.portfolios.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('vendor.portfolios.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('vendor.portfolios.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Portfolio / Galeri</span>
                </a>

                {{-- Layanan Dropdown for Owner/Admin --}}
                <div x-data="{ servicesOpen: {{ request()->routeIs('company.products.*') || request()->routeIs('company.packages.*') || request()->routeIs('event-packages.*') ? 'true' : 'false' }} }">
                    <button @click="servicesOpen = !servicesOpen"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] w-full text-left {{ request()->routeIs('company.products.*') || request()->routeIs('company.packages.*') || request()->routeIs('event-packages.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                        <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('company.products.*') || request()->routeIs('company.packages.*') || request()->routeIs('event-packages.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span class="text-sm font-medium whitespace-nowrap flex-1" x-show="sidebarOpen" x-transition>Layanan (Services)</span>
                        <svg :class="{'rotate-180': servicesOpen}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="sidebarOpen" x-transition>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div x-show="servicesOpen" x-transition class="mt-1 space-y-1 pl-4">
                        <a href="{{ route('company.products.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('company.products.*') ? 'bg-[#E8F5E9] text-[#27AE60]' : 'text-[#666666]' }}">
                            <span class="text-sm whitespace-nowrap" x-show="sidebarOpen" x-transition>Layanan Individual</span>
                        </a>
                        <a href="{{ route('company.packages.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('company.packages.*') ? 'bg-[#E8F5E9] text-[#27AE60]' : 'text-[#666666]' }}">
                            <span class="text-sm whitespace-nowrap" x-show="sidebarOpen" x-transition>Paket Layanan</span>
                        </a>
                        @if(auth()->user()->hasRole(['Owner', 'Admin']))
                        <a href="{{ route('event-packages.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('event-packages.*') ? 'bg-[#E8F5E9] text-[#27AE60]' : 'text-[#666666]' }}">
                            <span class="text-sm whitespace-nowrap" x-show="sidebarOpen" x-transition>Event Packages</span>
                        </a>
                        @endif
                    </div>
                </div>
             @endif

                {{-- Venue --}}
                @hasrole('SuperUser')
                <a href="{{ route('venues.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('venues.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('venues.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z" />
                        <path d="M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2" />
                        <path d="M18 22v-3" />
                        <circle cx="10" cy="10" r="3" />
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Venue</span>
                </a>
                @else
                @can('view_venues')
                <a href="{{ route('venues.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] text-[#1A1A1A]">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded bg-[#012A4A] text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z" />
                        <path d="M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2" />
                        <path d="M18 22v-3" />
                        <circle cx="10" cy="10" r="3" />
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Venue</span>
                </a>
                @endcan
                @endhasrole

                {{-- Event --}}
                @hasrole('SuperUser')
                <a href="{{ route('events.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('events.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('events.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Event</span>
                </a>
                @else
                @can('view_events')
                <a href="{{ route('events.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] text-[#1A1A1A]">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded bg-[#012A4A] text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Event</span>
                </a>
                @endcan
                @endhasrole

                {{-- Business Profile (for Vendors) --}}
                @hasrole('Vendor')
                <a href="{{ route('vendor.business-profile.edit') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('vendor.business-profile.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('vendor.business-profile.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Business Profile</span>
                </a>

                <a href="{{ route('vendor.portfolios.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('vendor.portfolios.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('vendor.portfolios.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Portfolio / Galeri</span>
                </a>

                {{-- Layanan Dropdown for Vendor --}}
                <div x-data="{ servicesOpen: {{ request()->routeIs('vendor.products.*') || request()->routeIs('vendor.packages.*') ? 'true' : 'false' }} }">
                    <button @click="servicesOpen = !servicesOpen"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] w-full text-left {{ request()->routeIs('vendor.products.*') || request()->routeIs('vendor.packages.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                        <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('vendor.products.*') || request()->routeIs('vendor.packages.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span class="text-sm font-medium whitespace-nowrap flex-1" x-show="sidebarOpen" x-transition>Layanan (Services)</span>
                        <svg :class="{'rotate-180': servicesOpen}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="sidebarOpen" x-transition>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div x-show="servicesOpen" x-transition class="mt-1 space-y-1 pl-4">
                        <a href="{{ route('vendor.products.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('vendor.products.*') && !request()->routeIs('vendor.packages.*') ? 'bg-[#E8F5E9] text-[#27AE60]' : 'text-[#666666]' }}">
                            <span class="text-sm whitespace-nowrap" x-show="sidebarOpen" x-transition>Layanan Individual</span>
                        </a>
                        <a href="{{ route('vendor.packages.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('vendor.packages.*') ? 'bg-[#E8F5E9] text-[#27AE60]' : 'text-[#666666]' }}">
                            <span class="text-sm whitespace-nowrap" x-show="sidebarOpen" x-transition>Paket Layanan</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('vendor.catalog.items.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('vendor.catalog.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('vendor.catalog.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Katalog / Inventaris</span>
                </a>
                @endhasrole

                {{-- My Invoices --}}
                @unlessrole('SuperUser')
                <a href="{{ route('invoices.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('invoices.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('invoices.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" x2="8" y1="13" y2="13" />
                        <line x1="16" x2="8" y1="17" y2="17" />
                        <line x1="10" x2="8" y1="9" y2="9" />
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>My Invoices</span>
                </a>
                @endunlessrole

                {{-- Voucher --}}
                @hasrole('SuperUser')
                <a href="{{ route('vouchers.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('vouchers.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('vouchers.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 000 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 000-4V7a2 2 0 012-2h14z" />
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Voucher</span>
                </a>
                @else
                @can('view_vouchers')
                <a href="{{ route('vouchers.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('vouchers.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('vouchers.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 000 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 000-4V7a2 2 0 012-2h14z" />
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Voucher</span>
                </a>
                @endcan
                @endhasrole

                {{-- Tickets --}}
                @hasrole('SuperUser')
                <a href="{{ route('events.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('tickets.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('tickets.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                        <path d="M13 5v2" />
                        <path d="M13 17v2" />
                        <path d="M13 11v2" />
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Tickets</span>
                </a>
                @else
                @can('view_tickets')
                <a href="{{ route('events.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] text-[#1A1A1A]">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded bg-[#012A4A] text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                        <path d="M13 5v2" />
                        <path d="M13 17v2" />
                        <path d="M13 11v2" />
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Tickets</span>
                </a>
                @endcan
                @endhasrole
            </div>

            {{-- SuperUser Menu --}}
            @hasrole('SuperUser')
            <div class="border-t border-[#E0E0E0] pt-4 mt-4 space-y-2">
                <a href="{{ route('superuser.dashboard.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('superuser.dashboard.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('superuser.dashboard.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                        <line x1="16" x2="16" y1="2" y2="6" />
                        <line x1="8" x2="8" y1="2" y2="6" />
                        <line x1="3" x2="21" y1="10" y2="10" />
                        <path d="M10 16H8" />
                        <path d="M16 16H14" />
                        <path d="M10 12H8" />
                        <path d="M16 12H14" />
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>SuperUser Dashboard</span>
                </a>

                <a href="{{ route('superuser.users.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('superuser.users.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }} ml-4">
                    <svg class="w-5 h-5 flex-shrink-0 p-1 rounded {{ request()->routeIs('superuser.users.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="text-xs font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Manage Users</span>
                </a>

                <a href="{{ route('superuser.roles.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('superuser.roles.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }} ml-4">
                    <svg class="w-5 h-5 flex-shrink-0 p-1 rounded {{ request()->routeIs('superuser.roles.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="text-xs font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Manage Roles</span>
                </a>

                <a href="{{ route('superuser.invoices.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('superuser.invoices.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }} ml-4">
                    <svg class="w-5 h-5 flex-shrink-0 p-1 rounded {{ request()->routeIs('superuser.invoices.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" x2="8" y1="13" y2="13" />
                        <line x1="16" x2="8" y1="17" y2="17" />
                        <line x1="10" x2="8" y1="9" y2="9" />
                    </svg>
                    <span class="text-xs font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Manage Invoices</span>
                </a>

                <a href="{{ route('superuser.permissions.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('superuser.permissions.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }} ml-4">
                    <svg class="w-5 h-5 flex-shrink-0 p-1 rounded {{ request()->routeIs('superuser.permissions.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                    </svg>
                    <span class="text-xs font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Permissions Matrix</span>
                </a>

                <a href="{{ route('superuser.settings.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('superuser.settings.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }} ml-4">
                    <svg class="w-5 h-5 flex-shrink-0 p-1 rounded {{ request()->routeIs('superuser.settings.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M12 1v6m0 6v6m11-7h-6m-6 0H1" />
                    </svg>
                    <span class="text-xs font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Company Settings</span>
                </a>
            </div>
            @endhasrole

            {{-- Settings for Owner --}}
            @if(auth()->user()->hasRole('Super User') || auth()->user()->hasRole('Owner'))
            <div class="border-t border-[#E0E0E0] pt-4 mt-4 space-y-2">
                <a href="{{ route('superuser.settings.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-[#F0F0F0] {{ request()->routeIs('superuser.settings.*') ? 'bg-[#012A4A] text-white' : 'text-[#1A1A1A]' }}">
                    <svg class="w-6 h-6 flex-shrink-0 p-1 rounded {{ request()->routeIs('superuser.settings.*') ? 'bg-[#c1dfeb] text-[#012A4A]' : 'bg-[#012A4A] text-white' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M12 1v6m0 6v6m11-7h-6m-6 0H1" />
                    </svg>
                    <span class="text-sm font-medium whitespace-nowrap" x-show="sidebarOpen" x-transition>Company Settings</span>
                </a>
            </div>
            @endif
        </div>

        {{-- Profile Section --}}
        <div class="border-t border-[#E0E0E0] p-4">
            <div class="bg-[#012A4A] rounded-lg px-3 py-3 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#E0E0E0] flex items-center justify-center flex-shrink-0">
                    <span class="text-sm font-semibold text-[#1A1A1A]">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </span>
                </div>

                <div class="flex-1 min-w-0" x-show="sidebarOpen" x-transition>
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

            <div x-show="sidebarOpen" x-transition class="mt-3 space-y-1">
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

{{-- Backdrop untuk mobile ketika sidebar terbuka --}}
<div x-show="sidebarOpen && window.innerWidth < 1024"
    x-transition:enter="transition-opacity ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="sidebarOpen = false"
    class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>