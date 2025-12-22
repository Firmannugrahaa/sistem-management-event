@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-start">
            <div class="flex items-center space-x-4">
                <a href="{{ route('client-requests.show', $recommendation->clientRequest) }}" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $recommendation->title }}</h1>
                    <div class="flex items-center mt-1 space-x-3">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $recommendation->status_badge_color }}">
                            {{ ucfirst($recommendation->status) }}
                        </span>
                        <span class="text-sm text-gray-500">Created by {{ $recommendation->creator->name }} on {{ $recommendation->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex space-x-3">
                @if($recommendation->status === 'draft')
                <form action="{{ route('recommendations.send', $recommendation) }}" method="POST" onsubmit="return confirm('Send this recommendation to client?');">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Send to Client
                    </button>
                </form>
                @endif
                
                @if($recommendation->status === 'sent')
                <button disabled class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-lg font-medium text-sm text-gray-400 cursor-not-allowed">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Waiting Response
                </button>
                @endif

                <form action="{{ route('recommendations.destroy', $recommendation) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this recommendation? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-red-300 rounded-lg font-medium text-sm text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition shadow-sm" title="Delete Recommendation">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                @if($recommendation->description)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Note</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $recommendation->description }}</p>
                </div>
                @endif

                <!-- Items List -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">Recommended Items</h2>
                        <span class="text-sm text-gray-500">{{ $recommendation->items->count() }} items</span>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        @foreach($recommendation->items as $item)
                        <li class="p-6 hover:bg-gray-50 transition relative">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                            {{ $item->category }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wide {{ $item->recommendation_type_badge_color }}">
                                            {{ $item->recommendation_type }}
                                        </span>
                                        @if($item->status !== 'pending')
                                            <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wide {{ $item->status_badge_color }}">
                                                {{ $item->status }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <h3 class="text-base font-bold text-gray-900 mt-2">
                                        {{ $item->vendor_name }}
                                        @if(!$item->vendor_id)
                                            <span class="ml-2 text-xs font-normal text-gray-400 border border-gray-200 rounded px-1">External</span>
                                        @endif
                                    </h3>
                                    
                                    @if($item->service_name)
                                        <p class="text-sm font-medium text-blue-600">{{ $item->service_name }}</p>
                                    @endif

                                    @if($item->notes)
                                    <p class="text-sm text-gray-600 mt-2 bg-gray-50 p-2 rounded border border-gray-100 inline-block">
                                        <span class="font-semibold text-gray-500 text-xs uppercase">Note:</span> {{ $item->notes }}
                                    </p>
                                    @endif

                                    @if($item->status === 'rejected' && $item->rejection_reason)
                                    <div class="mt-3 p-3 bg-red-50 border border-red-100 rounded-md">
                                        <p class="text-xs font-bold text-red-800 uppercase mb-1">Client Rejection Reason:</p>
                                        <p class="text-sm text-red-700">{{ $item->rejection_reason }}</p>
                                    </div>
                                    @endif
                                </div>
                                <div class="text-right ml-4">
                                    <p class="text-lg font-bold text-gray-900">
                                        Rp {{ number_format($item->estimated_price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                        <span class="font-medium text-gray-900">Total Estimated Budget</span>
                        <span class="text-xl font-bold text-blue-600">
                            Rp {{ number_format($recommendation->total_estimated_budget, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Client Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Client Details</h3>
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-lg">
                            {{ substr($recommendation->clientRequest->client_name, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $recommendation->clientRequest->client_name }}</p>
                            <p class="text-xs text-gray-500">{{ $recommendation->clientRequest->client_email }}</p>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Event Type</span>
                            <span class="font-medium text-gray-900">{{ $recommendation->clientRequest->event_type }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Event Date</span>
                            <span class="font-medium text-gray-900">{{ $recommendation->clientRequest->event_date->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Timeline</h3>
                    <div class="space-y-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="h-2 w-2 rounded-full bg-gray-400 mt-2"></div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Created</p>
                                <p class="text-xs text-gray-500">{{ $recommendation->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @if($recommendation->sent_at)
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="h-2 w-2 rounded-full bg-blue-400 mt-2"></div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Sent to Client</p>
                                <p class="text-xs text-gray-500">{{ $recommendation->sent_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        @if($recommendation->responded_at)
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="h-2 w-2 rounded-full {{ $recommendation->status === 'accepted' ? 'bg-green-400' : 'bg-red-400' }} mt-2"></div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Client Responded</p>
                                <p class="text-xs text-gray-500">{{ $recommendation->responded_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
