<!-- Animate.css Library -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!-- 5-Second Fullscreen Glass Loading Overlay -->
<div id="website-loader" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-slate-950/90 backdrop-blur-2xl transition-opacity duration-700 pointer-events-auto">
    <div class="flex flex-col items-center gap-6 p-8 rounded-3xl ios-glass border border-white/20 shadow-2xl text-center max-w-sm w-full mx-4 animate__animated animate__zoomIn">
        
        <!-- Glowing Profile Avatar Spinner -->
        <div class="relative w-24 h-24 flex items-center justify-center">
            <div class="absolute inset-0 rounded-full border-4 border-t-orange-500 border-r-pink-500 border-b-purple-500 border-l-transparent animate-spin"></div>
            <img src="{{ asset('images/profile.jpg') }}" alt="Parsa Besharat" class="w-16 h-16 rounded-full border-2 border-white/50 object-cover object-[50%_25%] shadow-lg">
        </div>

        <div class="space-y-2">
            <h3 class="text-xl font-extrabold text-white tracking-tight">Parsa Besharat</h3>
            <p class="text-xs font-semibold text-orange-400 font-mono tracking-wider uppercase">AI Engineer & Data Scientist</p>
        </div>

        <!-- Animated Progress Bar & Indicator -->
        <div class="w-full bg-white/10 rounded-full h-1.5 overflow-hidden relative">
            <div id="loader-progress-bar" class="h-full bg-gradient-to-r from-orange-500 via-rose-500 to-pink-500 w-0 transition-all duration-[5000ms] ease-out"></div>
        </div>

        <p class="text-[11px] text-gray-300 font-mono animate-pulse">Initializing AI Systems & Components...</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loader = document.getElementById('website-loader');
        const progressBar = document.getElementById('loader-progress-bar');
        
        if (progressBar) {
            setTimeout(() => {
                progressBar.style.width = '100%';
            }, 50);
        }

        if (loader) {
            setTimeout(function() {
                loader.classList.add('opacity-0', 'pointer-events-none');
                
                // Add fade-in entrance to main container
                const mainContainer = document.getElementById('main-container');
                if (mainContainer) {
                    mainContainer.classList.add('animate__animated', 'animate__fadeIn');
                }

                setTimeout(function() {
                    loader.style.display = 'none';
                }, 700);
            }, 5000);
        }
    });
</script>
