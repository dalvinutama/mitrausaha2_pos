<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Penyesuaian Select2 dengan Tema Tailwind (Light Mode & Dark Mode) */
        
        /* --- LIGHT MODE (Default) --- */
        .select2-container--default .select2-selection--single {
            background-color: #f9fafb !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.75rem !important;
            height: 3.25rem !important;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1f2937 !important;
            padding-left: 0.5rem !important;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 10px !important;
        }
        /* Dropdown Box Light */
        .select2-dropdown {
            border-color: #d1d5db !important;
            border-radius: 0.75rem !important;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        /* Hover Option Light */
        .select2-results__option--highlighted {
            background-color: #D00000 !important;
            color: white !important;
        }
        /* Selected Option Light (Ralat / Previously Selected) */
        .select2-results__option[aria-selected="true"],
        .select2-results__option--selected {
            background-color: #fee2e2 !important; /* Light Red */
            color: #b91c1c !important; /* Dark Red */
            font-weight: bold;
        }
        /* Override Hover on Selected Light */
        .select2-results__option--highlighted[aria-selected="true"],
        .select2-results__option--highlighted.select2-results__option--selected {
            background-color: #D00000 !important;
            color: white !important;
        }

        /* --- DARK MODE --- */
        html.dark .select2-container--default .select2-selection--single {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
        }
        html.dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f3f4f6 !important;
        }
        html.dark .select2-dropdown {
            background-color: #1f2937 !important;
            border-color: #4b5563 !important;
        }
        html.dark .select2-search input {
            background-color: #374151 !important;
            color: #f3f4f6 !important;
            border-color: #4b5563 !important;
            border-radius: 0.5rem !important;
        }
        /* Hover Option Dark */
        html.dark .select2-results__option--highlighted {
            background-color: #D00000 !important;
            color: white !important;
        }
        /* Selected Option Dark (Ralat / Previously Selected) */
        html.dark .select2-results__option[aria-selected="true"],
        html.dark .select2-results__option--selected {
            background-color: #4b5563 !important; /* Dark Gray */
            color: #f3f4f6 !important; /* White Text */
            font-weight: bold;
        }
        /* Override Hover on Selected Dark */
        html.dark .select2-results__option--highlighted[aria-selected="true"],
        html.dark .select2-results__option--highlighted.select2-results__option--selected {
            background-color: #b91c1c !important; /* Darker Red on Hover to distinguish */
            color: white !important;
        }
        
        /* Remove default white background from options in dark mode */
        html.dark .select2-results__option {
            color: #d1d5db !important;
            background-color: transparent;
        }

    </style>

    <div class="flex h-screen bg-gray-50 dark:bg-gray-900 overflow-hidden font-sans text-gray-800 dark:text-gray-100 transition-colors duration-300">

        @include('layouts.sidebar')

        <div id="overlay" class="fixed inset-0 bg-black/50 hidden z-30 lg:hidden backdrop-blur-sm transition-all"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            @include('layouts.header')

            {{-- PERBAIKAN KONTRAS: bg-gray-100 --}}
            <div class="flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-100 dark:bg-gray-900 custom-scrollbar transition-colors duration-300">
                
                {{-- HEADER HALAMAN --}}
                <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-gray-400 dark:text-gray-500 mb-2">
                            <a href="{{ route('dashboard') }}" class="hover:text-[#D00000] dark:hover:text-red-400 transition-colors" title="{{ __('to_dashboard') }}">
                                <i class="fas fa-home text-sm"></i>
                            </a> 
                            <span>/</span> 
                            <span>{{ __('transaction') }}</span> 
                            <span>/</span> 
                            <span class="text-[#D00000] dark:text-red-400">{{ __('outgoing_items') }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-gray-800 dark:text-white tracking-tight">{{ __('stock_issue') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('stock_issue_desc') }}</p>
                    </div>
                    
                    {{-- Hanya tampilkan No. Transaksi Baru jika User = Penjualan --}}
                    @if(Auth::user()->role === 'penjualan')
                    {{-- PERBAIKAN KONTRAS: shadow-md --}}
                    <div class="bg-white dark:bg-gray-800 px-5 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex items-center gap-4 transition-colors">
                        <div class="w-10 h-10 bg-red-50 dark:bg-red-900/30 text-[#D00000] dark:text-red-500 rounded-xl flex items-center justify-center text-lg border border-red-100 dark:border-red-800/50">
                            <i class="fas fa-truck-loading"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('new_transaction_no') }}</p>
                            <p class="text-lg font-black text-gray-800 dark:text-white">{{ $autoNumber ?? 'BK-'.date('Ymd').'-XXX' }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- NOTIFIKASI --}}
                @if(session('success'))
                    <div class="mb-6 bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded-xl shadow-sm font-bold transition-colors">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded-xl shadow-sm font-bold transition-colors">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- ========================================================= --}}
                {{-- BLOK CREATE (C) - HANYA BISA DIAKSES OLEH BAGIAN PENJUALAN --}}
                {{-- ========================================================= --}}
                @if(Auth::user()->role === 'penjualan')
                
                <form id="formTransaksi" action="{{ route('stok_keluar.store') }}" method="POST" class="space-y-6">
                    @csrf 
                    
                    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 h-[calc(100vh-140px)] min-h-[600px]">
                        
                        {{-- ========================================== --}}
                        {{-- KIRI: KATALOG PRODUK (POS STYLE) --}}
                        {{-- ========================================== --}}
                        <div class="xl:col-span-8 flex flex-col gap-4 h-full overflow-hidden">
                            {{-- Header POS: Kategori & Pencarian --}}
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col sm:flex-row gap-4 items-center justify-between shrink-0">
                                
                                {{-- Kategori Pills --}}
                                <div class="flex gap-2 overflow-x-auto w-full custom-scrollbar pb-4 px-1" id="categoryTabs">
                                      <button type="button" onclick="filterCategory('all')" class="category-btn active px-5 py-2 rounded-full text-xs font-bold bg-slate-800 dark:bg-slate-700 text-white shadow-md border border-slate-700 whitespace-nowrap transition-all" data-category="all">Semua</button>
                                      @foreach($kategoriProduk as $katP)
                                      <button type="button" onclick="filterCategory('{{ $katP->id }}')" class="category-btn px-5 py-2 rounded-full text-xs font-bold bg-transparent border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 whitespace-nowrap transition-all" data-category="{{ $katP->id }}">
                                          {{ $katP->nama_kategori }}
                                      </button>
                                      @endforeach
                                  </div>
                                
                                {{-- Search Bar --}}
                                <div class="relative w-full sm:w-64 shrink-0">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-search text-gray-400"></i></div>
                                    <input type="text" id="posSearch" onkeyup="handleScannerInput(event)" placeholder="{{ __('search_scan_item') }}" class="w-full pl-10 pr-10 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-sm rounded-xl focus:ring-[#D00000] focus:border-[#D00000] transition-colors dark:text-white">
                                    <button type="button" onclick="openBarcodeScanner()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#D00000]"><i class="fas fa-barcode"></i></button>
                                </div>
                            </div>

                            {{-- Grid Produk --}}
                            <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 pb-10">
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4" id="productGrid">
                                    @foreach($products as $p)
                                    @php
                                        // Tentukan ikon berdasarkan kategori secara sederhana
                                        $icon = 'fa-box'; // default
                                        $katName = strtolower($p->kategori->nama_kategori ?? '');
                                        if(str_contains($katName, 'semen')) $icon = 'fa-cubes';
                                        elseif(str_contains($katName, 'paku') || str_contains($katName, 'besi') || str_contains($katName, 'baja')) $icon = 'fa-hammer';
                                        elseif(str_contains($katName, 'cat')) $icon = 'fa-paint-roller';
                                        elseif(str_contains($katName, 'pipa') || str_contains($katName, 'paralon')) $icon = 'fa-water';
                                        elseif(str_contains($katName, 'kabel') || str_contains($katName, 'listrik')) $icon = 'fa-bolt';
                                        elseif(str_contains($katName, 'kayu')) $icon = 'fa-tree';
                                    @endphp
                                    <div class="product-card group bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-200 dark:border-gray-700 hover:border-slate-400 dark:hover:border-slate-500 hover:shadow-lg hover:shadow-slate-200/50 dark:hover:shadow-slate-900/50 transition-all duration-300 cursor-pointer flex flex-col h-32 relative overflow-hidden" 
                                           data-category="{{ $p->kategori_id }}" 
                                           data-name="{{ strtolower($p->nama_barang) }}" 
                                           data-sku="{{ strtolower($p->sku) }}"
                                           data-barcode="{{ $p->barcode }}"
                                           onclick="addToCartQuick('{{ $p->id }}', '{{ addslashes(htmlspecialchars($p->nama_barang, ENT_QUOTES)) }}', {{ $p->harga_jual ?? 0 }}, {{ $p->stok }}, '{{ $p->satuan }}')">
                                          
                                          <div class="flex justify-between items-start mb-2 relative z-10">
                                              <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $p->sku }}</span>
                                              <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $p->stok > 10 ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20' : 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border-red-200 dark:border-red-500/20' }}">Stok: {{ $p->stok }}</span>
                                          </div>
                                          
                                          <h3 class="font-bold text-sm text-gray-800 dark:text-gray-100 leading-snug line-clamp-2 mb-2 relative z-10 group-hover:text-slate-700 dark:group-hover:text-white transition-colors">{{ $p->nama_barang }}</h3>
                                          
                                          <div class="mt-auto flex items-end relative z-10">
                                              <span class="text-[10px] text-gray-500 font-bold mb-0.5 mr-1">Rp</span>
                                              <span class="text-lg font-black text-slate-800 dark:text-white tracking-tight">{{ number_format($p->harga_jual ?? 0, 0, ',', '.') }}</span>
                                          </div>
                                          
                                          <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-gradient-to-br from-transparent to-slate-200 dark:to-slate-600/20 rounded-full blur-xl group-hover:to-slate-300 dark:group-hover:to-slate-500/40 transition-all duration-500"></div>
                                      </div>
                                    @endforeach
                                </div>
                                <div id="noProductMsg" class="hidden text-center py-12 text-gray-400 font-medium">Barang tidak ditemukan.</div>
                            </div>
                        </div>

                        {{-- ========================================== --}}
                        {{-- KANAN: KERANJANG & INFO TUJUAN --}}
                        {{-- ========================================== --}}
                        <div class="xl:col-span-4 flex flex-col gap-4 h-fit sticky top-[80px] z-10">
                            
                            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col h-fit overflow-hidden relative">
                                
                                {{-- DAFTAR KERANJANG (Scrollable) --}}
                                <div class="bg-white dark:bg-gray-800 px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center shrink-0 z-10 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] relative">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-red-50 dark:bg-red-900/30 flex items-center justify-center text-[#D00000] dark:text-red-400">
                                            <i class="fas fa-shopping-cart text-sm"></i>
                                        </div>
                                        <h3 class="font-bold text-gray-800 dark:text-white text-base tracking-tight">Daftar Keranjang</h3>
                                    </div>
                                    <button type="button" onclick="clearCart()" class="group flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-xs text-gray-400 hover:text-red-500 font-semibold transition-all">
                                        <i class="fas fa-trash-alt group-hover:rotate-12 transition-transform"></i> Kosongkan
                                    </button>
                                </div>

                                <div class="h-[400px] shrink-0 overflow-y-auto custom-scrollbar p-2 bg-gray-50/80 dark:bg-gray-900/50 relative" id="cartContainer">
                                    <div id="emptyCartMsg" class="flex flex-col items-center justify-center h-full text-gray-400 pt-16 pb-16">
                                        <div class="w-24 h-24 mb-6 rounded-full bg-gradient-to-tr from-gray-100 to-white dark:from-gray-800 dark:to-gray-700 shadow-inner flex items-center justify-center relative">
                                            <i class="fas fa-shopping-basket text-4xl text-gray-300 dark:text-gray-600"></i>
                                            <div class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center animate-bounce shadow-sm">
                                                <i class="fas fa-arrow-down text-red-400 text-xs"></i>
                                            </div>
                                        </div>
                                        <h4 class="font-bold text-gray-800 dark:text-gray-200 text-lg mb-1 tracking-tight">Belum Ada Barang</h4>
                                        <p class="text-xs text-gray-500 max-w-[200px] text-center leading-relaxed">Pilih barang dari katalog di sebelah kiri untuk menambah ke keranjang belanja.</p>
                                    </div>
                                    <ul id="cartList" class="space-y-2">
                                        {{-- Items will be appended here via JS --}}
                                    </ul>
                                </div>

                                {{-- 3. FOOTER TOTAL & SUBMIT --}}
                                <div class="bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 p-3 shrink-0 z-10 shadow-[0_-10px_20px_-15px_rgba(0,0,0,0.1)]">
                                    <!-- Info Pelanggan & Pengiriman -->
                                    <div class="mb-3 space-y-2">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1">
                                                <input type="text" name="tujuan" placeholder="Nama Pelanggan / Proyek" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-xs p-2.5 focus:ring-red-500 focus:border-red-500 font-semibold text-gray-800 dark:text-gray-200" autocomplete="off">
                                            </div>
                                            <label class="flex items-center justify-center gap-1.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors select-none" title="Dikirim / Delivery?">
                                                <input type="checkbox" name="metode_pengambilan" value="dikirim" id="checkboxDikirim" class="text-red-600 rounded focus:ring-red-500 w-4 h-4 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600" onchange="toggleMetodePengambilanFast()">
                                                <i class="fas fa-truck text-gray-500 text-xs"></i>
                                            </label>
                                        </div>
                                        <input type="hidden" name="kategori_keluar" value="Penjualan">
                                        <input type="hidden" name="outbound_date" value="{{ date('Y-m-d') }}">
                                    </div>

                                    <!-- Bagian Penyesuaian Harga (Diskon/Biaya) -->
                                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-3 border border-gray-100 dark:border-gray-700 mb-3 flex flex-col gap-2">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-1.5 text-xs font-bold text-gray-600 dark:text-gray-300">
                                                <i class="fas fa-tags text-gray-400"></i> Diskon / Ongkir
                                            </div>
                                            <div class="flex bg-white dark:bg-gray-800 rounded-lg p-0.5 border border-gray-200 dark:border-gray-700 shadow-sm text-[10px] font-bold">
                                                <input type="hidden" name="diskon" id="hiddenDiskonValue" value="0">
                                                <input type="hidden" id="adjType" value="-">
                                                <button type="button" id="btnTypeMin" onclick="setAdjType('-')" class="px-2.5 py-1 rounded-md bg-[#D00000] text-white shadow-sm transition-all cursor-pointer">- Diskon</button>
                                                <button type="button" id="btnTypePlus" onclick="setAdjType('+')" class="px-2.5 py-1 rounded-md text-gray-500 hover:text-gray-800 dark:hover:text-white transition-all cursor-pointer">+ Biaya Ongkir</button>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <div class="flex bg-white dark:bg-gray-800 rounded-lg p-0.5 border border-gray-200 dark:border-gray-700 shadow-sm text-[10px] font-bold shrink-0">
                                                <input type="hidden" id="adjUnit" value="Rp">
                                                <button type="button" id="btnUnitRp" onclick="setAdjUnit('Rp')" class="px-3 py-1.5 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm transition-all cursor-pointer">Rp</button>
                                                <button type="button" id="btnUnitPct" onclick="setAdjUnit('%')" class="px-3 py-1.5 rounded-md text-gray-500 hover:text-gray-800 dark:hover:text-white transition-all cursor-pointer">%</button>
                                            </div>
                                            <div class="relative flex-1">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400" id="adjPrefix">Rp</span>
                                                <input type="text" id="inputAdj" value="0" onkeyup="formatAdjInput(this); calculateTotal()" class="w-full text-right bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-white text-sm py-1.5 pl-8 pr-3 rounded-lg focus:ring-2 focus:ring-[#D00000] focus:border-[#D00000] font-black transition-all shadow-sm">
                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 hidden" id="adjSuffix">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center mb-3 px-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-semibold text-gray-500">Total Item:</span>
                                            <span class="text-sm font-black text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-md"><span id="totalItems">0</span></span>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 items-center">
                                        <div class="flex-1">
                                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block mb-0.5">Total Bayar</span>
                                            <span class="text-xl font-black text-[#D00000] dark:text-red-400 leading-none tracking-tight block" id="grandTotal">Rp 0</span>
                                        </div>
                                        <button type="button" onclick="submitFinalPOS()" class="flex-1 bg-gradient-to-r from-[#D00000] to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold text-xs py-2.5 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                                            <i class="fas fa-print text-sm"></i> 
                                            <span>BAYAR & CETAK</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                

                                
</form>
                
                @else
                {{-- ========================================================= --}}
                {{-- BLOK READ ONLY (R) - OWNER, ADMIN, GUDANG, KASIR, PENGIRIMAN --}}
                {{-- ========================================================= --}}
                <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 text-blue-700 dark:text-blue-400 p-4 rounded-xl shadow-sm transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-800/50 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-300 shrink-0 transition-colors shadow-sm">
                            <i class="fas fa-user-shield text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-sm uppercase">{{ __('limited_access_role') }} {{ Auth::user()->role }}</h4>
                            <p class="text-xs mt-0.5">{!! __('no_create_access_desc') !!}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ========================================================= --}}
                {{-- TABEL RIWAYAT TRANSAKSI (TAMPIL UNTUK SEMUA ROLE YANG PUNYA AKSES GET) --}}
                {{-- ========================================================= --}}
                {{-- PERBAIKAN KONTRAS: shadow-md --}}
                <div class="mt-8 bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-md transition-colors relative">
                    <div class="bg-gray-100/50 dark:bg-gray-800/80 px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center transition-colors rounded-t-3xl">
                        <div class="flex items-start sm:items-center gap-2 sm:gap-3">
                            <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/50 text-[#D00000] dark:text-red-400 flex items-center justify-center text-sm shadow-sm"><i class="fas fa-history"></i></div>
                            <h3 class="font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide text-sm flex items-center gap-2">
                                {{ __('outgoing_transaction_history') }}
                                <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-500 cursor-pointer transition-colors text-xs text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Daftar seluruh riwayat transaksi pengeluaran barang yang sebelumnya sudah berhasil dicatat dalam sistem.
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                            </h3>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase bg-gray-200/60 dark:bg-gray-700/50 border-b border-gray-300 dark:border-gray-700 transition-colors">
                                <tr>
                                    <th class="px-6 py-4">{{ __('transaction_no') }}</th>
                                    <th class="px-6 py-4">{{ __('issue_date') }}</th>
                                    <th class="px-6 py-4">{{ __('category') }}</th>
                                    <th class="px-6 py-4">{{ __('destination_customer') }}</th>
                                    <th class="px-6 py-4 text-right">{{ __('total_value_rp') }}</th>
                                    <th class="px-6 py-4">{{ __('created_by') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                                @forelse($transaksiKeluar as $trx)
                                <tr class="hover:bg-red-50/20 dark:hover:bg-red-900/10 transition-colors">
                                    <td class="px-6 py-4 font-bold text-[#D00000] dark:text-red-400">{{ $trx->no_transaksi }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($trx->tanggal)->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded text-[10px] font-bold transition-colors">{{ $trx->kategori_keluar }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-800 dark:text-gray-300">{{ $trx->tujuan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right font-black text-gray-800 dark:text-white">{{ number_format($trx->total_nilai, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-user-circle mr-1 text-gray-400 dark:text-gray-500"></i> {{ $trx->user->name ?? __('system') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-500 italic font-medium">{{ __('no_outgoing_history') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL SCANNER BARCODE --}}
    @if(Auth::user()->role === 'penjualan')
    <div id="barcodeModal" class="fixed inset-0 bg-black/80 z-[100] hidden flex items-center justify-center backdrop-blur-sm p-4 transition-all">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-[dropIn_0.3s_ease-out] transition-colors">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-100 dark:bg-gray-900 transition-colors">
                <h3 class="font-black text-gray-800 dark:text-white text-lg"><i class="fas fa-camera text-[#D00000] dark:text-red-500 mr-2"></i> {{ __('scan_barcode') }}</h3>
                <button type="button" onclick="closeBarcodeScanner()" class="text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-5 bg-black flex justify-center items-center min-h-[300px] relative">
                <div class="absolute inset-0 pointer-events-none flex items-center justify-center z-10">
                    <div class="w-48 h-48 border-2 border-[#D00000] dark:border-red-500 rounded-lg shadow-[0_0_0_9999px_rgba(0,0,0,0.5)]"></div>
                </div>
                <div id="reader" class="w-full max-w-[350px] rounded-lg overflow-hidden"></div>
            </div>
            <div class="p-5 text-center bg-white dark:bg-gray-800 transition-colors border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-4">{{ __('point_camera_to_barcode_out') }}</p>
                <button type="button" onclick="closeBarcodeScanner()" class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 font-bold py-3 rounded-xl transition-colors text-sm shadow-sm">{{ __('close_camera') }}</button>
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================== --}}
    {{-- SCANNER RADAR: FLOATING INDICATOR + HELP TOOLTIP --}}
    {{-- ============================================================== --}}
    @if(Auth::user()->role === 'penjualan')
    <div id="scannerRadarIndicator" class="fixed bottom-6 right-6 z-[90] flex items-center gap-2">
        {{-- Help Button with Tooltip --}}
        <div class="relative">
            <button type="button" onclick="toggleScannerHelp()" id="scannerHelpBtn" class="w-9 h-9 rounded-full bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border border-gray-200 dark:border-gray-700 shadow-lg flex items-center justify-center text-gray-400 hover:text-blue-500 dark:hover:text-blue-400 transition-all hover:scale-110 hover:shadow-xl" title="Panduan Scanner">
                <i class="fas fa-question-circle text-sm"></i>
            </button>
            {{-- Tooltip Panel --}}
            <div id="scannerHelpTooltip" class="hidden absolute bottom-full right-0 mb-3 w-80 bg-white dark:bg-gray-800 rounded-2xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] dark:shadow-[0_20px_60px_-15px_rgba(0,0,0,0.6)] border border-gray-200 dark:border-gray-700 overflow-hidden animate-[dropIn_0.2s_ease-out]">
                <div class="bg-gradient-to-r from-slate-800 to-slate-900 dark:from-gray-900 dark:to-gray-950 px-5 py-3 flex items-center justify-between">
                    <h4 class="text-white font-bold text-sm flex items-center gap-2"><i class="fas fa-satellite-dish text-yellow-400"></i> Panduan Scanner Barcode</h4>
                    <button type="button" onclick="toggleScannerHelp()" class="text-gray-400 hover:text-white transition-colors"><i class="fas fa-times text-xs"></i></button>
                </div>
                <div class="p-4 space-y-4 text-xs max-h-[400px] overflow-y-auto custom-scrollbar">
                    {{-- USB Scanner --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-3 border border-blue-100 dark:border-blue-800/50">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 rounded-lg bg-blue-500 text-white flex items-center justify-center shadow-sm"><i class="fas fa-usb text-xs"></i></div>
                            <span class="font-bold text-blue-800 dark:text-blue-300 text-sm">Scanner USB (Kabel)</span>
                        </div>
                        <ol class="space-y-1.5 text-gray-700 dark:text-gray-300 list-decimal list-inside leading-relaxed">
                            <li><b>Colokkan</b> kabel USB scanner ke port USB komputer/laptop.</li>
                            <li>Tunggu 2-3 detik — <b>Tidak perlu instal driver</b> apapun.</li>
                            <li>Buka halaman ini, lalu <b>tembak barcode</b> ke arah barang.</li>
                            <li>Barang otomatis masuk keranjang! <i class="fas fa-check-circle text-green-500"></i></li>
                        </ol>
                    </div>
                    {{-- Bluetooth Scanner --}}
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-xl p-3 border border-indigo-100 dark:border-indigo-800/50">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 rounded-lg bg-indigo-500 text-white flex items-center justify-center shadow-sm"><i class="fab fa-bluetooth-b text-xs"></i></div>
                            <span class="font-bold text-indigo-800 dark:text-indigo-300 text-sm">Scanner Bluetooth (Nirkabel)</span>
                        </div>
                        <ol class="space-y-1.5 text-gray-700 dark:text-gray-300 list-decimal list-inside leading-relaxed">
                            <li><b>Nyalakan</b> scanner Bluetooth dan masuk ke mode pairing.</li>
                            <li>Di HP/Tablet/Laptop, buka <b>Pengaturan Bluetooth</b> → cari nama scanner → <b>Pair/Hubungkan</b>.</li>
                            <li>Setelah tersambung, buka halaman ini di browser.</li>
                            <li>Tembak barcode — barang langsung masuk keranjang! <i class="fas fa-check-circle text-green-500"></i></li>
                        </ol>
                    </div>
                    {{-- Tips --}}
                    <div class="bg-amber-50 dark:bg-amber-900/10 rounded-xl p-3 border border-amber-100 dark:border-amber-800/50">
                        <div class="flex items-center gap-2 mb-1.5">
                            <i class="fas fa-lightbulb text-amber-500"></i>
                            <span class="font-bold text-amber-800 dark:text-amber-400 text-sm">Tips Penting</span>
                        </div>
                        <ul class="space-y-1 text-gray-700 dark:text-gray-300 list-disc list-inside leading-relaxed">
                            <li>Scanner dikenali sebagai <b>keyboard</b> — tidak perlu software tambahan.</li>
                            <li>Sistem otomatis mendeteksi tembakan scanner vs ketikan manusia berdasarkan <b>kecepatan ketik</b>.</li>
                            <li>Anda <b>tidak perlu</b> klik kolom pencarian terlebih dahulu. Cukup tembak kapan saja!</li>
                            <li>Untuk barang curah (kayu, pasir), gunakan <b>Buku Katalog Barcode</b> di meja kasir.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        {{-- Scanner Status Badge --}}
        <div class="flex items-center gap-2 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md px-4 py-2.5 rounded-full border border-gray-200 dark:border-gray-700 shadow-lg transition-all" id="scannerBadge">
            <div class="relative">
                <i class="fas fa-satellite-dish text-gray-400 dark:text-gray-500 text-sm" id="scannerIcon"></i>
                <div class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-emerald-400 border border-white dark:border-gray-800" id="scannerDot"></div>
            </div>
            <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider" id="scannerLabel">Scanner Siap</span>
        </div>
    </div>

    {{-- Toast Notification Container --}}
    <div id="scannerToast" class="fixed top-6 right-6 z-[200] pointer-events-none hidden">
        <div class="pointer-events-auto flex items-center gap-3 px-5 py-3 rounded-2xl shadow-2xl border animate-[dropIn_0.25s_ease-out] min-w-[280px]" id="scannerToastInner">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0" id="scannerToastIcon"></div>
            <div>
                <p class="font-bold text-sm leading-tight" id="scannerToastTitle"></p>
                <p class="text-xs mt-0.5 opacity-80" id="scannerToastSub"></p>
            </div>
        </div>
    </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        /* Dark mode scrollbar */
        html.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }
        html.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }
        
        @keyframes dropIn { from { opacity: 0; transform: scale(0.95) translateY(-10px); } to { opacity: 1; transform: scale(1) translateY(0); } }

        /* Scanner Radar Pulse Animation */
        @keyframes scanPulse {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        #scannerBadge.scanning { animation: scanPulse 0.6s ease-out; }
    </style>


    <script>
function toggleSidebar() { document.getElementById('sidebar').classList.toggle('-translate-x-full'); document.getElementById('overlay').classList.toggle('hidden'); }
        document.getElementById('overlay')?.addEventListener('click', toggleSidebar);

        @if(Auth::user()->role === 'penjualan')
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ !== 'undefined') {
                // Inisialisasi Select2 untuk pencarian material
                $('#temp_product').select2({
                    placeholder: "{{ __('search_scan_item') }}",
                    width: '100%',
                    dropdownAutoWidth: true
                });

                // Inisialisasi Select2 untuk kategori
                $('#kategoriSelect').select2({
                    placeholder: "{{ __('select_category') }}",
                    width: '100%',
                    dropdownAutoWidth: true
                });

                // Event listener Material berubah
                $('#temp_product').on('change', function() {
                    updateTempInfo();
                });
            }
        });

        // ==========================================
        // LOGIKA KATEGORI DINAMIS (AJAX CRUD)
        // ==========================================
        const getSweetAlertConfig = () => {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                background: isDark ? '#1f2937' : '#fff',
                color: isDark ? '#f3f4f6' : '#545454'
            };
        };

        function tambahKategori() {
            const theme = getSweetAlertConfig();
            
            Swal.fire({
                title: '{{ __('new_issue_category') }}',
                input: 'text',
                inputPlaceholder: '{{ __('type_category_name') }}',
                showCancelButton: true,
                confirmButtonText: '{{ __('save') }}',
                cancelButtonText: '{{ __('cancel') }}',
                confirmButtonColor: '#D00000',
                showLoaderOnConfirm: true,
                ...theme,
                preConfirm: (kategoriName) => {
                    if (!kategoriName) {
                        Swal.showValidationMessage('{{ __('category_name_not_empty') }}');
                        return false;
                    }
                    
                    return fetch(`{{ route('stok_keluar.kategori.store') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ nama_kategori: kategoriName })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errInfo => {
                                throw new Error(errInfo.error || '{{ __('system_error_on_save') }}');
                            });
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`{{ __('failed') }} ${error.message}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    const newKategori = result.value.nama;
                    const newId = result.value.id;
                    
                    const select = $('#kategoriSelect');
                    const newOption = new Option(newKategori, newKategori, true, true);
                    $(newOption).attr('data-id', newId);
                    select.append(newOption).trigger('change');
                    
                    Swal.fire({icon: 'success', title: '{{ __('success') }}', text: '{{ __('new_category_added') }}', timer: 1500, showConfirmButton: false, ...theme});
                }
            });
        }

        function editKategori() {
            const select = $('#kategoriSelect');
            const selectedOption = select.find('option:selected');
            const id = selectedOption.attr('data-id');
            const currentName = selectedOption.val();
            const theme = getSweetAlertConfig();

            if (!id || !currentName) {
                Swal.fire({icon: 'warning', title: 'Oops...', text: 'Pilih kategori yang ingin diubah terlebih dahulu!', confirmButtonColor: '#D00000', ...theme});
                return;
            }

            Swal.fire({
                title: 'Edit Kategori',
                input: 'text',
                inputValue: currentName,
                inputPlaceholder: 'Nama Kategori Baru',
                showCancelButton: true,
                confirmButtonText: 'Simpan Perubahan',
                cancelButtonText: '{{ __('cancel') }}',
                confirmButtonColor: '#D00000',
                showLoaderOnConfirm: true,
                ...theme,
                preConfirm: (newName) => {
                    if (!newName || newName === currentName) {
                        Swal.showValidationMessage('Nama kategori tidak boleh kosong atau sama.');
                        return false;
                    }
                    
                    return fetch(`{{ url('stok_keluar/kategori') }}/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ nama_kategori: newName })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errInfo => {
                                throw new Error(errInfo.error || 'Gagal mengubah kategori.');
                            });
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`{{ __('failed') }} ${error.message}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    const newName = result.value.nama;
                    selectedOption.val(newName).text(newName);
                    select.trigger('change');
                    Swal.fire({icon: 'success', title: 'Berhasil', text: 'Kategori berhasil diubah', timer: 1500, showConfirmButton: false, ...theme});
                }
            });
        }

        function hapusKategori() {
            const select = $('#kategoriSelect');
            const selectedOption = select.find('option:selected');
            const id = selectedOption.attr('data-id');
            const currentName = selectedOption.val();
            const theme = getSweetAlertConfig();

            if (!id || !currentName) {
                Swal.fire({icon: 'warning', title: 'Oops...', text: 'Pilih kategori yang ingin dihapus terlebih dahulu!', confirmButtonColor: '#D00000', ...theme});
                return;
            }

            Swal.fire({
                title: 'Hapus Kategori?',
                text: `Anda yakin ingin menghapus kategori "${currentName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D00000',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                ...theme
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('stok_keluar/kategori') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal menghapus kategori.');
                        return response.json();
                    })
                    .then(data => {
                        selectedOption.remove();
                        select.val('').trigger('change');
                        Swal.fire({icon: 'success', title: 'Terhapus!', text: 'Kategori berhasil dihapus.', timer: 1500, showConfirmButton: false, ...theme});
                    })
                    .catch(error => {
                        Swal.fire({icon: 'error', title: 'Gagal', text: error.message, confirmButtonColor: '#D00000', ...theme});
                    });
                }
            });
        }

        // ==========================================
        // ==========================================
        // LOGIKA KERANJANG BARANG KELUAR (POS)
        // ==========================================
        let cartItems = {};

        function addToCartQuick(id, name, price, stock, satuan) {
            const isDark = document.documentElement.classList.contains('dark');
            const bgPopup = isDark ? '#1f2937' : '#fff';
            const colorText = isDark ? '#f3f4f6' : '#545454';

            if (stock <= 0) {
                Swal.fire({ icon: 'warning', title: 'Stok Habis', text: 'Stok barang ini kosong.', confirmButtonColor: '#D00000', background: bgPopup, color: colorText });
                return;
            }

            if (cartItems[id]) {
                if (cartItems[id].qty >= stock) {
                    Swal.fire({ icon: 'error', title: 'Stok Tidak Cukup', text: `Maksimal stok yang tersedia hanya ${stock} ${satuan}.`, confirmButtonColor: '#D00000', background: bgPopup, color: colorText });
                    return;
                }
                cartItems[id].qty++;
            } else {
                cartItems[id] = { id, name, price: parseInt(price), qty: 1, stock: parseInt(stock), satuan };
            }
            renderCart();
            
            // Animasi Feedback
            const card = document.querySelector(`.product-card[data-category][onclick*="'${id}'"]`);
            if(card) {
                card.style.transform = 'scale(0.95)';
                setTimeout(() => card.style.transform = '', 150);
            }
        }

        function updateCartQty(id, delta) {
            const isDark = document.documentElement.classList.contains('dark');
            const bgPopup = isDark ? '#1f2937' : '#fff';
            const colorText = isDark ? '#f3f4f6' : '#545454';

            if (cartItems[id]) {
                const newQty = cartItems[id].qty + delta;
                if (newQty <= 0) {
                    delete cartItems[id];
                } else if (newQty > cartItems[id].stock) {
                    Swal.fire({ icon: 'error', title: 'Stok Tidak Cukup', text: `Maksimal stok yang tersedia hanya ${cartItems[id].stock} ${cartItems[id].satuan}.`, confirmButtonColor: '#D00000', background: bgPopup, color: colorText });
                    return;
                } else {
                    cartItems[id].qty = newQty;
                }
                renderCart();
            }
        }

        
        function updateCartQtyDirect(id, newQty) {
            const isDark = document.documentElement.classList.contains('dark');
            const bgPopup = isDark ? '#1f2937' : '#fff';
            const colorText = isDark ? '#f3f4f6' : '#545454';
            
            if (cartItems[id]) {
                const qty = parseInt(newQty);
                if (isNaN(qty) || qty <= 0) {
                    delete cartItems[id];
                } else if (qty > cartItems[id].stock) {
                    Swal.fire({ icon: 'error', title: 'Stok Tidak Cukup', text: `Maksimal stok yang tersedia hanya ${cartItems[id].stock} ${cartItems[id].satuan}.`, confirmButtonColor: '#D00000', background: bgPopup, color: colorText });
                    cartItems[id].qty = cartItems[id].stock;
                } else {
                    cartItems[id].qty = qty;
                }
                renderCart();
            }
        }

        function handleScannerInput(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchVal = e.target.value.toLowerCase().trim();
                if (!searchVal) return;

                const cards = document.querySelectorAll('.product-card');
                let exactMatch = null;
                let matchCount = 0;

                cards.forEach(card => {
                    const sku = card.getAttribute('data-sku') || '';
                    const barcode = card.getAttribute('data-barcode') || '';
                    if (!card.classList.contains('hidden')) {
                        matchCount++;
                    }
                    if (sku === searchVal || barcode.toLowerCase() === searchVal) {
                        exactMatch = card;
                    }
                });

                if (exactMatch) {
                    exactMatch.click();
                    e.target.value = '';
                    filterSearch();
                } else if (matchCount === 1) {
                    const visibleCard = document.querySelector('.product-card:not(.hidden)');
                    if (visibleCard) {
                        visibleCard.click();
                        e.target.value = '';
                        filterSearch();
                    }
                }
            } else {
                filterSearch();
            }
        }

        function updateCartPrice(id, newPrice) {
            if (cartItems[id]) {
                cartItems[id].price = parseInt(newPrice.replace(/[^0-9]/g, '')) || 0;
                renderCart();
            }
        }

        function removeCartItem(id) {
            delete cartItems[id];
            renderCart();
        }

        function clearCart() {
            cartItems = {};
            renderCart();
        }

        function setAdjType(type) {
            document.getElementById('adjType').value = type;
            const btnMin = document.getElementById('btnTypeMin');
            const btnPlus = document.getElementById('btnTypePlus');
            if (type === '-') {
                btnMin.className = "px-2.5 py-1 rounded-md bg-[#D00000] text-white shadow-sm transition-all cursor-pointer";
                btnPlus.className = "px-2.5 py-1 rounded-md text-gray-500 hover:text-gray-800 dark:hover:text-white transition-all cursor-pointer";
            } else {
                btnPlus.className = "px-2.5 py-1 rounded-md bg-blue-600 text-white shadow-sm transition-all cursor-pointer";
                btnMin.className = "px-2.5 py-1 rounded-md text-gray-500 hover:text-gray-800 dark:hover:text-white transition-all cursor-pointer";
            }
            calculateTotal();
        }

        function setAdjUnit(unit) {
            document.getElementById('adjUnit').value = unit;
            const btnRp = document.getElementById('btnUnitRp');
            const btnPct = document.getElementById('btnUnitPct');
            const prefix = document.getElementById('adjPrefix');
            const suffix = document.getElementById('adjSuffix');
            const input = document.getElementById('inputAdj');
            
            input.value = "0"; // Reset value when changing unit

            if (unit === 'Rp') {
                btnRp.className = "px-3 py-1.5 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm transition-all cursor-pointer";
                btnPct.className = "px-3 py-1.5 rounded-md text-gray-500 hover:text-gray-800 dark:hover:text-white transition-all cursor-pointer";
                prefix.classList.remove('hidden');
                suffix.classList.add('hidden');
                input.classList.remove('pr-8', 'pl-3');
                input.classList.add('pl-8', 'pr-3');
            } else {
                btnPct.className = "px-3 py-1.5 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm transition-all cursor-pointer";
                btnRp.className = "px-3 py-1.5 rounded-md text-gray-500 hover:text-gray-800 dark:hover:text-white transition-all cursor-pointer";
                prefix.classList.add('hidden');
                suffix.classList.remove('hidden');
                input.classList.remove('pl-8', 'pr-3');
                input.classList.add('pr-8', 'pl-3');
            }
            calculateTotal();
        }

        function formatAdjInput(el) {
            const unit = document.getElementById('adjUnit').value;
            if (unit === '%') {
                let val = parseInt(el.value.replace(/[^0-9]/g, '')) || 0;
                if (val > 100) val = 100;
                el.value = val;
            } else {
                el.value = el.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
        }

        function calculateTotal() {
            let subtotalAll = 0;
            for (const id in cartItems) {
                subtotalAll += (cartItems[id].qty * cartItems[id].price);
            }
            
            const adjType = document.getElementById('adjType').value;
            const adjUnit = document.getElementById('adjUnit').value;
            const adjInput = document.getElementById('inputAdj');
            
            let adjValue = 0;
            if (adjInput) {
                adjValue = parseInt(adjInput.value.replace(/[^0-9]/g, '')) || 0;
            }
            
            let finalAdjAmount = 0;
            if (adjUnit === '%') {
                finalAdjAmount = (subtotalAll * adjValue) / 100;
            } else {
                finalAdjAmount = adjValue;
            }

            // Jika '-', itu diskon. Jika '+', itu biaya tambahan (dikirim negatif diskon).
            let trueDiscount = (adjType === '-') ? finalAdjAmount : -finalAdjAmount;
            
            // Simpan ke hidden input (untuk backend)
            document.getElementById('hiddenDiskonValue').value = trueDiscount;
            
            let finalTotal = subtotalAll - trueDiscount;
            if(finalTotal < 0) finalTotal = 0;
            
            document.getElementById('grandTotal').innerText = 'Rp ' + finalTotal.toLocaleString('id-ID');
        }

        function renderCart() {
            const list = document.getElementById('cartList');
            const emptyMsg = document.getElementById('emptyCartMsg');
            let total = 0;
            let count = 0;
            
            list.innerHTML = '';
            
            if (Object.keys(cartItems).length === 0) {
                emptyMsg.classList.remove('hidden');
                document.getElementById('totalItems').innerText = 0;
                document.getElementById('grandTotal').innerText = 'Rp 0';
                return;
            }
            
            emptyMsg.classList.add('hidden');
            
            for (const id in cartItems) {
                const item = cartItems[id];
                const subtotal = item.qty * item.price;
                total += subtotal;
                count++;
                
                const li = document.createElement('li');
                li.className = 'group bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-[0_4px_15px_-5px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_25px_-5px_rgba(0,0,0,0.1)] hover:border-red-100 dark:hover:border-red-900/50 transition-all duration-300 relative overflow-hidden flex flex-col gap-3';
                li.innerHTML = `
                    <div class="flex flex-col px-2 py-1.5 w-full relative group">
                        <div class="flex justify-between items-start mb-1.5">
                            <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100 leading-tight line-clamp-1 flex-1 pr-2 cursor-default" title="${item.name}">${item.name}</h4>
                            <button type="button" onclick="removeCartItem('${id}')" class="text-gray-300 dark:text-gray-600 hover:text-red-500 dark:hover:text-red-400 transition-colors p-0.5"><i class="fas fa-times text-xs"></i></button>
                        </div>
                        <div class="flex items-center justify-between gap-1.5 bg-white dark:bg-gray-800 rounded-lg p-1.5 border border-gray-200 dark:border-gray-700 shadow-sm group-hover:border-red-200 transition-colors">
                            <div class="flex items-center bg-gray-50 dark:bg-gray-900 px-1.5 rounded border border-gray-200 dark:border-gray-700">
                                <span class="text-[9px] text-gray-400 mr-1">Rp</span>
                                <input type="text" value="${item.price.toLocaleString('id-ID')}" onchange="updateCartPrice('${id}', this.value)" onkeyup="this.value=this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')" class="w-16 text-xs font-bold p-0 border-none bg-transparent text-gray-800 dark:text-white focus:ring-0 h-6">
                            </div>
                            <span class="text-gray-300 text-[9px]"><i class="fas fa-times"></i></span>
                            <div class="flex items-center bg-gray-50 dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700">
                                <button type="button" onclick="updateCartQty('${id}', -1)" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:bg-red-100 hover:text-red-600 transition-colors rounded-l border-r border-gray-200 dark:border-gray-700"><i class="fas fa-minus text-[8px]"></i></button>
                                <input type="number" value="${item.qty}" onchange="updateCartQtyDirect('${id}', this.value)" class="w-8 text-center text-xs font-black p-0 border-none bg-transparent text-gray-800 dark:text-white focus:ring-0 h-6 appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" onclick="updateCartQty('${id}', 1)" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:bg-green-100 hover:text-green-600 transition-colors rounded-r border-l border-gray-200 dark:border-gray-700"><i class="fas fa-plus text-[8px]"></i></button>
                            </div>
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-200 uppercase ml-1 min-w-[28px]">${item.satuan}</span>
                            <div class="flex-1 text-right ml-1">
                                <p class="font-black text-[#D00000] dark:text-red-400 text-[13px] tracking-tight leading-none">Rp ${subtotal.toLocaleString('id-ID')}</p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="product_id[]" value="${id}">
                    <input type="hidden" name="qty[]" value="${item.qty}">
                    <input type="hidden" name="price[]" value="${item.price}">
                `;
                list.appendChild(li);
            }
            
            document.getElementById('totalItems').innerText = count;
            calculateTotal();
        }

        function filterCategory(catId) {
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.className = "category-btn px-5 py-2 rounded-full text-xs font-bold bg-transparent border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 whitespace-nowrap transition-all";
            });
            const activeBtn = document.querySelector(`.category-btn[data-category="${catId}"]`);
            if(activeBtn) {
                activeBtn.className = "category-btn active px-5 py-2 rounded-full text-xs font-bold bg-slate-800 dark:bg-slate-700 text-white shadow-md border border-slate-700 whitespace-nowrap transition-all";
            }
            filterCards();
        }

        function filterCards() {
            const searchInput = document.getElementById('posSearch');
            const searchVal = searchInput ? searchInput.value.toLowerCase().trim() : '';
            const activeCat = document.querySelector('.category-btn.active')?.getAttribute('data-category') || 'all';
            let visibleCount = 0;

            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.getAttribute('data-name') || '';
                const sku = card.getAttribute('data-sku') || '';
                const barcode = card.getAttribute('data-barcode') || '';
                const cat = card.getAttribute('data-category') || '';

                const matchSearch = !searchVal || name.includes(searchVal) || sku.includes(searchVal) || barcode.toLowerCase().includes(searchVal);
                const matchCat = activeCat === 'all' || cat === activeCat;

                if (matchSearch && matchCat) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            const noMsg = document.getElementById('noProductMsg');
            if (noMsg) {
                if (visibleCount === 0) {
                    noMsg.classList.remove('hidden');
                } else {
                    noMsg.classList.add('hidden');
                }
            }
        }

        // Mapping old filterSearch to filterCards to not break other events
        function filterSearch() {
            filterCards();
        }

        

        function toggleMetodePengambilanFast() {
            const isDikirim = document.getElementById('checkboxDikirim').checked;
            const inputTujuan = document.querySelector('input[name="tujuan"]');
            if (isDikirim) {
                inputTujuan.placeholder = "Alamat Pengiriman (*)";
                inputTujuan.setAttribute('required', 'required');
            } else {
                inputTujuan.placeholder = "Nama Pelanggan / Proyek";
                inputTujuan.removeAttribute('required');
            }
        }

        function submitFinalPOS() {
            const isDikirim = document.getElementById('checkboxDikirim') ? document.getElementById('checkboxDikirim').checked : false;
            const tujuanInput = document.querySelector('input[name="tujuan"]');
            
            const isDark = document.documentElement.classList.contains('dark');
            const theme = { background: isDark ? '#1f2937' : '#fff', color: isDark ? '#f3f4f6' : '#545454' };

            if (Object.keys(cartItems).length === 0) {
                Swal.fire({ icon: 'warning', title: 'Keranjang Kosong', text: 'Pilih minimal 1 barang.', confirmButtonColor: '#D00000', ...theme });
                return false;
            }
            
            if(isDikirim && (!tujuanInput || !tujuanInput.value.trim())) {
                Swal.fire({ icon: 'warning', title: 'Alamat Kosong', text: 'Silakan isi alamat pengiriman.', confirmButtonColor: '#D00000', ...theme });
                if(tujuanInput) tujuanInput.focus();
                return false;
            }

            Swal.fire({
                  title: 'Menyimpan Transaksi...',
                  text: 'Mohon tunggu sebentar',
                  allowOutsideClick: false,
                  showConfirmButton: false,
                  ...theme,
                  didOpen: () => {
                      Swal.showLoading();
                      document.getElementById('formTransaksi').submit();
                  }
              });
          }

        // ==========================================
        // 🔫 SCANNER RADAR: GLOBAL BARCODE LISTENER
        // ==========================================
        // Mendeteksi tembakan barcode scanner fisik (USB/Bluetooth)
        // berdasarkan kecepatan ketik super cepat (<50ms per karakter)
        
        let scanBuffer = '';
        let scanLastTime = 0;
        let scanTimeout = null;
        const SCAN_SPEED_THRESHOLD = 50;  // Max ms antara karakter (scanner ~10-20ms, manusia ~100-300ms)
        const SCAN_MIN_LENGTH = 5;        // Minimum panjang barcode
        
        document.addEventListener('keydown', function(e) {
            // Abaikan jika sedang dalam modal/popup atau input text yang sedang di-focus manual
            const activeEl = document.activeElement;
            const tagName = activeEl ? activeEl.tagName.toLowerCase() : '';
            const isInModal = activeEl && activeEl.closest('#barcodeModal, .swal2-container, #scannerHelpTooltip');
            
            if (isInModal) return;
            
            const now = Date.now();
            const timeDiff = now - scanLastTime;
            scanLastTime = now;
            
            // Jika Enter ditekan
            if (e.key === 'Enter') {
                if (scanBuffer.length >= SCAN_MIN_LENGTH) {
                    // BINGO! Ini tembakan scanner!
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    const barcode = scanBuffer.trim();
                    scanBuffer = '';
                    clearTimeout(scanTimeout);
                    
                    processScannerBarcode(barcode);
                    return;
                }
                // Bukan scanner, biarkan Enter berfungsi normal
                scanBuffer = '';
                clearTimeout(scanTimeout);
                return;
            }
            
            // Abaikan tombol modifier dan non-printable
            if (e.key.length > 1 && e.key !== 'Shift') {
                scanBuffer = '';
                clearTimeout(scanTimeout);
                return;
            }
            
            // Karakter printable
            if (e.key.length === 1) {
                if (timeDiff < SCAN_SPEED_THRESHOLD || scanBuffer.length === 0) {
                    // Tambahkan ke buffer (kecepatan kilat = scanner)
                    scanBuffer += e.key;
                    
                    // Jika ini diketik di kolom input, cegah karakter masuk ke kolom
                    if (scanBuffer.length >= 3 && tagName !== 'textarea') {
                        // Hanya intercept jika kita yakin ini scanner (sudah kumpul 3+ karakter cepat)
                        if (tagName === 'input' && activeEl.id !== 'posSearch') {
                            e.preventDefault();
                        }
                    }
                    
                    // Pulse indicator
                    const badge = document.getElementById('scannerBadge');
                    if (badge) {
                        badge.classList.remove('scanning');
                        void badge.offsetWidth; // Force reflow
                        badge.classList.add('scanning');
                    }
                } else {
                    // Terlalu lambat — reset buffer, ini manusia
                    scanBuffer = e.key;
                }
                
                // Auto-clear buffer setelah 200ms tanpa input baru
                clearTimeout(scanTimeout);
                scanTimeout = setTimeout(() => { scanBuffer = ''; }, 200);
            }
        }, true); // useCapture = true agar intercept sebelum listener lain
        
        function processScannerBarcode(barcode) {
            const barcodeClean = barcode.toLowerCase().trim();
            
            // Cari di product cards berdasarkan data-barcode
            const cards = document.querySelectorAll('.product-card');
            let matchedCard = null;
            
            cards.forEach(card => {
                const cardBarcode = (card.getAttribute('data-barcode') || '').toLowerCase();
                const cardSku = (card.getAttribute('data-sku') || '').toLowerCase();
                
                if (cardBarcode === barcodeClean || cardSku === barcodeClean) {
                    matchedCard = card;
                }
            });
            
            if (matchedCard) {
                // SUKSES! Klik kartu untuk menambahkan ke keranjang
                matchedCard.click();
                
                // Animasi highlight pada kartu
                matchedCard.style.transition = 'all 0.15s ease';
                matchedCard.style.transform = 'scale(0.92)';
                matchedCard.style.boxShadow = '0 0 0 3px rgba(16,185,129,0.5)';
                setTimeout(() => {
                    matchedCard.style.transform = '';
                    matchedCard.style.boxShadow = '';
                }, 200);
                
                // Ambil nama barang dari kartu
                const itemName = matchedCard.querySelector('h3')?.innerText || 'Barang';
                
                showScannerToast('success', '✓ ' + itemName, 'Berhasil masuk keranjang');
                playBeep('success');
            } else {
                // GAGAL — Barcode tidak ditemukan di katalog
                showScannerToast('error', '✗ Barcode Tidak Ditemukan', barcode);
                playBeep('error');
            }
        }
        
        // ==========================================
        // TOAST NOTIFICATION SYSTEM
        // ==========================================
        let toastTimer = null;
        
        function showScannerToast(type, title, subtitle) {
            const toast = document.getElementById('scannerToast');
            const inner = document.getElementById('scannerToastInner');
            const iconEl = document.getElementById('scannerToastIcon');
            const titleEl = document.getElementById('scannerToastTitle');
            const subEl = document.getElementById('scannerToastSub');
            
            if (!toast) return;
            
            clearTimeout(toastTimer);
            
            titleEl.innerText = title;
            subEl.innerText = subtitle;
            
            if (type === 'success') {
                inner.className = 'pointer-events-auto flex items-center gap-3 px-5 py-3 rounded-2xl shadow-2xl border animate-[dropIn_0.25s_ease-out] min-w-[280px] bg-emerald-50 dark:bg-emerald-900/30 border-emerald-200 dark:border-emerald-800/50';
                iconEl.className = 'w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0 bg-emerald-500 text-white shadow-sm';
                iconEl.innerHTML = '<i class="fas fa-check"></i>';
                titleEl.className = 'font-bold text-sm leading-tight text-emerald-800 dark:text-emerald-300';
                subEl.className = 'text-xs mt-0.5 opacity-80 text-emerald-700 dark:text-emerald-400';
            } else {
                inner.className = 'pointer-events-auto flex items-center gap-3 px-5 py-3 rounded-2xl shadow-2xl border animate-[dropIn_0.25s_ease-out] min-w-[280px] bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-800/50';
                iconEl.className = 'w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0 bg-red-500 text-white shadow-sm';
                iconEl.innerHTML = '<i class="fas fa-times"></i>';
                titleEl.className = 'font-bold text-sm leading-tight text-red-800 dark:text-red-300';
                subEl.className = 'text-xs mt-0.5 opacity-80 text-red-700 dark:text-red-400';
            }
            
            toast.classList.remove('hidden');
            
            toastTimer = setTimeout(() => {
                toast.classList.add('hidden');
            }, type === 'success' ? 1500 : 2500);
        }
        
        // ==========================================
        // AUDIO FEEDBACK (via AudioContext — no file needed)
        // ==========================================
        function playBeep(type) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                
                if (type === 'success') {
                    osc.frequency.value = 880;  // A5 — nada tinggi
                    gain.gain.value = 0.15;
                    osc.start();
                    osc.stop(ctx.currentTime + 0.1);
                    
                    // Nada kedua (naik)
                    setTimeout(() => {
                        const ctx2 = new (window.AudioContext || window.webkitAudioContext)();
                        const osc2 = ctx2.createOscillator();
                        const gain2 = ctx2.createGain();
                        osc2.connect(gain2);
                        gain2.connect(ctx2.destination);
                        osc2.frequency.value = 1318; // E6
                        gain2.gain.value = 0.12;
                        osc2.start();
                        osc2.stop(ctx2.currentTime + 0.12);
                    }, 80);
                } else {
                    osc.frequency.value = 220; // A3 — nada rendah (error)
                    gain.gain.value = 0.2;
                    osc.start();
                    osc.stop(ctx.currentTime + 0.25);
                }
            } catch(e) { /* AudioContext not supported, silent fallback */ }
        }
        
        // ==========================================
        // SCANNER HELP TOOLTIP TOGGLE
        // ==========================================
        function toggleScannerHelp() {
            const tooltip = document.getElementById('scannerHelpTooltip');
            if (tooltip) {
                tooltip.classList.toggle('hidden');
            }
        }
        
        // Close tooltip when clicking outside
        document.addEventListener('click', function(e) {
            const tooltip = document.getElementById('scannerHelpTooltip');
            const helpBtn = document.getElementById('scannerHelpBtn');
            if (tooltip && !tooltip.contains(e.target) && !helpBtn.contains(e.target)) {
                tooltip.classList.add('hidden');
            }
        });

        @endif
    </script>
</x-app-layout>
