@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center space-x-4">
            <a href="{{ route('client.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $clientRequest->event_type }}</h1>
                <p class="text-sm text-gray-600">Request ID: #{{ $clientRequest->id }} • {{ $clientRequest->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Status Banner -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Current Status</p>
                            <h2 class="text-xl font-bold text-gray-900 mt-1">{{ ucfirst(str_replace('_', ' ', $clientRequest->detailed_status)) }}</h2>
                            <p class="text-sm text-gray-600 mt-2">
                                @if($clientRequest->detailed_status == 'new')
                                    Your request has been received and is waiting for review.
                                @elseif($clientRequest->detailed_status == 'contacted')
                                    Our team is reviewing your request and will contact you shortly.
                                @elseif($clientRequest->detailed_status == 'recommendation_sent')
                                    We have sent a recommendation! Please check below.
                                @elseif($clientRequest->detailed_status == 'approved')
                                    Great! You've approved our proposal. We are preparing the next steps.
                                @endif
                            </p>
                        </div>
                        <div class="h-12 w-12 rounded-full flex items-center justify-center {{ $clientRequest->status_badge_color }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Recommendations List -->
                @if($clientRequest->recommendations->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900">Proposals & Recommendations</h2>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        @foreach($clientRequest->recommendations as $rec)
                        @if($rec->status !== 'draft')
                        <li class="hover:bg-gray-50 transition">
                            <a href="{{ route('client.recommendations.show', $rec) }}" class="block px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-base font-medium text-blue-600">{{ $rec->title }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">{{ $rec->items->count() }} items included</p>
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $rec->status_badge_color }}">
                                                {{ ucfirst($rec->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900">Rp {{ number_format($rec->total_estimated_budget, 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-500">Est. Budget</p>
                                        <div class="mt-2 text-sm text-blue-600 font-medium flex items-center justify-end">
                                            View Details
                                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Event Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Details</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Event Date</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $clientRequest->event_date->format('d F Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estimated Budget</dt>
                            <dd class="mt-1 text-base text-gray-900">Rp {{ number_format($clientRequest->budget, 0, ',', '.') }}</dd>
                        </div>
                        <div class="col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Your Message</dt>
                            <dd class="mt-1 text-base text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $clientRequest->message }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contact Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Need Help?</h3>
                    <p class="text-sm text-gray-600 mb-4">If you have questions or need to change details, please contact us.</p>
                    <div class="space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            +62 812 3456 7890
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            support@eventorganizer.com
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
