@props(['packages'])

<!-- Packages Section -->
<section id="packages" class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-[#012A4A]">Paket Pernikahan Fleksibel</h2>
            <p class="text-lg text-gray-600 mt-2">Pilih paket yang paling sesuai dengan kebutuhan Anda.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($packages as $package)
            <div class="rounded-xl p-8 text-center transform hover:-translate-y-3 transition-transform duration-300 
                        {{ $package['is_featured'] ? 'bg-[#012A4A] text-white shadow-2xl scale-105' : 'bg-white shadow-lg' }}"
                 data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <h3 class="text-2xl font-bold mb-4">{{ $package['name'] }}</h3>
                <p class="text-4xl font-extrabold mb-4">Rp {{ $package['price'] }}</p>
                <ul class="space-y-3 my-6">
                    @foreach($package['features'] as $feature)
                    <li class="flex items-center justify-center">
                        <svg class="w-5 h-5 {{ $package['is_featured'] ? 'text-green-300' : 'text-green-500' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
                <button class="mt-8 px-8 py-3 rounded-lg font-semibold 
                               {{ $package['is_featured'] ? 'bg-white text-[#012A4A] hover:bg-gray-200' : 'bg-[#012A4A] text-white hover:bg-[#013d70]' }}">
                    Detail Paket
                </button>
            </div>
            @endforeach
        </div>
    </div>
</section>
