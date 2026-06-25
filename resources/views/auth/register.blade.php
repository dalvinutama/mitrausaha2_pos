<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - {{ config('aplikasi.nama_aplikasi', 'Mitra Usaha 2') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Figtree', 'sans-serif'] },
                    colors: {
                        brand: { 
                            red: '#D00000', 
                            dark: '#111115', 
                            gray: '#f5f5f7', 
                        }
                    },
                    animation: {
                        'slow-pan': 'panImage 40s linear infinite alternate',
                        'pulse-slow': 'pulse 6s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        panImage: { 
                            '0%': { objectPosition: '0% 50%', transform: 'scale(1.05)' }, 
                            '100%': { objectPosition: '100% 50%', transform: 'scale(1.1)' } 
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Efek Tombol Animasi EPL Sweep */
        .epl-btn {
            position: relative; overflow: hidden; z-index: 1; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s ease;
        }
        .epl-btn::before {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1; transform-origin: left; transform: scaleX(0);
            transition: transform 0.4s cubic-bezier(0.19, 1, 0.22, 1);
            background-color: #111115; /* Merah menyapu jadi Hitam Pekat */
        }
        .epl-btn:hover { transform: translateY(-4px); box-shadow: 0 15px 30px -5px rgba(208, 0, 0, 0.3); }
        .epl-btn:hover::before { transform: scaleX(1); }
        .epl-btn:active { transform: scale(0.95); }
        .epl-btn *, .epl-btn i { transition: all 0.3s ease; }
        .epl-btn:hover i.epl-icon-slide { transform: translateX(6px) scale(1.1); }

        /* Input Form Focus Styles */
        .input-premium:focus {
            border-color: #D00000 !important;
            box-shadow: 0 0 0 4px rgba(208, 0, 0, 0.1) !important;
        }
        
        /* Glassmorphism Effect untuk Kiri */
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        /* Custom Scrollbar untuk area form agar rapi jika layar kecil */
        .form-scroll::-webkit-scrollbar { width: 6px; }
        .form-scroll::-webkit-scrollbar-track { background: transparent; }
        .form-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .form-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="antialiased bg-white text-gray-900 selection:bg-brand-red selection:text-white font-sans h-screen overflow-hidden">

    <div class="flex w-full h-full">

        <div class="hidden lg:flex w-1/2 relative items-center justify-center overflow-hidden bg-gray-100">
            
            <div class="absolute inset-0 w-full h-full">
                <img src="{{ asset('images/hero-industrial.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1541888086225-eb430c5d56b0?q=80&w=1000&auto=format&fit=crop'" alt="Background" class="w-full h-full object-cover animate-slow-pan">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-brand-dark/30 via-brand-dark/60 to-brand-dark/95"></div>
            </div>

            <div class="absolute -top-32 -left-32 w-[600px] h-[600px] bg-white/10 rounded-full blur-[120px] animate-pulse-slow pointer-events-none z-0"></div>

            <div class="relative z-10 glass-card rounded-[2rem] p-12 flex flex-col items-center text-center max-w-md mx-8 transition-transform hover:scale-[1.02] duration-500">
                <div class="w-24 h-24 bg-white rounded-2xl flex items-center justify-center overflow-hidden shadow-xl mb-6 border-[3px] border-white/50">
                    <img src="{{ asset('storage/logos/logo-utama.png') }}" onerror="this.src='{{ asset('images/mu2.jpeg') }}'" alt="Logo" class="w-full h-full object-cover">
                </div>
                
                <h1 class="text-4xl font-black text-white tracking-tight mb-3 drop-shadow-md">
                    Registrasi Staf<br>Mitra Usaha 2
                </h1>
                
                <p class="text-white/90 text-sm font-medium leading-relaxed drop-shadow-sm">
                    Daftarkan akun Anda untuk mendapatkan akses penuh ke ekosistem manajemen digital toko.
                </p>
                
                <div class="mt-8 pt-6 border-t border-white/20 flex items-center gap-3 text-white font-bold text-xs uppercase tracking-widest">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_10px_rgba(52,211,153,0.8)]"></div>
                    <span>Sistem Terenkripsi Aman</span>
                </div>
            </div>
            
        </div>

        <div class="w-full lg:w-1/2 flex flex-col relative bg-white px-8 sm:px-16 md:px-24 overflow-y-auto form-scroll py-10 lg:py-0">
            
            <div class="lg:absolute lg:top-8 lg:left-12 mb-8 lg:mb-0">
                <a href="{{ url('/') }}" class="text-sm font-bold text-gray-400 hover:text-brand-red transition-colors flex items-center gap-2 group w-fit">
                    <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform text-gray-600"></i>
                    </div>
                    <span>Beranda</span>
                </a>
            </div>

            <div class="w-full max-w-md mx-auto my-auto">
                
                <div class="mb-8 text-center lg:text-left mt-4 lg:mt-0">
                    <div class="w-16 h-16 bg-white rounded-2xl flex lg:hidden items-center justify-center overflow-hidden shadow-md border border-gray-100 mx-auto mb-6">
                        <img src="{{ asset('storage/logos/logo-utama.png') }}" onerror="this.src='{{ asset('images/mu2.jpeg') }}'" alt="Logo" class="w-full h-full object-cover">
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black text-brand-dark tracking-tight mb-2">Buat Akun Baru.</h2>
                    <p class="text-gray-500 font-medium text-sm sm:text-base">Lengkapi data di bawah ini untuk mendaftar.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block font-bold text-xs text-gray-500 mb-1.5 uppercase tracking-widest">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-user"></i>
                            </div>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Contoh: Budi Santoso" 
                                class="input-premium block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl transition-all duration-300 outline-none placeholder-gray-400 font-medium">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-brand-red font-semibold" />
                    </div>

                    <div>
                        <label for="email" class="block font-bold text-xs text-gray-500 mb-1.5 uppercase tracking-widest">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com" 
                                class="input-premium block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl transition-all duration-300 outline-none placeholder-gray-400 font-medium">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-brand-red font-semibold" />
                    </div>

                    <div>
                        <label for="password" class="block font-bold text-xs text-gray-500 mb-1.5 uppercase tracking-widest">Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" 
                                class="input-premium block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl transition-all duration-300 outline-none placeholder-gray-400 font-medium">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-brand-red font-semibold" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block font-bold text-xs text-gray-500 mb-1.5 uppercase tracking-widest">Konfirmasi Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" 
                                class="input-premium block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-xl transition-all duration-300 outline-none placeholder-gray-400 font-medium">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-brand-red font-semibold" />
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="epl-btn w-full bg-brand-red text-white font-black text-sm tracking-widest uppercase py-4 rounded-xl shadow-lg shadow-brand-red/20 border border-transparent mb-4">
                            <span>{{ __('Daftar Sekarang') }}</span> <i class="fas fa-user-plus epl-icon-slide ml-1"></i>
                        </button>
                        
                        <div class="text-center">
                            <span class="text-sm text-gray-500 font-medium">Sudah punya akun?</span>
                            <a href="{{ route('login') }}" class="text-sm font-bold text-brand-red hover:text-red-900 transition-colors ml-1">
                                {{ __('Masuk di sini') }}
                            </a>
                        </div>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-100 text-center pb-8 lg:pb-0">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                        &copy; {{ date('Y') }} {{ config('aplikasi.nama_aplikasi', 'Mitra Usaha 2') }}. Hak Cipta Dilindungi.
                    </p>
                </div>

            </div>
        </div>

    </div>

</body>
</html>