<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Book Now - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        :root {
            --booking-primary: #9CAF88;
            --booking-primary-dark: #7A9A6B;
            --booking-secondary: #1C2440;
            --booking-accent: #D4AF37;
            --booking-cream: #F7EDE2;
            --booking-light: #FDFCFB;
        }
        
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Playfair Display', serif; }
        
        .step-indicator { transition: all 0.3s ease; }
        .step-indicator.active { background: var(--booking-primary); color: white; }
        .step-indicator.completed { background: var(--booking-accent); color: white; }
        
        .card-selection { 
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }
        .card-selection:hover { 
            border-color: var(--booking-primary);
            transform: translateY(-2px);
        }
        .card-selection.selected {
            border-color: var(--booking-accent);
            background: linear-gradient(135deg, #FFFBF5 0%, #FFF9F0 100%);
        }
        
        .package-card {
            transition: all 0.3s ease;
        }
        .package-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15);
        }
        .package-card.selected {
            ring: 3px;
            ring-color: var(--booking-accent);
        }
    </style>
</head>
<body class="bg-[#F7EDE2] min-h-screen">

<div x-data="bookingWizard()" x-init="init()" class="min-h-screen">
    
    <!-- Header -->
    <header class="bg-white/90 backdrop-blur-sm border-b border-gray-100 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('landing.page') }}" class="flex items-center gap-2 text-[#1C2440] hover:text-[#9CAF88] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="font-medium">Kembali</span>
            </a>
            <h1 class="font-heading text-xl font-semibold text-[#1C2440]">Book Your Event</h1>
            <div class="w-20"></div>
        </div>
    </header>

    <!-- Progress Steps -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <template x-for="(step, index) in steps" :key="index">
                    <div class="flex items-center" :class="index < steps.length - 1 ? 'flex-1' : ''">
                        <div class="flex flex-col items-center">
                            <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold"
                                 :class="{
                                     'active': currentStep === index + 1,
                                     'completed': currentStep > index + 1,
                                     'bg-gray-200 text-gray-500': currentStep < index + 1
                                 }">
                                <span x-show="currentStep <= index + 1" x-text="index + 1"></span>
                                <svg x-show="currentStep > index + 1" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-xs mt-2 text-center hidden sm:block" 
                                  :class="currentStep >= index + 1 ? 'text-[#1C2440] font-medium' : 'text-gray-400'"
                                  x-text="step"></span>
                        </div>
                        <div x-show="index < steps.length - 1" 
                             class="flex-1 h-1 mx-2 rounded"
                             :class="currentStep > index + 1 ? 'bg-[#D4AF37]' : 'bg-gray-200'"></div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 py-8">
        
        <!-- Step 1: Gambaran Acara -->
        <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
            @include('public.booking._step1-event')
        </div>

        <!-- Step 2: Cara Booking -->
        <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
            @include('public.booking._step2-method')
        </div>

        <!-- Step 3: Pilih Layanan -->
        <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
            @include('public.booking._step3-services')
        </div>

        <!-- Step 4: Ringkasan -->
        <div x-show="currentStep === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
            @include('public.booking._step4-summary')
        </div>

        <!-- Step 5: Data Diri -->
        <div x-show="currentStep === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
            @include('public.booking._step5-personal')
        </div>

    </main>

    <!-- Navigation Footer -->
    <footer class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-30">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <button x-show="currentStep > 1" @click="prevStep()" 
                    class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-medium hover:border-[#9CAF88] hover:text-[#9CAF88] transition">
                ← Kembali
            </button>
            <div x-show="currentStep === 1"></div>
            
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500" x-show="currentStep < 5">
                    Step <span x-text="currentStep"></span> dari 5
                </span>
                
                <button x-show="currentStep < 5" @click="nextStep()" 
                        :disabled="!canProceed()"
                        :class="canProceed() ? 'bg-[#9CAF88] hover:bg-[#7A9A6B]' : 'bg-gray-300 cursor-not-allowed'"
                        class="px-8 py-3 text-white rounded-xl font-semibold transition shadow-lg">
                    Lanjut →
                </button>
                
                <button x-show="currentStep === 5" @click="submitBooking()" 
                        :disabled="!canSubmit() || isSubmitting"
                        :class="canSubmit() && !isSubmitting ? 'bg-[#D4AF37] hover:bg-[#C9A032]' : 'bg-gray-300 cursor-not-allowed'"
                        class="px-8 py-3 text-white rounded-xl font-semibold transition shadow-lg flex items-center gap-2">
                    <svg x-show="isSubmitting" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="isSubmitting ? 'Memproses...' : 'Kirim Booking'"></span>
                </button>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div x-show="showLoginModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8" @click.away="showLoginModal = false">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-[#F7EDE2] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3 class="font-heading text-2xl font-bold text-[#1C2440]">Login Diperlukan</h3>
                <p class="text-gray-600 mt-2">Silakan login untuk melanjutkan booking Anda</p>
                <p class="text-sm text-[#9CAF88] mt-1">Data booking Anda akan tersimpan</p>
            </div>
            <div class="space-y-4">
                <button @click="redirectToLogin()" 
                   class="block w-full py-3 bg-[#9CAF88] text-white text-center rounded-xl font-semibold hover:bg-[#7A9A6B] transition">
                    Login
                </button>
                <button @click="redirectToRegister()" 
                   class="block w-full py-3 border-2 border-[#9CAF88] text-[#9CAF88] text-center rounded-xl font-semibold hover:bg-[#9CAF88] hover:text-white transition">
                    Daftar Baru
                </button>
            </div>
            <button @click="showLoginModal = false" class="mt-6 w-full text-gray-500 text-sm hover:text-gray-700">Batal</button>
        </div>
    </div>

</div>

<script>
function bookingWizard() {
    return {
        currentStep: 1,
        steps: ['Gambaran Acara', 'Cara Booking', 'Pilih Layanan', 'Ringkasan', 'Data Diri'],
        isSubmitting: false,
        showLoginModal: false,
        isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
        
        // Step 1 Data
        eventType: '',
        eventDate: '',
        eventLocation: '',
        eventNotes: '',
        
        // Step 2 Data
        bookingMethod: '', // 'package' or 'custom'
        
        // Step 3 Data
        selectedPackage: null,
        serviceSelections: {},
        nonPartnerVendors: [],
        
        // Step 5 Data
        clientName: '{{ auth()->user()->name ?? '' }}',
        clientEmail: '{{ auth()->user()->email ?? '' }}',
        clientPhone: '{{ auth()->user()->clientProfile->phone ?? '' }}',
        groomName: '',
        brideName: '',
        fillCoupleLater: false,
        
        // Available data from server
        packages: @json($allPackages ?? []),
        serviceTypes: @json($serviceTypes ?? []),
        activeBooking: @json($activeBooking),
        hasActiveBooking: {{ isset($activeBooking) && $activeBooking ? 'true' : 'false' }},
        proceedWithNewBooking: false,
        
        init() {
            // Check if returning from login - use localStorage for persistence across pages
            const saved = localStorage.getItem('bookingFormData');
            const wasInLoginFlow = localStorage.getItem('bookingLoginFlow');
            
            if (saved) {
                try {
                    const data = JSON.parse(saved);
                    // Restore all saved fields
                    this.eventType = data.eventType || '';
                    this.eventDate = data.eventDate || '';
                    this.eventLocation = data.eventLocation || '';
                    this.eventNotes = data.eventNotes || '';
                    this.bookingMethod = data.bookingMethod || '';
                    this.selectedPackage = data.selectedPackage || null;
                    this.serviceSelections = data.serviceSelections || {};
                    this.nonPartnerVendors = data.nonPartnerVendors || [];
                    this.groomName = data.groomName || '';
                    this.brideName = data.brideName || '';
                    
                    // If user just logged in and was in login flow, advance to step 5
                    if (wasInLoginFlow === 'true' && this.isLoggedIn) {
                        this.currentStep = 5;
                        localStorage.removeItem('bookingLoginFlow');
                        // Smooth scroll to top after a short delay
                        setTimeout(() => {
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }, 100);
                    } else {
                        // Restore previous step (max step 4 if not logged in)
                        this.currentStep = data.currentStep || 1;
                        if (this.currentStep > 4 && !this.isLoggedIn) {
                            this.currentStep = 4;
                        }
                    }
                } catch (e) {
                    console.warn('Could not parse saved booking data');
                }
            }
            
            // Auto-save on changes
            this.$watch('eventType', () => this.saveToStorage());
            this.$watch('eventDate', () => this.saveToStorage());
            this.$watch('eventLocation', () => this.saveToStorage());
            this.$watch('eventNotes', () => this.saveToStorage());
            this.$watch('bookingMethod', () => this.saveToStorage());
            this.$watch('selectedPackage', () => this.saveToStorage());
            this.$watch('serviceSelections', () => this.saveToStorage());
            this.$watch('nonPartnerVendors', () => this.saveToStorage());
            this.$watch('groomName', () => this.saveToStorage());
            this.$watch('brideName', () => this.saveToStorage());
            this.$watch('currentStep', () => this.saveToStorage());
            
            // Check for active booking and show SweetAlert (only for logged-in users with no login flow)
            // Use setTimeout to ensure page is fully loaded and prevent race conditions
            if (this.isLoggedIn && this.hasActiveBooking && wasInLoginFlow !== 'true') {
                // Use a session flag to prevent showing multiple times on same page load
                const alertShown = sessionStorage.getItem('activeBookingAlertShown');
                if (!alertShown) {
                    sessionStorage.setItem('activeBookingAlertShown', 'true');
                    // Delay to ensure DOM is ready
                    setTimeout(() => {
                        this.showActiveBookingAlert();
                    }, 500);
                }
            } else {
                // Clear the flag when not applicable
                sessionStorage.removeItem('activeBookingAlertShown');
            }
        },
        
        showActiveBookingAlert() {
            const booking = this.activeBooking;
            const eventDate = booking.event_date ? new Date(booking.event_date).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long', 
                year: 'numeric'
            }) : '-';
            
            Swal.fire({
                title: 'Booking Sudah Ada',
                html: `
                    <div class="text-left">
                        <p class="mb-4">Anda sudah memiliki booking yang sedang berjalan:</p>
                        <div class="bg-gray-100 rounded-lg p-4 mb-4">
                            <p class="font-semibold text-gray-800">${booking.event_type || 'Event'}</p>
                            <p class="text-sm text-gray-600">Tanggal: ${eventDate}</p>
                            <p class="text-sm text-gray-600">Status: <span class="font-medium">${this.formatStatus(booking.status)}</span></p>
                        </div>
                        <p class="text-sm text-gray-500">Pilih aksi yang ingin Anda lakukan:</p>
                    </div>
                `,
                icon: 'info',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: '📋 Lanjutkan Booking yang Ada',
                denyButtonText: '➕ Buat Booking Baru',
                cancelButtonText: 'Batalkan',
                confirmButtonColor: '#9CAF88',
                denyButtonColor: '#D4AF37',
                cancelButtonColor: '#6B7280',
                allowOutsideClick: false,
                allowEscapeKey: false,
            }).then((result) => {
                // Clear the alert shown flag after user makes a choice
                sessionStorage.removeItem('activeBookingAlertShown');
                
                if (result.isConfirmed) {
                    // Redirect to client dashboard to continue existing booking
                    window.location.href = '{{ route("client.dashboard") }}';
                } else if (result.isDenied) {
                    // User wants to create a new booking - clear any saved data and proceed
                    localStorage.removeItem('bookingFormData');
                    this.proceedWithNewBooking = true;
                    Swal.fire({
                        title: 'Membuat Booking Baru',
                        text: 'Anda dapat melanjutkan membuat booking baru. Booking sebelumnya tidak akan terpengaruh.',
                        icon: 'success',
                        confirmButtonText: 'Lanjutkan',
                        confirmButtonColor: '#9CAF88',
                        timer: 2000,
                        timerProgressBar: true,
                    });
                } else {
                    // User cancelled - redirect back to homepage
                    window.location.href = '{{ route("landing.page") }}';
                }
            });
        },
        
        formatStatus(status) {
            const statusMap = {
                'pending': 'Menunggu Konfirmasi',
                'in_review': 'Sedang Ditinjau',
                'approved': 'Disetujui',
                'in_progress': 'Sedang Berjalan',
                'completed': 'Selesai',
                'cancelled': 'Dibatalkan'
            };
            return statusMap[status] || status;
        },
        
        saveToStorage() {
            const data = {
                currentStep: this.currentStep,
                eventType: this.eventType,
                eventDate: this.eventDate,
                eventLocation: this.eventLocation,
                eventNotes: this.eventNotes,
                bookingMethod: this.bookingMethod,
                selectedPackage: this.selectedPackage,
                serviceSelections: this.serviceSelections,
                nonPartnerVendors: this.nonPartnerVendors,
                groomName: this.groomName,
                brideName: this.brideName,
            };
            localStorage.setItem('bookingFormData', JSON.stringify(data));
        },
        
        canProceed() {
            switch(this.currentStep) {
                case 1: return this.eventType && this.eventDate && this.eventLocation;
                case 2: return this.bookingMethod !== '';
                case 3: 
                    if (this.bookingMethod === 'package') return this.selectedPackage !== null;
                    return Object.keys(this.serviceSelections).length > 0 || this.nonPartnerVendors.length > 0;
                case 4: return true;
                default: return true;
            }
        },
        
        canSubmit() {
            return this.clientName && this.clientEmail && this.clientPhone && 
                   (this.eventType !== 'Wedding' || this.fillCoupleLater || (this.groomName && this.brideName));
        },
        
        nextStep() {
            if (this.currentStep === 4 && !this.isLoggedIn) {
                this.showLoginModal = true;
                return;
            }
            if (this.canProceed() && this.currentStep < 5) {
                this.currentStep++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        
        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        
        goToStep(step) {
            if (step <= this.currentStep) {
                this.currentStep = step;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        
        selectPackage(pkg) {
            this.selectedPackage = pkg;
        },
        
        calculateTotal() {
            let total = 0;
            if (this.bookingMethod === 'package' && this.selectedPackage) {
                total = parseFloat(this.selectedPackage.final_price) || 0;
            } else {
                Object.values(this.serviceSelections).forEach(s => {
                    total += parseFloat(s.subtotal) || 0;
                });
                this.nonPartnerVendors.forEach(v => {
                    total += parseFloat(v.charge) || 600000;
                });
            }
            return total;
        },
        
        formatPrice(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        },
        
        // Redirect to login with state preservation
        redirectToLogin() {
            // Save current state and set login flow flag
            this.saveToStorage();
            localStorage.setItem('bookingLoginFlow', 'true');
            // Redirect to login page
            window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent(window.location.href);
        },
        
        // Redirect to register with state preservation
        redirectToRegister() {
            // Save current state and set login flow flag
            this.saveToStorage();
            localStorage.setItem('bookingLoginFlow', 'true');
            // Redirect to register page
            window.location.href = '{{ route("register") }}?redirect=' + encodeURIComponent(window.location.href);
        },
        
        async submitBooking() {
            if (!this.isLoggedIn) {
                this.showLoginModal = true;
                return;
            }
            
            this.isSubmitting = true;
            
            try {
                const response = await fetch('{{ route("public.booking.store.ajax") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        client_name: this.clientName,
                        client_email: this.clientEmail,
                        client_phone: this.clientPhone,
                        event_type: this.eventType,
                        event_date: this.eventDate,
                        message: `Lokasi: ${this.eventLocation}\n${this.eventNotes}`,
                        package_id: this.selectedPackage?.id || null,
                        cpp_name: this.groomName,
                        cpw_name: this.brideName,
                        fill_couple_later: this.fillCoupleLater,
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Clear all booking data from localStorage on success
                    localStorage.removeItem('bookingFormData');
                    localStorage.removeItem('bookingLoginFlow');
                    window.location.href = '{{ route("client.dashboard") }}';
                } else {
                    alert(result.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>

</body>
</html>
