@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center space-x-4">
            <a href="{{ route('client.requests.show', $recommendation->clientRequest) }}" class="text-gray-500 hover:text-gray-700">
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
                    <span class="text-sm text-gray-500">Received on {{ $recommendation->sent_at ? $recommendation->sent_at->format('d M Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                @if($recommendation->description)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Note from Organizer</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $recommendation->description }}</p>
                </div>
                @endif

                <!-- Items List -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">Package Details</h2>
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
                                    </div>
                                    @if($item->notes)
                                    <p class="text-sm text-gray-600 mt-1">{{ $item->notes }}</p>
                                    @endif
                                </div>
                                <div class="text-right ml-4">
                                    <p class="text-sm font-medium text-gray-900">
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

            <!-- Action Sidebar -->
            <div class="space-y-6">
                <!-- Action Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Response</h3>
                    
                    @if($recommendation->status === 'sent')
                        <p class="text-sm text-gray-600 mb-6">Please review the proposal and let us know your decision. You can accept it to proceed, reject it, or ask for revisions.</p>
                        
                        <form action="{{ route('client.recommendations.respond', $recommendation) }}" method="POST" id="response-form">
                            @csrf
                            <input type="hidden" name="action" id="response-action">
                            
                            <div class="space-y-3">
                                <button type="button" onclick="submitResponse('accept')" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Accept Proposal
                                </button>
                                
                                <button type="button" onclick="showFeedback('revision')" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Request Revision
                                </button>
                                
                                <button type="button" onclick="showFeedback('reject')" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Reject
                                </button>
                            </div>

                            <!-- Feedback Modal / Area (Hidden by default) -->
                            <div id="feedback-area" class="hidden mt-4 pt-4 border-t border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-2" id="feedback-label">Reason / Feedback</label>
                                <textarea name="feedback" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Please provide details..."></textarea>
                                <div class="mt-3 flex justify-end space-x-3">
                                    <button type="button" onclick="hideFeedback()" class="text-sm text-gray-500 hover:text-gray-700">Cancel</button>
                                    <button type="button" onclick="submitWithFeedback()" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Submit Response
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full {{ $recommendation->status === 'accepted' ? 'bg-green-100' : ($recommendation->status === 'rejected' ? 'bg-red-100' : 'bg-orange-100') }}">
                                @if($recommendation->status === 'accepted')
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                @elseif($recommendation->status === 'rejected')
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                @else
                                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                @endif
                            </div>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">
                                You {{ str_replace('_', ' ', $recommendation->status) }} this proposal
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Response recorded on {{ $recommendation->responded_at->format('d M Y, H:i') }}
                            </p>
                            @if($recommendation->client_feedback)
                            <div class="mt-4 bg-gray-50 p-3 rounded-lg text-left">
                                <p class="text-xs font-medium text-gray-500 mb-1">Your Feedback:</p>
                                <p class="text-sm text-gray-700">{{ $recommendation->client_feedback }}</p>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentAction = '';

    function submitResponse(action) {
        if(confirm('Are you sure you want to ' + action + ' this proposal?')) {
            document.getElementById('response-action').value = action;
            document.getElementById('response-form').submit();
        }
    }

    function showFeedback(action) {
        currentAction = action;
        const area = document.getElementById('feedback-area');
        const label = document.getElementById('feedback-label');
        
        area.classList.remove('hidden');
        
        if (action === 'revision') {
            label.textContent = 'What changes would you like to request?';
        } else {
            label.textContent = 'Please tell us why you are rejecting this proposal (Optional)';
        }
    }

    function hideFeedback() {
        document.getElementById('feedback-area').classList.add('hidden');
        currentAction = '';
    }

    function submitWithFeedback() {
        if (!currentAction) return;
        
        document.getElementById('response-action').value = currentAction;
        document.getElementById('response-form').submit();
    }
</script>
@endpush
@endsection
