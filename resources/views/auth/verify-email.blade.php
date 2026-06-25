<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email - {{ config('aplikasi.nama_aplikasi', 'Mitra Usaha 2') }}</title>

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
            background-color: #111115;
        }
        .epl-btn:hover { transform: translateY(-4px); box-shadow: 0 15px 30px -5px rgba(208, 0, 0, 0.3); }
        .epl-btn:hover::before { transform: scaleX(1); }
        .epl-btn:active { transform: scale(0.95); }
        .epl-btn *, .epl-btn i { transition: all 0.3s ease; }
        .epl-btn:hover i.epl-icon-slide { transform: translateX(6px) scale(1.1); }

        /* Glassmorphism Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
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
                    Verifikasi Akun<br>Mitra Usaha 2
                </h1>
                
                <p class="text-white/90 text-sm font-medium leading-relaxed drop-shadow-sm">
                    Satu langkah lagi untuk memastikan keamanan identitas Anda sebelum mengakses data sensitif perusahaan.
                </p>
                
                <div class="mt-8 pt-6 border-t border-white/20 flex items-center gap-3 text-white font-bold text-xs uppercase tracking-widest">
                    <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse shadow-[0_0_10px_rgba(96,165,250,0.8)]"></div>
                    <span>Menunggu Konfirmasi</span>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center relative bg-white px-8 sm:px-16 md:px-24">
            
            <div class="w-full max-w-md mx-auto">
                
                <div class="mb-10 text-center lg:text-left">
                    <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-3xl mb-8 mx-auto lg:mx-0 shadow-inner">
                        <i class="fas fa-envelope-open-text animate-bounce"></i>
                    </div>
                    
                    <h2 class="text-3xl sm:text-4xl font-black text-brand-dark tracking-tight mb-4">Cek Email Anda.</h2>
                    
                    <p class="text-gray-500 font-medium text-sm sm:text-base leading-relaxed mb-6">
                        {{ __('Terima kasih telah mendaftar! Sebelum memulai, harap verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan. Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkan yang baru.') }}
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-8 bg-emerald-50 border border-emerald-100 p-5 rounded-2xl flex items-center gap-4 animate-fade-in-down">
                        <div class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center shrink-0 shadow-lg">
                            <i class="fas fa-check"></i>
                        </div>
                        <p class="text-sm font-bold text-emerald-700 leading-tight">
                            {{ __('Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.') }}
                        </p>
                    </div>
                @endif

                <div class="flex flex-col gap-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="epl-btn w-full bg-brand-red text-white font-black text-sm tracking-widest uppercase py-4 rounded-xl shadow-lg shadow-brand-red/20 border border-transparent">
                            <span>{{ __('Kirim Ulang Email Verifikasi') }}</span> <i class="fas fa-paper-plane epl-icon-slide ml-1"></i>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full group flex items-center justify-center gap-2 text-sm font-bold text-gray-400 hover:text-brand-red transition-all duration-300">
                            <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i>
                            <span class="underline decoration-2 underline-offset-4">{{ __('Keluar Sistem') }}</span>
                        </button>
                    </form>
                </div>

                <div class="mt-12 pt-6 border-t border-gray-100 text-center">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                        &copy; {{ date('Y') }} {{ config('aplikasi.nama_aplikasi', 'Mitra Usaha 2') }}.
                    </p>
                </div>

            </div>
        </div>

    </div>

</body>
</html>