<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="flex h-screen bg-gray-50 dark:bg-gray-900 overflow-hidden font-sans text-gray-800 dark:text-gray-100 transition-colors duration-300">

    @include('layouts.sidebar')

        <div id="overlay" class="fixed inset-0 bg-black/50 hidden z-30 lg:hidden backdrop-blur-sm transition-all"></div>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            @include('layouts.header')

            <div class="flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-100 dark:bg-gray-900 custom-scrollbar space-y-6 transition-colors duration-300">
                
                {{-- HEADER --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-semibold text-gray-400 dark:text-gray-500 mb-2">
                            <a href="{{ route('dashboard') }}" class="hover:text-[#D00000] dark:hover:text-red-400 transition-colors" title="{{ __('to_dashboard') }}">
                                <i class="fas fa-home text-sm"></i>
                            </a> 
                            <span>/</span> 
                            <span>{{ __('system') }}</span> 
                            <span>/</span> 
                            <span class="text-[#D00000] dark:text-red-400">{{ __('report_audit_center') }}</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-gray-800 dark:text-white tracking-tight">{{ __('audit_mutation_system') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('audit_mutation_desc') }}</p>
                    </div>

                    <div class="flex gap-2">
                        {{-- PERUBAHAN: Tombol memanggil Modal Preview, bukan langsung Download --}}
                        <button onclick="openExportPreview('excel')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl shadow-md font-bold flex items-center gap-2 transition-all duration-300 transform hover:-translate-y-1 text-sm card-shadow-emerald">
                            <i class="fas fa-file-excel"></i> {{ __('export_excel') }}
                        </button>
                        <button onclick="openExportPreview('pdf')" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2.5 rounded-xl shadow-md font-bold flex items-center gap-2 transition-all duration-300 transform hover:-translate-y-1 text-sm card-shadow-red">
                            <i class="fas fa-file-pdf"></i> {{ __('export_pdf') }}
                        </button>
                    </div>
                </div>

                {{-- TAB NAVIGASI INTEGRASI: MUTASI VS OPNAME --}}
                <div class="flex border-b border-gray-300 dark:border-gray-700">
                    <a href="{{ route('laporan') }}" class="py-3 px-6 text-sm font-bold border-b-2 border-[#D00000] text-[#D00000] dark:border-red-500 dark:text-red-500 transition-colors">
                        <i class="fas fa-file-invoice mr-2"></i> {{ __('stock_mutation_report_system') }}
                    </a>
                    <a href="{{ route('stock_opname') }}" class="py-3 px-6 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                        <i class="fas fa-clipboard-check mr-2"></i> {{ __('audit_stock_opname_physical') }}
                    </a>
                </div>

                {{-- FORM FILTER --}}
                <div class="bg-white dark:bg-gray-800 p-5 lg:p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md transition-colors">
                    <form action="{{ route('laporan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                        <div class="md:col-span-4">
                            <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('start_period') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-calendar text-gray-400 dark:text-gray-500"></i></div>
                                <input type="date" name="start_date" value="{{ request('start_date', date('Y-m-01')) }}" class="w-full pl-9 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm font-semibold rounded-xl focus:ring-red-500 focus:border-[#D00000] block p-3 transition-all dark:[color-scheme:dark]">
                            </div>
                        </div>

                        <div class="md:col-span-4">
                            <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('end_period') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-calendar text-gray-400 dark:text-gray-500"></i></div>
                                <input type="date" name="end_date" value="{{ request('end_date', date('Y-m-t')) }}" class="w-full pl-9 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white text-sm font-semibold rounded-xl focus:ring-red-500 focus:border-[#D00000] block p-3 transition-all dark:[color-scheme:dark]">
                            </div>
                        </div>

                        <div class="md:col-span-4 flex gap-2">
                            <button type="submit" class="w-full bg-[#1e1e2d] dark:bg-gray-700 hover:bg-black text-white px-4 py-3 rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-sm border border-[#1e1e2d] dark:border-gray-600">
                                <i class="fas fa-filter"></i> {{ __('pull_data') }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- TABEL LAPORAN (DESAIN KONTRAST TINGGI & SPREADSHEET STYLE) --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-md flex flex-col transition-colors">
                    <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-100/50 dark:bg-gray-800/80 flex justify-between items-center transition-colors">
                        <h3 class="font-black text-gray-800 dark:text-gray-200 uppercase tracking-wide text-sm flex items-center gap-2">
                            <i class="fas fa-table-list text-[#D00000] dark:text-red-500"></i> {{ __('data_mutation_recapitulation') }}
                        </h3>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar min-h-[400px] pb-4" style="-webkit-overflow-scrolling: touch;">
                        <table class="w-max min-w-full text-sm text-left whitespace-nowrap border-collapse">
                            {{-- HEADER TINGKAT 1 --}}
                            <thead class="text-[10px] font-black text-gray-800 dark:text-gray-300 uppercase tracking-wider bg-gray-200 dark:bg-gray-700 border-b border-gray-300 dark:border-gray-600">
                                <tr>
                                    <th rowspan="2" class="px-5 py-3 text-center align-middle md:sticky md:left-0 bg-gray-200 dark:bg-gray-800 z-20 w-16 border border-gray-300 dark:border-gray-600">{{ __('no') }}</th>
                                    <th rowspan="2" class="px-5 py-3 align-middle min-w-[250px] md:sticky md:left-[64px] bg-gray-200 dark:bg-gray-800 z-20 border border-gray-300 dark:border-gray-600 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.5)]">{{ __('material_specifications') }}</th>
                                    
                                    <th colspan="2" class="px-5 py-3 text-center border border-gray-300 dark:border-gray-600 bg-gray-300/50 dark:bg-slate-800">{{ __('initial_inventory') }}</th>
                                    <th colspan="2" class="px-5 py-3 text-center border border-gray-300 dark:border-gray-600 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-900 dark:text-emerald-400">{{ __('incoming_goods') }}</th>
                                    <th colspan="2" class="px-5 py-3 text-center border border-gray-300 dark:border-gray-600 bg-rose-100 dark:bg-rose-900/40 text-rose-900 dark:text-rose-400">{{ __('outgoing_goods') }}</th>
                                    
                                    <th colspan="2" class="px-5 py-3 text-center border border-gray-300 dark:border-gray-600 bg-blue-100 dark:bg-blue-900/40 text-blue-900 dark:text-blue-400">{{ __('system_end') }}</th>
                                    <th colspan="2" class="px-5 py-3 text-center border border-gray-300 dark:border-gray-600 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-900 dark:text-indigo-400">{{ __('physical_opname') }}</th>
                                    <th colspan="2" class="px-5 py-3 text-center border border-gray-300 dark:border-gray-600 bg-orange-100 dark:bg-orange-900/40 text-orange-900 dark:text-orange-400">{{ __('difference') }}</th>
                                </tr>
                                
                                {{-- HEADER TINGKAT 2 --}}
                                <tr class="bg-gray-100 dark:bg-gray-700/80">
                                    <th class="px-3 py-2.5 text-center border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-gray-200/50 dark:bg-gray-700">{{ __('qty') }}</th>
                                    <th class="px-3 py-2.5 text-right border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-gray-200/50 dark:bg-gray-700">{{ __('value_rp') }}</th>
                                    
                                    <th class="px-3 py-2.5 text-center border border-gray-300 dark:border-gray-600 text-emerald-800 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20">{{ __('qty') }}</th>
                                    <th class="px-3 py-2.5 text-right border border-gray-300 dark:border-gray-600 text-emerald-800 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20">{{ __('value_rp') }}</th>
                                    
                                    <th class="px-3 py-2.5 text-center border border-gray-300 dark:border-gray-600 text-rose-800 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20">{{ __('qty') }}</th>
                                    <th class="px-3 py-2.5 text-right border border-gray-300 dark:border-gray-600 text-rose-800 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20">{{ __('value_rp') }}</th>
                                    
                                    <th class="px-3 py-2.5 text-center border border-gray-300 dark:border-gray-600 text-blue-800 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20">{{ __('qty') }}</th>
                                    <th class="px-3 py-2.5 text-right border border-gray-300 dark:border-gray-600 text-blue-800 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20">{{ __('value_rp') }}</th>
                                    
                                    <th class="px-3 py-2.5 text-center border border-gray-300 dark:border-gray-600 text-indigo-800 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20">{{ __('qty') }}</th>
                                    <th class="px-3 py-2.5 text-right border border-gray-300 dark:border-gray-600 text-indigo-800 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20">{{ __('value_rp') }}</th>
                                    
                                    <th class="px-3 py-2.5 text-center border border-gray-300 dark:border-gray-600 text-orange-800 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/20">{{ __('qty') }}</th>
                                    <th class="px-3 py-2.5 text-right border border-gray-300 dark:border-gray-600 text-orange-800 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/20">{{ __('value_rp') }}</th>
                                </tr>
                            </thead>
                            
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                @forelse($laporan ?? [] as $index => $item)
                                    @php
                                        $hpp = $item->harga_pokok ?? 0;
                                        $n_awal = ($item->stok_awal ?? 0) * $hpp;
                                        $n_masuk = ($item->masuk ?? 0) * $hpp;
                                        $n_keluar = ($item->keluar ?? 0) * $hpp;
                                        $n_akhir = ($item->stok_akhir ?? 0) * $hpp;
                                        
                                        $fisik = $item->stok_fisik ?? $item->stok_akhir; 
                                        $n_fisik = $fisik * $hpp;
                                        $selisih = $fisik - ($item->stok_akhir ?? 0);
                                        $n_selisih = $selisih * $hpp;
                                    @endphp

                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                        {{-- Info Produk (Sticky desktop) --}}
                                        <td class="px-5 py-4 text-center font-mono text-xs text-gray-700 dark:text-gray-300 font-bold md:sticky md:left-0 bg-white dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-700/80 border border-gray-200 dark:border-gray-700 z-10 w-16 transition-colors">{{ $loop->iteration }}</td>
                                        
                                        <td class="px-5 py-4 md:sticky md:left-[64px] bg-white dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-700/80 border border-gray-200 dark:border-gray-700 z-10 transition-colors shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)] dark:shadow-[2px_0_5px_-2px_rgba(0,0,0,0.5)]">
                                            <div class="font-bold text-gray-900 dark:text-gray-100">{{ $item->nama }}</div>
                                            <div class="flex gap-2 items-center mt-1">
                                                <span class="text-[9px] font-mono bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-1.5 py-0.5 rounded border border-gray-200 dark:border-gray-600">{{ $item->kode ?? '-' }}</span>
                                                <span class="text-[9px] font-bold text-gray-500 dark:text-gray-400">@ Rp {{ number_format($hpp, 0, ',', '.') }}/{{ $item->satuan }}</span>
                                            </div>
                                        </td>
                                        
                                        {{-- Stok Awal --}}
                                        <td class="px-3 py-4 text-center font-bold text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-700">{{ $item->stok_awal ?? 0 }}</td>
                                        <td class="px-3 py-4 text-right font-semibold text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700">{{ number_format($n_awal, 0, ',', '.') }}</td>
                                        
                                        {{-- Barang Masuk (Grid Pastel Background) --}}
                                        <td class="px-3 py-4 text-center font-black text-emerald-700 dark:text-emerald-400 bg-emerald-50/30 dark:bg-emerald-900/10 border border-gray-200 dark:border-gray-700">{{ $item->masuk > 0 ? '+'.$item->masuk : 0 }}</td>
                                        <td class="px-3 py-4 text-right font-semibold text-emerald-700/80 dark:text-emerald-500/80 bg-emerald-50/30 dark:bg-emerald-900/10 border border-gray-200 dark:border-gray-700">{{ number_format($n_masuk, 0, ',', '.') }}</td>
                                        
                                        {{-- Barang Keluar --}}
                                        <td class="px-3 py-4 text-center font-black text-rose-700 dark:text-rose-400 bg-rose-50/30 dark:bg-rose-900/10 border border-gray-200 dark:border-gray-700">{{ $item->keluar > 0 ? '-'.$item->keluar : 0 }}</td>
                                        <td class="px-3 py-4 text-right font-semibold text-rose-700/80 dark:text-rose-500/80 bg-rose-50/30 dark:bg-rose-900/10 border border-gray-200 dark:border-gray-700">{{ number_format($n_keluar, 0, ',', '.') }}</td>
                                        
                                        {{-- Sistem Akhir --}}
                                        <td class="px-3 py-4 text-center font-black text-blue-700 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-900/10 border border-gray-200 dark:border-gray-700 text-base">{{ $item->stok_akhir ?? 0 }}</td>
                                        <td class="px-3 py-4 text-right font-bold text-blue-700/80 dark:text-blue-500/80 bg-blue-50/30 dark:bg-blue-900/10 border border-gray-200 dark:border-gray-700">{{ number_format($n_akhir, 0, ',', '.') }}</td>
                                        
                                        {{-- Fisik Gudang --}}
                                        <td class="px-3 py-4 text-center font-black text-indigo-700 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-900/10 border border-gray-200 dark:border-gray-700 text-base">{{ $fisik }}</td>
                                        <td class="px-3 py-4 text-right font-bold text-indigo-700/80 dark:text-indigo-500/80 bg-indigo-50/30 dark:bg-indigo-900/10 border border-gray-200 dark:border-gray-700">{{ number_format($n_fisik, 0, ',', '.') }}</td>
                                        
                                        {{-- Selisih Dinamis --}}
                                        <td class="px-3 py-4 text-center font-black border border-gray-200 dark:border-gray-700 bg-orange-50/10 dark:bg-orange-900/5">
                                            @if($selisih < 0)
                                                <span class="text-white bg-rose-500 dark:bg-rose-600 px-2 py-0.5 rounded shadow-sm">{{ $selisih }}</span>
                                            @elseif($selisih > 0)
                                                <span class="text-white bg-emerald-500 dark:bg-emerald-600 px-2 py-0.5 rounded shadow-sm">+{{ $selisih }}</span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 text-right font-black border border-gray-200 dark:border-gray-700 bg-orange-50/10 dark:bg-orange-900/5">
                                            @if($selisih < 0)
                                                <span class="text-rose-600 dark:text-rose-400">{{ number_format($n_selisih, 0, ',', '.') }}</span>
                                            @elseif($selisih > 0)
                                                <span class="text-emerald-600 dark:text-emerald-400">+{{ number_format($n_selisih, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="14" class="px-5 py-20 text-center text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-700">
                                            <div class="flex flex-col items-center justify-center w-full">
                                                <i class="fas fa-folder-open text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                                <p class="font-medium text-base">{{ __('empty_mutation_data_for_period') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL PREVIEW EXPORT --}}
    <div id="exportPreviewModal" class="fixed inset-0 bg-black/60 hidden z-[100] flex items-center justify-center backdrop-blur-sm p-4 transition-all">
        <div class="bg-gray-100 dark:bg-gray-900 rounded-3xl w-full max-w-4xl overflow-hidden shadow-2xl animate-[dropIn_0.3s_ease-out] flex flex-col max-h-[90vh]">
            
            {{-- Header Modal --}}
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-white dark:bg-gray-800 shrink-0 transition-colors">
                <h3 class="font-black text-gray-800 dark:text-white text-lg flex items-center gap-2">
                    <i id="previewIcon" class="fas fa-file-pdf text-rose-600"></i> <span id="previewTitle">{{ __('document_preview') }}</span>
                </h3>
                <button onclick="closeExportPreview()" class="text-gray-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 dark:hover:bg-red-900/20">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            {{-- Body Modal (Mockup Dokumen) --}}
            <div class="p-4 sm:p-8 overflow-y-auto custom-scrollbar flex-1 bg-gray-200 dark:bg-black/50 flex justify-center items-start transition-colors">
                {{-- Kertas Mockup --}}
                <div class="bg-white text-black p-6 sm:p-10 shadow-lg w-full max-w-3xl rounded-sm min-h-[500px]">
                    <div class="text-center border-b-2 border-black pb-4 mb-6">
                        <h2 class="font-bold text-lg sm:text-xl uppercase tracking-wider mb-1">{{ __('recap_mutation_valuation_report') }}</h2>
                        <h3 class="font-bold text-base sm:text-lg text-gray-700">{{ __('store_name') }}</h3>
                        <p class="text-xs italic text-gray-600 mt-2">{{ __('period:') }} {{ request('start_date', date('Y-m-01')) }} s/d {{ request('end_date', date('Y-m-t')) }}</p>
                    </div>
                    
                    {{-- Cuplikan Tabel --}}
                    <div class="overflow-hidden border border-gray-400 rounded-sm">
                        <table class="w-full text-[10px] sm:text-xs text-left border-collapse">
                            <thead class="bg-gray-100 font-bold border-b border-gray-400 text-gray-800 uppercase tracking-wide">
                                <tr>
                                    <th class="p-2.5 border-r border-gray-300 text-center w-10">{{ __('no') }}</th>
                                    <th class="p-2.5 border-r border-gray-300">{{ __('material_name') }}</th>
                                    <th class="p-2.5 border-r border-gray-300 text-center w-16">{{ __('initial') }}</th>
                                    <th class="p-2.5 border-r border-gray-300 text-center w-16 text-green-700">{{ __('in') }}</th>
                                    <th class="p-2.5 border-r border-gray-300 text-center w-16 text-red-700">{{ __('out') }}</th>
                                    <th class="p-2.5 text-center w-16 text-blue-700">{{ __('end') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $previewData = collect($laporan ?? [])->take(5); @endphp
                                @forelse($previewData as $index => $item)
                                <tr class="border-b border-gray-200">
                                    <td class="p-2 border-r border-gray-300 text-center text-gray-600">{{ $index + 1 }}</td>
                                    <td class="p-2 border-r border-gray-300 font-bold text-gray-800">{{ $item->nama }}</td>
                                    <td class="p-2 border-r border-gray-300 text-center font-semibold text-gray-700">{{ $item->stok_awal ?? 0 }}</td>
                                    <td class="p-2 border-r border-gray-300 text-center font-bold text-green-600">+{{ $item->masuk ?? 0 }}</td>
                                    <td class="p-2 border-r border-gray-300 text-center font-bold text-red-600">-{{ $item->keluar ?? 0 }}</td>
                                    <td class="p-2 text-center font-black text-blue-700">{{ $item->stok_akhir ?? 0 }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-gray-500 italic bg-gray-50">{{ __('no_mutation_data_to_display') }}</td>
                                </tr>
                                @endforelse
                                
                                @if(count($laporan ?? []) > 5)
                                <tr>
                                    <td colspan="6" class="p-3 text-center text-gray-500 italic bg-gray-50/80 font-medium">{{ __('and') }}{{ count($laporan) - 5 }}{{ __('other_data_rows') }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        <div class="text-center">
                            <p class="text-[10px] mb-12">{{ __('printed_by') }}</p>
                            <p class="text-xs font-bold underline">{{ Auth::user()->name ?? __('administrator') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Footer Modal --}}
            <div class="p-5 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex justify-end gap-3 shrink-0 transition-colors">
                <button onclick="closeExportPreview()" class="px-6 py-2.5 rounded-xl font-bold text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 transition-colors text-sm">{{ __('cancel') }}</button>
                <a id="btnConfirmDownload" href="#" onclick="processDownload(event)" class="px-8 py-2.5 rounded-xl font-black text-white transition-all hover:-translate-y-0.5 text-sm flex items-center gap-2 shadow-md">
                    <i class="fas fa-download"></i> <span id="downloadBtnText">{{ __('download_now') }}</span>
                </a>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 8px; height: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        html.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }
        html.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }
        
        /* Table Grid Fixes */
        table { border-spacing: 0; }
        th, td { border-collapse: collapse; }
        @keyframes dropIn { from { opacity: 0; transform: scale(0.95) translateY(-10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    </style>

    <script>
        // Sidebar dan Dropdown dikelola secara global di layouts.header

        // --- LOGIKA MODAL PREVIEW EXPORT ---
        let currentExportType = '';

        function openExportPreview(type) {
            currentExportType = type;
            const modal = document.getElementById('exportPreviewModal');
            const icon = document.getElementById('previewIcon');
            const title = document.getElementById('previewTitle');
            const btnDownload = document.getElementById('btnConfirmDownload');
            const btnText = document.getElementById('downloadBtnText');

            // Generate URL menggunakan query string yang ada di page saat ini
            const currentUrlParams = window.location.search; 
            
            if (type === 'excel') {
                icon.className = 'fas fa-file-excel text-emerald-600 dark:text-emerald-500';
                title.innerText = '{{ __('preview_export_excel') }}';
                btnDownload.className = 'px-8 py-2.5 rounded-xl font-black text-white transition-all hover:-translate-y-0.5 text-sm flex items-center gap-2 shadow-md bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/30';
                btnText.innerText = '{{ __('download_excel') }}';
                // Set href
                btnDownload.href = "{{ route('laporan.excel') }}" + currentUrlParams;
            } else if (type === 'pdf') {
                icon.className = 'fas fa-file-pdf text-rose-600 dark:text-rose-500';
                title.innerText = '{{ __('preview_export_pdf') }}';
                btnDownload.className = 'px-8 py-2.5 rounded-xl font-black text-white transition-all hover:-translate-y-0.5 text-sm flex items-center gap-2 shadow-md bg-rose-600 hover:bg-rose-700 shadow-rose-600/30';
                btnText.innerText = '{{ __('download_pdf') }}';
                // Set href
                btnDownload.href = "{{ route('laporan.pdf') }}" + currentUrlParams;
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Kunci scroll background
        }

        function closeExportPreview() {
            document.getElementById('exportPreviewModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Buka scroll background
        }

        function processDownload(event) {
            // Tutup modal
            closeExportPreview();

            // Tampilkan SweetAlert Loading
            const isDark = document.documentElement.classList.contains('dark');
            const colorButton = currentExportType === 'excel' ? '#10b981' : '#e11d48';

            Swal.fire({
                title: '{{ __('processing_document') }}',
                html: `{{ __('system_compiling_report_wait') }}`,
                icon: 'info',
                showConfirmButton: false,
                timer: 2500, // Alert otomatis hilang setelah 2.5 detik (karena file mulai terdownload)
                timerProgressBar: true,
                background: isDark ? '#1f2937' : '#fff',
                color: isDark ? '#f3f4f6' : '#545454'
            });

            // Note: Event href tetap berjalan di background untuk mengunduh file
        }
    </script>
</x-app-layout>