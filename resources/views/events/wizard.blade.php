@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900">Create New Event</h1>
            <p class="mt-2 text-gray-600">Choose how you want to start this event project</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Option 1: From Pipeline -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 hover:border-blue-500 hover:shadow-md transition group relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                    <svg class="w-32 h-32 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Convert from Lead</h2>
                    <p class="text-gray-500 mb-6 h-12">Select a client request that has been approved and is ready for execution.</p>

                    <form action="{{ route('events.create') }}" method="GET">
                        <div class="mb-4">
                            <select name="client_request_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Select Approved Lead...</option>
                                @foreach($approvedRequests as $req)
                                    <option value="{{ $req->id }}">
                                        {{ $req->client_name }} - {{ $req->event_type }} ({{ $req->event_date->format('d M Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition flex items-center justify-center">
                            Continue to Event Setup
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Option 2: Direct Booking -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 hover:border-green-500 hover:shadow-md transition group relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                    <svg class="w-32 h-32 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>

                <div class="relative z-10">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 mb-2">Direct Booking (Express)</h2>
                    <p class="text-gray-500 mb-6 h-12">For walk-in clients or immediate deals. Automatically creates a client record.</p>

                    <form action="{{ route('events.create') }}" method="GET">
                        <input type="hidden" name="direct_booking" value="true">
                        <div class="mb-4 h-[42px] flex items-center text-sm text-gray-400 italic">
                            Skip the lead pipeline process
                        </div>
                        <button type="submit" class="w-full py-3 bg-white border-2 border-green-600 text-green-600 rounded-lg font-semibold hover:bg-green-50 transition flex items-center justify-center">
                            Create Direct Event
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
