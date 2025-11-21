<!-- Hero Section -->
<section id="hero" class="relative min-h-screen flex items-center justify-center hero-gradient text-center overflow-hidden pt-20">
    <!-- Parallax Background -->
    <div class="hero-bg absolute inset-0 w-full h-full bg-cover bg-center opacity-10" style="background-image: url('https://images.unsplash.com/photo-1523438885209-e0793309c961?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1740&q=80');"></div>
    
    <div class="container mx-auto px-6 z-10">
        <div data-aos="fade-up" data-aos-delay="200">
            <h1 class="text-4xl md:text-6xl font-extrabold text-[#012A4A] leading-tight">
                Temukan Vendor Pernikahan <br class="hidden md:block" /> Terpercaya Dengan Lebih Mudah
            </h1>
            <p class="mt-4 text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                Jelajahi ribuan vendor pernikahan profesional untuk mewujudkan hari bahagiamu.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                <a href="#vendors" class="bg-[#012A4A] text-white px-8 py-3 rounded-lg font-semibold text-lg hover:bg-[#013d70] transition-transform transform hover:scale-105">
                    Jelajahi Vendor
                </a>
                <button @click="isModalOpen = true" class="bg-white text-[#012A4A] border border-[#012A4A] px-8 py-3 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-transform transform hover:scale-105">
                    Daftar Sekarang
                </button>
            </div>
        </div>
    </div>
</section>
