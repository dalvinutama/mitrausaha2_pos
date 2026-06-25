<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="description" content="Mitra Usaha 2 - Penyedia alat dan bahan bangunan terpercaya, berkualitas, dan berdaya saing tinggi di Pontianak dan sekitarnya.">
    <meta name="keywords" content="Toko Bangunan, Material Bangunan, Semen, Besi Baja, Pontianak, Konstruksi, Jl Re Martadinata, Arsitektur, Kontraktor">
    <meta name="author" content="Mitra Usaha 2">
    <meta name="theme-color" content="#111115">
    
    <title>{{ config('aplikasi.nama_aplikasi', 'Mitra Usaha 2') }} - Material Bangunan Premium & Solusi Konstruksi</title>
    @php
        $logoPath = public_path('storage/logos/logo-utama.png');
        $faviconUrl = file_exists($logoPath) ? asset('storage/logos/logo-utama.png') . '?v=' . filemtime($logoPath) : asset('images/mu2.jpeg');
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['Figtree', 'sans-serif'] 
                    },
                    colors: {
                        brand: { 
                            red: '#D00000', 
                            redhover: '#a30000',
                            dark: '#111115', 
                            gray: '#f4f5f7', 
                            text: '#2d2d30',
                            accent: '#ffeded'
                        }
                    },
                    animation: {
                        'slow-pan': 'panImage 40s linear infinite alternate',
                        'float-slow': 'float 8s ease-in-out infinite',
                        'float-fast': 'float 5s ease-in-out infinite',
                        'spin-slow': 'spin 20s linear infinite',
                        'pulse-slow': 'pulse 6s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'marquee': 'marquee 40s linear infinite',
                        'marquee-reverse': 'marqueeRev 40s linear infinite',
                        'bounce-x': 'bounceX 2s infinite',
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                    },
                    keyframes: {
                        panImage: { 
                            '0%': { objectPosition: '0% 50%', transform: 'scale(1.05)' }, 
                            '100%': { objectPosition: '100% 50%', transform: 'scale(1.15)' } 
                        },
                        float: { 
                            '0%, 100%': { transform: 'translateY(0)' }, 
                            '50%': { transform: 'translateY(-20px)' } 
                        },
                        marquee: {
                            '0%': { transform: 'translateX(0%)' },
                            '100%': { transform: 'translateX(-50%)' }
                        },
                        marqueeRev: {
                            '0%': { transform: 'translateX(-50%)' },
                            '100%': { transform: 'translateX(0%)' }
                        },
                        bounceX: {
                            '0%, 100%': { transform: 'translateX(0)' },
                            '50%': { transform: 'translateX(8px)' }
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* ========================================================
           GLOBAL UTILITIES & FIXES
           ======================================================== */
        body { 
            -webkit-font-smoothing: antialiased; 
            -moz-osx-font-smoothing: grayscale; 
            background-color: #ffffff;
        }

        /* Wrapper untuk mencegah scroll horizontal (Fix Bug Android) */
        .app-wrapper {
            position: relative;
            width: 100%;
            overflow-x: hidden;
        }

        /* Custom Scrollbar Premium */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f8f9fa; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #D00000; }

        /* ========================================================
           NAVIGATION & HEADER
           ======================================================== */
        .nav-scrolled { 
            background-color: rgba(255, 255, 255, 0.98); 
            backdrop-filter: blur(15px); 
            border-bottom: 1px solid rgba(0,0,0,0.05); 
            box-shadow: 0 10px 40px rgba(0,0,0,0.04); 
            top: 0 !important;
        }
        .topbar-hidden { transform: translateY(-100%); opacity: 0; }

        /* ========================================================
           BACKGROUND PATTERNS & SVGS
           ======================================================== */
        .bg-blueprint { 
            background-image: linear-gradient(rgba(0, 0, 0, 0.03) 1px, transparent 1px), 
                              linear-gradient(90deg, rgba(0, 0, 0, 0.03) 1px, transparent 1px); 
            background-size: 40px 40px; 
        }
        .bg-blueprint-dark { 
            background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px), 
                              linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px); 
            background-size: 40px 40px; 
        }
        .text-circle { 
            font-family: 'Figtree', sans-serif; font-size: 11.5px; font-weight: 900; 
            letter-spacing: 4px; text-transform: uppercase; fill: #D00000; 
        }

        /* ========================================================
           EPL STYLE ANIMATION - CARDS (SWEEP & POP)
           ======================================================== */
        .epl-card {
            position: relative; overflow: hidden; z-index: 1; cursor: pointer;
            border: 1px solid #e5e7eb; background: #ffffff;
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.5s ease, border-color 0.3s;
        }
        .epl-card::before {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-color: #D00000; 
            z-index: -1; transform-origin: left; transform: scaleX(0);
            transition: transform 0.5s cubic-bezier(0.19, 1, 0.22, 1);
        }
        .epl-card.sweep-dark::before { background-color: #111115; }
        .epl-card.sweep-gray::before { background-color: #f4f5f7; }
        
        .epl-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            border-color: transparent !important;
        }
        .epl-card:hover::before { transform: scaleX(1); }
        .epl-card:active { transform: translateY(-2px) scale(0.98); }
        
        .epl-card *, .epl-card i { transition: color 0.3s ease, transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .epl-card:hover h3, .epl-card:hover p, .epl-card:hover span, .epl-card:hover h4, .epl-card:hover strong, .epl-card:hover li, .epl-card:hover div:not(.bg-icon):not(.keep-color) {
            color: #ffffff !important;
        }
        .epl-card.sweep-gray:hover h3, .epl-card.sweep-gray:hover p, .epl-card.sweep-gray:hover span, .epl-card.sweep-gray:hover h4, .epl-card.sweep-gray:hover strong {
            color: #111115 !important;
        }
        
        .epl-card:hover i:not(.bg-icon):not(.keep-color) { color: #ffffff !important; }
        .epl-card:hover .epl-icon { transform: scale(1.2) rotate(10deg); }
        .epl-card .bg-icon { transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1); }
        .epl-card:hover .bg-icon { color: rgba(255, 255, 255, 0.08) !important; transform: scale(1.3) rotate(-15deg); }

        /* ========================================================
           EPL STYLE ANIMATION - BUTTONS
           ======================================================== */
        .epl-btn {
            position: relative; overflow: hidden; z-index: 1; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s ease;
        }
        .epl-btn::before {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1; transform-origin: left; transform: scaleX(0);
            transition: transform 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        }
        .epl-btn.bg-brand-red::before { background-color: #111115; } 
        .epl-btn.bg-brand-dark::before { background-color: #D00000; } 
        .epl-btn.bg-white::before { background-color: #D00000; } 
        .epl-btn.border-white::before { background-color: #ffffff; } 
        
        .epl-btn:hover { transform: translateY(-4px); box-shadow: 0 15px 25px -5px rgba(0,0,0,0.2); }
        .epl-btn:hover::before { transform: scaleX(1); }
        .epl-btn:active { transform: scale(0.95); }
        
        .epl-btn *, .epl-btn i { transition: all 0.3s ease; }
        .epl-btn:hover, .epl-btn:hover span { color: #ffffff !important; }
        .epl-btn.border-white:hover, .epl-btn.border-white:hover span { color: #111115 !important; }
        .epl-btn.border-white:hover i { color: #111115 !important; }
        .epl-btn:hover i.epl-icon-slide { transform: translateX(6px); }

        /* ========================================================
           GALLERY, MAPS & FAQ
           ======================================================== */
        .category-card { overflow: hidden; position: relative; cursor: pointer; border-radius: 1.5rem; }
        .category-card img { transition: transform 1.2s cubic-bezier(0.2, 0.8, 0.2, 1); }
        .category-card:hover img { transform: scale(1.12); }
        .category-card .overlay { background: linear-gradient(to top, rgba(17,17,21,0.95) 0%, rgba(17,17,21,0.4) 50%, transparent 100%); transition: opacity 0.4s ease; }
        .category-card .content-hover { opacity: 0; transform: translateY(20px); transition: all 0.4s ease; }
        .category-card:hover .content-hover { opacity: 1; transform: translateY(0); }

        .faq-content { transition: max-height 0.4s ease, opacity 0.4s ease, padding 0.4s ease; max-height: 0; opacity: 0; overflow: hidden; padding-top: 0; }
        .faq-content.open { max-height: 500px; opacity: 1; padding-top: 1.5rem; }
        
        #btn-back-to-top { transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); opacity: 0; visibility: hidden; transform: translateY(30px); }
        #btn-back-to-top.show { opacity: 1; visibility: visible; transform: translateY(0); }
        
        /* Layout Utils */
        .glass-panel { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="antialiased bg-white text-brand-text selection:bg-brand-red selection:text-white">

    <div class="app-wrapper" id="app-wrapper">

        <div id="topbar" class="hidden md:block bg-brand-dark text-gray-400 py-2.5 px-6 border-b border-white/5 transition-all duration-500 absolute w-full z-50">
            <div class="max-w-[1400px] mx-auto flex justify-between items-center text-[11px] font-bold tracking-widest uppercase">
                <div class="flex items-center gap-6">
                    <a href="https://maps.google.com/?q=-0.019773491600085204,109.31349080139726" target="_blank" class="flex items-center gap-2 hover:text-white transition-colors cursor-pointer">
                        <i class="fas fa-map-marker-alt text-brand-red"></i> Jl. Re.Martadinata, Pontianak
                    </a>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-clock text-brand-red"></i> Senin - Sabtu: 08:00 - 17:00
                    </span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="mailto:info@mitrausaha2.com" class="flex items-center gap-2 hover:text-white transition-colors"><i class="fas fa-envelope text-brand-red"></i> info@mitrausaha2.com</a>
                    <a href="tel:0895374156688" class="flex items-center gap-2 hover:text-white transition-colors"><i class="fas fa-phone-alt text-brand-red"></i> 0895-3741-56688</a>
                    <div class="flex items-center gap-3 border-l border-gray-700 pl-6 ml-2">
                        <a href="#" class="hover:text-brand-red transition-colors" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="hover:text-brand-red transition-colors" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <nav id="navbar" class="fixed w-full z-40 transition-all duration-500 py-4 md:py-6 top-0 md:top-10 border-b border-white/5 md:border-none">
            <div class="max-w-[1400px] mx-auto px-5 md:px-12 flex justify-between items-center">
                
                <div class="flex items-center gap-3 md:gap-4 cursor-pointer group" onclick="window.scrollTo(0,0)">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-white rounded-none flex items-center justify-center overflow-hidden border border-gray-200 shadow-md group-hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('storage/logos/logo-utama.png') }}" onerror="this.src='{{ asset('images/mu2.jpeg') }}'" alt="Logo Mitra Usaha 2" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col">
                        <h1 id="nav-brand" class="font-black text-xl md:text-2xl tracking-tighter text-white transition-colors duration-500 leading-none">
                            {{ config('aplikasi.nama_aplikasi', 'Mitra Usaha 2') }}
                        </h1>
                        <span id="nav-tagline" class="text-[9px] md:text-[10px] font-bold text-gray-400 tracking-[0.2em] uppercase mt-1 transition-colors duration-500 hidden sm:block">Building Materials</span>
                    </div>
                </div>

                <div class="hidden lg:flex items-center gap-8 bg-brand-dark/40 backdrop-blur-md px-8 py-3 rounded-full border border-white/10" id="nav-menu-container">
                    <a href="#beranda" class="nav-item text-sm font-bold text-white hover:text-brand-red transition-colors">Beranda</a>
                    <a href="#tentang" class="nav-item text-sm font-bold text-white hover:text-brand-red transition-colors">Tentang Kami</a>
                    <a href="#layanan" class="nav-item text-sm font-bold text-white hover:text-brand-red transition-colors">Layanan</a>
                    <a href="#produk" class="nav-item text-sm font-bold text-white hover:text-brand-red transition-colors">Katalog</a>
                    <a href="#faq" class="nav-item text-sm font-bold text-white hover:text-brand-red transition-colors">FAQ</a>
                </div>

                <div class="hidden lg:flex items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="epl-btn bg-brand-red text-white font-black uppercase tracking-widest px-6 py-3 text-xs rounded-full shadow-lg">
                            <i class="fas fa-columns mr-2"></i> <span>Dashboard Sistem</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" id="nav-login" class="epl-btn bg-white text-brand-dark border border-transparent font-black uppercase tracking-widest px-6 py-3 text-xs rounded-full shadow-lg hover:bg-gray-100">
                            <i class="fas fa-lock epl-icon-slide text-brand-red"></i> <span class="ml-1">Portal Staff</span>
                        </a>
                    @endauth
                </div>

                <button id="mobile-menu-btn" class="lg:hidden w-10 h-10 flex flex-col justify-center items-center gap-1.5 focus:outline-none group z-50">
                    <span class="w-6 h-[2px] bg-white transition-all duration-300 group-hover:bg-brand-red nav-line"></span>
                    <span class="w-6 h-[2px] bg-white transition-all duration-300 group-hover:bg-brand-red nav-line"></span>
                    <span class="w-4 h-[2px] bg-white transition-all duration-300 group-hover:bg-brand-red nav-line self-end"></span>
                </button>
            </div>
        </nav>

        <div id="mobile-menu" class="fixed inset-0 bg-brand-dark z-[60] transform translate-x-full transition-transform duration-500 flex flex-col justify-between pt-24 pb-10 px-8 lg:hidden">
            <button id="close-menu-btn" class="absolute top-6 right-6 w-12 h-12 bg-white/5 rounded-full text-white text-xl flex items-center justify-center hover:bg-brand-red transition-colors"><i class="fas fa-times"></i></button>
            
            <div class="flex flex-col gap-6 mt-10">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-2 border-b border-white/10 pb-4">Menu Navigasi</p>
                <a href="#beranda" class="mobile-link text-3xl font-black text-white hover:text-brand-red transition-colors tracking-tight flex items-center justify-between group">
                    <span>Beranda</span> <i class="fas fa-arrow-right text-sm text-gray-600 group-hover:text-brand-red group-hover:translate-x-2 transition-all"></i>
                </a>
                <a href="#tentang" class="mobile-link text-3xl font-black text-white hover:text-brand-red transition-colors tracking-tight flex items-center justify-between group">
                    <span>Visi Misi & Profil</span> <i class="fas fa-arrow-right text-sm text-gray-600 group-hover:text-brand-red group-hover:translate-x-2 transition-all"></i>
                </a>
                <a href="#layanan" class="mobile-link text-3xl font-black text-white hover:text-brand-red transition-colors tracking-tight flex items-center justify-between group">
                    <span>Layanan Kami</span> <i class="fas fa-arrow-right text-sm text-gray-600 group-hover:text-brand-red group-hover:translate-x-2 transition-all"></i>
                </a>
                <a href="#produk" class="mobile-link text-3xl font-black text-white hover:text-brand-red transition-colors tracking-tight flex items-center justify-between group">
                    <span>Katalog Material</span> <i class="fas fa-arrow-right text-sm text-gray-600 group-hover:text-brand-red group-hover:translate-x-2 transition-all"></i>
                </a>
                <a href="#lokasi" class="mobile-link text-3xl font-black text-white hover:text-brand-red transition-colors tracking-tight flex items-center justify-between group">
                    <span>Peta Lokasi</span> <i class="fas fa-arrow-right text-sm text-gray-600 group-hover:text-brand-red group-hover:translate-x-2 transition-all"></i>
                </a>
            </div>
            
            <div class="mt-auto pt-10">
                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 text-center">
                    <i class="fas fa-shield-alt text-3xl text-gray-600 mb-4 block"></i>
                    <h4 class="text-white font-bold mb-2">Area Sistem Internal</h4>
                    <p class="text-xs text-gray-400 mb-6 leading-relaxed">Akses ke dalam sistem manajemen inventaris dilindungi enkripsi. Khusus staf resmi.</p>
                    
                    @auth
                        <a href="{{ url('/dashboard') }}" class="epl-btn bg-brand-red text-white font-black px-6 py-4 text-sm uppercase tracking-widest rounded-xl w-full">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="epl-btn bg-white text-brand-dark font-black px-6 py-4 text-sm uppercase tracking-widest rounded-xl w-full">Masuk Portal</a>
                    @endauth
                </div>
            </div>
        </div>

        <section id="beranda" class="relative min-h-[100svh] w-full flex flex-col justify-center items-center text-center bg-brand-dark overflow-hidden pt-20 pb-20">
            <div class="absolute inset-0 w-full h-full">
                <img src="{{ asset('images/hero-industrial.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1541888086225-eb430c5d56b0?auto=format&fit=crop&w=2000&q=80'" alt="Warehouse Background" class="w-full h-full object-cover opacity-30 animate-slow-pan">
                <div class="absolute inset-0 bg-gradient-to-b from-brand-dark/95 via-brand-dark/60 to-brand-dark/95"></div>
                <div class="absolute inset-0 bg-blueprint-dark opacity-20"></div>
            </div>

            <div class="relative z-10 px-5 max-w-[1200px] mx-auto w-full flex flex-col items-center justify-center mt-10">
                
                <div data-aos="fade-down" data-aos-duration="1000">
                    <div class="inline-flex items-center gap-3 py-2 px-5 bg-white/5 backdrop-blur-md border border-white/10 rounded-full text-white text-[10px] md:text-xs font-bold tracking-[0.2em] uppercase mb-8 shadow-[0_0_20px_rgba(208,0,0,0.15)]">
                        <span class="w-2 h-2 rounded-full bg-brand-red animate-pulse"></span>
                        Supplier Konstruksi Terpercaya Pontianak
                    </div>
                </div>
                
                <h1 data-aos="fade-up" data-aos-duration="1200" data-aos-delay="200" class="text-4xl sm:text-5xl md:text-7xl lg:text-[6.5rem] font-black text-white tracking-tighter leading-[1.05] mb-6 drop-shadow-2xl">
                    Membangun <span class="text-brand-red">Pondasi</span> <br class="hidden sm:block" />
                    Masa Depan Kota.
                </h1>
                
                <p data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400" class="text-sm sm:text-base md:text-xl lg:text-2xl text-gray-400 font-medium max-w-3xl mx-auto mb-10 md:mb-12 leading-relaxed px-2">
                    Mitra Usaha 2 menghadirkan material bangunan kelas industri dengan standar SNI. Kami menjamin kualitas, ketersediaan stok riil, dan ketepatan waktu pengiriman untuk menyukseskan setiap proyek Anda.
                </p>
                
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600" class="flex flex-col sm:flex-row justify-center items-center gap-4 w-full px-4 sm:px-0">
                    <a href="#produk" class="epl-btn bg-brand-red text-white font-black text-xs md:text-sm tracking-widest uppercase px-8 md:px-10 py-4 md:py-5 rounded-full w-full sm:w-auto shadow-[0_10px_30px_rgba(208,0,0,0.3)] hover:shadow-[0_15px_40px_rgba(208,0,0,0.4)]">
                        <i class="fas fa-cubes epl-icon-slide mr-2"></i> Eksplorasi Katalog
                    </a>
                    <a href="#layanan" class="epl-btn bg-white/5 backdrop-blur-sm border border-white/20 text-white font-black text-xs md:text-sm tracking-widest uppercase px-8 md:px-10 py-4 md:py-5 rounded-full w-full sm:w-auto hover:bg-white hover:text-brand-dark transition-all">
                        Pelajari Layanan Kami
                    </a>
                </div>
            </div>
            
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-gray-500 animate-bounce cursor-pointer flex flex-col items-center hover:text-white transition-colors z-20" onclick="document.getElementById('stats-section').scrollIntoView()">
                <div class="w-6 h-10 border-2 border-gray-500 rounded-full flex justify-center p-1 mb-2">
                    <div class="w-1 h-2 bg-gray-500 rounded-full animate-ping"></div>
                </div>
            </div>
        </section>

        <section class="bg-brand-gray relative z-20 shadow-inner overflow-hidden border-b border-gray-200">
            <div class="absolute -left-20 top-0 w-[300px] h-[300px] bg-brand-red/5 rounded-full blur-[80px] z-0"></div>

            <div class="max-w-[1400px] mx-auto px-5 md:px-12 py-16 md:py-24 relative z-10" id="stats-section">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-8 text-left">
                    
                    <div class="epl-card sweep-dark bg-white border border-gray-200 p-6 md:p-8 rounded-[1.5rem] md:rounded-[2rem] h-56 md:h-72 flex flex-col justify-end" data-aos="fade-up" data-aos-delay="0">
                        <div class="absolute -right-4 -top-4 md:-right-8 md:-top-8 text-[8rem] md:text-[11rem] text-gray-50 z-0 bg-icon"><i class="fas fa-shield-check"></i></div>
                        <div class="relative z-10">
                            <div class="text-3xl md:text-4xl text-brand-red mb-4 md:mb-6 epl-icon"><i class="fas fa-shield-check"></i></div>
                            <h3 class="text-4xl md:text-5xl lg:text-6xl font-black text-brand-dark mb-1 md:mb-2 tracking-tighter flex items-center gap-2 md:gap-3">
                                <span><span class="counter" data-target="100">0</span>%</span>
                            </h3>
                            <p class="text-[10px] md:text-[12px] font-black text-gray-500 uppercase tracking-widest mt-1">Kualitas Terjamin</p>
                        </div>
                    </div>
                    
                    <div class="epl-card bg-brand-red border border-brand-red p-6 md:p-8 rounded-[1.5rem] md:rounded-[2rem] h-56 md:h-72 flex flex-col justify-end" data-aos="fade-up" data-aos-delay="100">
                        <div class="absolute -right-4 -top-4 md:-right-8 md:-top-8 text-[8rem] md:text-[11rem] text-white/10 z-0 bg-icon"><i class="fas fa-bolt"></i></div>
                        <div class="relative z-10">
                            <div class="text-3xl md:text-4xl text-white mb-4 md:mb-6 epl-icon"><i class="fas fa-bolt"></i></div>
                            <h3 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-1 md:mb-2 tracking-tighter">24/7</h3>
                            <p class="text-[10px] md:text-[12px] font-black text-white/80 uppercase tracking-widest mt-1">Sinkronisasi Stok</p>
                        </div>
                    </div>
                    
                    <div class="epl-card sweep-dark bg-white border border-gray-200 p-6 md:p-8 rounded-[1.5rem] md:rounded-[2rem] h-56 md:h-72 flex flex-col justify-end" data-aos="fade-up" data-aos-delay="200">
                        <div class="absolute -right-4 -top-4 md:-right-8 md:-top-8 text-[8rem] md:text-[11rem] text-gray-50 z-0 bg-icon"><i class="fas fa-truck-fast"></i></div>
                        <div class="relative z-10">
                            <div class="text-3xl md:text-4xl text-brand-red mb-4 md:mb-6 epl-icon"><i class="fas fa-truck-fast"></i></div>
                            <h3 class="text-4xl md:text-5xl lg:text-6xl font-black text-brand-dark mb-1 md:mb-2 tracking-tighter"><span class="counter" data-target="24">0</span><span class="text-2xl md:text-3xl text-gray-400">jam</span></h3>
                            <p class="text-[10px] md:text-[12px] font-black text-gray-500 uppercase tracking-widest mt-1">Estimasi Pengiriman</p>
                        </div>
                    </div>
                    
                    <div class="epl-card bg-brand-dark border border-brand-dark p-6 md:p-8 rounded-[1.5rem] md:rounded-[2rem] h-56 md:h-72 flex flex-col justify-end" data-aos="fade-up" data-aos-delay="300">
                        <div class="absolute -right-4 -top-4 md:-right-8 md:-top-8 text-[8rem] md:text-[11rem] text-white/5 z-0 bg-icon"><i class="fas fa-handshake"></i></div>
                        <div class="relative z-10">
                            <div class="text-3xl md:text-4xl text-brand-red mb-4 md:mb-6 epl-icon"><i class="fas fa-handshake"></i></div>
                            <h3 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-1 md:mb-2 tracking-tighter">100+</h3>
                            <p class="text-[10px] md:text-[12px] font-black text-gray-400 uppercase tracking-widest mt-1">Supplier & Rekanan</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="tentang" class="py-16 md:py-24 lg:py-32 bg-white bg-blueprint relative overflow-hidden border-b border-gray-100">
            <div class="absolute right-0 top-0 w-1/2 h-full bg-brand-gray/50 -skew-x-12 transform origin-top-right z-0 hidden lg:block"></div>
            
            <svg class="absolute left-0 bottom-0 opacity-5 w-64 md:w-96 text-brand-dark" viewBox="0 0 100 100" fill="currentColor">
                <rect x="10" y="10" width="80" height="80" stroke="currentColor" stroke-width="2" fill="none" />
                <path d="M 10 50 L 90 50 M 50 10 L 50 90" stroke="currentColor" stroke-width="2" />
            </svg>

            <div class="max-w-[1400px] mx-auto px-5 md:px-12 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-center">
                    
                    <div class="lg:col-span-5" data-aos="fade-right" data-aos-duration="1000">
                        <div class="flex items-center gap-3 mb-4 md:mb-6">
                            <div class="h-[2px] md:h-[3px] w-8 md:w-12 bg-brand-red"></div>
                            <h2 class="text-brand-red font-black tracking-[0.15em] md:tracking-[0.2em] uppercase text-[10px] md:text-xs"><i class="fas fa-eye mr-2"></i> Profil Perusahaan</h2>
                        </div>
                        
                        <h3 class="text-3xl md:text-5xl font-black tracking-tight text-brand-dark leading-[1.15] mb-6 md:mb-8">
                            Kokoh Bersama <br />Membangun Peradaban.
                        </h3>
                        
                        <p class="text-sm md:text-base text-gray-500 font-medium leading-relaxed mb-6 text-justify">
                            Berdiri di pusat kota Pontianak, <strong class="text-brand-dark">Mitra Usaha 2</strong> bukan sekadar toko material biasa. Kami adalah mitra strategis bagi ratusan kontraktor, mandor, dan individu dalam merealisasikan proyek arsitektural mereka. 
                        </p>
                        <p class="text-sm md:text-base text-gray-500 font-medium leading-relaxed mb-8 text-justify">
                            Dari pasir pondasi hingga cat interior, kami mengkurasi produk dari *brand* terbaik. Ditambah integrasi manajemen digital, kami memastikan pesanan akurat dan pengiriman tepat waktu.
                        </p>

                        <div class="epl-card bg-brand-dark p-6 rounded-2xl border-l-4 border-l-brand-red shadow-xl mt-8">
                            <div class="flex gap-4">
                                <div class="text-3xl text-white/20 epl-icon"><i class="fas fa-eye"></i></div>
                                <div>
                                    <h4 class="font-black text-white uppercase tracking-widest text-xs mb-2">Visi Utama</h4>
                                    <p class="text-sm text-gray-400 font-medium leading-relaxed italic">"Menjadi pusat ritel dan grosir material bangunan paling modern, terintegrasi, dan terpercaya di Kalimantan Barat."</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-7 relative h-[350px] sm:h-[450px] md:h-[600px] w-full lg:ml-10 mt-8 lg:mt-0" data-aos="fade-left">
                        <div class="absolute top-0 right-0 w-[85%] h-[80%] rounded-[1.5rem] md:rounded-[2rem] overflow-hidden shadow-2xl z-10 border border-gray-200">
                            <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1000&q=80" alt="Proyek Konstruksi" class="w-full h-full object-cover filter contrast-110">
                            <div class="absolute inset-0 bg-brand-dark/10"></div>
                        </div>
                        
                        <div class="absolute bottom-0 left-0 w-[60%] h-[50%] rounded-[1.5rem] md:rounded-[2rem] overflow-hidden shadow-[0_20px_40px_rgba(0,0,0,0.3)] border-[4px] md:border-[8px] border-white z-20 animate-float-slow">
                            <img src="https://images.unsplash.com/photo-1587293852726-70cdb56c2866?auto=format&fit=crop&w=600&q=80" alt="Stok Gudang" class="w-full h-full object-cover">
                        </div>

                        <div class="absolute top-[50%] left-[-5%] bg-white p-4 md:p-6 rounded-2xl shadow-xl z-30 flex items-center gap-4 animate-bounce-x border border-gray-100 hidden sm:flex">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-red rounded-full flex items-center justify-center text-white text-lg md:text-xl"><i class="fas fa-check"></i></div>
                            <div>
                                <h4 class="font-black text-brand-dark text-sm md:text-base">Kualitas SNI</h4>
                                <p class="text-[10px] md:text-xs text-gray-500 font-bold">Lulus Uji Teknis</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-brand-dark relative border-b border-gray-800 overflow-hidden">
            <div class="absolute inset-0 bg-blueprint-dark opacity-30"></div>
            
            <div class="max-w-[1400px] mx-auto px-5 md:px-12 relative z-10">
                <div class="text-center mb-12 md:mb-20" data-aos="fade-up">
                    <h2 class="text-brand-red font-black tracking-[0.2em] uppercase text-xs mb-3">Fondasi Bisnis Kami</h2>
                    <h3 class="text-3xl md:text-5xl font-black tracking-tight text-white">6 Pilar Mitra Usaha 2</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    <div class="glass-panel p-8 md:p-10 rounded-[1.5rem] hover:bg-white/10 transition-colors group cursor-pointer" data-aos="fade-up" data-aos-delay="50">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-brand-red to-red-800 text-white rounded-2xl flex items-center justify-center text-xl md:text-2xl mb-6 shadow-lg group-hover:scale-110 transition-transform"><i class="fas fa-balance-scale"></i></div>
                        <h4 class="text-xl font-black mb-3 text-white">Integritas Takaran</h4>
                        <p class="text-gray-400 text-sm font-medium leading-relaxed">Kejujuran adalah modal utama. Kami memastikan panjang besi, berat semen, dan volume sesuai spesifikasi tanpa manipulasi.</p>
                    </div>
                    <div class="glass-panel p-8 md:p-10 rounded-[1.5rem] hover:bg-white/10 transition-colors group cursor-pointer" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-brand-red to-red-800 text-white rounded-2xl flex items-center justify-center text-xl md:text-2xl mb-6 shadow-lg group-hover:scale-110 transition-transform"><i class="fas fa-tachometer-alt"></i></div>
                        <h4 class="text-xl font-black mb-3 text-white">Agilitas Pengiriman</h4>
                        <p class="text-gray-400 text-sm font-medium leading-relaxed">Armada transportasi dikelola dengan sistem antrean pintar untuk memastikan barang tiba di lokasi proyek Anda tepat waktu.</p>
                    </div>
                    <div class="glass-panel p-8 md:p-10 rounded-[1.5rem] hover:bg-white/10 transition-colors group cursor-pointer" data-aos="fade-up" data-aos-delay="150">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-brand-red to-red-800 text-white rounded-2xl flex items-center justify-center text-xl md:text-2xl mb-6 shadow-lg group-hover:scale-110 transition-transform"><i class="fas fa-hand-holding-heart"></i></div>
                        <h4 class="text-xl font-black mb-3 text-white">Kemitraan Strategis</h4>
                        <p class="text-gray-400 text-sm font-medium leading-relaxed">Membangun kolaborasi solid jangka panjang dengan kontraktor, mandor, dan supplier untuk ekosistem konstruksi yang sehat.</p>
                    </div>
                    <div class="glass-panel p-8 md:p-10 rounded-[1.5rem] hover:bg-white/10 transition-colors group cursor-pointer" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-brand-red to-red-800 text-white rounded-2xl flex items-center justify-center text-xl md:text-2xl mb-6 shadow-lg group-hover:scale-110 transition-transform"><i class="fas fa-microchip"></i></div>
                        <h4 class="text-xl font-black mb-3 text-white">Inovasi Digital</h4>
                        <p class="text-gray-400 text-sm font-medium leading-relaxed">Menerapkan sistem IT terpusat untuk melacak mutasi stok, laporan keuangan, dan audit fisik gudang secara transparan.</p>
                    </div>
                    <div class="glass-panel p-8 md:p-10 rounded-[1.5rem] hover:bg-white/10 transition-colors group cursor-pointer" data-aos="fade-up" data-aos-delay="250">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-brand-red to-red-800 text-white rounded-2xl flex items-center justify-center text-xl md:text-2xl mb-6 shadow-lg group-hover:scale-110 transition-transform"><i class="fas fa-helmet-safety"></i></div>
                        <h4 class="text-xl font-black mb-3 text-white">Fokus Keamanan K3</h4>
                        <p class="text-gray-400 text-sm font-medium leading-relaxed">Keselamatan kerja diterapkan ketat dalam area pergudangan kami untuk melindungi para staf operasional.</p>
                    </div>
                    <div class="glass-panel p-8 md:p-10 rounded-[1.5rem] hover:bg-white/10 transition-colors group cursor-pointer" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-brand-red to-red-800 text-white rounded-2xl flex items-center justify-center text-xl md:text-2xl mb-6 shadow-lg group-hover:scale-110 transition-transform"><i class="fas fa-award"></i></div>
                        <h4 class="text-xl font-black mb-3 text-white">Kualitas Kurasi</h4>
                        <p class="text-gray-400 text-sm font-medium leading-relaxed">Kami memfilter secara selektif setiap *brand* yang masuk ke toko untuk memastikan mutu setara *grade* industri.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="layanan" class="py-16 md:py-24 bg-brand-gray border-b border-gray-200">
            <div class="max-w-[1400px] mx-auto px-5 md:px-12">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 md:mb-16 gap-6" data-aos="fade-up">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="h-[2px] w-8 bg-brand-red"></div>
                            <h2 class="text-brand-red font-black tracking-[0.2em] uppercase text-xs">Jasa & Pelayanan</h2>
                        </div>
                        <h3 class="text-3xl md:text-5xl font-black tracking-tight text-brand-dark">Solusi Menyeluruh.</h3>
                    </div>
                    <p class="text-gray-500 font-medium max-w-md text-left md:text-right text-sm md:text-base">
                        Tidak hanya menjual barang, kami memberikan nilai tambah melalui layanan konsultasi RAB hingga pengiriman.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="epl-card sweep-dark bg-white p-6 md:p-8 rounded-[1.5rem] md:rounded-[2rem] border border-gray-200 flex flex-col md:flex-row gap-5 md:gap-6 items-start" data-aos="fade-up" data-aos-delay="0">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-red-50 text-brand-red rounded-full flex items-center justify-center text-2xl md:text-3xl shrink-0 epl-icon-bg"><i class="fas fa-boxes-packing epl-icon"></i></div>
                        <div>
                            <h4 class="text-xl md:text-2xl font-black text-brand-dark mb-2 md:mb-3">Grosir & Eceran (Retail)</h4>
                            <p class="text-gray-500 text-xs md:text-sm font-medium leading-relaxed">Melayani pembelian partai besar untuk developer perumahan, hingga eceran untuk renovasi rumah dengan harga kompetitif.</p>
                        </div>
                    </div>
                    <div class="epl-card bg-brand-red p-6 md:p-8 rounded-[1.5rem] md:rounded-[2rem] flex flex-col md:flex-row gap-5 md:gap-6 items-start" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-black/20 text-white rounded-full flex items-center justify-center text-2xl md:text-3xl shrink-0 epl-icon-bg"><i class="fas fa-truck-fast epl-icon"></i></div>
                        <div>
                            <h4 class="text-xl md:text-2xl font-black text-white mb-2 md:mb-3">Logistik & Pengiriman Cepat</h4>
                            <p class="text-white/80 text-xs md:text-sm font-medium leading-relaxed">Armada dump truck dan pick-up siap mengantarkan material ke titik bongkar proyek Anda dengan aman di area Pontianak.</p>
                        </div>
                    </div>
                    <div class="epl-card sweep-dark bg-white p-6 md:p-8 rounded-[1.5rem] md:rounded-[2rem] border border-gray-200 flex flex-col md:flex-row gap-5 md:gap-6 items-start" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-red-50 text-brand-red rounded-full flex items-center justify-center text-2xl md:text-3xl shrink-0 epl-icon-bg"><i class="fas fa-calculator epl-icon"></i></div>
                        <div>
                            <h4 class="text-xl md:text-2xl font-black text-brand-dark mb-2 md:mb-3">Estimasi Kebutuhan RAB</h4>
                            <p class="text-gray-500 text-xs md:text-sm font-medium leading-relaxed">Tim operasional siap membantu menghitung estimasi kebutuhan bata, semen, atau atap berdasar ukuran lahan Anda.</p>
                        </div>
                    </div>
                    <div class="epl-card sweep-dark bg-white p-6 md:p-8 rounded-[1.5rem] md:rounded-[2rem] border border-gray-200 flex flex-col md:flex-row gap-5 md:gap-6 items-start" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-red-50 text-brand-red rounded-full flex items-center justify-center text-2xl md:text-3xl shrink-0 epl-icon-bg"><i class="fas fa-file-contract epl-icon"></i></div>
                        <div>
                            <h4 class="text-xl md:text-2xl font-black text-brand-dark mb-2 md:mb-3">Pre-Order Material Khusus</h4>
                            <p class="text-gray-500 text-xs md:text-sm font-medium leading-relaxed">Butuh besi ulir ukuran spesifik atau warna cat kustom? Kami menerima (Purchase Order) langsung dari pabrik rekanan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="produk" class="py-16 md:py-32 bg-white relative border-b border-gray-200">
            <div class="max-w-[1400px] mx-auto px-5 md:px-12">
                
                <div class="text-center mb-12 md:mb-20" data-aos="fade-up">
                    <h2 class="text-brand-red font-black tracking-[0.2em] uppercase text-xs mb-3">Eksplorasi Material</h2>
                    <h3 class="text-3xl md:text-5xl font-black tracking-tight text-brand-dark mb-4">Katalog Terlengkap.</h3>
                    <p class="text-gray-500 font-medium max-w-2xl mx-auto text-sm md:text-base px-2">
                        Lebih dari 2.000 SKU item tersedia. Mulai dari pondasi awal hingga sentuhan estetika akhir, temukan semuanya di sini.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 md:gap-6">
                    <div class="category-card h-[280px] md:h-[350px] group shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="0">
                        <img src="https://images.unsplash.com/photo-1589939705384-5185137a7f0f?auto=format&fit=crop&w=600&q=80" alt="Semen" class="absolute inset-0 w-full h-full object-cover">
                        <div class="overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full z-10">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-red rounded-xl flex items-center justify-center text-white text-lg md:text-xl mb-3 shadow-lg"><i class="fas fa-layer-group"></i></div>
                            <h4 class="text-xl md:text-2xl font-black text-white mb-2">Semen & Perekat</h4>
                            <div class="content-hover hidden sm:block">
                                <p class="text-gray-300 text-xs font-medium leading-relaxed line-clamp-2">Semen Portland, Semen Putih, acian, dan mortar instan.</p>
                            </div>
                        </div>
                    </div>

                    <div class="category-card h-[280px] md:h-[350px] group shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="50">
                        <img src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=600&q=80" alt="Besi" class="absolute inset-0 w-full h-full object-cover">
                        <div class="overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full z-10">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-white text-brand-dark rounded-xl flex items-center justify-center text-lg md:text-xl mb-3 shadow-lg"><i class="fas fa-cubes"></i></div>
                            <h4 class="text-xl md:text-2xl font-black text-white mb-2">Besi Beton & Baja</h4>
                            <div class="content-hover hidden sm:block">
                                <p class="text-gray-300 text-xs font-medium leading-relaxed line-clamp-2">Besi ulir, polos, siku, wiremesh, CNP, UNP standar SNI.</p>
                            </div>
                        </div>
                    </div>

                    <div class="category-card h-[280px] md:h-[350px] group shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="100">
                        <img src="https://images.unsplash.com/photo-1533090161767-e6ffed986c88?auto=format&fit=crop&w=600&q=80" alt="Kayu" class="absolute inset-0 w-full h-full object-cover">
                        <div class="overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full z-10">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-red rounded-xl flex items-center justify-center text-white text-lg md:text-xl mb-3 shadow-lg"><i class="fas fa-tree"></i></div>
                            <h4 class="text-xl md:text-2xl font-black text-white mb-2">Kayu & Plywood</h4>
                            <div class="content-hover hidden sm:block">
                                <p class="text-gray-300 text-xs font-medium leading-relaxed line-clamp-2">Papan, balok stuktural, triplek, multiplek, dan GRC board.</p>
                            </div>
                        </div>
                    </div>

                    <div class="category-card h-[280px] md:h-[350px] group shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="150">
                        <img src="https://images.unsplash.com/photo-1562259949-e8e7689d7828?auto=format&fit=crop&w=600&q=80" alt="Cat" class="absolute inset-0 w-full h-full object-cover">
                        <div class="overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full z-10">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-white text-brand-dark rounded-xl flex items-center justify-center text-lg md:text-xl mb-3 shadow-lg"><i class="fas fa-paint-roller"></i></div>
                            <h4 class="text-xl md:text-2xl font-black text-white mb-2">Cat & Plitur</h4>
                            <div class="content-hover hidden sm:block">
                                <p class="text-gray-300 text-xs font-medium leading-relaxed line-clamp-2">Cat interior, eksterior, anti-bocor, pelarut, dan kuas.</p>
                            </div>
                        </div>
                    </div>

                    <div class="category-card h-[280px] md:h-[350px] group shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="200">
                        <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80" alt="Atap" class="absolute inset-0 w-full h-full object-cover">
                        <div class="overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full z-10">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-white text-brand-dark rounded-xl flex items-center justify-center text-lg md:text-xl mb-3 shadow-lg"><i class="fas fa-home"></i></div>
                            <h4 class="text-xl md:text-2xl font-black text-white mb-2">Atap & Rangka</h4>
                            <div class="content-hover hidden sm:block">
                                <p class="text-gray-300 text-xs font-medium leading-relaxed line-clamp-2">Seng gelombang, genteng metal, asbes, spandek, baja ringan.</p>
                            </div>
                        </div>
                    </div>

                    <div class="category-card h-[280px] md:h-[350px] group shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="250">
                        <img src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=600&q=80" alt="Pipa" class="absolute inset-0 w-full h-full object-cover">
                        <div class="overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full z-10">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-red rounded-xl flex items-center justify-center text-white text-lg md:text-xl mb-3 shadow-lg"><i class="fas fa-faucet"></i></div>
                            <h4 class="text-xl md:text-2xl font-black text-white mb-2">Pipa & Plumbing</h4>
                            <div class="content-hover hidden sm:block">
                                <p class="text-gray-300 text-xs font-medium leading-relaxed line-clamp-2">Pipa PVC AW/D, fitting, lem pipa, kran air, tangki tandon.</p>
                            </div>
                        </div>
                    </div>

                    <div class="category-card h-[280px] md:h-[350px] group shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="300">
                        <img src="https://images.unsplash.com/photo-1523413363574-c30aa1c2a516?auto=format&fit=crop&w=600&q=80" alt="Keramik" class="absolute inset-0 w-full h-full object-cover">
                        <div class="overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full z-10">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-red rounded-xl flex items-center justify-center text-white text-lg md:text-xl mb-3 shadow-lg"><i class="fas fa-border-all"></i></div>
                            <h4 class="text-xl md:text-2xl font-black text-white mb-2">Keramik & Granit</h4>
                            <div class="content-hover hidden sm:block">
                                <p class="text-gray-300 text-xs font-medium leading-relaxed line-clamp-2">Keramik lantai, dinding, granit tile berbagai ukuran motif.</p>
                            </div>
                        </div>
                    </div>

                    <div class="category-card h-[280px] md:h-[350px] group shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="350">
                        <img src="https://images.unsplash.com/photo-1505015920881-0f83c2f7c95e?auto=format&fit=crop&w=600&q=80" alt="Alat Tukang" class="absolute inset-0 w-full h-full object-cover">
                        <div class="overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full z-10">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-white text-brand-dark rounded-xl flex items-center justify-center text-lg md:text-xl mb-3 shadow-lg"><i class="fas fa-toolbox"></i></div>
                            <h4 class="text-xl md:text-2xl font-black text-white mb-2">Perkakas Kerja</h4>
                            <div class="content-hover hidden sm:block">
                                <p class="text-gray-300 text-xs font-medium leading-relaxed line-clamp-2">Cangkul, sekop, meteran, waterpas, tang, palu, alat proyek.</p>
                            </div>
                        </div>
                    </div>

                    <div class="category-card h-[280px] md:h-[350px] group shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="400">
                        <img src="https://images.unsplash.com/photo-1558611997-f5b241c6d15a?auto=format&fit=crop&w=600&q=80" alt="Paku & Baut" class="absolute inset-0 w-full h-full object-cover">
                        <div class="overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full z-10">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-white text-brand-dark rounded-xl flex items-center justify-center text-lg md:text-xl mb-3 shadow-lg"><i class="fas fa-screwdriver-wrench"></i></div>
                            <h4 class="text-xl md:text-2xl font-black text-white mb-2">Paku & Fastener</h4>
                            <div class="content-hover hidden sm:block">
                                <p class="text-gray-300 text-xs font-medium leading-relaxed line-clamp-2">Paku beton, paku triplek, dynabolt, sekrup baja ringan.</p>
                            </div>
                        </div>
                    </div>

                    <div class="category-card h-[280px] md:h-[350px] group shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="450">
                        <img src="https://images.unsplash.com/photo-1544724569-5f546fd6f2b6?auto=format&fit=crop&w=600&q=80" alt="Elektrikal" class="absolute inset-0 w-full h-full object-cover">
                        <div class="overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full z-10">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-red rounded-xl flex items-center justify-center text-white text-lg md:text-xl mb-3 shadow-lg"><i class="fas fa-plug"></i></div>
                            <h4 class="text-xl md:text-2xl font-black text-white mb-2">Elektrikal Dasar</h4>
                            <div class="content-hover hidden sm:block">
                                <p class="text-gray-300 text-xs font-medium leading-relaxed line-clamp-2">Kabel listrik instalasi, stop kontak, saklar, dan fitting.</p>
                            </div>
                        </div>
                    </div>

                    <div class="category-card h-[280px] md:h-[350px] group shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="500">
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=600&q=80" alt="Pintu Jendela" class="absolute inset-0 w-full h-full object-cover">
                        <div class="overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full z-10">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-red rounded-xl flex items-center justify-center text-white text-lg md:text-xl mb-3 shadow-lg"><i class="fas fa-door-open"></i></div>
                            <h4 class="text-xl md:text-2xl font-black text-white mb-2">Pintu & Kunci</h4>
                            <div class="content-hover hidden sm:block">
                                <p class="text-gray-300 text-xs font-medium leading-relaxed line-clamp-2">Pintu kamar mandi, handle, engsel, gembok, dan aksesoris.</p>
                            </div>
                        </div>
                    </div>

                    <div class="category-card h-[280px] md:h-[350px] group shadow-md border border-gray-100" data-aos="fade-up" data-aos-delay="550">
                        <img src="https://images.unsplash.com/photo-1621360155021-98782a2b0ce8?auto=format&fit=crop&w=600&q=80" alt="Bata Hebel" class="absolute inset-0 w-full h-full object-cover">
                        <div class="overlay absolute inset-0"></div>
                        <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full z-10">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-white text-brand-dark rounded-xl flex items-center justify-center text-lg md:text-xl mb-3 shadow-lg"><i class="fas fa-cubes-stacked"></i></div>
                            <h4 class="text-xl md:text-2xl font-black text-white mb-2">Bata & Batako</h4>
                            <div class="content-hover hidden sm:block">
                                <p class="text-gray-300 text-xs font-medium leading-relaxed line-clamp-2">Bata merah press, batako, hebel (bata ringan) presisi tinggi.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-brand-gray border-t border-gray-200 overflow-hidden">
            <div class="max-w-[1400px] mx-auto px-5 md:px-12">
                <div class="text-center mb-12 md:mb-16" data-aos="fade-up">
                    <div class="flex items-center justify-center gap-3 mb-3">
                        <div class="h-[2px] w-6 bg-brand-red"></div>
                        <h2 class="text-brand-red font-black tracking-[0.2em] uppercase text-[10px] md:text-xs">Alur Kerja</h2>
                        <div class="h-[2px] w-6 bg-brand-red"></div>
                    </div>
                    <h3 class="text-3xl md:text-5xl font-black tracking-tight text-brand-dark">Proses Pembelian Transparan</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10 md:gap-8 relative">
                    <div class="hidden md:block absolute top-10 lg:top-14 left-[12%] right-[12%] h-1 bg-gray-200 z-0"></div>
                    
                    <div class="relative z-10 flex flex-col items-center text-center group" data-aos="fade-right" data-aos-delay="0">
                        <div class="w-20 h-20 md:w-28 md:h-28 bg-white border-[4px] md:border-[6px] border-brand-gray rounded-full flex items-center justify-center text-3xl md:text-4xl text-gray-300 mb-4 md:mb-6 shadow-sm group-hover:border-brand-red group-hover:text-brand-red transition-all duration-500 transform group-hover:scale-110 group-hover:-translate-y-2">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h4 class="text-lg md:text-xl font-black text-brand-dark mb-2">1. Pemesanan</h4>
                        <p class="text-xs md:text-sm text-gray-500 font-medium px-2 md:px-4 leading-relaxed">Pesan via WA atau toko. Kasir menginput langsung ke sistem.</p>
                    </div>
                    
                    <div class="relative z-10 flex flex-col items-center text-center group" data-aos="fade-right" data-aos-delay="100">
                        <div class="w-20 h-20 md:w-28 md:h-28 bg-white border-[4px] md:border-[6px] border-brand-gray rounded-full flex items-center justify-center text-3xl md:text-4xl text-gray-300 mb-4 md:mb-6 shadow-sm group-hover:border-brand-red group-hover:text-brand-red transition-all duration-500 transform group-hover:scale-110 group-hover:-translate-y-2 relative">
                            <i class="fas fa-clipboard-check"></i>
                            <div class="absolute -top-1 -right-1 bg-green-500 text-white w-6 h-6 md:w-9 md:h-9 rounded-full border-2 md:border-4 border-white flex items-center justify-center text-[10px] md:text-sm shadow-md animate-bounce-x">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        <h4 class="text-lg md:text-xl font-black text-brand-dark mb-2">2. Penyiapan Gudang</h4>
                        <p class="text-xs md:text-sm text-gray-500 font-medium px-2 md:px-4 leading-relaxed">Staf memverifikasi & menyiapkan material berdasar faktur digital.</p>
                    </div>
                    
                    <div class="relative z-10 flex flex-col items-center text-center group" data-aos="fade-right" data-aos-delay="200">
                        <div class="w-20 h-20 md:w-28 md:h-28 bg-white border-[4px] md:border-[6px] border-brand-gray rounded-full flex items-center justify-center text-3xl md:text-4xl text-gray-300 mb-4 md:mb-6 shadow-sm group-hover:border-brand-red group-hover:text-brand-red transition-all duration-500 transform group-hover:scale-110 group-hover:-translate-y-2">
                            <i class="fas fa-truck-loading"></i>
                        </div>
                        <h4 class="text-lg md:text-xl font-black text-brand-dark mb-2">3. Pengiriman Armada</h4>
                        <p class="text-xs md:text-sm text-gray-500 font-medium px-2 md:px-4 leading-relaxed">Armada khusus berangkat mengirim material ke lokasi proyek Anda.</p>
                    </div>

                    <div class="relative z-10 flex flex-col items-center text-center group" data-aos="fade-right" data-aos-delay="300">
                        <div class="w-20 h-20 md:w-28 md:h-28 bg-brand-dark border-[4px] md:border-[6px] border-brand-dark rounded-full flex items-center justify-center text-3xl md:text-4xl text-white mb-4 md:mb-6 shadow-xl group-hover:bg-brand-red group-hover:border-brand-red transition-all duration-500 transform group-hover:scale-110 group-hover:-translate-y-2 relative overflow-hidden">
                            <i class="fas fa-handshake-angle z-10"></i>
                        </div>
                        <h4 class="text-lg md:text-xl font-black text-brand-dark mb-2">4. Transaksi Selesai</h4>
                        <p class="text-xs md:text-sm text-gray-500 font-medium px-2 md:px-4 leading-relaxed">Tanda terima tervalidasi. Sistem otomatis memperbarui sisa stok.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="sistem" class="py-16 md:py-32 bg-brand-dark relative overflow-hidden border-b border-gray-800">
            <div class="absolute -right-20 -bottom-20 w-[300px] md:w-[600px] h-[300px] md:h-[600px] bg-brand-red/10 rounded-full blur-[80px] md:blur-[120px] animate-pulse-slow z-0"></div>
            <div class="absolute inset-0 bg-blueprint-dark opacity-20"></div>

            <div class="max-w-[1400px] mx-auto px-5 md:px-12 relative z-10">
                <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
                    
                    <div class="w-full lg:w-5/12 text-white" data-aos="fade-right" data-aos-duration="1000">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 md:px-4 md:py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-[9px] md:text-[10px] font-black uppercase tracking-widest mb-6 shadow-[0_0_15px_rgba(255,255,255,0.1)]">
                            <i class="fas fa-microchip text-brand-red"></i> Infrastruktur IT Internal
                        </div>
                        
                        <h2 class="text-3xl md:text-5xl lg:text-6xl font-black tracking-tight mb-5 leading-[1.1]">
                            Operasional dalam <br />
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-gray-300 to-gray-500">Genggaman.</span>
                        </h2>
                        
                        <p class="text-sm md:text-lg text-gray-400 font-medium mb-10 leading-relaxed">
                            Kami meninggalkan metode manual. Sistem Informasi Manajemen dirancang untuk memastikan akurasi mutasi persediaan, mempercepat pembuatan *Purchase Order*, dan meminimalisir kesalahan audit fisik gudang.
                        </p>
                        
                        <div class="space-y-4">
                            <div class="epl-card sweep-dark bg-white/5 border border-white/10 p-5 rounded-2xl flex items-center gap-5">
                                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center shrink-0 text-white text-xl epl-icon-bg"><i class="fas fa-database epl-icon"></i></div>
                                <div>
                                    <h4 class="font-black text-base text-white">Database Tersentralisasi</h4>
                                    <p class="text-xs text-gray-400 mt-1 font-medium">Stok terhubung otomatis dari POS kasir ke gudang.</p>
                                </div>
                            </div>
                            <div class="epl-card bg-brand-red border border-brand-red p-5 rounded-2xl flex items-center gap-5">
                                <div class="w-12 h-12 rounded-xl bg-black/20 flex items-center justify-center shrink-0 text-white text-xl epl-icon-bg"><i class="fas fa-bell epl-icon"></i></div>
                                <div>
                                    <h4 class="font-black text-base text-white">Peringatan Batas Stok (Reorder)</h4>
                                    <p class="text-xs text-white/80 mt-1 font-medium">Notifikasi cerdas saat item menipis.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full lg:w-7/12 mt-10 lg:mt-0" data-aos="fade-left" data-aos-duration="1200">
                        <div class="bg-brand-dark p-2 border-[4px] md:border-[6px] border-gray-800 shadow-[0_20px_50px_rgba(0,0,0,0.5)] animate-float-slow rounded-[1rem] md:rounded-[2rem] relative">
                            <div class="w-full bg-brand-dark h-6 rounded-t-md flex items-center px-3 gap-1.5 border-b border-gray-800">
                                <div class="w-2 h-2 rounded-full bg-gray-700"></div>
                                <div class="w-2 h-2 rounded-full bg-gray-700"></div>
                                <div class="w-2 h-2 rounded-full bg-gray-700"></div>
                            </div>
                            <img src="{{ asset('images/mockup-dashboard.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=Dashboard+System&background=111&color=fff&size=800'" alt="Mockup Sistem Internal" class="w-full h-auto rounded-b-md md:rounded-b-xl opacity-90 hover:opacity-100 transition-opacity">
                            
                            <div class="absolute -left-4 md:-left-8 bottom-6 md:bottom-12 bg-white border border-gray-200 px-4 py-3 rounded-xl md:rounded-2xl shadow-2xl flex items-center gap-3 md:gap-4 z-20">
                                <div class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-red opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-brand-red"></span>
                                </div>
                                <div>
                                    <span class="text-brand-dark font-black text-[10px] md:text-xs uppercase tracking-widest block">Live Production</span>
                                    <span class="text-gray-500 text-[9px] md:text-[10px] font-bold">24/7 Server Aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="faq" class="py-16 md:py-24 bg-brand-gray relative border-b border-gray-200">
            <div class="max-w-[1000px] mx-auto px-5 md:px-8">
                <div class="text-center mb-10 md:mb-16" data-aos="fade-up">
                    <div class="inline-flex items-center justify-center w-16 h-16 md:w-20 md:h-20 bg-white shadow-sm border border-gray-200 text-brand-red rounded-full mb-4 md:mb-6 text-2xl md:text-3xl">
                        <i class="fas fa-question"></i>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-black text-brand-dark tracking-tight">Tanya Jawab Umum</h2>
                    <p class="text-gray-500 mt-3 md:mt-4 font-medium text-sm md:text-lg">Panduan informasi seputar layanan operasional & kebijakan toko.</p>
                </div>

                <div class="space-y-3 md:space-y-4">
                    <div class="epl-card sweep-dark bg-white border border-gray-200 rounded-[1rem] md:rounded-[1.5rem] p-5 md:p-6" data-aos="fade-up" data-aos-delay="0" onclick="toggleFaq('faq1', this)">
                        <div class="flex justify-between items-center cursor-pointer">
                            <h4 class="font-black text-sm md:text-lg text-brand-dark flex items-center gap-3 w-5/6"><i class="fas fa-clock text-gray-300 text-lg md:text-xl epl-icon shrink-0"></i> Kapan jam buka operasional toko fisik?</h4>
                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 epl-border">
                                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-500 epl-icon" id="icon-faq1"></i>
                            </div>
                        </div>
                        <div id="faq1" class="faq-content text-gray-500 text-xs md:text-sm font-medium leading-relaxed mt-0">
                            <div class="border-t border-gray-100 epl-border">
                                Toko kami beroperasi setiap hari Senin hingga Sabtu, mulai pukul 08:00 pagi hingga 17:00 sore WIB. Untuk hari Minggu dan Hari Libur Nasional, kami libur.
                            </div>
                        </div>
                    </div>

                    <div class="epl-card sweep-dark bg-white border border-gray-200 rounded-[1rem] md:rounded-[1.5rem] p-5 md:p-6" data-aos="fade-up" data-aos-delay="50" onclick="toggleFaq('faq2', this)">
                        <div class="flex justify-between items-center cursor-pointer">
                            <h4 class="font-black text-sm md:text-lg text-brand-dark flex items-center gap-3 w-5/6"><i class="fas fa-truck text-gray-300 text-lg md:text-xl epl-icon shrink-0"></i> Apakah melayani pengiriman material ke lokasi?</h4>
                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 epl-border">
                                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-500 epl-icon" id="icon-faq2"></i>
                            </div>
                        </div>
                        <div id="faq2" class="faq-content text-gray-500 text-xs md:text-sm font-medium leading-relaxed mt-0">
                            <div class="border-t border-gray-100 epl-border">
                                Tentu. Kami memiliki armada internal (pick-up dan dump truck) untuk pengiriman ke seluruh area Pontianak, Kubu Raya, dan sekitarnya. Pengiriman dapat dilakukan di hari yang sama atau maksimal H+1.
                            </div>
                        </div>
                    </div>

                    <div class="epl-card sweep-dark bg-white border border-gray-200 rounded-[1rem] md:rounded-[1.5rem] p-5 md:p-6" data-aos="fade-up" data-aos-delay="100" onclick="toggleFaq('faq3', this)">
                        <div class="flex justify-between items-center cursor-pointer">
                            <h4 class="font-black text-sm md:text-lg text-brand-dark flex items-center gap-3 w-5/6"><i class="fas fa-money-bill-wave text-gray-300 text-lg md:text-xl epl-icon shrink-0"></i> Metode pembayaran apa saja yang diterima?</h4>
                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 epl-border">
                                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-500 epl-icon" id="icon-faq3"></i>
                            </div>
                        </div>
                        <div id="faq3" class="faq-content text-gray-500 text-xs md:text-sm font-medium leading-relaxed mt-0">
                            <div class="border-t border-gray-100 epl-border">
                                Kami menerima pembayaran Tunai (Cash), Transfer Antar Bank (BCA, Mandiri, BRI), QRIS, serta gesek Kartu Debit/Kredit langsung di mesin EDC kasir kami.
                            </div>
                        </div>
                    </div>

                    <div class="epl-card sweep-dark bg-white border border-gray-200 rounded-[1rem] md:rounded-[1.5rem] p-5 md:p-6" data-aos="fade-up" data-aos-delay="150" onclick="toggleFaq('faq4', this)">
                        <div class="flex justify-between items-center cursor-pointer">
                            <h4 class="font-black text-sm md:text-lg text-brand-dark flex items-center gap-3 w-5/6"><i class="fas fa-rotate-left text-gray-300 text-lg md:text-xl epl-icon shrink-0"></i> Apakah barang sisa bisa diretur?</h4>
                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 epl-border">
                                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-500 epl-icon" id="icon-faq4"></i>
                            </div>
                        </div>
                        <div id="faq4" class="faq-content text-gray-500 text-xs md:text-sm font-medium leading-relaxed mt-0">
                            <div class="border-t border-gray-100 epl-border">
                                Retur diperbolehkan maksimal 2x24 jam sejak barang diterima, dengan syarat barang belum rusak/dipakai dan wajib melampirkan nota pembelian asli. Cat oplos dan semen tidak dapat diretur.
                            </div>
                        </div>
                    </div>

                    <div class="epl-card sweep-dark bg-white border border-gray-200 rounded-[1rem] md:rounded-[1.5rem] p-5 md:p-6" data-aos="fade-up" data-aos-delay="200" onclick="toggleFaq('faq5', this)">
                        <div class="flex justify-between items-center cursor-pointer">
                            <h4 class="font-black text-sm md:text-lg text-brand-dark flex items-center gap-3 w-5/6"><i class="fas fa-tags text-gray-300 text-lg md:text-xl epl-icon shrink-0"></i> Apakah ada diskon untuk pembelian partai besar / developer?</h4>
                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 epl-border">
                                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-500 epl-icon" id="icon-faq5"></i>
                            </div>
                        </div>
                        <div id="faq5" class="faq-content text-gray-500 text-xs md:text-sm font-medium leading-relaxed mt-0">
                            <div class="border-t border-gray-100 epl-border">
                                Tentu. Kami memiliki skema harga khusus (Grosir) untuk pemborong, kontraktor, atau developer perumahan. Harga akan disesuaikan dengan kuantitas pesanan.
                            </div>
                        </div>
                    </div>

                    <div class="epl-card sweep-dark bg-white border border-gray-200 rounded-[1rem] md:rounded-[1.5rem] p-5 md:p-6" data-aos="fade-up" data-aos-delay="250" onclick="toggleFaq('faq6', this)">
                        <div class="flex justify-between items-center cursor-pointer">
                            <h4 class="font-black text-sm md:text-lg text-brand-dark flex items-center gap-3 w-5/6"><i class="fas fa-users-cog text-gray-300 text-lg md:text-xl epl-icon shrink-0"></i> Bagaimana cara registrasi akun di portal karyawan?</h4>
                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 epl-border">
                                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-500 epl-icon" id="icon-faq6"></i>
                            </div>
                        </div>
                        <div id="faq6" class="faq-content text-gray-500 text-xs md:text-sm font-medium leading-relaxed mt-0">
                            <div class="border-t border-gray-100 epl-border">
                                <strong class="text-brand-dark">PENTING:</strong> Sistem ini bersifat tertutup (Internal). Kami sengaja menonaktifkan fitur registrasi publik demi keamanan. Semua akun staf dan admin hanya dapat dibuat dan diberikan secara langsung oleh Pemilik (Owner) toko.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="lokasi" class="py-16 md:py-24 bg-white relative border-b border-gray-200">
            <div class="max-w-[1400px] mx-auto px-5 md:px-12">
                <div class="flex flex-col lg:flex-row gap-10 md:gap-16 items-center">
                    
                    <div class="w-full lg:w-1/3" data-aos="fade-right">
                        <h2 class="text-brand-red font-black tracking-[0.2em] uppercase text-xs mb-3 md:mb-4"><i class="fas fa-map-marked-alt mr-2"></i> Titik Pusat</h2>
                        <h3 class="text-3xl md:text-5xl font-black text-brand-dark mb-4 md:mb-6 tracking-tight">Kunjungi Toko Fisik Kami.</h3>
                        <p class="text-gray-500 font-medium mb-8 leading-relaxed text-sm md:text-base">Tim operasional kami siap menyambut dan mendiskusikan kebutuhan konstruksi Anda secara langsung.</p>
                        
                        <div class="epl-card sweep-dark bg-white p-5 md:p-6 rounded-2xl mb-4 shadow-sm border border-gray-200">
                            <div class="flex gap-4 md:gap-5">
                                <div class="w-12 h-12 md:w-14 md:h-14 bg-red-50 rounded-xl flex items-center justify-center text-brand-red text-xl md:text-2xl shadow-inner shrink-0 epl-icon-bg"><i class="fas fa-location-dot epl-icon"></i></div>
                                <div>
                                    <h4 class="font-black text-brand-dark mb-1 text-base">Mitra Usaha 2 Pontianak</h4>
                                    <p class="text-xs text-gray-500 leading-relaxed font-medium">Jl. RE Martadinata, Sungai Jawi Dalam, Kec. Pontianak Bar., Kota Pontianak, Kalbar 78244</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="epl-card bg-brand-red p-5 md:p-6 rounded-2xl shadow-md border border-brand-red">
                            <div class="flex gap-4 md:gap-5">
                                <div class="w-12 h-12 md:w-14 md:h-14 bg-black/20 rounded-xl flex items-center justify-center text-white text-xl md:text-2xl shadow-inner shrink-0 epl-icon-bg"><i class="fas fa-phone-alt epl-icon"></i></div>
                                <div>
                                    <h4 class="font-black text-white mb-1 text-base">Kontak & Telepon</h4>
                                    <p class="text-sm text-white/90 leading-relaxed font-bold tracking-wider">0895-3741-56688</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-full lg:w-2/3 h-[350px] md:h-[500px] rounded-[1.5rem] md:rounded-[2rem] overflow-hidden shadow-2xl border-[4px] md:border-[8px] border-brand-gray relative group" data-aos="fade-left">
                        <div class="absolute inset-0 bg-brand-red/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-10 backdrop-blur-sm">
                            <div class="bg-white px-6 md:px-8 py-3 md:py-4 rounded-full shadow-2xl font-black text-brand-red transform translate-y-8 group-hover:translate-y-0 transition-transform duration-500 uppercase tracking-widest text-xs md:text-sm">
                                Pandu Arah ke Toko <i class="fas fa-location-arrow ml-2"></i>
                            </div>
                        </div>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.8178829983427!2d109.31068831524317!3d-0.0247656999806443!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e1d58540db59c19%3A0x8e826b15e478dc00!2sJl.%20RE%20Martadinata%2C%20Sungai%20Jawi%20Dalam%2C%20Kec.%20Pontianak%20Bar.%2C%20Kota%20Pontianak%2C%20Kalimantan%20Barat!5e0!3m2!1sid!2sid!4v1690000000000!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="relative z-0 filter contrast-100 grayscale-[10%] group-hover:grayscale-0 transition-all duration-700"></iframe>
                    </div>

                </div>
            </div>
        </section>

        @guest
        <section class="py-20 md:py-24 bg-brand-dark border-b border-gray-800 relative overflow-hidden">
            <div class="absolute inset-0 bg-blueprint-dark opacity-10 z-0"></div>
            
            <div class="max-w-4xl mx-auto px-5 text-center relative z-10" data-aos="zoom-in" data-aos-duration="800">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-brand-red border-[4px] md:border-[6px] border-white rounded-full flex items-center justify-center text-white text-2xl md:text-3xl mx-auto mb-6 md:mb-8 shadow-2xl"><i class="fas fa-lock"></i></div>
                
                <h2 class="text-3xl md:text-5xl font-black text-white mb-4 tracking-tight">Kawasan Sistem Internal</h2>
                <p class="text-sm md:text-base text-gray-400 font-medium mb-8 md:mb-10 px-4 max-w-2xl mx-auto">Untuk menjaga integritas dan keamanan data inventaris, pembuatan akun kasir/admin hanya dikelola langsung oleh Pemilik (Owner).</p>
                
                <div class="flex justify-center">
                    <a href="{{ route('login') }}" class="epl-btn bg-white text-brand-dark border border-transparent font-black text-xs md:text-sm tracking-widest uppercase px-12 py-4 md:py-5 rounded-full shadow-[0_10px_30px_rgba(255,255,255,0.15)] hover:shadow-[0_15px_40px_rgba(255,255,255,0.25)] w-full sm:w-auto transition-transform">
                        <i class="fas fa-sign-in-alt epl-icon-slide text-brand-dark mr-2"></i> <span>Akses Login Karyawan</span>
                    </a>
                </div>
            </div>
        </section>
        @endguest

        <footer class="bg-brand-gray pt-16 md:pt-24 pb-8 relative z-10">
            <div class="max-w-[1400px] mx-auto px-5 md:px-12">
                
                <div class="bg-brand-dark text-white rounded-[1.5rem] md:rounded-[2rem] p-8 md:p-12 mb-10 md:mb-16 shadow-xl flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden" data-aos="fade-up">
                    <div class="absolute -right-10 -top-10 md:-right-20 md:-top-20 text-[8rem] md:text-[15rem] text-white/5 pointer-events-none"><i class="fas fa-headset"></i></div>
                    <div class="relative z-10 w-full md:w-2/3 text-center md:text-left">
                        <h3 class="text-2xl md:text-4xl font-black mb-2 tracking-tight">Perlu Konsultasi Rencana Proyek?</h3>
                        <p class="text-sm md:text-base text-gray-400 font-medium">Tim kami siap membantu Anda menyusun dan menghitung Rencana Anggaran Biaya material (RAB).</p>
                    </div>
                    <div class="relative z-10 w-full md:w-auto shrink-0">
                        <a href="https://wa.me/62895374156688" target="_blank" class="epl-btn bg-brand-red border border-brand-red text-white font-black text-xs md:text-sm tracking-widest uppercase px-8 md:px-10 py-4 rounded-xl shadow-lg w-full text-center">
                            <i class="fab fa-whatsapp text-lg md:text-xl epl-icon-slide mr-2"></i> <span>Hubungi via WA</span>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 md:gap-12 border-b border-gray-200 pb-10 md:pb-16">
                    
                    <div class="lg:col-span-4">
                        <div class="flex items-center gap-3 md:gap-4 mb-6 md:mb-8">
                            <div class="w-12 h-12 md:w-16 md:h-16 bg-white border border-gray-200 flex items-center justify-center overflow-hidden shadow-sm rounded-xl">
                                <img src="{{ asset('storage/logos/logo-utama.png') }}" onerror="this.src='{{ asset('images/mu2.jpeg') }}'" alt="Logo" class="w-full h-full object-cover">
                            </div>
                            <span class="font-black tracking-tight text-xl md:text-3xl uppercase text-brand-dark leading-none">{{ config('aplikasi.nama_aplikasi', 'Mitra Usaha 2') }}<br><span class="text-[9px] md:text-[11px] text-gray-500 tracking-[0.3em] font-bold block mt-1">Building Materials</span></span>
                        </div>
                        <p class="text-sm text-gray-500 font-medium leading-relaxed mb-6 md:mb-8 pr-4 text-justify">
                            Toko bangunan modern dengan layanan prima. Kami menyediakan alat dan bahan konstruksi terlengkap, harga distributor, dan pengiriman terpercaya di Pontianak. Membangun fondasi kuat berawal dari material yang tepat.
                        </p>
                        <div class="flex gap-3">
                            <a href="#" aria-label="Facebook" class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-brand-red hover:text-white hover:border-brand-red transition-all shadow-sm"><i class="fab fa-facebook-f text-base"></i></a>
                            <a href="#" aria-label="Instagram" class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-brand-red hover:text-white hover:border-brand-red transition-all shadow-sm"><i class="fab fa-instagram text-base"></i></a>
                            <a href="#" aria-label="Tiktok" class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-brand-red hover:text-white hover:border-brand-red transition-all shadow-sm"><i class="fab fa-tiktok text-base"></i></a>
                        </div>
                    </div>

                    <div class="lg:col-span-3">
                        <h4 class="font-black text-brand-dark uppercase tracking-widest text-xs md:text-sm mb-5 md:mb-6 border-b-2 border-brand-red inline-block pb-2">Material Unggulan</h4>
                        <ul class="space-y-3">
                            <li><a href="#produk" class="text-sm font-medium text-gray-500 hover:text-brand-red transition-colors flex items-center gap-2"><i class="fas fa-angle-right text-xs text-brand-red"></i> Semen, Beton & Acian</a></li>
                            <li><a href="#produk" class="text-sm font-medium text-gray-500 hover:text-brand-red transition-colors flex items-center gap-2"><i class="fas fa-angle-right text-xs text-brand-red"></i> Besi Beton & Baja Ringan</a></li>
                            <li><a href="#produk" class="text-sm font-medium text-gray-500 hover:text-brand-red transition-colors flex items-center gap-2"><i class="fas fa-angle-right text-xs text-brand-red"></i> Papan, Multiplek & GRC</a></li>
                            <li><a href="#produk" class="text-sm font-medium text-gray-500 hover:text-brand-red transition-colors flex items-center gap-2"><i class="fas fa-angle-right text-xs text-brand-red"></i> Cat, Thinner & Pelapis</a></li>
                            <li><a href="#produk" class="text-sm font-medium text-gray-500 hover:text-brand-red transition-colors flex items-center gap-2"><i class="fas fa-angle-right text-xs text-brand-red"></i> Pipa Air PVC & Fitting</a></li>
                        </ul>
                    </div>

                    <div class="lg:col-span-2">
                        <h4 class="font-black text-brand-dark uppercase tracking-widest text-xs md:text-sm mb-5 md:mb-6 border-b-2 border-brand-red inline-block pb-2">Navigasi Peta</h4>
                        <ul class="space-y-3">
                            <li><a href="#beranda" class="text-sm font-medium text-gray-500 hover:text-brand-red transition-colors flex items-center gap-2"><i class="fas fa-angle-right text-xs text-brand-red"></i> Beranda Utama</a></li>
                            <li><a href="#tentang" class="text-sm font-medium text-gray-500 hover:text-brand-red transition-colors flex items-center gap-2"><i class="fas fa-angle-right text-xs text-brand-red"></i> Profil Kami</a></li>
                            <li><a href="#layanan" class="text-sm font-medium text-gray-500 hover:text-brand-red transition-colors flex items-center gap-2"><i class="fas fa-angle-right text-xs text-brand-red"></i> Jasa & Layanan</a></li>
                            <li><a href="#faq" class="text-sm font-medium text-gray-500 hover:text-brand-red transition-colors flex items-center gap-2"><i class="fas fa-angle-right text-xs text-brand-red"></i> FAQ / Bantuan</a></li>
                            <li><a href="{{ route('login') }}" class="text-sm font-bold text-brand-red hover:text-brand-dark transition-colors flex items-center gap-2 mt-2"><i class="fas fa-lock text-xs"></i> Portal Karyawan</a></li>
                        </ul>
                    </div>

                    <div class="lg:col-span-3">
                        <h4 class="font-black text-brand-dark uppercase tracking-widest text-xs md:text-sm mb-5 md:mb-6 border-b-2 border-brand-red inline-block pb-2">Informasi Kontak</h4>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3 text-sm text-gray-500 font-medium">
                                <div class="w-8 h-8 rounded-full bg-white shadow-sm border border-gray-100 text-brand-red flex items-center justify-center shrink-0 mt-0.5"><i class="fas fa-location-dot text-xs"></i></div>
                                <a href="https://maps.google.com/?q=-0.019773491600085204,109.31349080139726" target="_blank" class="leading-relaxed hover:text-brand-red transition-colors">Jl. RE. Martadinata, Sungai Jawi Dalam, Pontianak Barat.</a>
                            </li>
                            <li class="flex items-center gap-3 text-sm text-gray-500 font-medium">
                                <div class="w-8 h-8 rounded-full bg-white shadow-sm border border-gray-100 text-brand-red flex items-center justify-center shrink-0"><i class="fas fa-phone-alt text-xs"></i></div>
                                <a href="tel:0895374156688" class="hover:text-brand-red transition-colors font-bold tracking-wider text-brand-dark">0895-3741-56688</a>
                            </li>
                            <li class="flex items-center gap-3 text-sm text-gray-500 font-medium">
                                <div class="w-8 h-8 rounded-full bg-white shadow-sm border border-gray-100 text-brand-red flex items-center justify-center shrink-0"><i class="fas fa-envelope text-xs"></i></div>
                                <a href="mailto:info@mitrausaha2.com" class="hover:text-brand-red transition-colors">info@mitrausaha2.com</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-center pt-6 gap-4">
                    <p class="text-[10px] md:text-xs font-black text-gray-400 uppercase tracking-[0.1em] text-center md:text-left">
                        &copy; {{ date('Y') }} {{ config('aplikasi.nama_aplikasi', 'Mitra Usaha 2') }} Pontianak. Hak Cipta Dilindungi.
                    </p>
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-[0.1em]">Sistem Inventaris Terenkripsi</span>
                        <i class="fas fa-shield-check text-lg text-emerald-500"></i>
                    </div>
                </div>
            </div>
        </footer>

        <button id="btn-back-to-top" class="fixed bottom-6 right-6 md:bottom-8 md:right-8 w-12 h-12 md:w-14 md:h-14 bg-brand-dark text-white rounded-full shadow-2xl flex items-center justify-center text-lg hover:bg-brand-red transition-all duration-300 z-50 focus:outline-none border-2 border-white/10" aria-label="{{ __('back_to_top') }}">
            <i class="fas fa-arrow-up"></i>
        </button>

    </div> <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // ========================================================
        // 1. Inisialisasi AOS (Perbaikan Bug Scroll Android)
        // ========================================================
        document.addEventListener("DOMContentLoaded", function() {
            // Tunda sedikit untuk memastikan layouting browser selesai
            setTimeout(function() {
                AOS.init({ 
                    once: true, 
                    offset: 50, 
                    easing: 'ease-out-cubic',
                    duration: 800
                });
            }, 150);
        });

        // Trigger refresh jika semua assets (seperti gambar) selesai diunduh
        window.addEventListener('load', function() {
            setTimeout(function() {
                AOS.refresh();
            }, 200);
        });

        // ========================================================
        // 2. Efek Transisi Navbar & Back To Top
        // ========================================================
        const topbar = document.getElementById('topbar');
        const navbar = document.getElementById('navbar');
        const navMenuContainer = document.getElementById('nav-menu-container');
        const navBrand = document.getElementById('nav-brand');
        const navTagline = document.getElementById('nav-tagline');
        const navItems = document.querySelectorAll('.nav-item');
        const navLogin = document.getElementById('nav-login');
        const navLines = document.querySelectorAll('.nav-line');
        const backToTopBtn = document.getElementById('btn-back-to-top');
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 80) {
                // Sembunyikan Topbar Contact
                if(topbar) topbar.classList.add('topbar-hidden');
                
                // Ubah gaya Navbar Desktop & Mobile
                navbar.classList.add('nav-scrolled', 'py-3'); 
                navbar.classList.remove('py-4', 'md:py-6', 'top-0', 'md:top-10');
                
                // Ubah warna text Logo
                navBrand.classList.replace('text-white', 'text-brand-dark');
                if(navTagline) navTagline.classList.replace('text-gray-400', 'text-brand-red');
                
                // Ubah Menu Container bg
                if(navMenuContainer) {
                    navMenuContainer.classList.remove('bg-brand-dark/40', 'border-white/10');
                    navMenuContainer.classList.add('bg-gray-100', 'border-gray-200');
                }
                
                // Ubah Item Menu text
                navItems.forEach(item => item.classList.replace('text-white', 'text-brand-dark'));
                
                // Ubah Mobile Menu Icon Hamburger lines
                navLines.forEach(line => line.classList.replace('bg-white', 'bg-brand-dark'));
                
                // Ubah Tombol Login
                if(navLogin) {
                    navLogin.classList.remove('bg-white', 'text-brand-dark', 'hover:bg-gray-100');
                    navLogin.classList.add('bg-brand-dark', 'text-white', 'hover:bg-brand-red');
                }
                
                // Tampilkan Back To top
                backToTopBtn.classList.add('show');
            } else {
                // Kembalikan Topbar Contact
                if(topbar) topbar.classList.remove('topbar-hidden');
                
                // Kembalikan posisi awal Navbar
                navbar.classList.remove('nav-scrolled', 'py-3'); 
                navbar.classList.add('py-4', 'md:py-6', 'top-0', 'md:top-10');
                
                navBrand.classList.replace('text-brand-dark', 'text-white');
                if(navTagline) navTagline.classList.replace('text-brand-red', 'text-gray-400');
                
                if(navMenuContainer) {
                    navMenuContainer.classList.add('bg-brand-dark/40', 'border-white/10');
                    navMenuContainer.classList.remove('bg-gray-100', 'border-gray-200');
                }
                
                navItems.forEach(item => item.classList.replace('text-brand-dark', 'text-white'));
                navLines.forEach(line => line.classList.replace('bg-brand-dark', 'bg-white'));
                
                if(navLogin) {
                    navLogin.classList.add('bg-white', 'text-brand-dark', 'hover:bg-gray-100');
                    navLogin.classList.remove('bg-brand-dark', 'text-white', 'hover:bg-brand-red');
                }
                
                backToTopBtn.classList.remove('show');
            }
        });

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // ========================================================
        // 3. Mobile Menu Overlay Logic
        // ========================================================
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMenuBtn = document.getElementById('close-menu-btn');
        
        function toggleMenu() { 
            mobileMenu.classList.toggle('translate-x-full'); 
            
            // Kunci scroll body saat menu terbuka untuk UX mobile
            if(mobileMenu.classList.contains('translate-x-full')) {
                document.body.style.overflow = 'visible';
            } else {
                document.body.style.overflow = 'hidden';
            }
        }
        
        mobileMenuBtn.addEventListener('click', toggleMenu);
        closeMenuBtn.addEventListener('click', toggleMenu);
        document.querySelectorAll('.mobile-link').forEach(link => {
            link.addEventListener('click', toggleMenu);
        });

        // ========================================================
        // 4. FAQ Accordion Interaction Logic
        // ========================================================
        function toggleFaq(id, element) {
            const content = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);
            
            // Tutup semua FAQ lainnya
            document.querySelectorAll('.faq-content').forEach(el => {
                if(el.id !== id) { el.classList.remove('open'); }
            });
            document.querySelectorAll('[id^="icon-faq"]').forEach(el => {
                if(el.id !== 'icon-' + id) { el.style.transform = 'rotate(0deg)'; }
            });

            // Buka FAQ yang diklik
            if (content.classList.contains('open')) {
                content.classList.remove('open');
                icon.style.transform = 'rotate(0deg)';
                element.classList.remove('border-brand-red');
            } else {
                content.classList.add('open');
                icon.style.transform = 'rotate(180deg)';
                // Tambah highlight di parent card
                document.querySelectorAll('.epl-card').forEach(c => c.classList.remove('border-brand-red'));
                element.classList.add('border-brand-red');
            }
            
            // Trigger AOS refresh agar halaman menyesuaikan kalkulasi tinggi jika panjang berubah
            setTimeout(() => { AOS.refresh(); }, 450);
        }
    </script>
</body>
</html>