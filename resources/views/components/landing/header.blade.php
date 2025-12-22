<!-- Header -->
<header class="fixed top-0 left-0 right-0 z-40 bg-white/80 backdrop-blur-sm shadow-sm transition-all duration-300">
    <div class="container mx-auto px-6 py-3 flex justify-between items-center">
        <a href="#" class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-[#012A4A]" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 25C141.421 25 175 58.5786 175 100C175 141.421 141.421 175 100 175C58.5786 175 25 141.421 25 100C25 58.5786 58.5786 25 100 25Z" stroke="currentColor" stroke-width="20"/>
                <path d="M100 70C116.569 70 130 83.4315 130 100C130 116.569 116.569 130 100 130" stroke="currentColor" stroke-width="20" stroke-linecap="round"/>
            </svg>
            <span class="text-2xl font-bold text-[#012A4A]">TemanMenujuHalal</span>
        </a>
        <nav class="hidden md:flex items-center space-x-8">
            <a href="#vendors" class="text-gray-600 hover:text-[#013d70]">Vendor</a>
            <a href="#portfolio" class="text-gray-600 hover:text-[#013d70]">Portfolio</a>
            <a href="#packages" class="text-gray-600 hover:text-[#013d70]">Paket</a>
            <a href="#contact" class="text-gray-600 hover:text-[#013d70]">Kontak</a>
        </nav>
        <button @click="isModalOpen = true" class="hidden md:block bg-[#012A4A] text-white px-5 py-2 rounded-lg hover:bg-[#013d70] transition-colors">
            Daftar Sekarang
        </button>
        <button class="md:hidden text-2xl text-[#012A4A]">
            &#9776;
        </button>
    </div>
</header>
