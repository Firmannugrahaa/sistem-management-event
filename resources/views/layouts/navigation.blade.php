<nav
    x-data="{ open: true }"
    class="fixed top-0 left-0 h-full bg-gradient-to-b from-slate-900 to-slate-800 text-white z-50 transition-all duration-300 ease-in-out"
    :class="open ? 'w-64' : 'w-20'">
    <!-- Primary Navigation Menu -->
    <div class="flex flex-col h-full">
        <!-- Logo Section -->
        <div class="p-6 border-b border-slate-700 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center flex-1">
                <!-- Teks logo disembunyikan jika sidebar ditutup -->
                <div class="flex items-center justify-center font-bold text-sm" x-show="open" x-transition>
                    Sistem Management Event
                </div>
            </a>
            <!-- Toggle Button -->
            <button @click="open = !open" class="p-2 rounded-lg hover:bg-slate-700 transition duration-200">
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
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500">
                    <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-icon lucide-house">
                        <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                        <path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>Dashboard</span>
                </a>

                <!-- My Team (for Owners) -->
                @hasrole('Owner')
                <a href="{{ route('team.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500">
                    <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>My Team</span>
                </a>
                @endhasrole

                <!-- Venue -->
                @hasrole('SuperUser')
                <a href="{{ route('venues.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500">
                    <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-house-icon lucide-map-pin-house">
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
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500">
                    <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-house-icon lucide-map-pin-house">
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
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500">
                    <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days-icon lucide-calendar-days">
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
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500">
                    <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days-icon lucide-calendar-days">
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

                <!-- My Invoices (for regular users) -->
                @unlessrole('SuperUser')
                <a href="{{ route('invoices.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500">
                    <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text-icon lucide-file-text">
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
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500">
                    <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-handshake-icon lucide-handshake">
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
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500">
                    <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-handshake-icon lucide-handshake">
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
                @hasrole('SuperUser')
                <a href="{{ route('vouchers.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700
                {{ request()->routeIs('vouchers.*') ? 'bg-slate-700' : '' }}">

                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 000 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 000-4V7a2 2 0 012-2h14z" />
                    </svg>

                    <span class="text-sm font-medium" x-show="open" x-transition>Voucher</span>
                </a>
                @else
                @can('view_vouchers')
                <a href="{{ route('vouchers.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700
                {{ request()->routeIs('vouchers.*') ? 'bg-slate-700' : '' }}">

                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 000 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 000-4V7a2 2 0 012-2h14z" />
                    </svg>

                    <span class="text-sm font-medium" x-show="open" x-transition>Voucher</span>
                </a>
                @endcan
                @endhasrole

                <!-- Tickets -->
                @hasrole('SuperUser')
                <a href="{{ route('events.index') }}" {{-- Since tickets are event-specific, link to events which contain tickets --}}
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500">
                    <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-icon lucide-ticket">
                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                        <path d="M13 5v2" />
                        <path d="M13 17v2" />
                        <path d="M13 11v2" />
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>Tickets</span>
                </a>
                @else
                @can('view_tickets')
                <a href="{{ route('events.index') }}" {{-- Since tickets are event-specific, link to events which contain tickets --}}
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500">
                    <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-icon lucide-ticket">
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

            <!-- SuperUser Menu - Only visible to SuperUsers -->
            @hasrole('SuperUser')
            <div class="border-t border-slate-700 pt-2 mt-2">
                <a href="{{ route('superuser.dashboard.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500">
                    <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                        <line x1="16" x2="16" y1="2" y2="6"/>
                        <line x1="8" x2="8" y1="2" y2="6"/>
                        <line x1="3" x2="21" y1="10" y2="10"/>
                        <path d="M10 16H8"/>
                        <path d="M16 16H14"/>
                        <path d="M10 12H8"/>
                        <path d="M16 12H14"/>
                    </svg>
                    <span class="text-sm font-medium" x-show="open" x-transition>SuperUser Dashboard</span>
                </a>

                <a href="{{ route('superuser.users.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500 ml-4">
                    <svg class="w-5 h-5 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="text-xs font-medium" x-show="open" x-transition>Manage Users</span>
                </a>

                <a href="{{ route('superuser.roles.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500 ml-4">
                    <svg class="w-5 h-5 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span class="text-xs font-medium" x-show="open" x-transition>Manage Roles</span>
                </a>

                <a href="{{ route('superuser.invoices.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500 ml-4">
                    <svg class="w-5 h-5 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text-icon lucide-file-text">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" x2="8" y1="13" y2="13" />
                        <line x1="16" x2="8" y1="17" y2="17" />
                        <line x1="10" x2="8" y1="9" y2="9" />
                    </svg>
                    <span class="text-xs font-medium" x-show="open" x-transition>Manage Invoices</span>
                </a>

                <a href="{{ route('superuser.permissions.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200 hover:bg-slate-700 active:bg-blue-500 ml-4">
                    <svg class="w-5 h-5 flex-shrink-0" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <span class="text-xs font-medium" x-show="open" x-transition>Permissions Matrix</span>
                </a>
            </div>
            @endhasrole
        </div>

        <!-- Profile Section -->
        <div class="p-4 border-t border-slate-700 space-y-3">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                    <span class="text-sm font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <!-- Sembunyikan teks profil saat sidebar kecil -->
                <div class="flex-1 min-w-0" x-show="open" x-transition>
                    <p class="text-sm font-medium truncate">{{ Auth::user()->name }}
                        @hasrole('SuperUser')
                        <span class="bg-red-500 text-white text-xs font-medium me-2 px-2.5 py-0.5 rounded">SuperUser</span>
                        @endhasrole
                    </p>
                    <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                </div>
            </div>

            <div x-show="open" x-transition>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 w-full px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-slate-700 transition duration-200">
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
                    <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 rounded-lg text-sm text-slate-300 hover:bg-red-500/10 hover:text-red-400 transition duration-200">
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