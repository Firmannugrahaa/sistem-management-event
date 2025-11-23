<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Order Review</h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Review Your Order</h1>
            <p class="text-gray-600">Please review your selections before confirming your event</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Order Summary</h2>
            
            <!-- Selected Venue -->
            <div class="border-b pb-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Selected Venue</h3>
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-lg text-gray-800">{{ $venue->name ?? 'No venue selected' }}</h4>
                        <p class="text-gray-600">{{ $venue->address ?? '' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-blue-600 font-semibold">Rp {{ $venue ? number_format($venue->price, 0, ',', '.') : '0' }}</p>
                    </div>
                </div>
            </div>

            <!-- Selected Vendors -->
            <div class="border-b pb-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Selected Vendors</h3>
                @forelse($vendors as $vendor)
                <div class="flex justify-between items-start py-3 border-b border-gray-100">
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $vendor->user ? $vendor->user->name : $vendor->contact_person }}</h4>
                        <p class="text-gray-600 text-sm">
                            Contact: {{ $vendor->phone_number ?? $vendor->user?->phone ?? 'N/A' }}
                        </p>
                        <p class="text-gray-600 text-sm">Service Type: {{ $vendor->serviceType ? $vendor->serviceType->name : 'Not specified' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-purple-600 font-semibold">Price to be confirmed</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-600">No vendors selected.</p>
                @endforelse
            </div>

            <!-- Total Cost -->
            <div class="border-b pb-6 mb-6">
                <div class="flex justify-between items-center text-xl">
                    <span class="font-bold text-gray-800">Total Cost:</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($totalCost, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Confirmation Form -->
            <div class="mt-8">
                <form method="POST" action="{{ route('client.order.confirm') }}">
                    @csrf
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <p class="text-yellow-700">
                            <strong>Note:</strong> Once you confirm your order, an invoice will be generated and sent to your email. 
                            Please make payment according to the due date mentioned in the invoice.
                        </p>
                    </div>
                    
                    <div class="flex justify-between">
                        <a href="{{ route('client.landing') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            Back to Selection
                        </a>
                        
                        <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Confirm Order & Generate Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>