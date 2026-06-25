<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900 overflow-hidden font-sans text-gray-800 dark:text-gray-100 transition-colors duration-300">
        @include('layouts.sidebar')

        <div id="overlay" class="fixed inset-0 bg-black/50 hidden z-30 lg:hidden backdrop-blur-sm transition-all"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @include('layouts.header')

            {{-- PERBAIKAN KONTRAS: bg-gray-100 --}}
            <div class="flex-1 overflow-y-auto p-4 lg:p-8 bg-gray-100 dark:bg-gray-900 custom-scrollbar space-y-8 transition-colors duration-300">
                
                {{-- HEADER PENCARIAN --}}
                {{-- PERBAIKAN KONTRAS: shadow-md & border-gray-200 --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col md:flex-row items-center justify-between gap-4 relative overflow-hidden transition-colors">
                    <div class="absolute -right-6 -top-6 opacity-5 dark:opacity-[0.03] transform rotate-12 pointer-events-none transition-opacity">
                        <i class="fas fa-search text-9xl text-gray-900 dark:text-white"></i>
                    </div>
                    
                    <div class="relative z-10 flex items-center gap-5 w-full">
                        <div class="w-14 h-14 bg-red-50 dark:bg-red-900/30 text-[#D00000] dark:text-red-500 rounded-2xl flex items-center justify-center text-2xl shrink-0 shadow-sm dark:shadow-none transition-colors">
                            <i class="fas fa-search"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('global_search_results') }}</p>
                            <h2 class="text-2xl sm:text-3xl font-black text-gray-800 dark:text-white tracking-tight">
                                "{{ $keyword }}"
                            </h2>
                        </div>
                    </div>
                    
                    @php
                        $totalFound = $products->count() + $transactions->count() + $suppliers->count();
                    @endphp
                    
                    <div class="relative z-10 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 px-5 py-3 rounded-2xl shrink-0 text-center w-full md:w-auto transition-colors">
                        <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-0.5">{{ __('total_found') }}</p>
                        <p class="text-2xl font-black text-gray-800 dark:text-white">{{ $totalFound }} <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('data') }}</span></p>
                    </div>
                </div>

                {{-- JIKA KOSONG SEMUA --}}
                @if($totalFound == 0)
                <div class="flex flex-col items-center justify-center py-20 opacity-70">
                    <div class="w-24 h-24 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mb-6 shadow-md border border-gray-200 dark:border-gray-700 transition-colors">
                        <i class="fas fa-ghost text-5xl text-gray-400 dark:text-gray-600"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-700 dark:text-gray-300">{{ __('no_match_found') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 text-center max-w-md">{{ __('no_match_desc') }} <strong class="text-gray-800 dark:text-white">"{{ $keyword }}"</strong>.</p>
                </div>
                @else

                <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                    
                    {{-- KIRI: MATERIAL & SUPPLIER (Grid 5 Kolom) --}}
                    <div class="xl:col-span-5 space-y-8">
                        
                        {{-- HASIL: MATERIAL --}}
                        {{-- PERBAIKAN KONTRAS: border-gray-200, shadow-md, divide-gray-200 --}}
                        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col h-max transition-colors">
                            <div class="bg-indigo-50/80 dark:bg-indigo-900/20 px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm shadow-sm"><i class="fas fa-box-open"></i></div>
                                    <h3 class="font-bold text-indigo-900 dark:text-indigo-200 tracking-wide text-sm">{{ __('material_master') }}</h3>
                                </div>
                                <span class="bg-white dark:bg-gray-800 border border-indigo-200 dark:border-gray-600 text-indigo-700 dark:text-indigo-400 font-black text-xs px-3 py-1 rounded-full shadow-sm transition-colors">{{ $products->count() }}</span>
                            </div>
                            
                            <div class="p-0 divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                                @forelse($products as $p)
                                <a href="{{ Route::has('persediaan') ? route('persediaan') : '#' }}" class="flex items-center justify-between p-5 hover:bg-indigo-50/50 dark:hover:bg-indigo-900/10 transition-colors group">
                                    <div>
                                        <p class="font-bold text-gray-800 dark:text-gray-200 group-hover:text-indigo-700 dark:group-hover:text-indigo-400 transition-colors">{{ $p->nama_barang }}</p>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <span class="text-[10px] font-mono font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-600 transition-colors">{{ $p->sku }}</span>
                                            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">{{ $p->kategori->nama_kategori ?? __('no_category_short') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-0.5">{{ __('current_stock') }}</span>
                                        <span class="text-sm font-black {{ $p->stok <= $p->min_stok ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">{{ $p->stok }} <span class="text-[10px] uppercase font-bold text-gray-500">{{ $p->satuan }}</span></span>
                                    </div>
                                </a>
                                @empty
                                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-box-open text-3xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-xs font-bold">{{ __('no_material_data_short') }}</p>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- HASIL: SUPPLIER --}}
                        {{-- PERBAIKAN KONTRAS: border-gray-200, shadow-md, divide-gray-200 --}}
                        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col h-max transition-colors">
                            <div class="bg-blue-50/80 dark:bg-blue-900/20 px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm shadow-sm"><i class="fas fa-building"></i></div>
                                    <h3 class="font-bold text-blue-900 dark:text-blue-200 tracking-wide text-sm">{{ __('partner_supplier_short') }}</h3>
                                </div>
                                <span class="bg-white dark:bg-gray-800 border border-blue-200 dark:border-gray-600 text-blue-700 dark:text-blue-400 font-black text-xs px-3 py-1 rounded-full shadow-sm transition-colors">{{ $suppliers->count() }}</span>
                            </div>
                            
                            <div class="p-0 divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                                @forelse($suppliers as $s)
                                <a href="{{ Route::has('supplier') ? route('supplier') : '#' }}" class="flex flex-col p-5 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-colors group">
                                    <div class="flex justify-between items-start mb-2">
                                        <p class="font-bold text-gray-800 dark:text-gray-200 text-sm group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ $s->nama_supplier }}</p>
                                        @if($s->status == 'Aktif')
                                            <span class="text-[9px] font-black text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800/50 px-2 py-0.5 rounded uppercase transition-colors shadow-sm">{{ __('active') }}</span>
                                        @else
                                            <span class="text-[9px] font-black text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800/50 px-2 py-0.5 rounded uppercase transition-colors shadow-sm">{{ __('inactive') }}</span>
                                        @endif
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <p class="text-gray-500 dark:text-gray-400 font-medium"><i class="fas fa-user text-gray-400 dark:text-gray-600 w-4"></i> {{ $s->nama_pic ?? __('no_pic') }}</p>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium"><i class="fas fa-phone text-gray-400 dark:text-gray-600 w-4"></i> {{ $s->kontak ?? '-' }}</p>
                                    </div>
                                </a>
                                @empty
                                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-building text-3xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-xs font-bold">{{ __('no_supplier_data_short') }}</p>
                                </div>
                                @endforelse
                            </div>
                        </div>

                    </div>

                    {{-- KANAN: TRANSAKSI (Grid 7 Kolom) --}}
                    <div class="xl:col-span-7">
                        {{-- PERBAIKAN KONTRAS: border-gray-200, shadow-md --}}
                        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col h-full min-h-[400px] transition-colors">
                            <div class="bg-emerald-50/80 dark:bg-emerald-900/20 px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm shadow-sm"><i class="fas fa-file-invoice"></i></div>
                                    <h3 class="font-bold text-emerald-900 dark:text-emerald-200 tracking-wide text-sm">{{ __('transaction_history_invoice') }}</h3>
                                </div>
                                <span class="bg-white dark:bg-gray-800 border border-emerald-200 dark:border-gray-600 text-emerald-700 dark:text-emerald-400 font-black text-xs px-3 py-1 rounded-full shadow-sm transition-colors">{{ $transactions->count() }}</span>
                            </div>
                            
                            <div class="overflow-x-auto">
                                @if($transactions->count() > 0)
                                <table class="w-full text-sm text-left border-collapse">
                                    <thead class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-100 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 transition-colors">
                                        <tr>
                                            <th class="px-6 py-4">{{ __('number_date') }}</th>
                                            <th class="px-6 py-4">{{ __('transaction_type') }}</th>
                                            <th class="px-6 py-4">{{ __('description_destination') }}</th>
                                            <th class="px-6 py-4 text-right">{{ __('rupiah_value') }}</th>
                                        </tr>
                                    </thead>
                                    {{-- PERBAIKAN KONTRAS: divide-gray-200 --}}
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                                        @foreach($transactions as $t)
                                        <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-900/10 transition-colors">
                                            <td class="px-6 py-4">
                                                <p class="font-bold text-gray-800 dark:text-gray-200 font-mono text-xs">{{ $t->no_transaksi }}</p>
                                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 font-medium"><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if(strtolower($t->jenis_transaksi) == 'masuk')
                                                    <span class="bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800/50 px-3 py-1 rounded-lg text-[10px] font-black tracking-wider uppercase inline-flex items-center gap-1 transition-colors shadow-sm"><i class="fas fa-arrow-down"></i> {{ strtoupper(__('in')) }}</span>
                                                @elseif(strtolower($t->jenis_transaksi) == 'keluar')
                                                    <span class="bg-red-50 dark:bg-red-900/30 text-[#D00000] dark:text-red-400 border border-red-200 dark:border-red-800/50 px-3 py-1 rounded-lg text-[10px] font-black tracking-wider uppercase inline-flex items-center gap-1 transition-colors shadow-sm"><i class="fas fa-arrow-up"></i> {{ strtoupper(__('out')) }}</span>
                                                @else
                                                    <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50 px-3 py-1 rounded-lg text-[10px] font-black tracking-wider uppercase inline-flex items-center gap-1 transition-colors shadow-sm"><i class="fas fa-file-contract"></i> {{ $t->jenis_transaksi }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="font-bold text-gray-700 dark:text-gray-300 text-xs">{{ $t->tujuan ?? ($t->supplier->nama_supplier ?? __('internal')) }}</p>
                                                @if($t->catatan)
                                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 line-clamp-2 italic" title="{{ $t->catatan }}">"{{ $t->catatan }}"</p>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span class="font-black text-gray-800 dark:text-gray-200 text-sm">Rp {{ number_format($t->total_nilai, 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                <div class="flex flex-col items-center justify-center h-full min-h-[300px] text-gray-500 dark:text-gray-400 p-8">
                                    <i class="fas fa-file-invoice text-4xl mb-4 text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-sm font-bold text-center">{{ __('no_transaction_history_short') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                @endif
            </div>
        </div>
    </div>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        /* Dark mode scrollbar */
        html.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }
        html.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }
        
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('-translate-x-full'); document.getElementById('overlay').classList.toggle('hidden'); }
        document.getElementById('overlay')?.addEventListener('click', toggleSidebar);
        
        // Tutup dropdown jika diklik di luar area (untuk header)
        window.addEventListener('click', function(e) {
            if (!e.target.closest('button[onclick*="toggle"]') && !e.target.closest('#dropdownUser') && !e.target.closest('#dropdownNotif') && !e.target.closest('#dropdownMessage')) {
                const dropdowns = ['dropdownUser', 'dropdownNotif', 'dropdownMessage'];
                dropdowns.forEach(id => { if (document.getElementById(id)) document.getElementById(id).classList.add('hidden'); });
            }
        });
    </script>
</x-app-layout>