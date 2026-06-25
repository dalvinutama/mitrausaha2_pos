<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Penyesuaian Select2 dengan Tema Tailwind / Dark Mode */
        .select2-container--default .select2-selection--single {
            background-color: #f9fafb !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.75rem !important;
            height: 3.25rem !important;
            display: flex;
            align-items: center;
        }
        html.dark .select2-container--default .select2-selection--single {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1f2937 !important;
            padding-left: 0.5rem !important;
            font-weight: 600;
            font-size: 0.875rem;
        }
        html.dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f3f4f6 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 10px !important;
        }
        html.dark .select2-dropdown {
            background-color: #374151 !important;
            color: #f3f4f6 !important;
            border-color: #4b5563 !important;
        }
        html.dark .select2-search input {
            background-color: #1f2937 !important;
            color: #f3f4f6 !important;
            border-color: #4b5563 !important;
        }
        html.dark .select2-results__option[aria-selected="true"] {
            background-color: #4b5563 !important;
        }
        html.dark .select2-results__option--highlighted[aria-selected] {
            background-color: #D00000 !important;
            color: white !important;
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
                            <span class="text-[#D00000] dark:text-red-400">{{ __('purchase_order') }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-gray-800 dark:text-white tracking-tight">{{ __('purchase_order_title') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('purchase_order_desc') }}</p>
                    </div>
                    
                    {{-- Tampilkan Info PO Baru HANYA jika User = Admin --}}
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'owner')
                    <div class="bg-white dark:bg-gray-800 px-5 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex items-center gap-4 transition-colors">
                        <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-lg border border-blue-100 dark:border-blue-800/50">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('po_number') }}</p>
                            <p class="text-lg font-black text-gray-800 dark:text-white">{{ $autoNumber ?? 'PO-'.date('Ymd').'-XXX' }}</p>
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

                {{-- TAB NAVIGATION --}}
                <div class="flex gap-2 mb-6 bg-white dark:bg-gray-800 p-2 rounded-xl border border-gray-200 dark:border-gray-700 w-fit shadow-sm relative z-10 transition-colors">
                    <button type="button" onclick="switchTab('create')" id="btnTab-create" class="px-5 py-2 rounded-lg text-sm font-bold transition-all bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 shadow-sm border border-blue-100 dark:border-blue-800/50">
                        <i class="fas fa-plus-circle mr-1"></i> {{ __('create_draft_po') }}
                    </button>
                    <button type="button" onclick="switchTab('history')" id="btnTab-history" class="relative px-5 py-2 rounded-lg text-sm font-bold transition-all text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 border border-transparent">
                        <i class="fas fa-history mr-1"></i> {{ __('po_history') }}
                        @php
                            $pendingPoCount = \App\Models\Transaction::where('jenis_transaksi', 'po')
                                                     ->whereIn('status', ['pending', 'approved'])
                                                     ->count();
                        @endphp
                        @if($pendingPoCount > 0)
                            <div class="absolute -top-1.5 -right-1.5 flex items-center justify-center min-w-[20px] h-5 px-1.5 bg-[#D00000] text-white text-[10px] font-black rounded-full shadow-[0_0_10px_rgba(208,0,0,0.5)] border border-[#ff3333]/30 animate-pulse">
                                {{ $pendingPoCount }}
                            </div>
                        @endif
                    </button>
                </div>

                <div id="tab-create" class="tab-content hidden">
                {{-- ========================================================= --}}
                {{-- BLOK CREATE (C) - HANYA BISA DIAKSES OLEH ADMIN KEUANGAN --}}
                {{-- ========================================================= --}}
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'owner')
                <form id="formTransaksi" action="{{ route('purchase_order.store') }}" method="POST" class="space-y-6">
                    @csrf 
                    
                    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                        
                        {{-- KOLOM KIRI: INFO SUPPLIER & KETENTUAN --}}
                        <div class="xl:col-span-4 flex flex-col gap-6">
                            {{-- PERBAIKAN KONTRAS: shadow-md --}}
                            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md transition-colors relative">
                                <div class="bg-gray-100/50 dark:bg-gray-800/80 px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3 transition-colors rounded-t-2xl">
                                    <div class="w-6 h-6 rounded-full bg-[#D00000] dark:bg-red-600 text-white flex items-center justify-center text-xs font-bold shadow-sm">1</div>
                                    <h3 class="font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide text-sm flex items-center gap-2">
                                        {{ __('identity_and_terms') }}
                                        <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-500 cursor-pointer transition-colors text-xs text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Lengkapi data supplier vendor beserta ketentuan tanggal pemesanan dan catatan opsional.
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                                    </h3>
                                </div>
                                
                                <div class="p-5 space-y-5">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('supplier_identity') }} <span class="text-red-500">*</span></label>
                                        <div class="relative w-full">
                                            <select name="supplier_id" id="supplierSelect" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm rounded-xl focus:ring-red-500/20 focus:border-[#D00000] block transition-all" required>
                                                <option value="" disabled selected>{{ __('select_supplier') }}</option>
                                                @foreach($suppliers ?? [] as $s)
                                                    <option value="{{ $s->id }}">{{ $s->nama_supplier }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('po_date') }} <span class="text-red-500">*</span></label>
                                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm font-semibold rounded-xl focus:bg-white dark:focus:bg-gray-800 focus:ring-4 focus:ring-red-500/10 dark:focus:ring-red-500/20 focus:border-[#D00000] dark:focus:border-red-500 block p-3 transition-all dark:[color-scheme:dark]" required>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 mb-1.5">{{ __('order_delivery_notes') }}</label>
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
                                    <div class="flex items-center gap-3">
                                        <div class="w-6 h-6 rounded-full bg-[#D00000] dark:bg-red-600 text-white flex items-center justify-center text-xs font-bold shadow-sm">2</div>
                                        <h3 class="font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide text-sm flex items-center gap-2">
                                            {{ __('order_material_list') }}
                                            <div class="relative inline-block mt-0.5 group z-50">
                                                <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
                                                    Pilih barang dari master data, tentukan jumlah pesanan dan perkiraan harga belinya ke dalam daftar draf PO.
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                                                </div>
                                            </div>
                                        </h3>
                                    </div>
                                    <button type="button" onclick="fetchRecommendations()" class="bg-gradient-to-r from-[#D00000] to-red-600 hover:from-red-700 hover:to-red-800 text-white font-bold text-xs px-4 py-2 rounded-xl shadow-[0_0_15px_rgba(208,0,0,0.4)] transition-all hover:scale-105 flex items-center gap-2 border border-red-500/30">
                                        <i class="fas fa-magic text-yellow-300"></i> Tarik Rekomendasi EOQ
                                    </button>
                                </div>

                                <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 space-y-4 transition-colors">
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                                        <div class="md:col-span-6">
                                            <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('select_material') }} <span class="text-red-500">*</span></label>
                                            <div class="relative w-full">
                                                <select id="temp_product" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm font-semibold rounded-xl focus:ring-red-500/20 focus:border-[#D00000] block transition-all" onchange="updateTempInfo()">
                                                    <option value="" disabled selected>{{ __('select_item_from_master') }}</option>
                                                    @foreach($products as $p)
                                                        <option value="{{ $p->id }}" data-price="{{ $p->harga_beli }}" data-satuan="{{ $p->satuan }}" data-eoq="{{ $p->eoq }}">
                                                            {{ $p->sku }} - {{ $p->nama_barang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="md:col-span-3">
                                            <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('current_stock_left') }}</label>
                                            <div class="relative flex items-center">
                                                <input type="text" id="temp_stock_info" value="-" readonly class="w-full bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 text-sm rounded-l-xl block p-3 text-center cursor-not-allowed font-bold">
                                                <span id="temp_satuan_info" class="bg-gray-200 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 border-l-0 text-gray-600 dark:text-gray-300 font-bold px-3 py-3 text-xs rounded-r-xl h-full flex items-center">UoM</span>
                                            </div>
                                        </div>
                                        <div class="md:col-span-3">
                                            <label class="block text-[10px] font-bold text-[#D00000] dark:text-red-400 uppercase tracking-wider mb-1.5 text-center">{{ __('order_qty') }}</label>
                                            <div class="flex items-center w-full bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800 rounded-xl overflow-hidden focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/10 transition-all shadow-inner dark:shadow-none">
                                                <button type="button" onmousedown="startHold(event, this, -1, 'temp_qty')" ontouchstart="startHold(event, this, -1, 'temp_qty')" oncontextmenu="return false;" class="w-10 h-10 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-minus text-xs pointer-events-none"></i></button>
                                                <input type="number" id="temp_qty" value="1" min="1" class="w-full bg-transparent border-none text-blue-700 dark:text-blue-400 font-bold text-lg text-center p-2 focus:ring-0 appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                <button type="button" onmousedown="startHold(event, this, 1, 'temp_qty')" ontouchstart="startHold(event, this, 1, 'temp_qty')" oncontextmenu="return false;" class="w-10 h-10 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-plus text-xs pointer-events-none"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                                        <div class="md:col-span-8">
                                            <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('estimated_unit_buy_price') }}</label>
                                            <div class="flex items-center w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl overflow-hidden focus-within:border-[#D00000] focus-within:ring-4 focus-within:ring-red-500/10 transition-all">
                                                <div class="pl-3 pr-1 flex items-center pointer-events-none shrink-0"><span class="text-gray-500 dark:text-gray-400 font-bold text-sm">Rp</span></div>
                                                <button type="button" onmousedown="startHold(event, this, -1000, 'temp_price')" ontouchstart="startHold(event, this, -1000, 'temp_price')" oncontextmenu="return false;" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors shrink-0"><i class="fas fa-minus text-xs pointer-events-none"></i></button>
                                                <input type="text" id="display_temp_price" oninput="formatPrice(this, 'temp_price')" placeholder="{{ __('according_to_master_data') }}" class="min-w-[40px] w-full text-left bg-transparent border-none text-gray-800 dark:text-white font-bold text-sm px-2 py-3 focus:ring-0 dark:placeholder-gray-500">
                                                <input type="hidden" id="temp_price">
                                                <button type="button" onmousedown="startHold(event, this, 1000, 'temp_price')" ontouchstart="startHold(event, this, 1000, 'temp_price')" oncontextmenu="return false;" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors mr-1 shrink-0"><i class="fas fa-plus text-xs pointer-events-none"></i></button>
                                            </div>
                                        </div>
                                        <div class="md:col-span-4">
                                            <button type="button" onclick="addToCart()" class="w-full bg-[#D00000] dark:bg-red-700 hover:bg-red-800 dark:hover:bg-red-600 text-white font-bold rounded-xl px-4 py-3 transition-all shadow-md shadow-red-900/20 dark:shadow-none flex items-center justify-center gap-2">
                                                <i class="fas fa-plus"></i> <span>{{ __('add_item') }}</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <div class="flex-1 overflow-x-auto min-h-[250px] bg-gray-50/50 dark:bg-gray-900/50 transition-colors">
                                    <table class="w-full text-sm text-left">
                                        <thead class="text-[10px] text-gray-600 dark:text-gray-400 uppercase bg-gray-200/60 dark:bg-gray-800/80 border-b border-gray-300 dark:border-gray-700 sticky top-0 transition-colors">
                                            <tr>
                                                <th class="px-5 py-3 font-bold tracking-wider">{{ __('material_detail') }}</th>
                                                <th class="px-5 py-3 text-center font-bold tracking-wider w-20">{{ __('order_qty') }}</th>
                                                <th class="px-5 py-3 text-right font-bold tracking-wider w-36">{{ __('unit_price') }}</th>
                                                <th class="px-5 py-3 text-right font-bold tracking-wider w-36">{{ __('subtotal') }}</th>
                                                <th class="px-5 py-3 text-center font-bold tracking-wider w-12"><i class="fas fa-cog"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody id="cartBody">
                                            <tr id="emptyRow">
                                                <td colspan="5" class="px-6 py-16 text-center">
                                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full flex items-center justify-center mb-4 transition-colors">
                                                            <i class="fas fa-clipboard-list text-3xl text-gray-400 dark:text-gray-600"></i>
                                                        </div>
                                                        <p class="font-bold text-gray-600 dark:text-gray-400">{{ __('order_list_empty') }}</p>
                                                        <p class="text-xs mt-1 text-gray-500 dark:text-gray-500">{{ __('select_material_to_order') }}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="bg-gray-100 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-5 sm:p-6 flex flex-col md:flex-row justify-between items-center gap-5 mt-auto transition-colors">
                                    <div class="flex gap-8 md:gap-12 w-full md:w-auto justify-between md:justify-start">
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('total_quantity') }}</p>
                                            <p class="text-2xl font-black text-gray-800 dark:text-white leading-none"><span id="totalItems">0</span> <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">{{ __('item') }}</span></p>
                                        </div>
                                        <div class="text-right md:text-left">
                                            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('total_estimated_po_cost') }}</p>
                                            <p class="text-2xl font-black text-blue-600 dark:text-blue-400 leading-none" id="grandTotal">Rp 0</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-center md:items-end w-full md:w-auto">
                                        <button type="submit" onclick="return confirmSubmit(event)" class="w-full md:w-auto bg-blue-600 dark:bg-blue-700 hover:bg-blue-700 dark:hover:bg-blue-600 text-white font-black py-3.5 px-8 rounded-xl shadow-md shadow-blue-500/30 dark:shadow-none transition-all hover:-translate-y-1 flex items-center justify-center gap-3">
                                            <i class="fas fa-paper-plane text-lg"></i> {{ __('create_draft_po') }}
                                        </button>
                                        <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 mt-2 text-center md:text-right max-w-xs leading-tight">
                                            <i class="fas fa-info-circle text-blue-500"></i> PO di bawah <b>Rp 5 Juta</b> otomatis disetujui. Di atas nominal tersebut butuh ACC Owner.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </form>

                @else
                {{-- ========================================================= --}}
                {{-- BLOK READ ONLY (R) UNTUK OWNER & DIVISI LAIN --}}
                {{-- ========================================================= --}}
                <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 text-blue-700 dark:text-blue-400 p-4 rounded-xl shadow-sm transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-800/50 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-300 shrink-0 transition-colors shadow-sm">
                            <i class="fas fa-user-shield text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-sm uppercase">{{ __('limited_access_role') }} {{ Auth::user()->role }}</h4>
                            <p class="text-xs mt-0.5">{!! __('based_on_matrix_role') !!}{{ strtoupper(Auth::user()->role) }}{!! __('no_po_create_access') !!}</p>
                            @if(Auth::user()->role === 'owner')
                            <p class="text-xs font-bold mt-1 text-red-600 dark:text-red-400"><i class="fas fa-exclamation-triangle"></i> {{ __('owner_can_approve_po') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                </div>
                @endif
                </div>

                {{-- ========================================================= --}}
                {{-- TABEL RIWAYAT PO & PERSETUJUAN (SEMUA ROLE) --}}
                {{-- ========================================================= --}}
                <div id="tab-history" class="tab-content @if(Auth::user()->role === 'admin' || Auth::user()->role === 'owner') hidden @endif">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md transition-colors relative">
                    <div class="bg-gray-100/50 dark:bg-gray-800/80 px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center transition-colors rounded-t-2xl">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide text-sm flex items-center gap-2">
                            <div><i class="fas fa-history text-blue-600 dark:text-blue-500"></i> {{ __('po_history') }}</div>
                            <div class="relative inline-block mt-0.5 group z-50">
    <i class="fas fa-question-circle text-gray-300 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-500 cursor-pointer transition-colors text-xs text-gray-400 dark:text-gray-500 hover:text-blue-500 cursor-pointer transition-colors text-xs peer"></i>
    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-max max-w-[85vw] sm:max-w-[250px] p-2.5 break-words whitespace-normal bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible peer-hover:opacity-100 peer-hover:visible transition-all duration-300 pointer-events-none text-center shadow-[0_10px_40px_rgba(0,0,0,0.5)] font-medium leading-tight z-[9999]">
        Riwayat seluruh Purchase Order (PO) yang telah dibuat, beserta status persetujuan dari Owner.
        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
    </div>
</div>
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-[10px] text-gray-600 dark:text-gray-400 font-bold uppercase bg-gray-200/60 dark:bg-gray-700/50 border-b border-gray-300 dark:border-gray-700 transition-colors">
                                <tr>
                                    <th class="px-5 py-3">{{ __('po_number') }}</th>
                                    <th class="px-5 py-3">{{ __('created_date') }}</th>
                                    <th class="px-5 py-3">{{ __('supplier') }}</th>
                                    <th class="px-5 py-3 text-center">Progres Kedatangan</th>
                                    <th class="px-5 py-3 text-right">{{ __('total_est_cost_rp') }}</th>
                                    <th class="px-5 py-3 text-center">{{ __('status') }}</th>
                                    <th class="px-5 py-3 text-center">{{ __('action_approval') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchaseOrders ?? [] as $po)
                                {{-- PERBAIKAN KONTRAS TABEL: border-gray-200 --}}
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-5 py-4 font-bold text-blue-600 dark:text-blue-400">{{ $po->no_transaksi }}</td>
                                    <td class="px-5 py-4 font-semibold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($po->tanggal)->format('d M Y') }}</td>
                                    <td class="px-5 py-4 font-bold text-gray-800 dark:text-gray-300">{{ $po->supplier->nama_supplier ?? __('deleted_supplier') }}</td>
                                    <td class="px-5 py-4 text-center">
                                        @php
                                            $totalQtyPesanan = $po->items->sum('qty');
                                            $totalQtyDiterima = $po->items->sum('qty_diterima');
                                            $persen = $totalQtyPesanan > 0 ? min(round(($totalQtyDiterima / $totalQtyPesanan) * 100), 100) : 0;
                                            
                                            $progColor = 'bg-gray-200';
                                            $textColor = 'text-gray-500';
                                            if($persen > 0 && $persen < 100) { $progColor = 'bg-blue-500'; $textColor = 'text-blue-600'; }
                                            if($persen >= 100) { $progColor = 'bg-green-500'; $textColor = 'text-green-600'; }
                                        @endphp
                                        @if($po->status === 'approved' || $po->status === 'selesai')
                                            <div class="flex flex-col gap-1 items-center">
                                                <div class="text-[10px] font-bold {{ $textColor }} tracking-wider">
                                                    {{ $totalQtyDiterima }} / {{ $totalQtyPesanan }} ITEM
                                                </div>
                                                <div class="w-24 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                                    <div class="h-full {{ $progColor }} transition-all duration-500" style="width: {{ $persen }}%"></div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex flex-col gap-1 items-center">
                                                <div class="text-[10px] font-bold text-gray-500 tracking-wider">
                                                    0 / {{ $totalQtyPesanan }} ITEM
                                                </div>
                                                <span class="text-[10px] text-gray-400 italic">
                                                    {{ $po->status === 'pending' ? 'Menunggu Persetujuan' : 'Dibatalkan' }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right font-black text-gray-800 dark:text-white">{{ number_format($po->total_nilai, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-center">
                                        @if($po->status === 'pending')
                                            <span class="bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border border-yellow-200 dark:border-yellow-800/50 transition-colors shadow-sm">
                                                <i class="fas fa-clock mr-1"></i> {{ __('waiting') }}
                                            </span>
                                        @elseif($po->status === 'approved')
                                            <span class="bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border border-green-200 dark:border-green-800/50 transition-colors shadow-sm">
                                                <i class="fas fa-check-circle mr-1"></i> {{ __('approved') }}
                                            </span>
                                        @elseif($po->status === 'selesai')
                                            <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border border-blue-200 dark:border-blue-800/50 transition-colors shadow-sm">
                                                <i class="fas fa-clipboard-check mr-1"></i> Selesai
                                            </span>
                                        @elseif($po->status === 'rejected')
                                            <span class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border border-red-200 dark:border-red-800/50 transition-colors shadow-sm">
                                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border border-gray-300 dark:border-gray-600 transition-colors shadow-sm">
                                                {{ $po->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        {{-- TOMBOL APPROVE & REJECT KHUSUS OWNER --}}
                                        @if(Auth::user()->role === 'owner' && $po->status === 'pending')
                                            <div class="flex items-center justify-center gap-2">
                                                <form action="{{ route('purchase_order.update', $po->id) }}" method="POST" onsubmit="return confirm('{{ __('are_you_sure_approve_order') }}');">
                                                    @csrf @method('PUT')
                                                    <button type="submit" class="bg-emerald-500 dark:bg-emerald-600 hover:bg-emerald-600 dark:hover:bg-emerald-500 text-white text-xs font-bold px-3 py-2 rounded-lg shadow-md dark:shadow-none transition-all hover:shadow-lg flex items-center gap-1">
                                                        <i class="fas fa-check"></i> Setuju
                                                    </button>
                                                </form>
                                                <form action="{{ route('purchase_order.reject', $po->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENOLAK draf PO ini?');">
                                                    @csrf @method('PUT')
                                                    <button type="submit" class="bg-red-500 dark:bg-red-600 hover:bg-red-600 dark:hover:bg-red-500 text-white text-xs font-bold px-3 py-2 rounded-lg shadow-md dark:shadow-none transition-all hover:shadow-lg flex items-center gap-1">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($po->status === 'approved')
                                            <div class="flex items-center justify-center gap-2">
                                                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-900/20 px-2 py-1.5 rounded border border-emerald-100 dark:border-emerald-800/30"><i class="fas fa-check-double"></i> {{ __('valid') }}</span>
                                                <a href="{{ route('purchase_order.print', $po->id) }}" target="_blank" class="bg-gray-800 dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-md dark:shadow-none transition-all hover:-translate-y-0.5 flex items-center gap-1">
                                                    <i class="fas fa-print"></i> Cetak
                                                </a>
                                            </div>
                                        @elseif($po->status === 'selesai')
                                            <div class="flex items-center justify-center gap-2">
                                                <span class="text-[10px] text-blue-600 dark:text-blue-400 font-bold bg-blue-50 dark:bg-blue-900/20 px-2 py-1.5 rounded border border-blue-100 dark:border-blue-800/30"><i class="fas fa-box-check"></i> Selesai</span>
                                                <a href="{{ route('purchase_order.print', $po->id) }}" target="_blank" class="bg-gray-800 dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-md dark:shadow-none transition-all hover:-translate-y-0.5 flex items-center gap-1">
                                                    <i class="fas fa-print"></i> Cetak
                                                </a>
                                            </div>
                                        @elseif($po->status === 'rejected')
                                            <span class="text-xs text-red-400 dark:text-red-500 italic"><i class="fas fa-ban"></i> Ditolak</span>
                                        @else
                                            <button onclick="showPoDetail({{ $po->id }})" class="text-xs text-indigo-500 hover:text-indigo-700 font-bold"><i class="fas fa-eye"></i> Detail</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center text-gray-500 dark:text-gray-500 italic font-medium">{{ __('no_po_history') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
    </div>

    {{-- MODAL REKOMENDASI EOQ --}}
    <div id="eoqModal" onclick="if(event.target === this) closeEoqModal()" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300 cursor-pointer">
        <div id="eoqModalContent" class="relative bg-white dark:bg-gray-800 w-[95%] min-w-[300px] max-w-6xl rounded-2xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden transform scale-95 transition-transform duration-300 border border-gray-200 dark:border-gray-700 cursor-default">
            
            <!-- Resizer Handles -->
            <div id="resize-handle-left" class="absolute inset-y-0 left-0 w-2 cursor-ew-resize z-50 bg-transparent hover:bg-blue-500/20 transition-colors"></div>
            <div id="resize-handle-right" class="absolute inset-y-0 right-0 w-2 cursor-ew-resize z-50 bg-transparent hover:bg-blue-500/20 transition-colors"></div>

            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/80">
                <h3 class="text-lg font-black text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-magic text-[#D00000] dark:text-red-500"></i> Pilih Rekomendasi Restock (EOQ)
                </h3>
                <button type="button" onclick="closeEoqModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto flex-1 bg-white dark:bg-gray-800 custom-scrollbar">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Pilih barang yang ingin ditambahkan ke Draft PO saat ini sesuai dengan supplier yang Anda targetkan. Daftar di bawah adalah barang dengan stok menipis (&le; ROP).</p>
                <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[10px] text-gray-600 dark:text-gray-400 uppercase bg-gray-100 dark:bg-gray-700/50">
                            <tr>
                                <th class="p-4 w-10 text-center border-b border-gray-200 dark:border-gray-700">
                                    <input type="checkbox" id="selectAllEoq" onchange="toggleAllEoq(this)" class="w-4 h-4 text-[#D00000] bg-white border-gray-300 rounded focus:ring-[#D00000] dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                                </th>
                                <th class="px-4 py-3 font-bold border-b border-gray-200 dark:border-gray-700">Detail Barang</th>
                                <th class="px-4 py-3 text-center font-bold border-b border-gray-200 dark:border-gray-700">Sisa Stok</th>
                                <th class="px-4 py-3 text-center font-bold border-b border-gray-200 dark:border-gray-700">Rekomendasi Qty</th>
                                <th class="px-4 py-3 text-left font-bold border-b border-gray-200 dark:border-gray-700">Supplier Terakhir</th>
                                <th class="px-4 py-3 text-right font-bold border-b border-gray-200 dark:border-gray-700">Est. Harga Beli</th>
                            </tr>
                        </thead>
                        <tbody id="eoqModalBody">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/80 flex justify-end gap-3">
                <button type="button" onclick="closeEoqModal()" class="px-5 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Batal</button>
                <button type="button" onclick="processSelectedEoq()" class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-[#D00000] to-red-600 rounded-xl hover:from-red-700 hover:to-red-800 shadow-md shadow-red-900/20 transition-all flex items-center gap-2">
                    <i class="fas fa-plus"></i> Tambahkan Terpilih
                </button>
            </div>
        </div>
    </div>

    {{-- SCANNER RADAR: FLOATING INDICATOR + HELP TOOLTIP --}}
    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'owner')
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
                            <li>Buka halaman PO ini, lalu <b>tembak barcode</b> ke barang.</li>
                            <li>Barang otomatis terpilih di dropdown! Tinggal isi jumlah pesanan. <i class="fas fa-check-circle text-green-500"></i></li>
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
                            <span class="font-bold text-amber-800 dark:text-amber-400 text-sm">Tips Purchase Order</span>
                        </div>
                        <ul class="space-y-1 text-gray-700 dark:text-gray-300 list-disc list-inside leading-relaxed">
                            <li>Scanner dikenali sebagai <b>keyboard</b> — tidak perlu software tambahan.</li>
                            <li><b>Tidak perlu</b> klik dropdown terlebih dahulu. Cukup tembak kapan saja!</li>
                            <li>Scan barang yang stoknya menipis untuk membuat draft PO cepat.</li>
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
        // Dropdown & Sidebar UI
        // (Dropdown & Sidebar UI sekarang dikelola secara global di layouts.header)

        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'owner')
        // LOGIKA KERANJANG PURCHASE ORDER
        
        function updateTempInfo() {
            const select = document.getElementById('temp_product');
            if(select.selectedIndex === 0) return;
            
            const selectedOption = select.options[select.selectedIndex];
            const price = parseInt(selectedOption.getAttribute('data-price')) || 0;
            const satuan = selectedOption.getAttribute('data-satuan') || 'UoM';
            
            document.getElementById('temp_price').value = price;
            if(document.getElementById('display_temp_price')) {
                document.getElementById('display_temp_price').value = price > 0 ? price.toLocaleString('id-ID') : '';
            }
            // Simulasi stok info (bisa dihilangkan atau disesuaikan jika ingin fetch live stock)
            document.getElementById('temp_stock_info').value = "?"; 
            document.getElementById('temp_satuan_info').innerText = satuan;
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
                displayInput.value = val > 0 ? val.toLocaleString('id-ID') : '';
            } else {
                const input = document.getElementById(id);
                let val = parseInt(input.value || '0', 10);
                val += amount;
                const min = parseInt(input.getAttribute('min') || '0', 10);
                if (val < min) val = min;
                input.value = val;
            }
        }

        let holdInterval;
        let holdTimeout;

        function startHold(e, btn, amount, type) {
            if (e.type === 'mousedown' && e.button !== 0) return;
            // Cegah perilaku default seperti text selection atau scrolling yang bisa membatalkan event
            if (e.cancelable) e.preventDefault(); 

            stopHold(); // Pastikan tidak ada timer ganda

            const doAction = () => {
                if (type === 'temp_qty') adjustNumber('temp_qty', amount);
                else if (type === 'temp_price') adjustNumber('temp_price', amount, true);
                else if (type === 'row_qty') adjustRowNumber(btn, amount);
            };

            doAction();

            holdTimeout = setTimeout(() => {
                holdInterval = setInterval(() => {
                    doAction();
                }, 80); 
            }, 300); 
        }

        function stopHold() {
            clearTimeout(holdTimeout);
            clearInterval(holdInterval);
        }

        window.addEventListener('mouseup', stopHold);
        window.addEventListener('touchend', stopHold);
        window.addEventListener('blur', stopHold); // Berhenti jika pindah tab

        function adjustRowNumber(btn, amount) {
            const container = btn.closest('div');
            const input = container.querySelector('.qty-input');
            let val = parseInt(input.value || '0', 10);
            val += amount;
            const min = parseInt(input.getAttribute('min') || '1', 10);
            if (val < min) val = min;
            input.value = val;
            updateRowSubtotal(input);
        }

        function addToCart() {
            const select = document.getElementById('temp_product');
            const qtyInput = document.getElementById('temp_qty');
            const priceInput = document.getElementById('temp_price');

            // Deteksi Tema untuk SweetAlert
            const isDark = document.documentElement.classList.contains('dark');
            const bgPopup = isDark ? '#1f2937' : '#fff';
            const colorText = isDark ? '#f3f4f6' : '#545454';

            if(select.selectedIndex === 0) {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Pilih material terlebih dahulu!', confirmButtonColor: '#D00000', background: bgPopup, color: colorText }); 
                return; 
            }

            const selectedOption = select.options[select.selectedIndex];
            const productId = selectedOption.value;
            const name = selectedOption.innerText;
            const satuan = selectedOption.getAttribute('data-satuan');
            const eoq = parseFloat(selectedOption.getAttribute('data-eoq')) || 0;
            
            const qty = parseInt(qtyInput.value);
            const price = parseInt(priceInput.value || 0); 

            if(!qty || qty <= 0) { Swal.fire({ icon: 'warning', title: 'Oops...', text: '{{ __('order_qty_greater_than_0') }}', confirmButtonColor: '#D00000', background: bgPopup, color: colorText }); qtyInput.focus(); return; }
            if(!price || price <= 0) { Swal.fire({ icon: 'warning', title: 'Oops...', text: '{{ __('estimated_buy_price_required') }}', confirmButtonColor: '#D00000', background: bgPopup, color: colorText }); priceInput.focus(); return; }
            
            const subtotal = qty * price; 

            // Cek apakah barang sudah ada di keranjang, jika ada tambahkan QTY-nya
            const existingRow = document.querySelector(`tr[data-product-id="${productId}"]`);
            if (existingRow) {
                const existingQtyInput = existingRow.querySelector('.qty-input');
                const newQty = parseInt(existingQtyInput.value) + qty;
                existingQtyInput.value = newQty;
                
                existingRow.querySelector('.qty-display').innerText = newQty;
                const newSubtotal = newQty * price;
                existingRow.querySelector('.subtotal-display').innerText = 'Rp ' + newSubtotal.toLocaleString('id-ID');
                
                Swal.fire({ icon: 'success', title: '{{ __('added') }}', text: `${name} {{ __('becomes') }} ${newQty} ${satuan}`, timer: 1000, showConfirmButton: false, background: bgPopup, color: colorText });
            } else {
                const emptyRow = document.getElementById('emptyRow');
                if(emptyRow) emptyRow.remove();

                // Variabel CSS untuk baris yang digenerate (Mendukung Dark Mode)
                const rowHtml = `
                    <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 hover:bg-blue-50/30 dark:hover:bg-gray-700/50 transition-colors group" data-product-id="${productId}">
                        <td class="px-5 py-4">
                            <div class="font-bold text-gray-800 dark:text-gray-200">${name}</div>
                            <input type="hidden" name="product_id[]" value="${productId}">
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex flex-col items-center">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="flex items-center bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800 rounded-lg overflow-hidden focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/10 transition-all shadow-inner dark:shadow-none">
                                        <button type="button" onmousedown="startHold(event, this, -1, 'row_qty')" ontouchstart="startHold(event, this, -1, 'row_qty')" oncontextmenu="return false;" class="w-7 h-8 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-minus text-[10px] pointer-events-none"></i></button>
                                        <input type="number" name="qty[]" data-eoq="${eoq}" class="qty-input w-12 text-center bg-transparent border-none text-blue-700 dark:text-blue-400 font-black px-1 py-1 focus:ring-0 appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="${qty}" min="1" onchange="updateRowSubtotal(this)" onkeyup="updateRowSubtotal(this)">
                                        <button type="button" onmousedown="startHold(event, this, 1, 'row_qty')" ontouchstart="startHold(event, this, 1, 'row_qty')" oncontextmenu="return false;" class="w-7 h-8 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-plus text-[10px] pointer-events-none"></i></button>
                                    </div>
                                    <span class="text-[10px] text-gray-500 dark:text-gray-400 font-bold">${satuan}</span>
                                </div>
                                <span class="eoq-warning text-[9px] font-bold mt-1 text-center w-full" style="display: none;"></span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-[10px] text-gray-500 font-bold">Rp</span>
                                <input type="number" name="price[]" class="price-input w-28 text-right bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-500 focus:outline-none transition-all" value="${price}" min="0" onchange="updateRowSubtotal(this)" onkeyup="updateRowSubtotal(this)">
                            </div>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="subtotal-display font-black text-gray-800 dark:text-gray-100">Rp ${subtotal.toLocaleString('id-ID')}</div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <button type="button" onclick="removeRow(this)" class="text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 w-8 h-8 rounded-lg transition-all focus:outline-none border border-transparent hover:border-red-200 dark:hover:border-red-800" title="{{ __('remove') }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
                document.getElementById('cartBody').insertAdjacentHTML('beforeend', rowHtml);
            }
            
            // Reset input form
            select.selectedIndex = 0;
            qtyInput.value = '1';
            priceInput.value = '';
            if(document.getElementById('display_temp_price')) { document.getElementById('display_temp_price').value = ''; }
            document.getElementById('temp_stock_info').value = '-';
            document.getElementById('temp_satuan_info').innerText = 'UoM';
            
            updateTotals();
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
                                    <i class="fas fa-clipboard-list text-3xl text-gray-400 dark:text-gray-600"></i>
                                </div>
                                <p class="font-bold text-gray-600 dark:text-gray-300">{{ __('order_list_empty') }}</p>
                                <p class="text-xs mt-1 text-gray-500 dark:text-gray-500">{{ __('select_material_to_order') }}</p>
                            </div>
                        </td>
                    </tr>
                `;
            }
            updateTotals();
        }

        function updateTotals() {
            let totalBiaya = 0;
            let totalBarang = 0;
            const rows = document.querySelectorAll('#cartBody tr:not(#emptyRow)');
            
            rows.forEach(r => {
                const qtyInput = r.querySelector('.qty-input');
                const priceInput = r.querySelector('.price-input');
                
                if (qtyInput && priceInput) {
                    const qty = parseInt(qtyInput.value) || 0;
                    const price = parseInt(priceInput.value) || 0;
                    
                    totalBiaya += (qty * price);
                    totalBarang += qty;
                }
            });
            
            document.getElementById('grandTotal').innerText = 'Rp ' + totalBiaya.toLocaleString('id-ID');
            document.getElementById('totalItems').innerText = totalBarang;
        }

        function updateRowSubtotal(input) {
            const row = input.closest('tr');
            const qtyInput = row.querySelector('.qty-input');
            const qty = parseInt(qtyInput.value) || 0;
            const price = parseInt(row.querySelector('.price-input').value) || 0;
            const subtotal = qty * price;
            
            row.querySelector('.subtotal-display').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            checkEoqDeviation(qtyInput);
            updateTotals();
        }

        function checkEoqDeviation(input) {
            const eoq = parseFloat(input.getAttribute('data-eoq')) || 0;
            if (eoq <= 0) return; // Tidak ada data EOQ yang valid

            const qty = parseFloat(input.value) || 0;
            const warningSpan = input.closest('td').querySelector('.eoq-warning');
            if(!warningSpan) return;

            if (qty < eoq) {
                warningSpan.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Under EOQ';
                warningSpan.className = 'eoq-warning text-[9px] font-bold mt-1 text-center w-full text-orange-500 dark:text-orange-400';
                warningSpan.style.display = 'block';
            } else if (qty > eoq) {
                warningSpan.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Over EOQ';
                warningSpan.className = 'eoq-warning text-[9px] font-bold mt-1 text-center w-full text-red-500 dark:text-red-400';
                warningSpan.style.display = 'block';
            } else {
                warningSpan.innerHTML = '<i class="fas fa-check-circle"></i> Sesuai EOQ';
                warningSpan.className = 'eoq-warning text-[9px] font-bold mt-1 text-center w-full text-emerald-500 dark:text-emerald-400';
                warningSpan.style.display = 'block';
            }
        }

        function confirmSubmit(e) {
            const rowCount = document.querySelectorAll('#cartBody tr:not(#emptyRow)').length;
            if(rowCount === 0) {
                e.preventDefault();
                const isDark = document.documentElement.classList.contains('dark');
                const bgPopup = isDark ? '#1f2937' : '#fff';
                const colorText = isDark ? '#f3f4f6' : '#545454';

                Swal.fire({ icon: 'error', title: '{{ __('empty_order') }}', text: '{{ __('please_add_1_material_to_order') }}', confirmButtonColor: '#D00000', background: bgPopup, color: colorText });
                return false;
            }
            return true; 
        }
        let currentEoqData = [];

        async function fetchRecommendations() {
            const isDark = document.documentElement.classList.contains('dark');
            const bgPopup = isDark ? '#1f2937' : '#fff';
            const colorText = isDark ? '#f3f4f6' : '#545454';

            Swal.fire({
                title: 'Menarik Rekomendasi AI...',
                text: 'Sedang menghitung ROP & EOQ',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); },
                background: bgPopup, color: colorText
            });

            try {
                const response = await fetch('/api/po-recommendation');
                const result = await response.json();

                if (!result.success) {
                    Swal.fire({ icon: 'info', title: 'Aman', text: result.message, background: bgPopup, color: colorText });
                    return;
                }

                currentEoqData = result.data;
                
                // Populate Modal
                const tbody = document.getElementById('eoqModalBody');
                tbody.innerHTML = '';
                document.getElementById('selectAllEoq').checked = false;

                currentEoqData.forEach((item, index) => {
                    // Cek apakah sudah ada di keranjang
                    const exists = document.querySelector(`tr[data-product-id="${item.id}"]`);
                    if(exists) return; // Skip yang sudah di keranjang

                    const tr = document.createElement('tr');
                    tr.className = "bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 hover:bg-blue-50/50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer";
                    tr.onclick = function(e) {
                        if(e.target.type !== 'checkbox') {
                            const cb = this.querySelector('.eoq-item-checkbox');
                            cb.checked = !cb.checked;
                        }
                    };
                    tr.innerHTML = `
                        <td class="p-4 w-10 text-center">
                            <input type="checkbox" class="eoq-item-checkbox w-4 h-4 text-[#D00000] bg-white border-gray-300 rounded focus:ring-[#D00000] dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer" data-index="${index}">
                        </td>
                        <td class="px-4 py-3 font-bold text-gray-800 dark:text-gray-200">
                            ${item.sku} - ${item.nama_barang}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold text-red-500">${item.stok}</span> <span class="text-xs text-gray-500">${item.satuan}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="inline-flex items-center justify-center bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-black px-3 py-1 rounded-lg border border-blue-200 dark:border-blue-800/50">
                                ${item.eoq} <span class="text-[10px] font-bold ml-1">${item.satuan}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-left">
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md border border-gray-200 dark:border-gray-600">
                                <i class="fas fa-truck text-gray-400 mr-1"></i> ${item.last_supplier || '-'}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-gray-800 dark:text-gray-200">
                            Rp ${parseInt(item.harga_beli).toLocaleString('id-ID')}
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                if(tbody.children.length === 0) {
                    Swal.fire({ icon: 'info', title: 'Info', text: 'Semua barang rekomendasi sudah ada di keranjang Anda.', background: bgPopup, color: colorText });
                    return;
                }

                Swal.close();
                openEoqModal();

            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Terjadi kesalahan sistem saat menarik data rekomendasi.', background: bgPopup, color: colorText });
                console.error(error);
            }
        }

        function openEoqModal() {
            const modal = document.getElementById('eoqModal');
            const modalInner = modal.querySelector('div');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Sedikit delay agar transisi display block ke opacity berjalan
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalInner.classList.remove('scale-95');
                modalInner.classList.add('scale-100');
            }, 10);
        }

        function closeEoqModal() {
            const modal = document.getElementById('eoqModal');
            const modalInner = modal.querySelector('div');
            
            modal.classList.add('opacity-0');
            modalInner.classList.remove('scale-100');
            modalInner.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }

        function toggleAllEoq(source) {
            const checkboxes = document.querySelectorAll('.eoq-item-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = source.checked;
            });
        }

        function processSelectedEoq() {
            const checkboxes = document.querySelectorAll('.eoq-item-checkbox:checked');
            if(checkboxes.length === 0) {
                const isDark = document.documentElement.classList.contains('dark');
                Swal.fire({
                    icon: 'warning', title: 'Kosong', text: 'Pilih minimal 1 barang untuk ditambahkan.',
                    background: isDark ? '#1f2937' : '#fff', color: isDark ? '#f3f4f6' : '#545454'
                });
                return;
            }

            const emptyRow = document.getElementById('emptyRow');
            if (emptyRow) emptyRow.remove();

            let itemsAdded = 0;

            checkboxes.forEach(cb => {
                const idx = cb.getAttribute('data-index');
                const item = currentEoqData[idx];

                const productId = item.id;
                const name = item.sku + ' - ' + item.nama_barang;
                const satuan = item.satuan;
                const qty = item.eoq;
                const price = item.harga_beli;
                const subtotal = qty * price;

                const rowHtml = `
                    <tr class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 hover:bg-blue-50/30 dark:hover:bg-gray-700/50 transition-colors group" data-product-id="${productId}">
                        <td class="px-5 py-4">
                            <div class="font-bold text-gray-800 dark:text-gray-200">${name}</div>
                            <input type="hidden" name="product_id[]" value="${productId}">
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex flex-col items-center">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="flex items-center bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800 rounded-lg overflow-hidden focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/10 transition-all shadow-inner dark:shadow-none">
                                        <button type="button" onclick="adjustRowNumber(this, -1)" class="w-7 h-8 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-minus text-[10px]"></i></button>
                                        <input type="number" name="qty[]" data-eoq="${qty}" class="qty-input w-12 text-center bg-transparent border-none text-blue-700 dark:text-blue-400 font-black px-1 py-1 focus:ring-0 appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="${qty}" min="1" onchange="updateRowSubtotal(this)" onkeyup="updateRowSubtotal(this)">
                                        <button type="button" onclick="adjustRowNumber(this, 1)" class="w-7 h-8 flex items-center justify-center text-blue-600 hover:text-white hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors shrink-0"><i class="fas fa-plus text-[10px]"></i></button>
                                    </div>
                                    <span class="text-[10px] text-gray-500 dark:text-gray-400 font-bold">${satuan}</span>
                                </div>
                                <span class="eoq-warning text-[9px] font-bold mt-1 text-center w-full" style="display: none;"></span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-[10px] text-gray-500 font-bold">Rp</span>
                                <input type="number" name="price[]" class="price-input w-28 text-right bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-500 focus:outline-none transition-all" value="${price}" min="0" onchange="updateRowSubtotal(this)" onkeyup="updateRowSubtotal(this)">
                            </div>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="subtotal-display font-black text-gray-800 dark:text-gray-100">Rp ${subtotal.toLocaleString('id-ID')}</div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <button type="button" onclick="removeRow(this)" class="text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 w-8 h-8 rounded-lg transition-all focus:outline-none border border-transparent hover:border-red-200 dark:hover:border-red-800" title="{{ __('remove') }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
                document.getElementById('cartBody').insertAdjacentHTML('beforeend', rowHtml);
                itemsAdded++;
            });

            updateTotals();
            closeEoqModal();
            
            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                icon: 'success', title: 'Berhasil!',
                text: `${itemsAdded} jenis barang berhasil ditambahkan ke keranjang PO.`,
                background: isDark ? '#1f2937' : '#fff', color: isDark ? '#f3f4f6' : '#545454',
                timer: 2000, showConfirmButton: false
            });
        }

        // Initialize Select2 for temp_product and supplier
        $(document).ready(function() {
            $('#temp_product').select2({
                placeholder: "{{ __('select_item_from_master') }}",
                width: '100%',
                dropdownAutoWidth: true
            });

            $('#supplierSelect').select2({
                placeholder: "{{ __('select_supplier') }}",
                width: '100%',
                dropdownAutoWidth: true
            });

            // Trigger updateTempInfo when Select2 value changes
            $('#temp_product').on('select2:select', function (e) {
                updateTempInfo();
            });

            // Modal Edge Resizer Logic
            const modalContent = document.getElementById('eoqModalContent');
            const handleLeft = document.getElementById('resize-handle-left');
            const handleRight = document.getElementById('resize-handle-right');
            let isResizing = false;
            let startX, startWidth;

            function initResize(e) {
                isResizing = true;
                startX = e.clientX;
                startWidth = parseInt(document.defaultView.getComputedStyle(modalContent).width, 10);
                document.documentElement.style.cursor = 'ew-resize';
                // Hindari seleksi teks saat dragging
                document.body.style.userSelect = 'none';
                
                // Hapus transition agar resizing mulus
                modalContent.classList.remove('transition-transform', 'duration-300');
            }

            function stopResize(e) {
                if(!isResizing) return;
                isResizing = false;
                document.documentElement.style.cursor = 'default';
                document.body.style.userSelect = 'auto';
                
                // Kembalikan transition
                modalContent.classList.add('transition-transform', 'duration-300');
            }

            function doResize(e) {
                if (!isResizing) return;
                
                // Karena modal ada di tengah (flex-center), merubah width 1px akan melebarkan 0.5px ke kiri dan 0.5px ke kanan.
                // Untuk efek tarikan yang natural, kita hitung jarak cursor x 2
                let dx = e.clientX - startX;
                
                // Jika ditarik dari kiri, geser ke kiri = width bertambah
                if (e.target === handleLeft || startX < (window.innerWidth / 2)) {
                    dx = -dx; 
                }

                let newWidth = startWidth + (dx * 2);
                
                // Limit width
                if(newWidth < 300) newWidth = 300;
                if(newWidth > window.innerWidth * 0.95) newWidth = window.innerWidth * 0.95;
                
                modalContent.style.maxWidth = 'none'; // lepas batasan tailwind max-w-6xl saat diresize manual
                modalContent.style.width = newWidth + 'px';
            }

            handleLeft.addEventListener('mousedown', initResize);
            handleRight.addEventListener('mousedown', initResize);
            window.addEventListener('mousemove', doResize);
            window.addEventListener('mouseup', stopResize);
        });

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
            const isInModal = activeEl && activeEl.closest('#eoqModal, .swal2-container, #scannerHelpTooltip');
            
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
                    
                    processScannerBarcodePO(barcode);
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
        
        function processScannerBarcodePO(barcode) {
            const barcodeClean = barcode.toLowerCase().trim();
            const select = document.getElementById('temp_product');
            let foundIndex = -1;
            let foundName = '';
            
            for (let i = 0; i < select.options.length; i++) {
                const optText = select.options[i].innerText.toLowerCase();
                // PO dropdown doesn't have data-barcode, match by text content
                if (optText.includes(barcodeClean)) {
                    foundIndex = i;
                    foundName = select.options[i].innerText.trim();
                    break;
                }
            }
            
            if (foundIndex > 0) {
                const productId = select.options[foundIndex].value;
                $('#temp_product').val(productId).trigger('change');
                updateTempInfo();
                
                setTimeout(() => {
                    document.getElementById('temp_qty').focus();
                    document.getElementById('temp_qty').select();
                }, 100);
                
                showScannerToast('success', '✓ ' + foundName.substring(0, 40), 'Terpilih — silakan isi jumlah pesanan');
                playBeep('success');
            } else {
                // Fallback: try API lookup
                fetch('/api/barcode-lookup/' + encodeURIComponent(barcode))
                    .then(r => r.json())
                    .then(data => {
                        if (data.found) {
                            $('#temp_product').val(data.id).trigger('change');
                            updateTempInfo();
                            setTimeout(() => {
                                document.getElementById('temp_qty').focus();
                            }, 100);
                            showScannerToast('success', '✓ ' + data.nama_barang, 'Terpilih via barcode lookup');
                            playBeep('success');
                        } else {
                            showScannerToast('error', '✗ Barcode Tidak Ditemukan', barcode);
                            playBeep('error');
                        }
                    })
                    .catch(() => {
                        showScannerToast('error', '✗ Barcode Tidak Ditemukan', barcode);
                        playBeep('error');
                    });
            }
        }
        
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
                osc.connect(gain); gain.connect(ctx.destination);
                if (type === 'success') {
                    osc.frequency.value = 880; gain.gain.value = 0.15;
                    osc.start(); osc.stop(ctx.currentTime + 0.1);
                    setTimeout(() => {
                        const c2 = new (window.AudioContext || window.webkitAudioContext)();
                        const o2 = c2.createOscillator(); const g2 = c2.createGain();
                        o2.connect(g2); g2.connect(c2.destination);
                        o2.frequency.value = 1318; g2.gain.value = 0.12;
                        o2.start(); o2.stop(c2.currentTime + 0.12);
                    }, 80);
                } else {
                    osc.frequency.value = 220; gain.gain.value = 0.2;
                    osc.start(); osc.stop(ctx.currentTime + 0.25);
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

        @endif

        // TAB SYSTEM LOGIC
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            let tab = document.getElementById('tab-' + tabId);
            if(tab) tab.classList.remove('hidden');

            document.querySelectorAll('[id^="btnTab-"]').forEach(btn => {
                btn.className = "px-5 py-2 rounded-lg text-sm font-bold transition-all text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 border border-transparent";
            });

            let activeBtn = document.getElementById('btnTab-' + tabId);
            if (activeBtn) {
                activeBtn.className = "px-5 py-2 rounded-lg text-sm font-bold transition-all bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 shadow-sm border border-blue-100 dark:border-blue-800/50";
            }
            
            localStorage.setItem('activePOTab', tabId);
        }

        document.addEventListener('DOMContentLoaded', () => {
            let activeTab = localStorage.getItem('activePOTab');
            if (activeTab) {
                switchTab(activeTab);
            } else {
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'owner')
                    switchTab('create');
                @else
                    switchTab('history');
                @endif
            }
        });
    </script>
</x-app-layout>