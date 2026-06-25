<!DOCTYPE html>
<html lang="{{ session('locale', str_replace('_', '-', app()->getLocale())) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Mitra Usaha 2') }}</title>

        {{-- FAVICON TAB BROWSER --}}
        @php
            $logoPath = public_path('storage/logos/logo-utama.png');
            $faviconUrl = file_exists($logoPath) ? asset('storage/logos/logo-utama.png') . '?v=' . filemtime($logoPath) : asset('images/mu2.jpeg');
        @endphp
        <link rel="icon" href="{{ $faviconUrl }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <style>
            /* ANIMASI & SHADOW GLOBAL UNTUK BUTTON & CARD (100% CSS MURNI) */
            
            /* 1. Transisi dasar */
            button, a, .shadow-md, .shadow-sm, .card, div[onclick], .cursor-pointer, [class*="card-shadow-"] {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            }

            /* 2. Pengecualian */
            button:disabled, .cursor-not-allowed {
                box-shadow: none !important;
                transform: none !important;
            }

            /* 3. Shadow Dinamis (Super Spesifik agar tidak tertimpa Tailwind JIT) */
            
            /* RED */
            html body .card-shadow-red:hover { box-shadow: 0 12px 25px -5px rgba(220, 38, 38, 0.5), 0 8px 10px -6px rgba(220, 38, 38, 0.3) !important; transform: translateY(-3px) !important; }
            html.dark body .card-shadow-red:hover { box-shadow: 0 12px 25px -5px rgba(248, 113, 113, 0.5) !important; }

            /* BLUE */
            html body .card-shadow-blue:hover { box-shadow: 0 12px 25px -5px rgba(37, 99, 235, 0.5), 0 8px 10px -6px rgba(37, 99, 235, 0.3) !important; transform: translateY(-3px) !important; }
            html.dark body .card-shadow-blue:hover { box-shadow: 0 12px 25px -5px rgba(96, 165, 250, 0.5) !important; }

            /* EMERALD / GREEN */
            html body .card-shadow-emerald:hover { box-shadow: 0 12px 25px -5px rgba(16, 185, 129, 0.5), 0 8px 10px -6px rgba(16, 185, 129, 0.3) !important; transform: translateY(-3px) !important; }
            html.dark body .card-shadow-emerald:hover { box-shadow: 0 12px 25px -5px rgba(52, 211, 153, 0.5) !important; }

            /* ORANGE */
            html body .card-shadow-orange:hover { box-shadow: 0 12px 25px -5px rgba(249, 115, 22, 0.5), 0 8px 10px -6px rgba(249, 115, 22, 0.3) !important; transform: translateY(-3px) !important; }
            html.dark body .card-shadow-orange:hover { box-shadow: 0 12px 25px -5px rgba(251, 146, 60, 0.5) !important; }

            /* TEAL */
            html body .card-shadow-teal:hover { box-shadow: 0 12px 25px -5px rgba(20, 184, 166, 0.5), 0 8px 10px -6px rgba(20, 184, 166, 0.3) !important; transform: translateY(-3px) !important; }
            html.dark body .card-shadow-teal:hover { box-shadow: 0 12px 25px -5px rgba(45, 212, 191, 0.5) !important; }

            /* PURPLE */
            html body .card-shadow-purple:hover { box-shadow: 0 12px 25px -5px rgba(168, 85, 247, 0.5), 0 8px 10px -6px rgba(168, 85, 247, 0.3) !important; transform: translateY(-3px) !important; }
            html.dark body .card-shadow-purple:hover { box-shadow: 0 12px 25px -5px rgba(192, 132, 252, 0.5) !important; }

            /* GRAY / BLACK */
            html body .card-shadow-gray:hover { box-shadow: 0 12px 25px -5px rgba(55, 65, 81, 0.5), 0 8px 10px -6px rgba(55, 65, 81, 0.3) !important; transform: translateY(-3px) !important; }
            html.dark body .card-shadow-gray:hover { box-shadow: 0 12px 25px -5px rgba(156, 163, 175, 0.3) !important; }
            
            /* Fallback generic shadow untuk div/button yang belum ada spesifik color */
            html body div.shadow-md:not([class*="card-shadow-"]):not(.cursor-not-allowed):hover,
            html body button.shadow-md:not([class*="card-shadow-"]):not(.cursor-not-allowed):hover {
                box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.15) !important;
                transform: translateY(-3px) !important;
            }
            html.dark body div.shadow-md:not([class*="card-shadow-"]):not(.cursor-not-allowed):hover,
            html.dark body button.shadow-md:not([class*="card-shadow-"]):not(.cursor-not-allowed):hover {
                box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.4) !important;
            }
        </style>
    </head>
    
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
        
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
            {{-- @include('layouts.navigation') --}}

            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow dark:shadow-none border-b border-transparent dark:border-gray-700 transition-colors duration-300">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                {{ $slot }}
            </main>
            
        </div>

        @if(session('access_denied_popup'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const isDark = document.documentElement.classList.contains('dark');
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak!',
                    text: 'Maaf, Anda tidak memiliki hak akses untuk membuka halaman ini.',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    background: isDark ? '#1f2937' : '#fff',
                    color: isDark ? '#f3f4f6' : '#545454'
                });
            });
        </script>
        @endif
        <script>
            // Fitur Global: Tahan Tombol (Click & Hold) untuk Tombol Plus/Minus
            document.addEventListener('DOMContentLoaded', function() {
                let holdTimer = null;
                let intervalTimer = null;
                let isHolding = false;

                function startHold(e, btn) {
                    isHolding = false;
                    holdTimer = setTimeout(() => {
                        isHolding = true;
                        let onclickStr = btn.getAttribute('onclick');
                        if(!onclickStr) return;
                        
                        let executeAdjust = new Function('event', onclickStr);
                        intervalTimer = setInterval(() => {
                            executeAdjust.call(btn, e);
                        }, 80); // Ulangi setiap 80ms
                    }, 400); // Mulai setelah ditahan 400ms
                }

                function stopHold() {
                    if (holdTimer) clearTimeout(holdTimer);
                    if (intervalTimer) clearInterval(intervalTimer);
                }

                // Mouse Events (Desktop)
                document.body.addEventListener('mousedown', function(e) {
                    let btn = e.target.closest('button[onclick^="adjust"]');
                    if (btn) startHold(e, btn);
                });
                document.body.addEventListener('mouseup', stopHold);
                document.body.addEventListener('mouseleave', stopHold);

                // Touch Events (Mobile)
                document.body.addEventListener('touchstart', function(e) {
                    let btn = e.target.closest('button[onclick^="adjust"]');
                    if (btn) startHold(e, btn);
                }, {passive: true});
                document.body.addEventListener('touchend', stopHold);
                document.body.addEventListener('touchcancel', stopHold);

                // Cegah klik ekstra saat dilepas (jika sebelumnya tombol ditahan)
                document.body.addEventListener('click', function(e) {
                    let btn = e.target.closest('button[onclick^="adjust"]');
                    if (btn && isHolding) {
                        e.preventDefault();
                        e.stopPropagation();
                        isHolding = false; // reset
                    }
                }, true); // Gunakan fase capture
            });
        </script>
    </body>
</html>