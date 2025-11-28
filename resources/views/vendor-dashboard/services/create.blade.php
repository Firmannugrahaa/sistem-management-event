<x-vendor-dashboard-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Layanan Baru</h2>
        <p class="text-gray-600 dark:text-gray-400">Pilih layanan dari katalog dan sesuaikan detailnya</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('vendor.services.store') }}">
        @csrf

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Pilih Layanan</h3>

            @if($services->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    @foreach($services as $service)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 cursor-pointer service-item"
                             data-service-id="{{ $service->id }}"
                             onclick="selectService({{ $service->id }})">
                            <div class="flex items-start">
                                <input type="radio" name="service_id" value="{{ $service->id }}" 
                                       id="service_{{ $service->id }}" 
                                       class="mt-1 mr-3 service-radio">
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $service->name }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ Str::limit($service->description, 100) }}</p>
                                    <p class="text-sm text-primary font-semibold mt-2">Rp {{ number_format($service->price, 0, ',', '.') }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 mt-2">
                                        {{ $service->category ?: 'Umum' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Belum ada layanan yang tersedia untuk ditambahkan.</p>
                </div>
            @endif
        </div>

        <!-- Service Details -->
        <div id="service-details" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6" style="display: none;">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Detail Layanan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Harga (Opsional)
                    </label>
                    <input type="number" name="price" id="price" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                           placeholder="Gunakan harga default jika kosong"
                           min="0">
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Harga yang akan Anda tawarkan untuk layanan ini</p>
                </div>

                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Durasi (jam)
                    </label>
                    <input type="number" name="duration" id="duration" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                           value="" readonly>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Durasi layanan dalam jam</p>
                </div>
            </div>

            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Deskripsi Tambahan (Opsional)
                </label>
                <textarea name="description" id="description" 
                          rows="3"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                          placeholder="Tambahkan informasi tambahan tentang layanan Anda..."></textarea>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Detail spesifik yang ingin Anda sampaikan tentang layanan ini</p>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" 
                    id="submit-btn"
                    disabled
                    class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md opacity-50 cursor-not-allowed"
                    onclick="return validateForm()">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Tambahkan Layanan
            </button>
        </div>
    </form>

    <script>
        function selectService(serviceId) {
            // Deselect all other items
            document.querySelectorAll('.service-item').forEach(item => {
                item.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/30');
                item.querySelector('input[type="radio"]').checked = false;
            });
            
            // Select the clicked item
            const selectedItem = document.querySelector(`[data-service-id="${serviceId}"]`);
            selectedItem.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/30');
            const radio = selectedItem.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Show service details
            document.getElementById('service-details').style.display = 'block';
            
            // Enable submit button
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            submitBtn.classList.add('opacity-100', 'hover:bg-blue-700', 'cursor-pointer');
            
            // Get service details for the selected service to populate fields
            fetch(`/api/vendor/services/${serviceId}`)
                .then(response => response.json())
                .then(service => {
                    document.getElementById('duration').value = service.duration || '';
                })
                .catch(error => console.error('Error:', error));
        }
        
        function validateForm() {
            const selectedService = document.querySelector('input[name="service_id"]:checked');
            if (!selectedService) {
                alert('Silakan pilih layanan terlebih dahulu.');
                return false;
            }
            return true;
        }
    </script>
</x-vendor-dashboard-layout>