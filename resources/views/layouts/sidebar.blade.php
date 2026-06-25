@php
    $role = Auth::user()->role;
    $userName = Auth::user()->name;
    
    $accBarangMasuk  = in_array($role, ['owner', 'admin', 'gudang']);
    $accBarangKeluar = in_array($role, ['owner', 'admin', 'penjualan', 'gudang', 'kasir', 'pengiriman']); 
    $accPO           = in_array($role, ['owner', 'admin', 'gudang']);
    $accPersediaan   = true; 
    $accKategori     = in_array($role, ['owner', 'admin', 'penjualan', 'gudang']);
    $accSupplier     = in_array($role, ['owner', 'admin', 'gudang']); 
    $accLaporan      = in_array($role, ['owner', 'admin']);
    $accStockOpname  = in_array($role, ['owner', 'admin', 'gudang']); 
    $accPengaturan   = $role === 'owner'; 
    $accPengguna     = in_array($role, ['owner', 'admin']); 
    
    // Hitung jumlah PO yang belum tuntas secara global untuk sidebar
    $pendingPoCount = 0;
    if ($accPO) {
        try {
            $pendingPoCount = \App\Models\Transaction::where('jenis_transaksi', 'po')
                                                     ->whereIn('status', ['pending', 'approved'])
                                                     ->count();
        } catch (\Exception $e) {
            // Abaikan jika tabel belum ada saat migrasi awal
        }
    }
@endphp

