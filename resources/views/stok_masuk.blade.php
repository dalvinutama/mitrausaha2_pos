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
                            <span class="text-[#D00000] dark:text-red-400">{{ __('incoming_items') }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-gray-800 dark:text-white tracking-tight">{{ __('stock_receipt') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('stock_receipt_desc') }}</p>
                    </div>
                    
                    {{-- Tampilkan Status Transaksi HANYA untuk Gudang --}}
                    @if(Auth::user()->role === 'gudang')
                    {{-- PERBAIKAN KONTRAS: shadow-md --}}
                    <div class="bg-white dark:bg-gray-800 px-5 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex items-center gap-4 transition-colors">
                        <div class="w-10 h-10 bg-red-50 dark:bg-red-900/30 text-[#D00000] dark:text-red-500 rounded-xl flex items-center justify-center text-lg border border-red-100 dark:border-red-800/50">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('new_transaction_no') }}</p>
                            <p class="text-lg font-black text-gray-800 dark:text-white">{{ $autoNumber ?? 'BM-'.date('Ymd').'-XXX' }}</p>
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
                {{-- BLOK CREATE (C) --}}
                {{-- ========================================================= --}}
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'gudang')
                
                <form id="formTransaksi" action="{{ route('stok_masuk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf 
                    
                    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                        
                        {{-- KOLOM KIRI: INFO DOKUMEN --}}
                        <div class="xl:col-span-4 flex flex-col gap-6">
                            {{-- PERBAIKAN KONTRAS: shadow-md --}}
                            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md transition-colors relative">
                                <div class="bg-gray-100/50 dark:bg-gray-800/80 px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-start sm:items-center gap-2 sm:gap-3 transition-colors rounded-t-2xl">
                                    <div class="w-6 h-6 rounded-full shrink-0 bg-[#D00000] dark:bg-red-600 text-white flex items-center justify-center text-xs font-bold shadow-sm">1</div>
                                    <h3 class="font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide text-sm flex items-center gap-2">
                                        {{ __('document_info') }}
                                        <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-500 cursor-pointer transition-colors text-xs text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Lengkapi informasi dasar dokumen penerimaan barang seperti asal supplier, nomor nota/referensi, dan metode pembayaran.
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                                    </h3>
                                </div>
                                
                                <div class="p-5 space-y-5">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('supplier_vendor') }} <span class="text-red-500">*</span></label>
                                        <div class="relative w-full">
                                            <select name="supplier_id" id="supplier_id" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:ring-red-500/20 focus:border-[#D00000] block transition-all" required>
                                                <option value="" disabled selected>{{ __('select_supplier') }}</option>
                                                @foreach($suppliers ?? [] as $s)
                                                    <option value="{{ $s->id }}">{{ $s->nama_supplier }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- TAMBAHAN: DROPDOWN TAUTAN PO AKTIF --}}
                                    <div class="bg-blue-50/50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-200 dark:border-blue-800/50 transition-colors">
                                        <label class="block text-xs font-bold text-blue-700 dark:text-blue-400 mb-1.5">{{ __('link_to_po') }}</label>
                                        <div class="relative w-full">
                                            <select name="po_id" id="po_id" class="w-full bg-white dark:bg-gray-700 border border-blue-300 dark:border-blue-600 text-gray-800 dark:text-white text-sm rounded-xl focus:ring-blue-500/30 focus:border-blue-500 block transition-all font-semibold" disabled>
                                                <option value="">-- Pilih Supplier Terlebih Dahulu --</option>
                                            </select>
                                        </div>
                                        <p class="text-[9px] text-blue-600 dark:text-blue-400 mt-1.5 font-bold">{{ __('po_auto_complete_info') }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('delivery_note_invoice_no') }} <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-receipt text-gray-400 dark:text-gray-500"></i>
                                            </div>
                                            <input type="text" name="no_referensi" placeholder="{{ __('example_invoice') }}" class="w-full pl-9 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all uppercase dark:placeholder-gray-500" required>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('receipt_date') }} <span class="text-red-500">*</span></label>
                                            <input type="date" name="tanggal" id="inputTanggal" value="{{ date('Y-m-d') }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:[color-scheme:dark]" required onchange="calculateTempoDate()">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('payment') }} Status <span class="text-red-500">*</span></label>
                                            <select name="tipe_pembayaran" id="tipePembayaran" onchange="togglePaymentOptions()" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all">
                                                <option value="tunai">Lunas (Tunai)</option>
                                                <option value="tempo">Jatuh Tempo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Metode Pembayaran <span class="text-red-500">*</span></label>
                                            <select name="metode_pembayaran" id="metodePembayaran" onchange="toggleTransferProof()" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all">
                                                <option value="cash">Uang Fisik (Cash)</option>
                                                <option value="transfer">Transfer Bank</option>
                                            </select>
                                        </div>
                                        <div id="divBuktiTransfer" class="hidden">
                                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">Bukti Transfer <span class="text-red-500">*</span></label>
                                            <input type="file" name="bukti_pembayaran" id="buktiPembayaran" accept="image/*" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:ring-4 focus:ring-red-500/10 focus:border-[#D00000] block p-2 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 dark:file:bg-red-900/30 dark:file:text-red-400">
                                        </div>
                                    </div>

                                    {{-- INPUT TANGGAL TEMPO (Tersembunyi jika Tunai) --}}
                                    <div id="divTempo" class="hidden mt-4 bg-red-50/80 dark:bg-red-900/10 p-4 rounded-xl border border-red-200 dark:border-red-900/30 transition-colors">
                                        <div class="mb-4 pb-4 border-b border-red-200 dark:border-red-800/30">
                                            <label class="block text-xs font-bold text-red-700 dark:text-red-400 mb-1.5">Nominal Uang Muka (DP)</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-red-500 font-bold">Rp</span>
                                                </div>
                                                <input type="number" name="dp_nominal" placeholder="0" class="w-full pl-10 bg-white dark:bg-gray-800 border border-red-300 dark:border-red-800/50 text-gray-800 dark:text-white text-sm font-semibold rounded-xl focus:ring-4 focus:ring-red-500/20 dark:focus:ring-red-500/30 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                            </div>
                                            <p class="text-[9px] text-red-600 dark:text-red-400 mt-1">Isi 0 jika tidak ada DP. Jika ada DP, pilih Cash/Transfer di atas.</p>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-red-700 dark:text-red-400 mb-1.5">@if(app()->getLocale() == 'id') Tempo (Hari) @else Term (Days) @endif <span class="text-red-500">*</span></label>
                                                <div class="flex items-center w-full bg-white dark:bg-gray-800 border border-red-300 dark:border-red-800/50 rounded-xl overflow-hidden focus-within:border-[#D00000] focus-within:ring-4 focus-within:ring-red-500/20 transition-all shadow-inner dark:shadow-none">
                                                    <button type="button" onclick="adjustNumber('inputHariTempo', -1)" class="w-10 h-10 flex items-center justify-center text-red-600 hover:text-white hover:bg-red-500 dark:hover:bg-red-600 transition-colors shrink-0"><i class="fas fa-minus text-xs"></i></button>
                                                    <input type="number" id="inputHariTempo" min="1" placeholder="14" class="w-full bg-transparent border-none text-red-700 dark:text-red-400 font-bold text-lg text-center p-2 focus:ring-0 appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" onchange="calculateTempoDate()" oninput="calculateTempoDate()">
                                                    <button type="button" onclick="adjustNumber('inputHariTempo', 1)" class="w-10 h-10 flex items-center justify-center text-red-600 hover:text-white hover:bg-red-500 dark:hover:bg-red-600 transition-colors shrink-0"><i class="fas fa-plus text-xs"></i></button>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-red-700 dark:text-red-400 mb-1.5">{{ __('select_due_date') }} <span class="text-red-500">*</span></label>
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <i class="fas fa-calendar-times text-red-500 dark:text-red-500"></i>
                                                    </div>
                                                    <input type="date" name="tanggal_tempo" id="inputTanggalTempo" class="w-full pl-9 bg-white dark:bg-gray-800 border border-red-300 dark:border-red-800/50 text-gray-800 dark:text-white text-sm font-semibold rounded-xl focus:ring-4 focus:ring-red-500/20 dark:focus:ring-red-500/30 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:[color-scheme:dark]" onchange="calculateTempoDays()">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-[9px] text-red-600 dark:text-red-400 mt-2 font-bold"><i class="fas fa-info-circle mr-1"></i> @if(app()->getLocale() == 'id') Isi jumlah hari atau pilih langsung di kalender. @else Enter the number of days or pick a date directly on the calendar. @endif</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('notes_optional') }}</label>
                                        <textarea name="catatan" rows="3" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:placeholder-gray-500" placeholder="{{ __('notes_placeholder') }}"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM KANAN: KERANJANG BARANG --}}
                        <div class="xl:col-span-8 flex flex-col gap-6">
                            
                            {{-- PERBAIKAN KONTRAS: shadow-md --}}
                            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col h-full transition-colors relative">
                                <div class="bg-gray-100/50 dark:bg-gray-800/80 px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center transition-colors rounded-t-2xl">
                                    <div class="flex items-start sm:items-center gap-2 sm:gap-3">
                                        <div class="w-6 h-6 rounded-full shrink-0 bg-[#D00000] dark:bg-red-600 text-white flex items-center justify-center text-xs font-bold shadow-sm">2</div>
                                        <h3 class="font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide text-sm flex items-center gap-2">
                                            {{ __('incoming_material_list') }}
                                            <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-500 cursor-pointer transition-colors text-xs text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Pilih dan masukkan material yang diterima ke dalam keranjang, lengkapi dengan harga modal dan jumlah stok fisiknya.
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                                        </h3>
                                    </div>
                                    <button type="button" onclick="openBarcodeScanner()" class="bg-[#1e1e2d] dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 text-white px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition-all shadow-sm dark:shadow-none hover:shadow-md">
                                        <i class="fas fa-barcode text-yellow-400"></i> {{ __('scan_camera') }}
                                    </button>
                                </div>

                                {{-- PANEL TARGET PO (DISEMBUNYIKAN SECARA DEFAULT) --}}
                                <div id="poTargetPanel" class="hidden bg-blue-50/50 dark:bg-blue-900/20 border-b border-blue-200 dark:border-blue-800/50 p-4 transition-colors">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-sm font-bold text-blue-800 dark:text-blue-300 flex items-center gap-2">
                                            <i class="fas fa-clipboard-list"></i> Target Penerimaan PO
                                        </h4>
                                        <button type="button" onclick="autoFillFromPo()" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg shadow-sm transition-all flex items-center gap-1 hover:-translate-y-0.5">
                                            <i class="fas fa-arrow-down"></i> Tarik Semua Sisa PO
                                        </button>
                                    </div>
                                    <div id="poTargetList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                        {{-- Target Items Rendered via JS --}}
                                    </div>
                                </div>

                                <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 space-y-4 transition-colors">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                                        <div class="md:col-span-5">
                                            <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('select_registered_material') }} <span class="text-red-500">*</span></label>
                                            <div class="relative w-full">
                                                <select id="temp_product" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm font-semibold rounded-xl focus:ring-red-500/20 focus:border-[#D00000] block transition-all">
                                                    <option value="" disabled selected>{{ __('search_scan_item') }}</option>
                                                    @foreach($products as $p)
                                                        <option value="{{ $p->id }}" data-price="{{ $p->harga_beli }}" data-barcode="{{ $p->barcode }}">
                                                            {{ $p->sku }} - {{ $p->nama_barang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="md:col-span-4">
                                            <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('expired_date') }} <span class="text-gray-400 dark:text-gray-500 lowercase normal-case">{{ __('optional_lowercase') }}</span></label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-calendar-alt text-gray-400 dark:text-gray-500"></i></div>
                                                <input type="date" id="temp_expired" class="w-full pl-9 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm font-semibold rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:[color-scheme:dark]">
                                            </div>
                                        </div>
                                        <div class="md:col-span-3">
                                            <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('unit_purchase_price') }} <span class="text-red-500">*</span></label>
                                            <div class="flex items-center w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl overflow-hidden focus-within:border-[#D00000] focus-within:ring-4 focus-within:ring-red-500/10 transition-all">
                                                <div class="pl-3 pr-1 flex items-center pointer-events-none shrink-0"><span class="text-gray-500 dark:text-gray-400 font-bold text-sm">Rp</span></div>
                                                <button type="button" onclick="adjustNumber('temp_price', -1000, true)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors shrink-0"><i class="fas fa-minus text-xs"></i></button>
                                                <input type="text" id="display_temp_price" oninput="formatPrice(this, 'temp_price')" placeholder="0" class="min-w-[40px] w-full text-right bg-transparent border-none text-gray-800 dark:text-white font-bold text-sm px-2 py-3 focus:ring-0 dark:placeholder-gray-500">
                                                <input type="hidden" id="temp_price">
                                                <button type="button" onclick="adjustNumber('temp_price', 1000, true)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors mr-1 shrink-0"><i class="fas fa-plus text-xs"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end bg-gray-100/50 dark:bg-gray-900/50 p-3 rounded-xl border border-gray-200 dark:border-gray-700 transition-colors">
                                        <div class="md:col-span-3">
                                            <label class="block text-[10px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5 text-center">{{ __('total_qty_received') }}</label>
                                            <div class="flex items-center w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl overflow-hidden focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/10 transition-all shadow-inner dark:shadow-none">
                                                <button type="button" onclick="adjustNumber('temp_qty_total', -1)" class="w-10 h-10 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-minus text-xs"></i></button>
                                                <input type="number" id="temp_qty_total" value="1" min="1" class="w-full bg-transparent border-none text-blue-700 dark:text-blue-400 font-bold text-lg text-center p-2 focus:ring-0 appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                <button type="button" onclick="adjustNumber('temp_qty_total', 1)" class="w-10 h-10 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-plus text-xs"></i></button>
                                            </div>
                                        </div>
                                        <div class="md:col-span-1 flex items-center justify-center font-bold text-gray-400 dark:text-gray-600 text-xl pb-2">-</div>
                                        <div class="md:col-span-3">
                                            <label class="block text-[10px] font-bold text-orange-600 dark:text-orange-400 uppercase tracking-wider mb-1.5 text-center">{{ __('damaged_qty_return') }}</label>
                                            <div class="flex items-center w-full bg-orange-50 dark:bg-orange-900/10 border border-orange-300 dark:border-orange-800 rounded-xl overflow-hidden focus-within:border-orange-500 focus-within:ring-4 focus-within:ring-orange-500/10 transition-all shadow-inner dark:shadow-none">
                                                <button type="button" onclick="adjustNumber('temp_qty_rusak', -1)" class="w-10 h-10 flex items-center justify-center text-orange-600 hover:text-white hover:bg-orange-500 dark:hover:bg-orange-600 transition-colors shrink-0"><i class="fas fa-minus text-xs"></i></button>
                                                <input type="number" id="temp_qty_rusak" value="0" min="0" class="w-full bg-transparent border-none text-orange-700 dark:text-orange-400 font-bold text-lg text-center p-2 focus:ring-0 appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                <button type="button" onclick="adjustNumber('temp_qty_rusak', 1)" class="w-10 h-10 flex items-center justify-center text-orange-600 hover:text-white hover:bg-orange-500 dark:hover:bg-orange-600 transition-colors shrink-0"><i class="fas fa-plus text-xs"></i></button>
                                            </div>
                                        </div>
                                        <div class="md:col-span-1 flex items-center justify-center font-bold text-gray-400 dark:text-gray-600 text-xl pb-2">=</div>
                                        <div class="md:col-span-4 flex justify-between gap-3">
                                            <button type="button" onclick="addToCart()" class="w-full bg-[#D00000] dark:bg-red-700 hover:bg-red-800 dark:hover:bg-red-600 text-white font-bold rounded-xl px-4 py-3 transition-all shadow-md dark:shadow-none flex items-center justify-center gap-2 hover:-translate-y-0.5">
                                                <i class="fas fa-level-down-alt transform rotate-90"></i> <span>{{ __('add_to_table') }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-1 overflow-x-auto min-h-[250px] bg-gray-50 dark:bg-gray-900/50 transition-colors">
                                    <table class="w-full text-sm text-left border-collapse">
                                        <thead class="text-[10px] text-gray-600 dark:text-gray-400 uppercase bg-gray-200/60 dark:bg-gray-800/80 border-b border-gray-300 dark:border-gray-700 sticky top-0 transition-colors">
                                            <tr>
                                                <th class="px-5 py-3 font-bold tracking-wider">{{ __('material_description') }}</th>
                                                <th class="px-5 py-3 text-center font-bold tracking-wider">{{ __('total_qty') }}</th>
                                                <th class="px-5 py-3 text-center font-bold tracking-wider">{{ __('stock_status') }}</th>
                                                <th class="px-5 py-3 text-right font-bold tracking-wider">{{ __('bill_subtotal') }}</th>
                                                <th class="px-5 py-3 text-center font-bold tracking-wider w-16"><i class="fas fa-cog"></i></th>
                                            </tr>
                                        </thead>
                                        {{-- PERBAIKAN KONTRAS TABEL: divide-gray-200 --}}
                                        <tbody id="cartBody" class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 transition-colors">
                                            <tr id="emptyRow">
                                                <td colspan="5" class="px-6 py-16 text-center">
                                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full flex items-center justify-center mb-4 transition-colors">
                                                            <i class="fas fa-shopping-cart text-3xl text-gray-300 dark:text-gray-600"></i>
                                                        </div>
                                                        <p class="font-bold text-gray-600 dark:text-gray-400">{{ __('cart_empty') }}</p>
                                                        <p class="text-xs mt-1 text-gray-500 dark:text-gray-500">{{ __('fill_form_to_add') }}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="bg-gray-100/80 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-5 sm:p-6 flex flex-col md:flex-row justify-between items-center gap-5 mt-auto transition-colors">
                                    <div class="flex gap-8 md:gap-12 w-full md:w-auto justify-between md:justify-start">
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('total_types') }}</p>
                                            <p class="text-2xl font-black text-gray-800 dark:text-white leading-none"><span id="totalItems">0</span> <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('item') }}</span></p>
                                        </div>
                                        <div class="text-right md:text-left">
                                            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('total_bill_invoice') }}</p>
                                            <p class="text-2xl font-black text-[#D00000] dark:text-red-500 leading-none" id="grandTotal">Rp 0</p>
                                        </div>
                                    </div>
                                    <button type="submit" onclick="return confirmSubmit(event)" class="w-full md:w-auto bg-green-500 dark:bg-green-600 hover:bg-green-600 dark:hover:bg-green-500 text-white font-black py-3.5 px-8 rounded-xl shadow-md dark:shadow-none transition-all hover:-translate-y-1 flex items-center justify-center gap-3 border border-green-600 dark:border-transparent">
                                        <i class="fas fa-check-circle text-lg"></i> {{ __('save_transaction') }}
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </form>
                
                @else
                {{-- ========================================================= --}}
                {{-- BLOK READ ONLY (R) - OWNER, ADMIN --}}
                {{-- ========================================================= --}}
                <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 text-blue-700 dark:text-blue-400 p-4 rounded-xl shadow-sm transition-colors">
                    <div class="flex items-start sm:items-center gap-2 sm:gap-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-800/50 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-300 shrink-0 transition-colors shadow-sm">
                            <i class="fas fa-info-circle text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">{{ __('read_only_access') }}</h4>
                            <p class="text-xs mt-0.5">{!! __('read_only_desc') !!}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ========================================================= --}}
                {{-- TABEL RIWAYAT TRANSAKSI MASUK (TAMPIL UNTUK SEMUA ROLE) --}}
                {{-- ========================================================= --}}
                {{-- PERBAIKAN KONTRAS: shadow-md --}}
                <div class="mt-8 bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-md transition-colors relative">
                    <div class="bg-gray-100/50 dark:bg-gray-800/80 px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center transition-colors rounded-t-3xl">
                        <div class="flex items-start sm:items-center gap-2 sm:gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-500 flex items-center justify-center text-sm shadow-sm"><i class="fas fa-history"></i></div>
                            <h3 class="font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide text-sm flex items-center gap-2">
                                {{ __('incoming_receipt_history') }}
                                <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-green-600 dark:hover:text-green-500 cursor-pointer transition-colors text-xs text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Daftar seluruh riwayat transaksi penerimaan barang yang sebelumnya sudah berhasil dicatat dalam sistem.
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                            </h3>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead class="text-[10px] text-gray-600 dark:text-gray-400 font-bold uppercase bg-gray-200/60 dark:bg-gray-700/50 border-b border-gray-300 dark:border-gray-700 transition-colors">
                                <tr>
                                    <th class="px-5 py-3">{{ __('transaction_no') }}</th>
                                    <th class="px-5 py-3">{{ __('receipt_date') }}</th>
                                    <th class="px-5 py-3">{{ __('supplier_vendor') }}</th>
                                    <th class="px-5 py-3">{{ __('delivery_note_no') }}</th>
                                    <th class="px-5 py-3">Rincian Barang</th>
                                    <th class="px-5 py-3 text-right">{{ __('total_value_rp') }}</th>
                                    <th class="px-5 py-3 text-center">Status Pembayaran</th>
                                    <th class="px-5 py-3">{{ __('receiver_warehouse') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                                @forelse($transaksiMasuk ?? [] as $trx)
                                <tr class="hover:bg-green-50/30 dark:hover:bg-green-900/10 transition-colors">
                                    <td class="px-5 py-4 font-bold text-green-600 dark:text-green-400">{{ $trx->no_transaksi }}</td>
                                    <td class="px-5 py-4 font-semibold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($trx->tanggal)->format('d M Y') }}</td>
                                    <td class="px-5 py-4 font-bold text-gray-800 dark:text-gray-300">{{ $trx->supplier->nama_supplier ?? '-' }}</td>
                                    <td class="px-5 py-4"><span class="bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded text-[10px] font-mono font-bold transition-colors">{{ $trx->no_referensi ?? '-' }}</span></td>
                                    <td class="px-5 py-4">
                                        <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                            @if($trx->items && $trx->items->count() > 0)
                                                @foreach($trx->items as $det)
                                                    <p class="flex items-center gap-1.5">
                                                        <span class="w-1 h-1 rounded-full bg-green-400 dark:bg-green-600"></span>
                                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $det->product->nama_barang ?? 'Barang' }}</span> 
                                                        <strong class="text-gray-900 dark:text-gray-100">({{ $det->qty }})</strong>
                                                    </p>
                                                @endforeach
                                            @else
                                                <em class="text-gray-400">Tidak ada rincian</em>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-right font-black text-gray-800 dark:text-white">{{ number_format($trx->total_nilai, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-center">
                                        @if($trx->status_pembayaran === 'lunas')
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800/50">Lunas</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50">Belum Lunas</span>
                                        @endif
                                        <div class="text-[10px] text-gray-500 mt-1 uppercase">{{ $trx->tipe_pembayaran }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-user-circle mr-1 text-gray-400 dark:text-gray-500"></i> {{ $trx->user->name ?? __('system') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center text-gray-500 dark:text-gray-500 italic font-medium">{{ __('no_incoming_history') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL SCANNER BARCODE (Hanya dirender jika User = Gudang) --}}
    @if(Auth::user()->role === 'gudang')
    <div id="barcodeModal" class="fixed inset-0 bg-black/80 z-[100] hidden flex items-center justify-center backdrop-blur-sm p-4 transition-all">
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-[dropIn_0.3s_ease-out] transition-colors">
            {{-- PERBAIKAN KONTRAS: bg-gray-100 --}}
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-100 dark:bg-gray-900 transition-colors">
                <h3 class="font-black text-gray-800 dark:text-white text-lg"><i class="fas fa-camera text-[#D00000] dark:text-red-500 mr-2"></i> {{ __('scan_barcode') }}</h3>
                <button type="button" onclick="closeBarcodeScanner()" class="text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 border border-transparent hover:border-red-200 hover:bg-red-50 dark:hover:bg-red-900/20 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
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
                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-4">{{ __('point_camera_to_barcode') }}</p>
                <button type="button" onclick="closeBarcodeScanner()" class="w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 font-bold py-3 rounded-xl transition-colors text-sm shadow-sm">{{ __('close_camera') }}</button>
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================== --}}
    {{-- SCANNER RADAR: FLOATING INDICATOR + HELP TOOLTIP --}}
    {{-- ============================================================== --}}
    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'gudang')
    <div id="scannerRadarIndicator" class="fixed bottom-6 right-6 z-[90] flex items-center gap-2">
        <div class="relative">
            <button type="button" onclick="toggleScannerHelp()" id="scannerHelpBtn" class="w-9 h-9 rounded-full bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border border-gray-200 dark:border-gray-700 shadow-lg flex items-center justify-center text-gray-400 hover:text-blue-500 dark:hover:text-blue-400 transition-all hover:scale-110 hover:shadow-xl" title="Panduan Scanner">
                <i class="fas fa-question-circle text-sm"></i>
            </button>
            <div id="scannerHelpTooltip" class="hidden absolute bottom-full right-0 mb-3 w-80 bg-white dark:bg-gray-800 rounded-2xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] dark:shadow-[0_20px_60px_-15px_rgba(0,0,0,0.6)] border border-gray-200 dark:border-gray-700 overflow-hidden animate-[dropIn_0.2s_ease-out]">
                <div class="bg-gradient-to-r from-slate-800 to-slate-900 dark:from-gray-900 dark:to-gray-950 px-5 py-3 flex items-center justify-between">
                    <h4 class="text-white font-bold text-sm flex items-center gap-2"><i class="fas fa-satellite-dish text-yellow-400"></i> Panduan Scanner Barcode</h4>
                    <button type="button" onclick="toggleScannerHelp()" class="text-gray-400 hover:text-white transition-colors"><i class="fas fa-times text-xs"></i></button>
                </div>
                <div class="p-4 space-y-4 text-xs max-h-[400px] overflow-y-auto custom-scrollbar">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-3 border border-blue-100 dark:border-blue-800/50">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 rounded-lg bg-blue-500 text-white flex items-center justify-center shadow-sm"><i class="fas fa-usb text-xs"></i></div>
                            <span class="font-bold text-blue-800 dark:text-blue-300 text-sm">Scanner USB (Kabel)</span>
                        </div>
                        <ol class="space-y-1.5 text-gray-700 dark:text-gray-300 list-decimal list-inside leading-relaxed">
                            <li><b>Colokkan</b> kabel USB scanner ke port USB komputer/laptop.</li>
                            <li>Tunggu 2-3 detik — <b>Tidak perlu instal driver</b> apapun.</li>
                            <li>Buka halaman ini, lalu <b>tembak barcode</b> ke barang dari truk/rak.</li>
                            <li>Barang otomatis terpilih di dropdown! Tinggal isi jumlah. <i class="fas fa-check-circle text-green-500"></i></li>
                        </ol>
                    </div>
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-xl p-3 border border-indigo-100 dark:border-indigo-800/50">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 rounded-lg bg-indigo-500 text-white flex items-center justify-center shadow-sm"><i class="fab fa-bluetooth-b text-xs"></i></div>
                            <span class="font-bold text-indigo-800 dark:text-indigo-300 text-sm">Scanner Bluetooth (Nirkabel)</span>
                        </div>
                        <ol class="space-y-1.5 text-gray-700 dark:text-gray-300 list-decimal list-inside leading-relaxed">
                            <li><b>Nyalakan</b> scanner Bluetooth dan masuk ke mode pairing.</li>
                            <li>Di HP/Tablet/Laptop, buka <b>Pengaturan Bluetooth</b> → cari nama scanner → <b>Pair/Hubungkan</b>.</li>
                            <li>Setelah tersambung, buka halaman ini di browser.</li>
                            <li>Tembak barcode — barang langsung terpilih! <i class="fas fa-check-circle text-green-500"></i></li>
                        </ol>
                    </div>
                    <div class="bg-amber-50 dark:bg-amber-900/10 rounded-xl p-3 border border-amber-100 dark:border-amber-800/50">
                        <div class="flex items-center gap-2 mb-1.5">
                            <i class="fas fa-lightbulb text-amber-500"></i>
                            <span class="font-bold text-amber-800 dark:text-amber-400 text-sm">Tips Gudang</span>
                        </div>
                        <ul class="space-y-1 text-gray-700 dark:text-gray-300 list-disc list-inside leading-relaxed">
                            <li>Scanner dikenali sebagai <b>keyboard</b> — tidak perlu software tambahan.</li>
                            <li><b>Tidak perlu</b> klik dropdown terlebih dahulu. Cukup tembak kapan saja!</li>
                            <li>Cocok untuk <b>Stok Opname</b>: bawa tablet + scanner Bluetooth keliling rak.</li>
                            <li>Ambil sampel barang dari truk, scan, lalu isi jumlah yang datang.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md px-4 py-2.5 rounded-full border border-gray-200 dark:border-gray-700 shadow-lg transition-all" id="scannerBadge">
            <div class="relative">
                <i class="fas fa-satellite-dish text-gray-400 dark:text-gray-500 text-sm" id="scannerIcon"></i>
                <div class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-emerald-400 border border-white dark:border-gray-800" id="scannerDot"></div>
            </div>
            <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider" id="scannerLabel">Scanner Siap</span>
        </div>
    </div>

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
        var overlay = document.getElementById('overlay');
        if (overlay) {
            overlay.addEventListener('click', toggleSidebar);
        }

        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'gudang')
        function toggleTempo() {
            const tipe = document.getElementById('tipePembayaran').value;
            const divTempo = document.getElementById('divTempo');
            const inputTempo = document.getElementById('inputTanggalTempo');
            const inputHari = document.getElementById('inputHariTempo');
            
            if (tipe === 'tempo') {
                divTempo.classList.remove('hidden');
                inputTempo.required = true;
                // Pre-fill if empty
                if(!inputTempo.value && !inputHari.value) {
                    inputHari.value = 14;
                    calculateTempoDate();
                }
            } else {
                divTempo.classList.add('hidden');
                inputTempo.required = false;
                inputTempo.value = '';
                inputHari.value = '';
            }
        }

        function calculateTempoDate() {
            const hari = parseInt(document.getElementById('inputHariTempo').value);
            const tanggalStok = document.getElementById('inputTanggal').value;
            
            if (hari && hari > 0 && tanggalStok) {
                const date = new Date(tanggalStok);
                date.setDate(date.getDate() + hari);
                
                // Format YYYY-MM-DD
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                
                document.getElementById('inputTanggalTempo').value = `${year}-${month}-${day}`;
            }
        }

        function calculateTempoDays() {
            const tanggalTempo = document.getElementById('inputTanggalTempo').value;
            const tanggalStok = document.getElementById('inputTanggal').value;
            
            if (tanggalTempo && tanggalStok) {
                const dateTempo = new Date(tanggalTempo);
                const dateStok = new Date(tanggalStok);
                
                // Hitung selisih hari
                const diffTime = dateTempo - dateStok;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays >= 0) {
                    document.getElementById('inputHariTempo').value = diffDays;
                } else {
                    document.getElementById('inputHariTempo').value = 0;
                }
            }
        }

        async function fetchSupplierPos(supplierId) {
            if (!supplierId) return;
            const $poSelect = $('#po_id');
            if ($poSelect.length === 0) return;
            
            // Set loading state using jQuery
            $poSelect.empty().append(new Option('Memuat data PO...', '', false, false)).trigger('change');
            $poSelect.prop('disabled', true);

            try {
                const fetchUrl = `{{ url('get-pos-by-supplier') }}/${supplierId}`;
                const response = await fetch(fetchUrl);
                
                if(!response.ok) {
                    throw new Error('Server merespons dengan status ' + response.status);
                }

                const data = await response.json();
                
                $poSelect.empty().append(new Option('-- No PO (Normal Receipt) --', '', false, false));
                
                if (data && data.length > 0) {
                    data.forEach(po => {
                        $poSelect.append(new Option(po.text, po.id, false, false));
                    });
                }
                
                $poSelect.prop('disabled', false).trigger('change');
                
            } catch (error) {
                console.error("Error fetching POs:", error);
                $poSelect.empty().append(new Option('Gagal memuat PO', '', false, false));
                $poSelect.prop('disabled', false).trigger('change');
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memuat PO',
                    text: 'Terjadi kesalahan: ' + error.message,
                    confirmButtonColor: '#D00000'
                });
            }
        }

        window.poTargetItems = [];

        async function fetchPoItems(poId) {
            const panel = document.getElementById('poTargetPanel');
            const list = document.getElementById('poTargetList');
            if (!poId) {
                panel.classList.add('hidden');
                window.poTargetItems = [];
                return;
            }

            try {
                const response = await fetch(`/get-po-items/${poId}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const data = await response.json();
                
                window.poTargetItems = data;
                renderPoTargetPanel();
                panel.classList.remove('hidden');
            } catch (error) {
                console.error("Error fetching PO items:", error);
                panel.classList.add('hidden');
                window.poTargetItems = [];
            }
        }

        function renderPoTargetPanel() {
            const list = document.getElementById('poTargetList');
            list.innerHTML = '';
            
            if (window.poTargetItems.length === 0) {
                list.innerHTML = '<div class="col-span-full text-sm text-gray-500 italic">Tidak ada target item yang tersisa di PO ini.</div>';
                return;
            }

            window.poTargetItems.forEach(item => {
                // Determine if fully fulfilled based on current cart
                let qtyInCart = getCartQty(item.product_id);
                let currentTotal = qtyInCart; // Sisa represents what was NOT received yet.
                // We show Sisa as target.
                
                let isComplete = qtyInCart >= item.sisa;
                let isOver = qtyInCart > item.sisa;
                
                let textClass = isComplete ? 'text-green-700 dark:text-green-400' : 'text-gray-700 dark:text-gray-300';
                let bgClass = isComplete ? 'bg-green-50 border-green-200 dark:bg-green-900/30 dark:border-green-800' : 'bg-white border-blue-100 dark:bg-gray-800 dark:border-blue-800';
                if (isOver) {
                    textClass = 'text-red-700 dark:text-red-400';
                    bgClass = 'bg-red-50 border-red-200 dark:bg-red-900/30 dark:border-red-800';
                }

                let checkIcon = isComplete ? `<i class="fas fa-check-circle text-green-500 ml-1"></i>` : '';

                // Add cursor-pointer and hover effect to make it look clickable
                let clickableClass = isComplete ? 'opacity-70 cursor-not-allowed' : 'cursor-pointer hover:ring-2 hover:ring-blue-400 hover:shadow-md transform hover:-translate-y-0.5';
                let onClickAttr = isComplete ? '' : `onclick="fillFormFromTarget(${item.product_id}, ${item.sisa}, ${item.harga_satuan})"`;

                list.innerHTML += `
                    <div class="${bgClass} border rounded-lg p-2.5 shadow-sm transition-all duration-200 ${clickableClass}" id="target-po-${item.product_id}" ${onClickAttr} title="Klik untuk mengisi form">
                        <div class="text-[10px] text-gray-500 font-bold mb-0.5 truncate">${item.sku}</div>
                        <div class="text-xs font-bold ${textClass} leading-tight line-clamp-1 mb-1">${item.nama_barang}</div>
                        <div class="flex justify-between items-end mt-1">
                            <div class="text-[10px] font-bold text-gray-500">Target sisa:</div>
                            <div class="text-sm font-black ${textClass}">${qtyInCart} / ${item.sisa} ${checkIcon}</div>
                        </div>
                        ${!isComplete ? '<div class="text-[8px] text-blue-500 dark:text-blue-400 text-right mt-1 font-bold italic">Klik untuk tarik <i class="fas fa-hand-pointer"></i></div>' : ''}
                    </div>
                `;
            });
        }

        function fillFormFromTarget(productId, sisa, harga) {
            let currentCartQty = getCartQty(productId);
            let neededQty = sisa - currentCartQty;
            
            if (neededQty <= 0) return;

            // Set dropdown
            const select = $('#temp_product');
            select.val(productId).trigger('change'); 
            
            // Set Qty
            document.getElementById('temp_qty_total').value = neededQty;
            document.getElementById('temp_qty_rusak').value = '0';
            
            // Set price
            document.getElementById('temp_price').value = harga;
            if(document.getElementById('display_temp_price')) {
                document.getElementById('display_temp_price').value = harga > 0 ? parseInt(harga).toLocaleString('id-ID') : '';
            }

            // Fokus ke input qty agar user siap mengetik
            document.getElementById('temp_qty_total').focus();
            
            // Optional: beri highlight singkat pada area form
            const formArea = document.getElementById('temp_product').closest('.border-b');
            if (formArea) {
                formArea.classList.add('ring-2', 'ring-blue-400', 'bg-blue-50/20');
                setTimeout(() => {
                    formArea.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50/20');
                }, 800);
            }
        }

        function getCartQty(productId) {
            let total = 0;
            const rows = document.querySelectorAll('#cartBody tr:not(#emptyRow)');
            rows.forEach(r => {
                const idInput = r.querySelector('input[name="product_id[]"]');
                const qtyInput = r.querySelector('input[name="qty[]"]');
                if (idInput && qtyInput && parseInt(idInput.value) === parseInt(productId)) {
                    total += parseInt(qtyInput.value);
                }
            });
            return total;
        }

        function autoFillFromPo() {
            if (window.poTargetItems.length === 0) return;

            // Loop and add to cart
            window.poTargetItems.forEach(item => {
                let currentCartQty = getCartQty(item.product_id);
                let neededQty = item.sisa - currentCartQty;
                
                if (neededQty > 0) {
                    // Set dropdown
                    const select = document.getElementById('temp_product');
                    select.value = item.product_id;
                    $(select).trigger('change'); // to update price
                    
                    // Set Qty
                    document.getElementById('temp_qty_total').value = neededQty;
                    // Reset price to PO price if available (trigger change might overwrite it, so overwrite again)
                    document.getElementById('temp_price').value = item.harga_satuan;
                    if(document.getElementById('display_temp_price')) {
                        document.getElementById('display_temp_price').value = item.harga_satuan > 0 ? parseInt(item.harga_satuan).toLocaleString('id-ID') : '';
                    }

                    // Trigger Add
                    addToCart();
                }
            });

            Swal.fire({
                icon: 'success',
                title: 'Berhasil Ditarik!',
                text: 'Semua sisa PO telah dimasukkan ke keranjang.',
                timer: 1500,
                showConfirmButton: false
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ !== 'undefined') {
                // Inisialisasi Select2 untuk pencarian material
                $('#temp_product').select2({
                    placeholder: "{{ __('search_scan_item') }}",
                    width: '100%',
                    dropdownAutoWidth: true
                });

                // Inisialisasi Select2 untuk supplier
                $('#supplier_id').select2({
                    placeholder: "{{ __('select_supplier') }}",
                    width: '100%',
                    dropdownAutoWidth: true
                });

                // Inisialisasi Select2 untuk PO
                $('#po_id').select2({
                    placeholder: "-- Pilih Supplier Terlebih Dahulu --",
                    width: '100%',
                    dropdownAutoWidth: true
                });

                // Event listener Material berubah
                $('#temp_product').on('change', function() {
                    updateTempInfo();
                });

                // Event listener Supplier berubah (memicu AJAX PO)
                $('#supplier_id').on('change', function() {
                    const val = $(this).val();
                    if (val) {
                        fetchSupplierPos(val);
                        // Reset PO Items
                        fetchPoItems(null);
                    }
                });

                // Event listener PO berubah
                $('#po_id').on('change', function() {
                    const val = $(this).val();
                    fetchPoItems(val);
                });
            } else {
                // Fallback jika tidak ada jQuery (Sangat jarang di Laravel)
                const supplierSelect = document.getElementById('supplier_id');
                if (supplierSelect) {
                    supplierSelect.addEventListener('change', function() {
                        fetchSupplierPos(this.value);
                    });
                }
            }
        });

        function updateTempInfo() {
            const select = document.getElementById('temp_product');
            if(select.selectedIndex === 0) return;
            
            const selectedOption = select.options[select.selectedIndex];
            const price = parseInt(selectedOption.getAttribute('data-price')) || 0;
            
            document.getElementById('temp_price').value = price;
            if(document.getElementById('display_temp_price')) {
                document.getElementById('display_temp_price').value = price > 0 ? price.toLocaleString('id-ID') : '';
            }
        }

        function formatPrice(input, hiddenId) {
            let value = input.value.replace(/[^0-9]/g, '');
            document.getElementById(hiddenId).value = value;
            input.value = value ? parseInt(value, 10).toLocaleString('id-ID') : '';
        }

        function adjustNumber(id, amount, isPrice = false) {
            if(isPrice) {
                const hiddenInput = document.getElementById(id);
                const displayInput = document.getElementById('display_' + id);
                let val = parseInt(hiddenInput.value || '0', 10);
                val += amount;
                if (val < 0) val = 0;
                hiddenInput.value = val;
                displayInput.value = val.toLocaleString('id-ID');
            } else {
                const input = document.getElementById(id);
                let val = parseInt(input.value || '0', 10);
                val += amount;
                const min = parseInt(input.getAttribute('min') || '0', 10);
                if (val < min) val = min;
                input.value = val;
                
                // Dispatch input event to trigger any bound calculation listeners
                input.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }

        function addToCart() {
            const select = document.getElementById('temp_product');
            const qtyTotalInput = document.getElementById('temp_qty_total');
            const qtyRusakInput = document.getElementById('temp_qty_rusak');
            const priceInput = document.getElementById('temp_price');
            const expiredInput = document.getElementById('temp_expired');
            
            const isDark = document.documentElement.classList.contains('dark');
            const bgPopup = isDark ? '#1f2937' : '#fff';
            const colorText = isDark ? '#f3f4f6' : '#545454';

            if(select.selectedIndex === 0) {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: '{{ __('select_material_first') }}', confirmButtonColor: '#D00000', background: bgPopup, color: colorText }); 
                return; 
            }

            const selectedOption = select.options[select.selectedIndex];
            const productId = selectedOption.value;
            const name = selectedOption.innerText;
            const qtyTotal = parseInt(qtyTotalInput.value);
            const qtyRusak = parseInt(qtyRusakInput.value || 0);
            const price = parseInt(priceInput.value);
            const expired = expiredInput.value;

            if(!qtyTotal || qtyTotal <= 0) { Swal.fire({ icon: 'warning', title: 'Oops...', text: '{{ __('qty_greater_than_0') }}', confirmButtonColor: '#D00000', background: bgPopup, color: colorText }); qtyTotalInput.focus(); return; }
            if(!price || price <= 0) { Swal.fire({ icon: 'warning', title: 'Oops...', text: '{{ __('purchase_price_required') }}', confirmButtonColor: '#D00000', background: bgPopup, color: colorText }); priceInput.focus(); return; }
            if(qtyRusak > qtyTotal) { Swal.fire({ icon: 'error', title: '{{ __('wrong_logic') }}', text: '{{ __('damaged_qty_error') }}', confirmButtonColor: '#D00000', background: bgPopup, color: colorText }); qtyRusakInput.focus(); return; }

            const qtyBagus = qtyTotal - qtyRusak;
            const subtotal = qtyTotal * price; 

            const emptyRow = document.getElementById('emptyRow');
            if(emptyRow) emptyRow.remove();

            let badges = '';
            if(expired) {
                const expDateArr = expired.split('-');
                const expDisplay = `${expDateArr[2]}/${expDateArr[1]}/${expDateArr[0]}`;
                badges += `<span class="inline-block mt-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-[9px] font-bold px-2 py-0.5 rounded border border-yellow-200 dark:border-yellow-800/50 transition-colors"><i class="fas fa-hourglass-half mr-1"></i> Exp: ${expDisplay}</span> `;
            }

            // Validasi PO Target
            if (window.poTargetItems.length > 0) {
                const poItem = window.poTargetItems.find(i => parseInt(i.product_id) === parseInt(productId));
                let cartQtyNow = getCartQty(productId) + qtyTotal; // hitung stok dengan yg mau ditambah

                if (!poItem) {
                    badges += `<span class="inline-block mt-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 text-[9px] font-bold px-2 py-0.5 rounded border border-orange-200 dark:border-orange-800/50 transition-colors"><i class="fas fa-exclamation-triangle mr-1"></i> Di Luar PO</span> `;
                } else if (cartQtyNow > poItem.sisa) {
                    badges += `<span class="inline-block mt-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-[9px] font-bold px-2 py-0.5 rounded border border-red-200 dark:border-red-800/50 transition-colors"><i class="fas fa-exclamation-circle mr-1"></i> Over QTY (${cartQtyNow}/${poItem.sisa})</span> `;
                } else {
                    badges += `<span class="inline-block mt-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-[9px] font-bold px-2 py-0.5 rounded border border-green-200 dark:border-green-800/50 transition-colors"><i class="fas fa-check mr-1"></i> Sesuai PO</span> `;
                }
            }
            
            const rowHtml = `
                <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                    <td class="px-5 py-4">
                        <div class="font-bold text-gray-800 dark:text-gray-200">${name}</div>
                        ${badges}
                        <input type="hidden" name="product_id[]" value="${productId}">
                        <input type="hidden" name="qty[]" value="${qtyTotal}">
                        <input type="hidden" name="qty_rusak[]" value="${qtyRusak}">
                        <input type="hidden" name="price[]" value="${price}">
                        <input type="hidden" name="tgl_expired[]" value="${expired}">
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-black px-3 py-1 rounded-lg border border-blue-200 dark:border-blue-800/50 text-base transition-colors shadow-sm">${qtyTotal}</span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex flex-col items-center gap-1">
                            <span class="text-[10px] font-bold text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded border border-green-200 dark:border-green-800/50 w-full transition-colors">BAGUS: ${qtyBagus}</span>
                            ${qtyRusak > 0 ? `<span class="text-[10px] font-bold text-orange-700 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/30 px-2 py-0.5 rounded border border-orange-200 dark:border-orange-800/50 w-full transition-colors">RUSAK: ${qtyRusak}</span>` : ''}
                        </div>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="font-black text-gray-800 dark:text-gray-100">Rp ${subtotal.toLocaleString('id-ID')}</div>
                        <div class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 mt-0.5">@ Rp ${price.toLocaleString('id-ID')}</div>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <button type="button" onclick="removeRow(this)" class="text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 w-8 h-8 rounded-lg transition-all focus:outline-none border border-transparent hover:border-red-200 dark:hover:border-red-800" title="{{ __('delete') }}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
            document.getElementById('cartBody').insertAdjacentHTML('beforeend', rowHtml);
            
            // Reset input form
            select.selectedIndex = 0;
            qtyTotalInput.value = '1';
            qtyRusakInput.value = '0';
            priceInput.value = '';
            if(document.getElementById('display_temp_price')) { document.getElementById('display_temp_price').value = ''; }
            expiredInput.value = '';
            
            updateTotals();
            if (window.poTargetItems.length > 0) renderPoTargetPanel();
        }

        function removeRow(btn) {
            btn.closest('tr').remove();
            
            const tbody = document.getElementById('cartBody');
            if(tbody.children.length === 0) {
                tbody.innerHTML = `
                    <tr id="emptyRow">
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full flex items-center justify-center mb-4 transition-colors">
                                    <i class="fas fa-shopping-cart text-3xl text-gray-400 dark:text-gray-600"></i>
                                </div>
                                <p class="font-bold text-gray-600 dark:text-gray-400">{{ __('cart_empty') }}</p>
                                <p class="text-xs mt-1 text-gray-500 dark:text-gray-500">{{ __('fill_form_to_add') }}</p>
                            </div>
                        </td>
                    </tr>
                `;
            }
            updateTotals();
            if (window.poTargetItems.length > 0) renderPoTargetPanel();
        }

        function updateTotals() {
            let total = 0;
            const rows = document.querySelectorAll('#cartBody tr:not(#emptyRow)');
            
            rows.forEach(r => {
                const subTextRaw = r.cells[3].innerText.split('\n')[0]; 
                const subText = subTextRaw.replace(/[^0-9]/g, ""); 
                total += parseInt(subText || 0);
            });
            
            document.getElementById('grandTotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('totalItems').innerText = rows.length;
        }

        function confirmSubmit(e) {
            const rowCount = document.querySelectorAll('#cartBody tr:not(#emptyRow)').length;
            if(rowCount === 0) {
                e.preventDefault();
                const isDark = document.documentElement.classList.contains('dark');
                const bgPopup = isDark ? '#1f2937' : '#fff';
                const colorText = isDark ? '#f3f4f6' : '#545454';

                Swal.fire({ icon: 'error', title: '{{ __('empty_cart') }}', text: '{{ __('add_1_material_min') }}', confirmButtonColor: '#D00000', background: bgPopup, color: colorText });
                return false;
            }
            return true; 
        }

        let html5QrcodeScanner = null;
        function openBarcodeScanner() {
            document.getElementById('barcodeModal').classList.remove('hidden');
            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: {width: 250, height: 100}, aspectRatio: 1.0 }, false);
                html5QrcodeScanner.render(onScanSuccess, () => {});
            }
        }
        function closeBarcodeScanner() {
            document.getElementById('barcodeModal').classList.add('hidden');
            if (html5QrcodeScanner) { html5QrcodeScanner.clear(); html5QrcodeScanner = null; }
        }
        
        function onScanSuccess(decodedText, decodedResult) {
            closeBarcodeScanner();
            const select = document.getElementById('temp_product');
            let found = false;
            
            const isDark = document.documentElement.classList.contains('dark');
            const bgPopup = isDark ? '#1f2937' : '#fff';
            const colorText = isDark ? '#f3f4f6' : '#545454';
            
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].getAttribute('data-barcode') === decodedText) {
                    select.selectedIndex = i;
                    updateTempInfo();
                    found = true;
                    Swal.fire({ title: '{{ __('found') }}', text: select.options[i].innerText, icon: 'success', timer: 1500, showConfirmButton: false, background: bgPopup, color: colorText });
                    document.getElementById('temp_qty_total').focus();
                    break;
                }
            }
            
            if(!found) {
                Swal.fire({ title: '{{ __('not_found') }}', text: '{{ __('barcode_not_found_msg') }}', icon: 'error', confirmButtonColor: '#D00000', background: bgPopup, color: colorText });
            }
        }

        // ==========================================
        // 🔫 SCANNER RADAR: GLOBAL BARCODE LISTENER
        // ==========================================
        let scanBuffer = '';
        let scanLastTime = 0;
        let scanTimeout = null;
        const SCAN_SPEED_THRESHOLD = 50;
        const SCAN_MIN_LENGTH = 5;
        
        document.addEventListener('keydown', function(e) {
            const activeEl = document.activeElement;
            const tagName = activeEl ? activeEl.tagName.toLowerCase() : '';
            const isInModal = activeEl && activeEl.closest('#barcodeModal, .swal2-container, #scannerHelpTooltip');
            
            if (isInModal) return;
            
            const now = Date.now();
            const timeDiff = now - scanLastTime;
            scanLastTime = now;
            
            if (e.key === 'Enter') {
                if (scanBuffer.length >= SCAN_MIN_LENGTH) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    const barcode = scanBuffer.trim();
                    scanBuffer = '';
                    clearTimeout(scanTimeout);
                    
                    processScannerBarcodeMasuk(barcode);
                    return;
                }
                scanBuffer = '';
                clearTimeout(scanTimeout);
                return;
            }
            
            if (e.key.length > 1 && e.key !== 'Shift') {
                scanBuffer = '';
                clearTimeout(scanTimeout);
                return;
            }
            
            if (e.key.length === 1) {
                if (timeDiff < SCAN_SPEED_THRESHOLD || scanBuffer.length === 0) {
                    scanBuffer += e.key;
                    
                    if (scanBuffer.length >= 3 && tagName === 'input') {
                        e.preventDefault();
                    }
                    
                    const badge = document.getElementById('scannerBadge');
                    if (badge) {
                        badge.classList.remove('scanning');
                        void badge.offsetWidth;
                        badge.classList.add('scanning');
                    }
                } else {
                    scanBuffer = e.key;
                }
                
                clearTimeout(scanTimeout);
                scanTimeout = setTimeout(() => { scanBuffer = ''; }, 200);
            }
        }, true);
        
        function processScannerBarcodeMasuk(barcode) {
            const barcodeClean = barcode.toLowerCase().trim();
            const select = document.getElementById('temp_product');
            let foundIndex = -1;
            let foundName = '';
            
            for (let i = 0; i < select.options.length; i++) {
                const optBarcode = (select.options[i].getAttribute('data-barcode') || '').toLowerCase();
                const optText = select.options[i].innerText.toLowerCase();
                
                if (optBarcode === barcodeClean || optText.includes(barcodeClean)) {
                    foundIndex = i;
                    foundName = select.options[i].innerText.trim();
                    break;
                }
            }
            
            if (foundIndex > 0) {
                // Set Select2 value via jQuery
                const productId = select.options[foundIndex].value;
                $('#temp_product').val(productId).trigger('change');
                updateTempInfo();
                
                // Focus qty input
                setTimeout(() => {
                    document.getElementById('temp_qty_total').focus();
                    document.getElementById('temp_qty_total').select();
                }, 100);
                
                showScannerToast('success', '✓ ' + foundName.substring(0, 40), 'Terpilih — silakan isi jumlah');
                playBeep('success');
            } else {
                showScannerToast('error', '✗ Barcode Tidak Ditemukan', barcode);
                playBeep('error');
            }
        }
        
        // Toast & Audio (shared)
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
            toastTimer = setTimeout(() => { toast.classList.add('hidden'); }, type === 'success' ? 1500 : 2500);
        }
        
        function playBeep(type) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                if (type === 'success') {
                    osc.frequency.value = 880;
                    gain.gain.value = 0.15;
                    osc.start();
                    osc.stop(ctx.currentTime + 0.1);
                    setTimeout(() => {
                        const c2 = new (window.AudioContext || window.webkitAudioContext)();
                        const o2 = c2.createOscillator();
                        const g2 = c2.createGain();
                        o2.connect(g2); g2.connect(c2.destination);
                        o2.frequency.value = 1318; g2.gain.value = 0.12;
                        o2.start(); o2.stop(c2.currentTime + 0.12);
                    }, 80);
                } else {
                    osc.frequency.value = 220;
                    gain.gain.value = 0.2;
                    osc.start();
                    osc.stop(ctx.currentTime + 0.25);
                }
            } catch(e) {}
        }
        
        function toggleScannerHelp() {
            const tooltip = document.getElementById('scannerHelpTooltip');
            if (tooltip) tooltip.classList.toggle('hidden');
        }
        
        document.addEventListener('click', function(e) {
            const tooltip = document.getElementById('scannerHelpTooltip');
            const helpBtn = document.getElementById('scannerHelpBtn');
            if (tooltip && !tooltip.contains(e.target) && !helpBtn.contains(e.target)) {
                tooltip.classList.add('hidden');
            }
        });

        // ==========================================
        // PEMBAYARAN & TEMPO LOGIC
        // ==========================================
        function togglePaymentOptions() {
            const tipe = document.getElementById('tipePembayaran').value;
            const divTempo = document.getElementById('divTempo');
            const inputHariTempo = document.getElementById('inputHariTempo');
            const inputTanggalTempo = document.getElementById('inputTanggalTempo');

            if (tipe === 'tempo') {
                divTempo.classList.remove('hidden');
                inputHariTempo.required = true;
                inputTanggalTempo.required = true;
                
                // Set default 14 hari
                if(!inputHariTempo.value) {
                    inputHariTempo.value = 14;
                    calculateTempoDate();
                }
            } else {
                divTempo.classList.add('hidden');
                inputHariTempo.required = false;
                inputTanggalTempo.required = false;
            }
        }

        function toggleTransferProof() {
            const metode = document.getElementById('metodePembayaran').value;
            const divBukti = document.getElementById('divBuktiTransfer');
            const inputBukti = document.getElementById('buktiPembayaran');

            if(metode === 'transfer') {
                divBukti.classList.remove('hidden');
                inputBukti.required = true;
            } else {
                divBukti.classList.add('hidden');
                inputBukti.required = false;
            }
        }

        function calculateTempoDate() {
            const tglMasuk = document.getElementById('inputTanggal').value;
            const hariTempo = parseInt(document.getElementById('inputHariTempo').value);
            
            if (tglMasuk && !isNaN(hariTempo)) {
                const date = new Date(tglMasuk);
                date.setDate(date.getDate() + hariTempo);
                
                const yyyy = date.getFullYear();
                const mm = String(date.getMonth() + 1).padStart(2, '0');
                const dd = String(date.getDate()).padStart(2, '0');
                
                document.getElementById('inputTanggalTempo').value = `${yyyy}-${mm}-${dd}`;
            }
        }

        function calculateTempoDays() {
            const tglMasuk = document.getElementById('inputTanggal').value;
            const tglTempo = document.getElementById('inputTanggalTempo').value;
            
            if (tglMasuk && tglTempo) {
                const start = new Date(tglMasuk);
                const end = new Date(tglTempo);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                
                document.getElementById('inputHariTempo').value = diffDays;
            }
        }

        function adjustNumber(id, delta) {
            const input = document.getElementById(id);
            let val = parseInt(input.value) || 0;
            val += delta;
            if(val < 1) val = 1;
            input.value = val;
            
            if(id === 'inputHariTempo') calculateTempoDate();
        }

        // Initialize state on load
        window.addEventListener('DOMContentLoaded', () => {
            togglePaymentOptions();
            toggleTransferProof();
        });

        @endif
    </script>
</x-app-layout>