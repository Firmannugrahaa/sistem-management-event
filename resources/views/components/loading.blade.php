<div x-data="{ loading: false }"
     x-show="loading"
     @loading.window="loading = $event.detail"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-[#F7F7F7]/80 backdrop-blur-sm"
     style="display: none;">
    
    <div class="relative flex flex-col items-center justify-center gap-4">
        <div class="relative flex items-center justify-center">
            <!-- Outer Ring -->
            <div class="w-16 h-16 border-4 border-[#E0E0E0] rounded-full"></div>
            <!-- Inner Spinning Ring -->
            <div class="absolute w-16 h-16 border-4 border-t-[#27AE60] border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
            <!-- Center Dot -->
            <div class="absolute w-3 h-3 bg-[#1A1A1A] rounded-full animate-pulse"></div>
        </div>
        <div class="text-[#1A1A1A] font-medium text-sm animate-pulse tracking-wider">LOADING</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Show loader on link clicks that are navigating to a new page
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');
                // Check if it's a valid link and not just a hash or javascript:void(0)
                if (href && href.startsWith('http') && !href.includes('#') && link.target !== '_blank') {
                    window.dispatchEvent(new CustomEvent('loading', { detail: true }));
                }
            });
        });

        // Hide loader when page is fully loaded (back/forward cache)
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                window.dispatchEvent(new CustomEvent('loading', { detail: false }));
            }
        });
    });
</script>