<aside id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 lg:static transition-all duration-300 w-[240px] bg-[#1a1c26] dark:bg-gray-900 text-gray-300 flex flex-col z-40 border-r border-gray-700/50 dark:border-gray-800 shadow-[4px_0_24px_rgba(0,0,0,0.2)]">
    
    {{-- LOGO & HEADER (Diperkecil) --}}
    <div class="h-[76px] px-5 border-b border-gray-700/50 dark:border-gray-800 bg-[#151720]/80 dark:bg-black/40 backdrop-blur-md sticky top-0 z-20 flex items-center">
        <div class="flex items-center gap-3 w-full">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#D00000] to-red-900 p-[2px] shadow-[0_0_15px_rgba(208,0,0,0.3)] shrink-0">
                <img src="{{ asset('storage/logos/logo-utama.png') }}?v={{ time() }}"
                    onerror="this.src='{{ asset('images/mu2.jpeg') }}'" 
                    alt="Logo" 
                    class="w-full h-full rounded-full object-cover bg-white">
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-xs font-black tracking-widest text-white uppercase truncate drop-shadow-md leading-none mb-0.5">
                    {{ config('aplikasi.nama_aplikasi', 'Mitra Usaha') }}
                </span>
                <span class="text-[8.5px] text-gray-400 font-medium truncate mb-1.5">
                    {{ config('aplikasi.tagline_aplikasi', __('management_system')) }}
                </span>
                <div class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)] animate-pulse"></span>
                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.2em] truncate leading-none">{{ $userName }}</span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- NAVIGATION MENU (Spasi dan ukuran diturunkan) --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto custom-scrollbar space-y-5" id="nav-links-container">
        
        {{-- SECTION: MAIN --}}
        <div>
            <ul class="space-y-1">
                <li>
                    {{-- PERBAIKAN KONTRAS: Tambahan border-l-4 jika aktif --}}
                    <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" class="group flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-[#D00000] to-red-700 text-white shadow-lg shadow-red-900/30 border-l-4 border-white' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center transition-colors {{ request()->routeIs('dashboard') ? 'bg-white/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                                <i class="fas fa-chart-pie text-xs {{ request()->routeIs('dashboard') ? 'text-white' : 'text-blue-400' }}"></i>
                            </div>
                            <span class="text-xs font-bold {{ request()->routeIs('dashboard') ? '' : 'tracking-wide' }}">{{ __('dashboard') }}</span>
                        </div>
                        @if(request()->routeIs('dashboard'))
                            <i class="fas fa-chevron-right text-[9px] opacity-70"></i>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        {{-- SECTION: TRANSAKSI --}}
        <div>
            <h3 class="px-3 text-[9px] font-black text-gray-500 uppercase tracking-[0.25em] mb-2">{{ __('operational') }}</h3>
            <ul class="space-y-1">
                <li>
                    {{-- PERBAIKAN KONTRAS: Tambahan border-l-4 jika aktif --}}
                    <a href="{{ $accBarangMasuk ? (Route::has('stok_masuk') ? route('stok_masuk') : '#') : 'javascript:void(0)' }}" class="group flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 {{ $accBarangMasuk ? (request()->routeIs('stok_masuk') ? 'bg-white/10 text-white border-l-4 border-emerald-400 shadow-md' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent') : 'opacity-40 cursor-not-allowed border-l-4 border-transparent' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center transition-colors {{ request()->routeIs('stok_masuk') ? 'bg-emerald-500/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                                <i class="fas fa-boxes-packing text-xs {{ request()->routeIs('stok_masuk') ? 'text-emerald-400' : 'text-emerald-500' }}"></i>
                            </div>
                            <span class="text-[11px] font-bold tracking-wide">{{ __('incoming_goods') }}</span>
                        </div>
                        @if(!$accBarangMasuk) <i class="fas fa-lock text-[9px] text-gray-600"></i> @endif
                    </a>
                </li>
                <li>
                    <a href="{{ $accBarangKeluar ? (Route::has('stok_keluar') ? route('stok_keluar') : '#') : 'javascript:void(0)' }}" class="group flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 {{ $accBarangKeluar ? (request()->routeIs('stok_keluar') ? 'bg-white/10 text-white border-l-4 border-rose-400 shadow-md' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent') : 'opacity-40 cursor-not-allowed border-l-4 border-transparent' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center transition-colors {{ request()->routeIs('stok_keluar') ? 'bg-rose-500/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                                <i class="fas fa-truck-fast text-xs {{ request()->routeIs('stok_keluar') ? 'text-rose-400' : 'text-rose-500' }}"></i>
                            </div>
                            <span class="text-[11px] font-bold tracking-wide">{{ __('outgoing_goods') }}</span>
                        </div>
                        @if(!$accBarangKeluar) <i class="fas fa-lock text-[9px] text-gray-600"></i> @endif
                    </a>
                </li>
                <li>
                    <a href="{{ $accPO ? (Route::has('purchase_order') ? route('purchase_order') : '#') : 'javascript:void(0)' }}" class="group flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 relative {{ $accPO ? (request()->routeIs('purchase_order') ? 'bg-white/10 text-white border-l-4 border-indigo-400 shadow-md' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent') : 'opacity-40 cursor-not-allowed border-l-4 border-transparent' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center transition-colors relative {{ request()->routeIs('purchase_order') ? 'bg-indigo-500/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                                <i class="fas fa-file-invoice text-xs {{ request()->routeIs('purchase_order') ? 'text-indigo-400' : 'text-indigo-500' }}"></i>
                            </div>
                            <span class="text-[11px] font-bold tracking-wide">{{ __('purchase_order') }}</span>
                        </div>
                        @if(!$accPO) 
                            <i class="fas fa-lock text-[9px] text-gray-600"></i> 
                        @elseif(isset($pendingPoCount) && $pendingPoCount > 0)
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center min-w-[20px] h-5 px-1.5 bg-[#D00000] text-white text-[10px] font-black rounded-full shadow-[0_0_10px_rgba(208,0,0,0.5)] border border-[#ff3333]/30 animate-pulse">
                                {{ $pendingPoCount }}
                            </div>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ $accLaporan ? (Route::has('hutang') ? route('hutang') : '#') : 'javascript:void(0)' }}" class="group flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 {{ $accLaporan ? (request()->routeIs('hutang') ? 'bg-white/10 text-white border-l-4 border-yellow-400 shadow-md' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent') : 'opacity-40 cursor-not-allowed border-l-4 border-transparent' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center transition-colors {{ request()->routeIs('hutang') ? 'bg-yellow-500/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                                <i class="fas fa-file-invoice-dollar text-xs {{ request()->routeIs('hutang') ? 'text-yellow-400' : 'text-yellow-500' }}"></i>
                            </div>
                            <span class="text-[11px] font-bold tracking-wide">Daftar Hutang</span>
                        </div>
                        @if(!$accLaporan) <i class="fas fa-lock text-[9px] text-gray-600"></i> @endif
                    </a>
                </li>
            </ul>
        </div>

        {{-- SECTION: DATABASE --}}
        <div>
            <h3 class="px-3 text-[9px] font-black text-gray-500 uppercase tracking-[0.25em] mb-2">{{ __('core_database') }}</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ $accPersediaan ? (Route::has('persediaan') ? route('persediaan') : '#') : 'javascript:void(0)' }}" class="group flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 {{ $accPersediaan ? (request()->routeIs('persediaan') ? 'bg-white/10 text-white border-l-4 border-amber-400 shadow-md' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent') : 'opacity-40 cursor-not-allowed border-l-4 border-transparent' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center transition-colors {{ request()->routeIs('persediaan') ? 'bg-amber-500/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                                <i class="fas fa-cubes-stacked text-xs {{ request()->routeIs('persediaan') ? 'text-amber-400' : 'text-amber-500' }}"></i>
                            </div>
                            <span class="text-[11px] font-bold tracking-wide">{{ __('inventory') }}</span>
                        </div>
                        @if(!$accPersediaan) <i class="fas fa-lock text-[9px] text-gray-600"></i> @endif
                    </a>
                </li>
                <li>
                    <a href="{{ $accSupplier ? (Route::has('supplier') ? route('supplier') : '#') : 'javascript:void(0)' }}" class="group flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 {{ $accSupplier ? (request()->routeIs('supplier') ? 'bg-white/10 text-white border-l-4 border-orange-400 shadow-md' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent') : 'opacity-40 cursor-not-allowed border-l-4 border-transparent' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center transition-colors {{ request()->routeIs('supplier') ? 'bg-orange-500/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                                <i class="fas fa-handshake-angle text-xs {{ request()->routeIs('supplier') ? 'text-orange-400' : 'text-orange-500' }}"></i>
                            </div>
                            <span class="text-[11px] font-bold tracking-wide">{{ __('supplier') }}</span>
                        </div>
                        @if(!$accSupplier) <i class="fas fa-lock text-[9px] text-gray-600"></i> @endif
                    </a>
                </li>
                <li>
                    <a href="{{ $accKategori ? (Route::has('kategori') ? route('kategori') : '#') : 'javascript:void(0)' }}" class="group flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 {{ $accKategori ? (request()->routeIs('kategori') ? 'bg-white/10 text-white border-l-4 border-teal-400 shadow-md' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent') : 'opacity-40 cursor-not-allowed border-l-4 border-transparent' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center transition-colors {{ request()->routeIs('kategori') ? 'bg-teal-500/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                                <i class="fas fa-tags text-xs {{ request()->routeIs('kategori') ? 'text-teal-400' : 'text-teal-500' }}"></i>
                            </div>
                            <span class="text-[11px] font-bold tracking-wide">{{ __('material_category') }}</span>
                        </div>
                        @if(!$accKategori) <i class="fas fa-lock text-[9px] text-gray-600"></i> @endif
                    </a>
                </li>
            </ul>
        </div>

        {{-- SECTION: SYSTEM --}}
        <div>
            <h3 class="px-3 text-[9px] font-black text-gray-500 uppercase tracking-[0.25em] mb-2">{{ __('system_audit') }}</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ $accLaporan ? (Route::has('laporan') ? route('laporan') : '#') : 'javascript:void(0)' }}" class="group flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 {{ $accLaporan ? (request()->routeIs('laporan') ? 'bg-white/10 text-white border-l-4 border-purple-400 shadow-md' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent') : 'opacity-40 cursor-not-allowed border-l-4 border-transparent' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center transition-colors {{ request()->routeIs('laporan') ? 'bg-purple-500/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                                <i class="fas fa-file-contract text-xs {{ request()->routeIs('laporan') ? 'text-purple-400' : 'text-purple-500' }}"></i>
                            </div>
                            <span class="text-[11px] font-bold tracking-wide">{{ __('mutation_report') }}</span>
                        </div>
                        @if(!$accLaporan) <i class="fas fa-lock text-[9px] text-gray-600"></i> @endif
                    </a>
                </li>
                <li>
                    <a href="{{ $accStockOpname ? (Route::has('stock_opname') ? route('stock_opname') : '#') : 'javascript:void(0)' }}" class="group flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 {{ $accStockOpname ? (request()->routeIs('stock_opname') ? 'bg-white/10 text-white border-l-4 border-cyan-400 shadow-md' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent') : 'opacity-40 cursor-not-allowed border-l-4 border-transparent' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center transition-colors {{ request()->routeIs('stock_opname') ? 'bg-cyan-500/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                                <i class="fas fa-clipboard-check text-xs {{ request()->routeIs('stock_opname') ? 'text-cyan-400' : 'text-cyan-500' }}"></i>
                            </div>
                            <span class="text-[11px] font-bold tracking-wide">{{ __('stock_opname') }}</span>
                        </div>
                        @if(!$accStockOpname) <i class="fas fa-lock text-[9px] text-gray-600"></i> @endif
                    </a>
                </li>
                
                <li>
                    <a href="{{ $accLaporan ? (Route::has('audit_log') ? route('audit_log') : '#') : 'javascript:void(0)' }}" class="group flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 {{ $accLaporan ? (request()->routeIs('audit_log') ? 'bg-white/10 text-white border-l-4 border-slate-400 shadow-md' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent') : 'opacity-40 cursor-not-allowed border-l-4 border-transparent' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center transition-colors {{ request()->routeIs('audit_log') ? 'bg-slate-500/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                                <i class="fas fa-shield-halved text-xs {{ request()->routeIs('audit_log') ? 'text-slate-300' : 'text-slate-400' }}"></i>
                            </div>
                            <span class="text-[11px] font-bold tracking-wide">Audit Trail</span>
                        </div>
                        @if(!$accLaporan) <i class="fas fa-lock text-[9px] text-gray-600"></i> @endif
                    </a>
                </li>
                
                <li class="pt-2">
                    <div class="h-px w-full bg-white/5 my-1.5"></div>
                </li>

                <li>
                    <a href="{{ $accPengaturan ? (Route::has('pengaturan') ? route('pengaturan') : '#') : 'javascript:void(0)' }}" class="group flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 {{ $accPengaturan ? (request()->routeIs('pengaturan') ? 'bg-white/10 text-white border-l-4 border-zinc-400 shadow-md' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent') : 'opacity-40 cursor-not-allowed border-l-4 border-transparent' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center transition-colors {{ request()->routeIs('pengaturan') ? 'bg-zinc-500/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                                <i class="fas fa-gear text-xs {{ request()->routeIs('pengaturan') ? 'text-zinc-300' : 'text-zinc-400' }}"></i>
                            </div>
                            <span class="text-[11px] font-bold tracking-wide">{{ __('store_settings') }}</span>
                        </div>
                        @if(!$accPengaturan) <i class="fas fa-lock text-[9px] text-gray-600"></i> @endif
                    </a>
                </li>

                <li>
                    <a href="{{ $accPengguna ? (Route::has('pengguna') ? route('pengguna') : '#') : 'javascript:void(0)' }}" class="group flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 {{ $accPengguna ? (request()->routeIs('pengguna') ? 'bg-white/10 text-white border-l-4 border-zinc-400 shadow-md' : 'hover:bg-white/5 hover:text-white border-l-4 border-transparent') : 'opacity-40 cursor-not-allowed border-l-4 border-transparent' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-md flex items-center justify-center transition-colors {{ request()->routeIs('pengguna') ? 'bg-zinc-500/20' : 'bg-white/5 group-hover:bg-white/10' }}">
                                <i class="fas fa-users-gear text-xs {{ request()->routeIs('pengguna') ? 'text-zinc-300' : 'text-zinc-400' }}"></i>
                            </div>
                            <span class="text-[11px] font-bold tracking-wide">{{ __('staff_management') }}</span>
                        </div>
                        @if(!$accPengguna) <i class="fas fa-lock text-[9px] text-gray-600"></i> @endif
                    </a>
                </li>
            </ul>
        </div>
        
        {{-- PANDUAN PENGGUNA --}}
        <div class="mt-6 mb-4 px-3">
            <a href="{{ Route::has('panduan') ? route('panduan') : '#' }}" class="group flex items-center justify-between px-3 py-2.5 rounded-xl bg-gradient-to-r from-blue-600/20 to-blue-800/10 hover:from-blue-600/40 hover:to-blue-800/30 border border-blue-500/30 transition-all duration-300 {{ request()->routeIs('panduan') ? 'border-blue-400 bg-blue-600/30' : '' }}">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all shadow-[0_0_10px_rgba(59,130,246,0)] group-hover:shadow-[0_0_10px_rgba(59,130,246,0.4)] {{ request()->routeIs('panduan') ? 'bg-blue-500 text-white shadow-[0_0_10px_rgba(59,130,246,0.6)]' : 'bg-blue-500/20 text-blue-400 group-hover:bg-blue-500 group-hover:text-white' }}">
                        <i class="fas fa-circle-question text-sm {{ request()->routeIs('panduan') ? 'animate-pulse' : '' }}"></i>
                    </div>
                    <div class="text-left">
                        <span class="block text-[11px] font-bold tracking-wide {{ request()->routeIs('panduan') ? 'text-white' : 'text-blue-100' }}">Masih Bingung?</span>
                        <span class="block text-[9px] transition-colors {{ request()->routeIs('panduan') ? 'text-blue-200' : 'text-blue-300/70 group-hover:text-blue-200' }}">Panduan Sistem</span>
                    </div>
                </div>
                <i class="fas fa-arrow-right text-[10px] transition-all duration-300 {{ request()->routeIs('panduan') ? 'text-blue-300 opacity-100 translate-x-1' : 'text-blue-400 opacity-50 group-hover:opacity-100 group-hover:translate-x-1' }}"></i>
            </a>
        </div>

        <div class="h-10"></div>
    </nav>

    {{-- FITUR AI SEMENTARA DINONAKTIFKAN SESUAI PERMINTAAN UNTUK FOKUS SKRIPSI --}}
</aside>

<div id="overlay" class="fixed inset-0 bg-black/60 hidden z-30 lg:hidden backdrop-blur-sm transition-all"></div>

<style>
    /* Styling Scrollbar khusus untuk Sidebar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1); 
        border-radius: 10px;
    }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2); 
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const disabledLinks = document.querySelectorAll('a[href="javascript:void(0)"]');
        disabledLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const isDark = document.documentElement.classList.contains('dark');
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("Akses Ditolak!") }}',
                    text: '{{ __("Anda tidak memiliki hak akses untuk membuka halaman ini.") }}',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    background: isDark ? '#1f2937' : '#fff',
                    color: isDark ? '#f3f4f6' : '#545454'
                });
            });
        });

        // AI Hub Toggle Logic
        const aiToggleBtn = document.getElementById('ai-hub-toggle');
        const aiMenu = document.getElementById('ai-hub-menu');
        const aiChevron = document.getElementById('ai-hub-chevron');
        let aiMenuOpen = false;

        if (aiToggleBtn) {
            aiToggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                aiMenuOpen = !aiMenuOpen;
                
                if (aiMenuOpen) {
                    aiMenu.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
                    aiChevron.classList.add('rotate-180');
                    aiToggleBtn.classList.add('shadow-[0_0_20px_rgba(16,185,129,0.5)]');
                } else {
                    aiMenu.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
                    aiChevron.classList.remove('rotate-180');
                    aiToggleBtn.classList.remove('shadow-[0_0_20px_rgba(16,185,129,0.5)]');
                }
            });

            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (aiMenuOpen && !document.getElementById('ai-hub-container').contains(e.target)) {
                    aiMenuOpen = false;
                    aiMenu.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
                    aiChevron.classList.remove('rotate-180');
                    aiToggleBtn.classList.remove('shadow-[0_0_20px_rgba(16,185,129,0.5)]');
                }
            });
        }
    });

    // Simulasi Pemicu Robot (UI Demo)
    function simulateRobotRun(robotName) {
        // Tutup menu dropup
        document.getElementById('ai-hub-toggle').click();
        
        const isDark = document.documentElement.classList.contains('dark');
        
        // Panggil command artisan via AJAX (Hanya contoh simulasi responsif UI)
        let routeUrl = robotName === 'Auto-PO' ? '/api/trigger-autopo' : '/api/trigger-dailycheck';

        Swal.fire({
            title: `Memulai ${robotName.replace('_', ' ')}...`,
            html: `Menganalisis matriks inventaris di latar belakang.<br><br><span class="text-[10px] text-gray-400">Menjalankan kalkulasi stokastik...</span>`,
            timer: 2500,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            },
            background: isDark ? '#1f2937' : '#fff',
            color: isDark ? '#f3f4f6' : '#545454'
        }).then((result) => {
            Swal.fire({
                icon: 'success',
                title: 'Perintah Dieksekusi!',
                text: `Robot ${robotName.replace('_', ' ')} berhasil menyelesaikan tugasnya. Cek notifikasi Anda.`,
                confirmButtonColor: '#10B981',
                background: isDark ? '#1f2937' : '#fff',
                color: isDark ? '#f3f4f6' : '#545454'
            });
        });
    }

    function showAccessDenied() {
        const isDark = document.documentElement.classList.contains('dark');
        Swal.fire({
            icon: 'error',
            title: '{{ __('ai_access_denied') }}',
            text: '{{ __('ai_access_denied_desc') }}',
            confirmButtonColor: '#D00000',
            background: isDark ? '#1f2937' : '#fff',
            color: isDark ? '#f3f4f6' : '#545454'
        });
    }

    @if($role === 'owner')
    function toggleAiAccordion(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }

    function openAiConfigModal() {
        document.getElementById('ai-hub-toggle').click();
        const modal = document.getElementById('ai-config-modal');
        modal.classList.remove('hidden');
        
        // Fetch current config and math
        fetch('/api/ai-config')
            .then(res => res.json())
            .then(data => {
                if (data.config) {
                    document.getElementById('ai-toggle-autopo').checked = data.config.auto_po_active == 1;
                    document.getElementById('ai-toggle-daily').checked = data.config.daily_check_active == 1;
                    document.getElementById('ai-biaya-pesan').value = data.config.biaya_pesan;
                }
                
                if (data.math) {
                    document.getElementById('ai-sample-product').innerText = data.math.sample_product;
                    
                    if (data.math.has_fallback_demand) {
                        document.getElementById('fallback-warning-demand').classList.remove('hidden');
                    } else {
                        document.getElementById('fallback-warning-demand').classList.add('hidden');
                    }
                    
                    if (data.math.has_fallback_lt) {
                        document.getElementById('fallback-warning-lt').classList.remove('hidden');
                    } else {
                        document.getElementById('fallback-warning-lt').classList.add('hidden');
                    }
                    
                    // Step 1
                    document.getElementById('lbl-total-out').innerText = data.math.demand.total_30d;
                    document.getElementById('lbl-mean-sub').innerText = `${data.math.demand.total_30d} / 30 = ${data.math.demand.avg}`;
                    document.getElementById('lbl-variance').innerText = data.math.demand.variance;
                    
                    // Step 2
                    let mu = data.math.demand.avg;
                    let lt = data.math.lead_time.avg;
                    let variance = data.math.demand.variance;
                    let z = data.math.rop.z_score;
                    let mul_lt_var = (lt * variance).toFixed(3);
                    let sqrt_lt_var = Math.sqrt(lt * variance).toFixed(3);
                    let part1 = (mu * lt).toFixed(2);
                    let part2 = (z * sqrt_lt_var).toFixed(2);

                    document.getElementById('lbl-rop-mu').innerText = mu;
                    document.getElementById('lbl-rop-lt').innerText = lt;
                    document.getElementById('lbl-rop-var').innerText = variance;
                    document.getElementById('lbl-rop-z').innerText = z;
                    
                    document.getElementById('lbl-rop-sub-1').innerText = `{{ __('ai_subst') }} (${mu} × ${lt}) + (${z} × √(${lt} × ${variance}))`;
                    document.getElementById('lbl-rop-sub-2').innerText = `{{ __('ai_calc') }} (${part1}) + (${z} × √${mul_lt_var})`;
                    document.getElementById('lbl-rop-sub-3').innerText = `{{ __('ai_calc') }} ${part1} + ${part2}`;
                    document.getElementById('lbl-final-rop').innerText = '{{ __('ai_final_result') }} ' + data.math.rop.final + ' {{ __('ai_units') }}';
                    
                    // Step 3
                    let d = data.math.eoq.annual_demand;
                    let s = data.math.eoq.ordering_cost;
                    let h = data.math.eoq.holding_cost;
                    let top = 2 * d * s;
                    let div = (top / h).toFixed(2);

                    document.getElementById('lbl-eoq-d').innerText = d;
                    document.getElementById('lbl-eoq-s').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(s);
                    document.getElementById('lbl-eoq-h').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(h);

                    document.getElementById('lbl-eoq-sub-1').innerText = `{{ __('ai_subst') }} √( (2 × ${d} × ${s}) / ${h} )`;
                    document.getElementById('lbl-eoq-sub-2').innerText = `{{ __('ai_calc') }} √( ${top} / ${h} )`;
                    document.getElementById('lbl-eoq-sub-3').innerText = `{{ __('ai_calc') }} √${div}`;
                    document.getElementById('lbl-final-eoq').innerText = '{{ __('ai_final_result') }} ' + data.math.eoq.final + ' {{ __('ai_units') }}';
                    
                    document.getElementById('ai-math-loading').classList.add('hidden');
                    document.getElementById('ai-math-content').classList.remove('hidden');
                    
                    // Auto-open step 2 to show the math instantly
                    document.getElementById('acc-step-2').classList.remove('hidden');
                    document.getElementById('icon-acc-step-2').classList.add('rotate-180');

                    setTimeout(() => {
                        document.getElementById('ai-math-content').classList.remove('opacity-0');
                    }, 50);
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('ai-math-loading').innerHTML = '<span class="text-red-500 font-bold">{{ __('ai_math_failed') }}</span>';
            });
    }

    function closeAiConfigModal() {
        document.getElementById('ai-config-modal').classList.add('hidden');
        document.getElementById('ai-math-loading').classList.remove('hidden');
        document.getElementById('ai-math-content').classList.add('opacity-0', 'hidden');
    }

    function saveAiConfig() {
        const btn = document.getElementById('btn-save-ai');
        const originalText = btn.innerText;
        btn.innerText = '{{ __('ai_btn_saving') }}';
        btn.disabled = true;

        const payload = {
            auto_po_active: document.getElementById('ai-toggle-autopo').checked ? 1 : 0,
            daily_check_active: document.getElementById('ai-toggle-daily').checked ? 1 : 0,
            biaya_pesan: document.getElementById('ai-biaya-pesan').value,
            _token: '{{ csrf_token() }}'
        };

        fetch('/api/ai-config', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            btn.innerText = originalText;
            btn.disabled = false;
            if (data.success) {
                closeAiConfigModal();
                Swal.fire({
                    icon: 'success',
                    title: '{{ __('ai_save_success') }}',
                    text: '{{ __('ai_save_success_desc') }}',
                    confirmButtonColor: '#10B981',
                    background: '#1f2937',
                    color: '#f3f4f6'
                });
            }
        });
    }
    @endif
</script>