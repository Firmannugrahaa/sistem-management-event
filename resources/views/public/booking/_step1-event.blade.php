{{-- Step 1: Gambaran Acara --}}
<div class="bg-white rounded-2xl shadow-lg p-8 mb-24">
    <div class="text-center mb-8">
        <h2 class="font-heading text-2xl font-bold text-[#1C2440] mb-2">Ceritakan Acara Anda</h2>
        <p class="text-gray-600">Beritahu kami tentang event yang ingin Anda selenggarakan</p>
    </div>

    <div class="space-y-6 max-w-xl mx-auto">
        {{-- Jenis Acara --}}
        <div>
            <label class="block text-sm font-semibold text-[#1C2440] mb-3">Jenis Acara <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach(['Wedding', 'Prewedding', 'Birthday', 'Corporate', 'Lainnya'] as $type)
                <button type="button" 
                        @click="eventType = '{{ $type }}'"
                        :class="eventType === '{{ $type }}' ? 'border-[#D4AF37] bg-[#FFFBF5] text-[#1C2440]' : 'border-gray-200 hover:border-[#9CAF88]'"
                        class="card-selection px-4 py-3 rounded-xl border-2 text-sm font-medium transition">
                    @if($type === 'Wedding')
                        💍 Wedding
                    @elseif($type === 'Prewedding')
                        📸 Prewedding
                    @elseif($type === 'Birthday')
                        🎂 Birthday
                    @elseif($type === 'Corporate')
                        🏢 Corporate
                    @else
                        ✨ Lainnya
                    @endif
                </button>
                @endforeach
            </div>
        </div>

        {{-- Tanggal Acara --}}
        <div>
            <label for="eventDate" class="block text-sm font-semibold text-[#1C2440] mb-2">
                Tanggal Acara <span class="text-red-500">*</span>
            </label>
            <input type="date" id="eventDate" x-model="eventDate"
                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#9CAF88] focus:ring-2 focus:ring-[#9CAF88]/20 transition outline-none">
            <p class="text-xs text-gray-500 mt-1">Pilih tanggal pelaksanaan event Anda</p>
        </div>

        {{-- Lokasi Acara --}}
        <div>
            <label for="eventLocation" class="block text-sm font-semibold text-[#1C2440] mb-2">
                Lokasi Acara <span class="text-red-500">*</span>
            </label>
            <input type="text" id="eventLocation" x-model="eventLocation"
                   placeholder="Contoh: Gedung Serbaguna Kecamatan Cilandak"
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#9CAF88] focus:ring-2 focus:ring-[#9CAF88]/20 transition outline-none">
            <p class="text-xs text-gray-500 mt-1">Tuliskan lokasi geografis acara (bukan nama venue spesifik)</p>
        </div>

        {{-- Catatan Tambahan --}}
        <div>
            <label for="eventNotes" class="block text-sm font-semibold text-[#1C2440] mb-2">
                Catatan Tambahan <span class="text-gray-400 font-normal">(opsional)</span>
            </label>
            <textarea id="eventNotes" x-model="eventNotes" rows="3"
                      placeholder="Ceritakan konsep atau kebutuhan khusus acara Anda..."
                      maxlength="500"
                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#9CAF88] focus:ring-2 focus:ring-[#9CAF88]/20 transition outline-none resize-none"></textarea>
            <p class="text-xs text-gray-400 text-right" x-text="(eventNotes?.length || 0) + '/500'"></p>
        </div>
    </div>
</div>
