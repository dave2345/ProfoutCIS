<x-layouts.app>
    <div class="flex flex-1 items-center justify-center px-6 py-12 bg-gray-50">

        <!-- Slider Container -->
        <div class="relative w-full max-w-5xl aspect-[16/9] overflow-hidden rounded-2xl shadow-xl bg-white flex items-center justify-center">

            <!-- Slides -->
            <div class="absolute inset-0">
                <img src="{{ asset('images/slide1.jpg') }}"
                     class="slide"
                     alt="Slide 1">
                <img src="{{ asset('images/slide2.jpg') }}"
                     class="slide"
                     alt="Slide 2">
                <img src="{{ asset('images/slide3.jpg') }}"
                     class="slide"
                     alt="Slide 3">
            </div>

        </div>

        <!-- Styles -->
        <style>
            /* Slide common style */
            .slide {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) scale(1);
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                opacity: 0;
                animation: slideAnimation 15s infinite;
            }

            /* Animation delays for each slide */
            .slide:nth-child(1) { animation-delay: 0s; }
            .slide:nth-child(2) { animation-delay: 5s; }
            .slide:nth-child(3) { animation-delay: 10s; }

            /* Fade + subtle zoom animation */
            @keyframes slideAnimation {
                0%   { opacity: 0; transform: translate(-50%, -50%) scale(1); }
                5%   { opacity: 1; transform: translate(-50%, -50%) scale(1.05); } /* Fade in + zoom slightly */
                30%  { opacity: 1; transform: translate(-50%, -50%) scale(1.05); } /* Stay visible */
                35%  { opacity: 0; transform: translate(-50%, -50%) scale(1); } /* Fade out + scale back */
                100% { opacity: 0; transform: translate(-50%, -50%) scale(1); }
            }
        </style>

    </div>
</x-layouts.app>
