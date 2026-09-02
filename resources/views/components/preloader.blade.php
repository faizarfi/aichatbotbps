{{-- Modern Executive Preloader Component (BPS Kabupaten Karanganyar) --}}
<div id="site-preloader" class="fixed inset-0 z-[99999] flex items-center justify-center bg-slate-900/50 backdrop-blur-xl transition-all duration-500 ease-out font-sans">
    
    {{-- Ambient Lighting Orbs --}}
    <div class="absolute w-80 h-80 rounded-full bg-blue-500/20 blur-3xl animate-pulse pointer-events-none"></div>
    <div class="absolute w-72 h-72 rounded-full bg-indigo-500/20 blur-3xl animate-pulse pointer-events-none" style="animation-delay: 1s;"></div>
    <div class="absolute w-64 h-64 rounded-full bg-sky-400/15 blur-3xl animate-pulse pointer-events-none" style="animation-delay: 2s;"></div>

    {{-- Center Card Modal --}}
    <div class="relative z-10 bg-white/95 backdrop-blur-2xl rounded-3xl p-7 sm:p-9 border border-white/80 shadow-2xl shadow-slate-950/30 max-w-xs sm:max-w-sm w-[90%] text-center flex flex-col items-center transform transition-all duration-300">
        
        {{-- Logo with Double Orbit Spinning Rings --}}
        <div class="relative w-24 h-24 mb-5 flex items-center justify-center">
            {{-- Outer Spinning Gradient Ring --}}
            <div class="absolute inset-0 rounded-full border-2 border-transparent border-t-blue-600 border-r-indigo-500 animate-spin" style="animation-duration: 1.2s;"></div>
            
            {{-- Inner Reverse Spinning Accent Ring --}}
            <div class="absolute inset-2 rounded-full border-2 border-transparent border-b-sky-400 border-l-blue-400 animate-spin" style="animation-duration: 1.8s; animation-direction: reverse;"></div>
            
            {{-- Subtle Pulse Glow --}}
            <div class="absolute inset-3 rounded-full bg-blue-50 animate-ping opacity-40"></div>
            
            {{-- Center BPS Emblem Badge --}}
            <div class="relative w-14 h-14 rounded-2xl bg-white p-2.5 flex items-center justify-center shadow-md border border-slate-100/90 hover:scale-105 transition-transform">
                <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS Karanganyar" class="w-full h-full object-contain">
            </div>
        </div>

        {{-- Institution Title --}}
        <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight leading-tight">
            BPS Kabupaten Karanganyar
        </h3>
        <span class="text-[10px] sm:text-[11px] font-extrabold text-blue-700 tracking-widest uppercase mt-1 mb-4">
            Pelayanan Statistik Terpadu
        </span>

        {{-- Modern Shimmer Linear Progress Bar --}}
        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden relative shadow-inner mb-3.5">
            <div id="preloader-progress-bar" class="h-full bg-gradient-to-r from-blue-600 via-sky-400 to-indigo-600 rounded-full preloader-bar-anim"></div>
        </div>

        {{-- Status Caption & Live Dot Indicator --}}
        <div class="flex items-center gap-1.5 text-[11px] text-slate-500 font-semibold">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-xs"></span>
            <span id="preloader-status-text">Memuat layanan data statistik...</span>
        </div>
    </div>
</div>

<style>
    .preloader-bar-anim {
        width: 100%;
        animation: preloader-slide 1.5s cubic-bezier(0.65, 0, 0.35, 1) infinite;
        transform-origin: 0% 50%;
    }

    @keyframes preloader-slide {
        0% {
            transform: translateX(-100%) scaleX(0.2);
        }
        50% {
            transform: translateX(0%) scaleX(0.8);
        }
        100% {
            transform: translateX(100%) scaleX(0.2);
        }
    }

    #site-preloader.preloader-hidden {
        opacity: 0 !important;
        transform: scale(1.03) !important;
        pointer-events: none !important;
        visibility: hidden !important;
    }
</style>

<script>
    (function() {
        const preloader = document.getElementById('site-preloader');
        const statusText = document.getElementById('preloader-status-text');

        window.showPreloader = function(message) {
            if (!preloader) return;
            if (message && statusText) {
                statusText.textContent = message;
            }
            preloader.classList.remove('preloader-hidden');
            preloader.style.display = 'flex';
            preloader.style.visibility = 'visible';
            requestAnimationFrame(() => {
                preloader.style.opacity = '1';
                preloader.style.transform = 'scale(1)';
            });
        };

        window.hidePreloader = function() {
            if (!preloader) return;
            preloader.style.opacity = '0';
            preloader.style.transform = 'scale(1.02)';
            setTimeout(() => {
                preloader.classList.add('preloader-hidden');
                preloader.style.display = 'none';
            }, 450);
        };

        // Smooth Auto-hide when DOM & assets finish loading
        if (document.readyState === 'complete') {
            setTimeout(window.hidePreloader, 200);
        } else {
            window.addEventListener('load', function() {
                setTimeout(window.hidePreloader, 250);
            });
        }

        // Safety fallback: ensure preloader hides after maximum 2.5s even if external resources are slow
        setTimeout(window.hidePreloader, 2500);
    })();
</script>
