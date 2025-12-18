<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Client Request
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header dengan Action Buttons -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Detail Client Request</h1>
                    <p class="mt-1 text-sm text-gray-600">Request ID: #{{ $clientRequest->id }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-4 py-2 text-sm font-medium rounded-full {{ $clientRequest->status_badge_color }}">
                        {{ $clientRequest->status_text }}
                    </span>
                    @can('update', $clientRequest)
                    <a href="{{ route('client-requests.edit', $clientRequest) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Edit
                    </a>
                    @endcan
                    <a href="{{ route('client-requests.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- SLA Alerts -->
        @if($clientRequest->isOverdueForContact())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">SLA Alert: Overdue for Contact</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>This lead was created {{ $clientRequest->created_at->diffForHumans() }} and has not been contacted yet. Please contact immediately.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($clientRequest->isOverdueForClientResponse())
        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">SLA Warning: Waiting for Client</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Waiting for client response for more than 72 hours. Consider following up.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Client Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informasi Client
                    </h2>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $clientRequest->client_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-base text-gray-900">
                                <a href="mailto:{{ $clientRequest->client_email }}" class="text-blue-600 hover:underline">
                                    {{ $clientRequest->client_email }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">No. Telepon</dt>
                            <dd class="mt-1 text-base text-gray-900">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $clientRequest->client_phone) }}" target="_blank" class="text-green-600 hover:underline flex items-center">
                                    {{ $clientRequest->client_phone }}
                                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                </a>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Event Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Detail Event
                    </h2>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipe Event</dt>
                            <dd class="mt-1 text-base text-gray-900 font-medium">{{ $clientRequest->event_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Event</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $clientRequest->event_date->format('d F Y') }}</dd>
                        </div>
                        @if($clientRequest->budget)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Budget</dt>
                            <dd class="mt-1 text-lg font-semibold text-green-600">Rp {{ number_format($clientRequest->budget, 0, ',', '.') }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sumber Request</dt>
                            <dd class="mt-1 text-base text-gray-900 capitalize">{{ str_replace('_', ' ', $clientRequest->request_source) }}</dd>
                        </div>
                    </dl>

                    @if($clientRequest->message)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <dt class="text-sm font-medium text-gray-500 mb-2">Pesan / Keterangan</dt>
                        <dd class="text-base text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $clientRequest->message }}</dd>
                    </div>
                    @endif
                </div>

                </div>

                <!-- Recommendations Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            Recommendations
                        </h2>
                        @if(auth()->user()->hasAnyRole(['SuperUser', 'Owner', 'Admin']))
                        <a href="{{ route('recommendations.create', $clientRequest) }}" class="text-sm px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 font-medium transition">
                            + Create New
                        </a>
                        @endif
                    </div>

                    @if($clientRequest->recommendations && $clientRequest->recommendations->count() > 0)
                        <div class="space-y-3">
                            @foreach($clientRequest->recommendations as $rec)
                            <a href="{{ route('recommendations.show', $rec) }}" class="block border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-sm transition group">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium text-gray-900 group-hover:text-blue-600">{{ $rec->title }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($rec->description, 60) }}</p>
                                        <div class="flex items-center mt-2 space-x-2 text-xs text-gray-400">
                                            <span>{{ $rec->items->count() }} items</span>
                                            <span>•</span>
                                            <span>{{ $rec->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-2 py-1 rounded text-xs font-medium {{ $rec->status_badge_color }}">
                                            {{ ucfirst($rec->status) }}
                                        </span>
                                        <p class="text-sm font-semibold text-gray-900 mt-2">
                                            Rp {{ number_format($rec->total_estimated_budget, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <p class="text-sm text-gray-500">No recommendations created yet.</p>
                            @if(auth()->user()->hasAnyRole(['SuperUser', 'Owner', 'Admin']))
                            <a href="{{ route('recommendations.create', $clientRequest) }}" class="inline-block mt-2 text-sm text-blue-600 font-medium hover:underline">
                                Create the first one
                            </a>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Internal Notes -->
                @if($clientRequest->notes)
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                    <h3 class="text-sm font-semibold text-yellow-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Catatan Internal
                    </h3>
                    <p class="text-sm text-yellow-800">{{ $clientRequest->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status & Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status & Timeline</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status Saat Ini</span>
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $clientRequest->status_badge_color }}">
                                {{ $clientRequest->status_text }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Dibuat</span>
                            <span class="text-gray-900">{{ $clientRequest->created_at->format('d M Y, H:i') }}</span>
                        </div>

                        @if($clientRequest->responded_at)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Direspon</span>
                            <span class="text-gray-900">{{ $clientRequest->responded_at->format('d M Y, H:i') }}</span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Update Terakhir</span>
                            <span class="text-gray-900">{{ $clientRequest->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Assignment -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Assignment</h3>
                    
                    @if($clientRequest->assignee)
                    <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ substr($clientRequest->assignee->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $clientRequest->assignee->name }}</p>
                            <p class="text-xs text-gray-600">{{ $clientRequest->assignee->email }}</p>
                        </div>
                    </div>
                    @else
                    <p class="text-sm text-gray-500 italic">Belum di-assign</p>
                    @endif

                    @can('update', $clientRequest)
                    @if(auth()->user()->hasAnyRole(['SuperUser', 'Owner', 'Admin']))
                    <div class="mt-4 space-y-3 border-t pt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assign to Staff</label>
                            <select id="assign-staff" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">Select Staff</option>
                                @foreach($staffMembers as $staff)
                                    <option value="{{ $staff->id }}" {{ $clientRequest->assigned_to == $staff->id ? 'selected' : '' }}>
                                        {{ $staff->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                            <select id="assign-priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="low" {{ $clientRequest->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $clientRequest->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $clientRequest->priority == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ $clientRequest->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assignment Note (Optional)</label>
                            <textarea id="assign-notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Add instructions for staff..."></textarea>
                        </div>

                        <button onclick="assignStaff({{ $clientRequest->id }})" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                            Update Assignment
                        </button>
                    </div>
                    @endif
                    @endcan
                </div>

                <!-- Vendor Info -->
                @if($clientRequest->vendor)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Vendor Terkait</h3>
                    <div class="space-y-2">
                        <p class="font-medium text-gray-900">{{ $clientRequest->vendor->name }}</p>
                        <p class="text-sm text-gray-600">{{ $clientRequest->vendor->category }}</p>
                        @if($clientRequest->vendor->phone)
                        <p class="text-sm text-gray-600">{{ $clientRequest->vendor->phone }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                @can('update', $clientRequest)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        @if($clientRequest->status == 'pending')
                        <button onclick="updateStatus({{ $clientRequest->id }}, 'on_process')" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Mulai Process
                        </button>
                        @elseif($clientRequest->status == 'on_process')
                        <button onclick="updateStatus({{ $clientRequest->id }}, 'done')" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Tandai Selesai
                        </button>
                        @endif
                    </div>
                </div>
                @endcan

                <!-- Convert to Event -->
                @if(auth()->user()->hasAnyRole(['SuperUser', 'Owner', 'Admin']))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Convert to Event</h3>
                    
                    @if($clientRequest->isConverted())
                        <!-- Already converted -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="ml-3">
                                    <h4 class="text-sm font-semibold text-green-900">Sudah diconvert</h4>
                                    <p class="mt-1 text-sm text-green-700">Request ini sudah diubah menjadi event.</p>
                                    <a href="{{ route('events.show', $clientRequest->event) }}" class="mt-2 inline-flex items-center text-sm font-medium text-green-800 hover:text-green-900">
                                        Lihat Event
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Readiness Checklist -->
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Checklist Kesiapan</h4>
                            @php 
                                $checklist = $clientRequest->getReadinessChecklist(); 
                                $isReady = $clientRequest->isReadyToConvert();
                            @endphp
                            <div class="space-y-2">
                                @foreach($checklist as $key => $item)
                                <div class="flex items-start">
                                    @if($item['completed'])
                                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    @endif
                                    <div>
                                        <p class="text-xs font-medium {{ $item['completed'] ? 'text-gray-900' : 'text-gray-500' }}">
                                            {{ $item['label'] }}
                                        </p>
                                        <p class="text-[10px] text-gray-500">{{ $item['description'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Not converted yet -->
                        @if($isReady)
                            <div class="bg-green-50 border border-green-100 rounded-lg p-3 mb-4">
                                <p class="text-xs text-green-700">
                                    <span class="font-bold">Ready!</span> Semua kriteria terpenuhi.
                                </p>
                            </div>

                             <form action="{{ route('client-requests.convert-to-event', $clientRequest) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Yakin ingin convert menjadi Event? Booking akan ditandai sebagai confirmed.')"
                                   class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg font-semibold hover:from-green-700 hover:to-teal-700 transition shadow-md hover:shadow-lg transform hover:scale-105 duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Confirm & Create Event
                                </button>
                            </form>
                        @elseif($clientRequest->detailed_status === 'ready_to_confirm')
                            <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-3 mb-4">
                                <p class="text-xs text-yellow-700">
                                    <span class="font-bold">Perhatian:</span> Lengkapi data utama (Paket/Vendor, Tanggal, dll) sebelum mengonversi menjadi event.
                                </p>
                            </div>
                            <button disabled class="w-full px-4 py-3 bg-gray-100 text-gray-400 rounded-lg font-semibold cursor-not-allowed flex items-center justify-center group relative">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Data Belum Lengkap
                            </button>
                        @else
                            <p class="text-sm text-gray-600 mb-4 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                Lengkapi data dan ubah status menjadi "Siap Konfirmasi" untuk enable tombol convert.
                            </p>
                        @endif
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
async function updateStatus(requestId, newStatus) {
    if (!confirm('Yakin ingin mengubah status request ini?')) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    try {
        const response = await fetch(`/client-requests/${requestId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.reload();
        } else {
            alert('Gagal mengubah status');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengubah status');
    }
}

async function assignStaff(requestId) {
    const staffId = document.getElementById('assign-staff').value;
    const priority = document.getElementById('assign-priority').value;
    const notes = document.getElementById('assign-notes').value;
    
    if (!staffId) {
        alert('Please select a staff member first');
        return;
    }
    
    if (!confirm('Assign this lead to the selected staff member?')) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    try {
        const response = await fetch(`/client-requests/${requestId}/assign-staff`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                assigned_to: staffId,
                priority: priority,
                notes: notes
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Staff assigned successfully!');
            window.location.reload();
        } else {
            alert('Failed to assign staff: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while assigning staff');
    }
}
</script>
@endpush
</x-app-layout>
