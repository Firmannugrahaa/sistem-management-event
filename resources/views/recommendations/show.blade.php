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
                        <li class="p-6 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-1">
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 mr-2">
                                            {{ $item->category }}
                                        </span>
                                        <h3 class="text-base font-semibold text-gray-900">
                                            {{ $item->vendor_name }}
                                        </h3>
                                        @if(!$item->vendor_id)
                                        <span class="ml-2 text-xs text-gray-400 border border-gray-200 rounded px-1">External</span>
                                        @endif
                                    </div>
                                    @if($item->notes)
                                    <p class="text-sm text-gray-600 mt-1">{{ $item->notes }}</p>
                                    @endif
                                </div>
                                <div class="text-right ml-4">
                                    <p class="text-sm font-medium text-gray-900">
                                        Rp {{ number_format($item->estimated_price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500">Est. Price</p>
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
