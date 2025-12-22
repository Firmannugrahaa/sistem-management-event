<!-- Registration Modal -->
<div 
    x-show="isModalOpen" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
>
    <div 
        @click.away="isModalOpen = false"
        x-show="isModalOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="relative bg-white rounded-xl shadow-2xl w-full max-w-md"
    >
        <div class="p-8">
            <button @click="isModalOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 class="text-2xl font-bold mb-6 text-center text-[#012A4A]">Daftar Akun Baru</h3>
            <form>
                <div class="space-y-4">
                    <input type="text" placeholder="Nama Lengkap" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#013d70]">
                    <input type="email" placeholder="Email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#013d70]">
                    <input type="tel" placeholder="Nomor HP" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#013d70]">
                    <input type="password" placeholder="Password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#013d70]">
                    <input type="password" placeholder="Konfirmasi Password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#013d70]">
                </div>
                <button type="submit" class="mt-6 w-full bg-[#012A4A] text-white py-3 rounded-lg font-semibold hover:bg-[#013d70] transition-colors">Daftar</button>
            </form>
        </div>
    </div>
</div>
